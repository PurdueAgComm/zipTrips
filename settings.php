<?php
include_once("includes/header.php");
include_once("includes/auth-admin.php");
?>
<link rel="stylesheet" href="css/bootstrap-switch.css">



<!-- start body -->
     <div class="section">
      <div class="container">
      	<div class="row">
        <div class="col-lg-12">
          <h1 class="page-header">Site Settings <small>Manage site functionality with a flick of a switch</small></h1>
          <ol class="breadcrumb">
  			<li><a href="dashboard.php">Dashboard</a></li>
        <li><a href="cPanel.php">Control Panel</a></li>
            <li class="active">Site Settings</li> 
           </ol>
        </div>
     	</div>
	
	    <div class="row">
        <?php
            if ($_SESSION["success"] != "") {
                  echo "<div class='alert alert-success alert-dismissable'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button><p><strong>Success!</strong> " . $_SESSION["success"] . "</p></div>";
                  $_SESSION["success"] = "";
            }

              if ($_SESSION["error"] != "") {
                  echo "<div class='alert alert-danger alert-dismissable'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button><p><strong>Error!</strong> " . $_SESSION["error"] . "</p></div>";
                  $_SESSION["error"] = "";
            }
      ?>

        <div class="col-12 col-sm-12 col-lg-12">

          <?php

            ($_SESSION["isRegistrationEnabled"]) ? $isRegistrationEnabled = "checked='checked'" : $isRegistrationEnabled = "";
            ($_SESSION["isLoginEnabled"]) ? $isLoginEnabled = "checked='checked'" : $isLoginEnabled = "";

          ?>
          
          <form class="form-horizontal" role="form" action="functions/doSettings.php" method="post">
             <div class="form-group">
              <label for="isRegistrationEnabled" class="col-sm-2 control-label">Enable Registration:</label>
              <div class="col-sm-10">
                 <div class="make-switch" data-on-label="<i class='fa fa-check'></i>" data-on="success" data-off="danger" data-off-label="<i class='fa fa-times'></i>">
                  <input value="1" name="isRegistrationEnabled" type="checkbox" <?= $isRegistrationEnabled ?>>
                </div>
              </div>
            </div>


             <div class="form-group">
              <label for="isLoginEnabled" class="col-sm-2 control-label">Enable Log in:</label>
              <div class="col-sm-10">
                <div class="make-switch"  data-on-label="<i class='fa fa-check'></i>" data-on="success" data-off="danger" data-off-label="<i class='fa fa-times'></i>">
                  <input value="1" name="isLoginEnabled" type="checkbox" <?= $isLoginEnabled ?>>
                </div>
              </div>
            </div>

            <div class="col-sm-offset-2 col-sm-8">
            <input name="action" value="save" type="hidden" />
            <button type="submit" class="btn btn-success"><i class='fa fa-save'></i> Save Settings</button>
            </div>
            </div>


          </form>


         
          
        </div><!--/span-->
      </div>










<?php
include_once("includes/footer.php");
?>
<script src="js/bootstrap-switch.js"></script>