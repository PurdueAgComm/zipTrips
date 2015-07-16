<?php
include_once("includes/header.php");
include_once("includes/auth-admin.php");


$sql = "SELECT * FROM tblTrip";
$result = mysql_query($sql);
$numTrips = mysql_num_rows($result);

?>


<!-- start body -->
     <div class="section">
      <div class="container">
      	<div class="row">
        <div class="col-lg-12">
          <h1 class="page-header">Manage zipTrips <small>Create, edit, and archive trips</small></h1>
          <ol class="breadcrumb">
  		    	<li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="cPanel.php">Control Panel</a></li>
            <li class="active">Manage zipTrips (<?= $numTrips ?>)</li> 
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
            <div class="col-12 col-sm-12 col-lg-12">

            <div style="margin-bottom: 10px;" class="pull-right"><a class="btn btn-default" href="addTrip.php"><i class="fa fa-plus"></i> Add Trip</a></div>
            <br class="clearfix" />
            <table class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                  <th style='width: 3%;'></th>
                  <th style='width: 3%;'></th>
                  <th style='width: 80%;'>zipTrip</th>
                  <th style='text-align: center;' colspan='3'>Admin</th>
                </tr>
            </thead>
            <tbody>
              <?php
              if($numTrips > 0) { 
                while($trip = mysql_fetch_array($result)) {
                    
                    ($trip["isArchived"]) ? $rowColor = "<tr class='danger'>" : $rowColor = "<tr>";
                    echo $rowColor;
                    ($trip["isArchived"]) ? $icon = "<td style='vertical-align: middle;'><i rel='tooltip' title='Not public' class='fa fa-times-circle'></i></td>" : $icon = "<td style='vertical-align: middle;'><i rel='tooltip' title='Public' class='fa fa-check-circle'></i></td>";
                    echo $icon;
                    ($trip["isFeatured"]) ? $feature = "<td><a rel='tooltip' title='This trip is currently featured' class='btn btn-success'><i class='fa fa-star'></i></a></td>" : $feature = "<td><a rel='tooltip' title='This trip is not featured. Click to make it featured.' href='functions/doTrip.php?action=feature&id=" . $trip["intTripID"] . "' class='btn btn-default'><i class='fa fa-star-o'></i></a>";
                    echo $feature;
                    echo "<td style='vertical-align: middle;'><a href='viewTrip.php?id=" . $trip["intTripID"] . "'>" . $trip["strTitle"] . "</a></td>";
                    echo "<td style='text-align: center;'><a rel='tooltip' title='Add Shows' href='addShow.php?id=" . $trip["intTripID"] . "' class='btn btn-success'><i class='fa fa-plus'></i></a></td>";
                    echo "<td style='text-align: center;'><a rel='tooltip' title='Edit' href='viewTrip.php?action=edit&id=" . $trip["intTripID"] . "' class='btn btn-default'><i class='fa fa-edit'></i></a></td>";
                    ($trip["isArchived"]) ? $hide = "<td style='text-align: center;'><a rel='tooltip' title='Make public' href='functions/doTrip.php?action=unhide&id=" . $trip["intTripID"] . "' class='btn btn-danger'><i class='fa fa-eye'></i></a></td>" : $hide = "<td style='text-align: center;'><a rel='tooltip' title='Hide' href='functions/doTrip.php?action=hide&id=" . $trip["intTripID"] . "' class='btn btn-danger'><i class='fa fa-eye-slash'></i></a></td>";
                    echo $hide;
                    echo "</tr>";
                }
              }
              else {
                  echo "<td colspan='4'>There are no zipTrips created.</td>";
                }
              ?>
            </tbody>
            </table>

         </div>
       </div>







<?php
include_once("includes/footer.php");
?>
