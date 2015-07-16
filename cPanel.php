<?php
include_once("includes/header.php");
include_once("includes/auth-admin.php");
?>


<!-- start body -->
     <div class="section">
      <div class="container">
      	<div class="row">
        <div class="col-lg-12">
          <h1 class="page-header">Control Panel <small>Where you find all the cool toys.</small></h1>
          <ol class="breadcrumb">
  			<li><a href="dashboard.php">Dashboard</a></li>
            <li class="active">Control Panel</li> 
           </ol>
        </div>
     	</div>

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

	
	    <div class="row">
            <div class="col-6 col-sm-6 col-lg-4  text-center">
              <h2>Manage zipTrips</h2>
              <p><i class="fa fa-rocket" style="font-size: 8em;"></i></p>
              <p><a class="btn btn-default btn-block" href="trips.php" role="button">Manage zipTrips &raquo;</a></p>
            </div><!--/span-->

            <div class="col-6 col-sm-6 col-lg-4  text-center">
              <h2>Manage Questions</h2>
              <p><i class="fa fa-question" style="font-size: 8em;"></i></p>
              <p><a class="btn btn-default btn-block" href="questions.php" role="button">Manage Questions &raquo;</a></p>
            </div><!--/span-->
            
            <div class="col-6 col-sm-6 col-lg-4  text-center">
              <h2>Manage News</h2>
              <p><i class="fa fa-envelope" style="font-size: 8em;"></i></p>
              <p><a class="btn btn-default btn-block" href="addNews.php" role="button">Manage News &raquo;</a></p>
            </div><!--/span-->
            

            

         </div>


      <div class="row">

            <div class="col-6 col-sm-6 col-lg-4  text-center">
              <h2>Manage Users</h2>
              <p><i class="fa fa-user" style="font-size: 8em;"></i></p>
              <p><a class="btn btn-default btn-block" href="users.php" role="button">Manage Users &raquo;</a></p>
            </div><!--/span-->

            <div class="col-6 col-sm-6 col-lg-4  text-center">
              <h2>View Stats</h2>
              <p><i class="fa fa-bar-chart-o" style="font-size: 8em;"></i></p>
              <p><a class="btn btn-default btn-block" href="stats.php" role="button">View Stats &raquo;</a></p>
            </div><!--/span-->


            <div class="col-6 col-sm-6 col-lg-4  text-center">
              <h2>Manage Site Settings</h2>
              <p><i class="fa fa-cog" style="font-size: 8em;"></i></p>
              <p><a class="btn btn-default btn-block" href="settings.php" role="button">Manage Site Settings &raquo;</a></p>
            </div><!--/span-->

         </div>









<?php
include_once("includes/footer.php");
?>
