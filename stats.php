<?php
include_once("includes/header.php");
include_once("includes/auth-admin.php");
?>

<!-- start body -->
     <div class="section">
      <div class="container">
        <div class="row">
        <div class="col-lg-12">
          <h1 class="page-header">Stats <small>Ooooh, ahhhhh</small></h1>
          <ol class="breadcrumb">
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="cPanel.php">Control Panel</a></li>
            <li class="active">Stats</li> 
           </ol>
        </div>
      </div>

      <div class="row">
            <div class="col-xs-3">
              <ul class="nav nav-pills nav-stacked">
                <? if($_GET["mode"] != "") { ?>
                  <li><a href="stats.php"><i class="fa fa-arrow-circle-o-left"></i> Back</a></li>
                <? } 
                  if($_GET["mode"] == "1") {

                    if($_GET["mode"] > 0) {
                 
                    } // end if mode > 0 
                    
                    $sqlTrips = "SELECT * FROM tblTrip WHERE isArchived=0;";
                    $resultTrips = mysql_query($sqlTrips);
                    while($trip = mysql_fetch_array($resultTrips)) {
                      if($trip["intTripID"] == (int) $_GET["id"]) {
                    ?>
                        <li class="active"><a href="stats.php?mode=1&id=<?= $trip["intTripID"] ?>"><?= $trip["strTitle"]?></a></li>
                      <?php }
                       else { ?>
                        <li><a href="stats.php?mode=1&id=<?= $trip["intTripID"] ?>"><?= $trip["strTitle"]?></a></li>
                      <?php } ?>


                <? } } else if($_GET["mode"] == "2") { 

                    $sqlShows = "SELECT * FROM tblTripShow ORDER BY dateBegin DESC;";
                    $resultShows = mysql_query($sqlShows);
                    $numShows = mysql_num_rows($resultShows);
                    while($show = mysql_fetch_array($resultShows)) {

                      $sql = "SELECT strTitle FROM tblTrip WHERE intTripID=" . $show["intTripID"];
                      $result = mysql_query($sql);
                      $trip = mysql_fetch_array($result);

                      if($show["intTripID"] == (int) $_GET["sid"]) {
                  ?>
                       <li class="active"><a href="stats.php?mode=2&id=<?= $show["intTripShowID"] ?>"><?= $trip["strTitle"]?><br/>
                       <small><?= $show["dateBegin"] ?></small></a>
                       </li>
                      <?php }
                       else { ?>
                        <li><a href="stats.php?mode=2&id=<?= $show["intTripShowID"] ?>"><?= $trip["strTitle"]?><br />
                        <small><?= date("F j, Y", strtotime($show["dateBegin"])) ?> at <?= date("g:i A", strtotime($show["dateBegin"])) ?></small></a>
                        </li>
                      <?php }  ?>


                <? } } else {?>
                  <li><a href="stats.php?mode=1">View By Trip</a></li>
                  <li><a href="stats.php?mode=2">View By Show</a></li>
                <? } ?>
                
               
            </ul>

          </div>


            
            <div class="col-xs-9">
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


            <?php if($_GET["mode"] == 1) {  

              $sqlShows = "SELECT DISTINCT intTripShowID FROM tblTripShow WHERE intTripID=" . (int) $_GET["id"];
              $resultShows = mysql_query($sqlShows);
              while($shows = mysql_fetch_array($resultShows)) {
                $sqlPeople = "SELECT * FROM tblUser JOIN tblUserShow ON tblUser.intUserID=tblUserShow.intUserID WHERE intTripShowID=" . $shows["intTripShowID"];
                $resultPeople = mysql_query($sqlPeople);
                while($user = mysql_fetch_array($resultPeople)) { 
                  $userCount++;
                  $classCount += $user["intClass"];
                  $countries[] = $user["strCountry"];
                }
              }

              $countryCount = count(array_unique($countries));

              if(empty($_GET["id"])) {
                $userCount = 0;
                $classCount = 0;
                $countryCount = 0;
              }

              ?>

            <div class="row" style="margin-bottom: 10px;">
              <div class="col-sm-4"><div class="userCount"><span class="statText">Schools</span><span class="statTextBig"><?= $userCount ?></span></div></div>
              <div class="col-sm-4"><div class="classCount"><span class="statText">Students</span><span class="statTextBig"><?= $classCount ?></span></div></div>
              <div class="col-sm-4"><div class="countryCount"><span class="statText">Countries</span><span class="statTextBig"><?= $countryCount ?></span></div></div>
            </div>


            <table class="table table-bordered table-hover table-striped">
              <tr>
                <th>SCHOOL</th>
                <th>CLASS</th>
                <th>STATE</th>
                <th>COUNTRY</th>
                <th>TYPE</th>
              </tr>

              <?php 
              $public = 0;
              $home = 0;
              $private = 0;
              $sqlShows = "SELECT DISTINCT intTripShowID FROM tblTripShow WHERE intTripID=" . (int) $_GET["id"];
              $resultShows = mysql_query($sqlShows);
              while($shows = mysql_fetch_array($resultShows)) {
                $sqlPeople = "SELECT * FROM tblUser JOIN tblUserShow ON tblUser.intUserID=tblUserShow.intUserID WHERE intTripShowID=" . $shows["intTripShowID"];
                $resultPeople = mysql_query($sqlPeople);
                while($user = mysql_fetch_array($resultPeople)) { 
                  if($user["strSchoolType"] == "a private school") {
                    $schoolType = "Private School";
                    $private++;
                  }
                  else if($user["strSchoolType"] == "a public school") {
                    $schoolType = "Public School";
                    $public++;
                  }
                  else {
                    $schoolType = "Home School";
                    $home++;
                  }
                ?>  
                  <tr>
                    <td><?= $totalUsers ?><a href="viewUser.php?action=edit&id=<?= $user["intUserID"]?>" rel="tooltip" title="<?= $user["strFirstName"] ?> <?= $user["strLastName"] ?>"><?= $user["strSchool"] ?></a></td>
                    <td><?= $user["intClass"] ?></td>
                    <td><?= $user["strState"] ?></td>
                    <td><?= $user["strCountry"] ?></td>
                    <td><?= $schoolType ?></td>
                  </tr>
            <?php } } ?>
                  <tr>
                    <td colspan="5">
                      <div style="float: right; margin-right: 10px;">
                        Public: <?= $public ?> <br />
                        Private: <?= $private ?> <br />
                        Home: <?= $home ?> <br />
                      </div>
                    </td>
            </table>


            <?php  } else if($_GET["mode"] == 2) {
              $sqlPeople = "SELECT * FROM tblUser JOIN tblUserShow ON tblUser.intUserID=tblUserShow.intUserID WHERE intTripShowID=" . (int) $_GET["id"];
              $resultPeople = mysql_query($sqlPeople);
              while($user = mysql_fetch_array($resultPeople)) { 
                $userCount++;
                $classCount += $user["intClass"];
                $countries[] = $user["strCountry"];
              }

              $countryCount = count(array_unique($countries));

              if(empty($_GET["id"])) {
                $userCount = 0;
                $classCount = 0;
                $countryCount = 0;
              }

              ?>

            <div class="row" style="margin-bottom: 10px;">
              <div class="col-sm-4"><div class="userCount"><span class="statText">Schools</span><span class="statTextBig"><?= $userCount ?></span></div></div>
              <div class="col-sm-4"><div class="classCount"><span class="statText">Students</span><span class="statTextBig"><?= $classCount ?></span></div></div>
              <div class="col-sm-4"><div class="countryCount"><span class="statText">Countries</span><span class="statTextBig"><?= $countryCount ?></span></div></div>
            </div>


            <table class="table table-bordered table-hover table-striped">
              <tr>
                <th>SCHOOL</th>
                <th>CLASS</th>
                <th>STATE</th>
                <th>COUNTRY</th>
                <th>TYPE</th>
              </tr>

              <?php 
              $public = 0;
              $home = 0;
              $private = 0;
              $sqlPeople = "SELECT * FROM tblUser JOIN tblUserShow ON tblUser.intUserID=tblUserShow.intUserID WHERE intTripShowID=" . (int) $_GET["id"];
              $resultPeople = mysql_query($sqlPeople);
              while($user = mysql_fetch_array($resultPeople)) { 
                if($user["strSchoolType"] == "a private school") {
                  $schoolType = "Private School";
                  $private++;
                }
                else if($user["strSchoolType"] == "a public school") {
                  $schoolType = "Public School";
                  $public++;
                }
                else {
                  $schoolType = "Home School";
                  $home++;
                }
                ?>  
                  <tr>
                    <td><?= $totalUsers ?><a href="viewUser.php?action=edit&id=<?= $user["intUserID"]?>" rel="tooltip" title="<?= $user['strFirstName'] ?> <?= $user['strLastName'] ?>"><?= $user["strSchool"] ?></a></td>
                    <td><?= $user["intClass"] ?></td>
                    <td><?= $user["strState"] ?></td>
                    <td><?= $user["strCountry"] ?></td>
                    <td><?= $schoolType ?></td>
                  </tr>
            <?php } ?>
                  <tr>
                    <td colspan="5">
                      <div style="float: right; margin-right: 10px;">
                        Public: <?= $public ?> <br />
                        Private: <?= $private ?> <br />
                        Home: <?= $home ?> <br />
                      </div>
                    </td>
            </table>
            <?php } else { ?>

              <div class="alert alert-info">To continue select a filter from the left.<ul><li><strong>View by Trip</strong> allows you to see all participants for all the shows of a specific trip.</li><li><strong>View by Show</strong> allows you to drill down to a specific show of a zipTrip on a particular date.</li></ul></div>
              <div class="alert alert-info">Upcoming Features:<ul><li>Sorting</li><li>Export to Excel</li></ul></div>

            <?php } ?>

              

            </div>
       </div>






<?php
include_once("includes/footer.php");
?>
