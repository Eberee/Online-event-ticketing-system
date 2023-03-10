<!DOCTYPE html>
<html>

<?php include 'header.php';?>

  <body>
    <div class="page">

    <?php include 'top_navbar.php';?>

      <div class="page-content d-flex align-items-stretch">

        <?php include 'side_navbar.php';?>

        <div class="content-inner">
          <!-- Page Header-->
          <header class="page-header">
            <div class="container-fluid">
              <h2 class="no-margin-bottom">TICKETING PANEL</h2>
            </div>
          </header>

            <?php
            $seat_query = $conn->query("SELECT * FROM seats WHERE seat_id='$_GET[seat_id]'") or die(mysql_error());
            $seat_row = $seat_query->fetch();

            /* $st_query = $conn->query("SELECT * FROM seats") or die(mysql_error());
            $seat_id = $st_query->rowCount(); */

            $event_query = $conn->query("SELECT * FROM events WHERE event_id='$seat_row[event_id]'") or die(mysql_error());
            $event_row = $event_query->fetch();
            ?>

            <br />
                <!-- Form Elements -->
                <div class="col-lg-12">
                  <div class="card">
                    <div class="card-header d-flex align-items-center">
                      <h3 class="h4">Enter Client Details</h3>
                    </div>
                    <div class="card-body">

                      <!-- Project-->
                      <div class="project">
                        <div class="row bg-white has-shadow">

                          <div class="left-col col-lg-6 d-flex align-items-center justify-content-between">
                            <div class="project-title d-flex align-items-center">
                              <div class="image has-shadow"><button class="btn btn-info" style="height: 100%; width: 100%; padding: 0px;"><i class="fa fa-ticket"></i><br /><small>Details</small></button></div>
                              <div class="text">
                                <h3 class="h4"><?php echo $seat_row['seat_prefix']; ?> @ $<?php echo $seat_row['seat_price']; ?></h3><small><?php echo $seat_row['seat_description']; ?></small>
                              </div>
                            </div>
                            <div class="project-date"><span class="hidden-sm-down">Last Sold Ticket: <?php echo $seat_row['seat_prefix'] . ' - ' . $seat_row['seat_counter']; ?></span></div>
                          </div>

                          <div class="right-col col-lg-6 d-flex align-items-center">
                            <div class="time"><i class="fa fa-ticket"></i>Total Seats: <?php echo $seat_row['seat_maximum']; ?></div>
                            <div class="comments"><i class="fa fa-dollar"></i>Total Sold: <?php echo $seat_row['seat_counter']; ?></div>
                            <div class="comments"><i class="fa fa-file"></i>Total Remaining: <?php echo $seat_row['seat_maximum'] - $seat_row['seat_counter']; ?></div>
                          </div>

                        </div>
                      </div>

                    <?php

                    //set it to writable location, a place for temp generated PNG files
                    $PNG_TEMP_DIR = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'temp' . DIRECTORY_SEPARATOR;

                    //include "qrlib.php";

                    //ofcourse we need rights to create temp dir
                    if (!file_exists($PNG_TEMP_DIR)) {
                        mkdir($PNG_TEMP_DIR);
                    }

                    $errorCorrectionLevel = 'H';
                    $matrixPointSize = 4;

                    ?>

                    <!-- config form -->
                    <?php echo '<form class="form-horizontal" action="checkout_ticket_save.php?seat_num=' . (isset($_REQUEST['data']) ? htmlspecialchars($_REQUEST['data']) : $_GET['seat_num']) . '&seat_id=' . $_GET['seat_id'] . '&event_id=' . $_GET['event_id'] . '&seat_counter=' . $_GET['seat_counter'] . '" method="POST">'; ?>

                        <?php echo 'Ticket Code:&nbsp;<input name="data" value="' . (isset($_REQUEST['data']) ? htmlspecialchars($_REQUEST['data']) : $_GET['seat_num']) . '" class="form-control" readonly="" />
                        <small>(Area Prefix) ' . $seat_row['seat_prefix'] . '-' . ($seat_row['seat_counter'] + 1) . ' (Seat Number)</small><br />

                        <select name="level" style="visibility: hidden;"><option value="H"' . (($errorCorrectionLevel == 'H') ? ' selected' : '') . '>H - best</option></select>
                        <select name="size" style="visibility: hidden;">';

                      for ($i = 1; $i <= 10; $i++) {
                          echo '<option value="' . $i . '"' . (($matrixPointSize == $i) ? ' selected' : '') . '>' . $i . '</option>';
                      }

                      echo '</select>'; ?>

                      <div><select name="user" required>
                      <?php 
                      $useraccess = $_SESSION['useraccess'];
                      $user_query = $conn->query("SELECT * FROM `users`");
                      echo '<option value="">Select Client</option>';
                      while ($user_row = $user_query->fetch())
                      {
                          echo '<option value="'.$user_row['email'].'">'.$user_row['lastname']. ' - ' .$user_row['lastname'].'</option>';
                      }
                      ?>        
                      </select> <br /> <div>

                        <!-- <div class="row">
                         <input type="hidden" value="<php echo $_GET['seat_counter']; ?>" name="seat_counter" />
                          <div class="col-sm-12">
                            <div class="form-group-material">
                              <input id="register-username" type="text" name="clientLName" class="input-material">
                              <label for="register-username" class="label-material">Last Name</label>
                            </div>
                            <div class="form-group-material">
                              <input id="register-email" type="text" name="clientFName" class="input-material">
                              <label for="register-email" class="label-material">First Name</label>
                            </div>
                            <div class="form-group-material">
                              <input id="register-password" type="text" name="clientContNum" class="input-material">
                              <label for="register-password" class="label-material">Contact #</label>
                            </div>
                          </div>
                        </div> -->

                        <div class="form-group row">
                          <div class="col-sm-12 offset-sm-7">
                            <a href="#" class="btn btn-secondary">CANCEL</a>
                            <button class="btn btn-success">GENERATE CODE &amp; PRINT</button>
                          </div>
                        </div>
                      <?php echo '</form>'; ?>
                    </div>
                  </div>
                </div>
              <br />
              <br />

          <?php include 'footer.php';?>

        </div>
      </div>
    </div>
    <!-- JavaScript files-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/popper.js/umd/popper.min.js"> </script>
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="vendor/jquery.cookie/jquery.cookie.js"> </script>
    <script src="vendor/chart.js/Chart.min.js"></script>
    <script src="vendor/jquery-validation/jquery.validate.min.js"></script>
    <script src="js/charts-home.js"></script>
    <!-- Main File-->
    <script src="js/front.js"></script>
  </body>
</html>