<?php
include_once("includes/header.php");
include_once("includes/auth-admin.php");


$sql = "SELECT * FROM tblUser WHERE strFirstName <> '' OR strLastName <> ''";
$result = mysql_query($sql);
$numUser = mysql_num_rows($result);

$sqlEmpty = "SELECT * FROM tblUser WHERE strFirstName IS NULL OR strLastName IS NULL";
$resultUserEmpty = mysql_query($sqlEmpty);
$numEmpty = mysql_num_rows($resultUserEmpty);

?>

<script>
function Verify_Delete(num)
  { 
    var r=confirm("Are you sure you want to delete this user? There is no way to undo this.");

    if (r==true)
      {

        window.location.href = 'functions/doAccount.php?action=delete&id=' + num
      }
    else
      {
        return false;
      }
  }
</script>

<!-- start body -->
     <div class="section">
      <div class="container">
      	<div class="row">
        <div class="col-lg-12">
          <h1 class="page-header">Manage Users <small>Create, edit, and delete users</small></h1>
          <ol class="breadcrumb">
  		    	<li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="cPanel.php">Control Panel</a></li>
            <li class="active">Manage Users (<?= $numUser+$numEmpty ?>)</li> 
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
            <table class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                  <th style='width: 3%;'>&nbsp;</th>
                  <th style='width: 42%;'>zipTrip</th>
                  <th style='width: 42%;'>Email</th>
                  <th style='text-align: center;' colspan='2'>Admin</th>
                </tr>
            </thead>
            <tbody>
              <?php
              if($numUser > 0) { 
                while($user = mysql_fetch_array($result)) {
                      ($user["strRole"] == "admin") ? $userAdmin = "<i class='fa fa-star' rel='tooltip' title='Administrator'></i>" : $userAdmin = "";
                      echo "<tr>";
                      echo "<td>" . $userAdmin . "</td>";
                      echo "<td><a href='viewUser.php?action=edit&id=" . $user["intUserID"] . "'>" . $user["strFirstName"] . " " . $user["strLastName"] . "</a></td>";
                      echo "<td><a href='mailto:" . $user["strEmail"] ."'>" . $user["strEmail"] ."</a></td>";
                      echo "<td style='text-align: center;'><a class='btn btn-default' href='viewUser.php?action=edit&id=" . $user["intUserID"] ."'><i class='fa fa-edit'></i></a></td>";
                      echo "<td style='text-align: center;'><a onClick='return Verify_Delete(" . $user["intUserID"] . ")' class='btn btn-danger'><i class='fa fa-trash-o'></i></a></td>";
                      echo "</tr>";
                }
              }
              else {
                  echo "<td colspan='4'>There are no zipTrips created.</td>";
                }

              echo "<tr class='warning'><th>&nbsp;</th><th>Incomplete Profiles</th><th>Date Registered</th><th colspan='2'>Admin</th></tr>";

              if($numEmpty > 0) { 
                while($user = mysql_fetch_array($resultUserEmpty)) {
                      echo "<tr>";
                      echo "<td>&nbsp;</td>";
                      echo "<td><a href='mailto:" . $user["strEmail"] ." '>" . $user["strEmail"] ."</a></td>";
                      echo "<td>" . date("M d, Y", strtotime($user["dateRegistered"])) . "</td>";
                      echo "<td style='text-align: center;'><a class='btn btn-default' href='viewUser.php?action=edit&id=" . $user["intUserID"] ."'><i class='fa fa-edit'></i></a></td>";
                      echo "<td style='text-align: center;'><a onClick='return Verify_Delete(" . $user["intUserID"] . ")' class='btn btn-danger'><i class='fa fa-trash-o'></i></a></td>";
                      echo "</tr>";
                }
              }
              else {
                  echo "<td colspan='5'><em>There are no incomplete profiles.</em></td>";
                }


              ?>
            </tbody>
            </table>

         </div>
       </div>







<?php
include_once("includes/footer.php");
?>
