<?php

include_once("includes/header.php");
include_once("includes/auth-admin.php");


$sql = "SELECT * FROM tblTrip WHERE intTripID=" . (int) $_GET["id"];
$result = mysql_query($sql);
$trip = mysql_fetch_array($result);
$numTrips = mysql_num_rows($result);

$sql = "SELECT * FROM tblTripShow WHERE intTripID=" . (int) $_GET["id"] . " ORDER BY dateEnd DESC";
$result = mysql_query($sql);
$numShows = mysql_num_rows($result);


($_GET["action"] == "edit") ? $actionTitle = "Edit" : $actionTitle = "Manage";
($trip["isFeatured"]) ? $featured = "<i class='fa fa-star'></i> Featured Trip" : $featured = "";


?>



<!-- start body -->
     <div class="section">
      <div class="container">
      	<div class="row">
        <div class="col-lg-12">
          <h1 class="page-header"><?= $actionTitle ?> zipTrip <small><?= $trip["strTitle"] ?></small></h1>
          <ol class="breadcrumb">
  		    	<li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="cPanel.php">Control Panel</a></li>
            <li><a href="trips.php">Manage zipTrips</a></li>
            <li class="active"><?= $actionTitle ?> <?= $trip["strTitle"] ?></li>
           </ol>
        </div>
     	</div>



<?php

/****************************************
#########################################
          EDIT TRIP & SHOWS
#########################################
****************************************/

if($_GET["action"] == "edit") {

  $sql = "SELECT * FROM tblTrip WHERE intTripID=" . (int) $_GET["id"];
  $result = mysql_query($sql);
  $trip = mysql_fetch_array($result);
  ?>

<div class="col-lg-6 col-md-6">
  <p>Modify the zipTrip using the fields below. If this trip has any shows associated with it, they will appear in the column to the right. You may edit the details of individual shows as well.</p>

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

          <form class="form-horizontal" action="functions/doTrip.php" method="post" role="form">
          <div class="form-group <?php if($_SESSION['errorTitle']) echo 'has-error'; ?>">
            <label for="addTitle" class="col-sm-2 control-label">Title</label>
            <div class="col-sm-8">
              <input type="text" class="form-control" value="<?php echo $trip['strTitle']; ?>"  id="addTitle" name="editTitle" placeholder="Title of zipTrip">
            </div>
          </div>

          <div class="form-group <?php if($_SESSION['errorDescription']) echo 'has-error'; ?>">
            <label for="editDescription" class="col-sm-2 control-label">Description</label>
            <div class="col-sm-8">
              <textarea type="text" rows="8" class="form-control" id="editDescription" name="editDescription" placeholder="Tell us about the zipTrip. This description will be used throughout the website."><?php echo $trip['strDescription']; ?></textarea>
            </div>
          </div>

          <div class="form-group <?php if($_SESSION['errorTeaser']) echo 'has-error'; ?>">
            <label for="editBlurb" class="col-sm-2 control-label">Teaser</label>
            <div class="col-sm-8">
              <textarea type="text" rows="4" class="form-control" id="editBlurb" name="editBlurb" placeholder="Provide a short teaser, 1 to 2 sentences that will be shown as a marketing call to action."><?php echo $trip['strBlurb']; ?></textarea>
            </div>
          </div>

          <div class="form-group <?php if($_SESSION['errorGrade']) echo 'has-error'; ?>">
            <label for="addGrade" class="col-sm-2 control-label">Targets</label>
            <div class="col-sm-8">
              <div class="input-group">
                <select class="form-control" id="addGrade" name="editGrade">
                  <?php
                  $gradeArray = array("Choose a Grade", "6th",  "7th", "8th", "High School");
                  foreach($gradeArray as $value) {
                    if ($trip['strGrade'] == $value) {
                      echo "<option selected='selected' value='".$value."'> " .$value. "</option>";
                    } else {
                      echo "<option value='".$value."'> ".$value."</option>";
                    }
                  }
                ?>
              </select>
              <span class="input-group-addon">graders</span>
            </div>
            </div>
          </div>

          <div class="form-group <?php if($_SESSION['errorGuide']) echo 'has-error'; ?>">
            <label for="editTeacherGuide" class="col-sm-2 control-label">Teacher Guide URL</label>
            <div class="col-sm-8">
               <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-file-text-o"></i></span>
                  <input type="text" class="form-control" value="<?php echo $trip['strTeacherGuide']; ?>"  id="editTeacherGuide" name="editTeacherGuide" placeholder="URL of Teacher Guide">
                </div>
            </div>
          </div>



          <div class="form-group <?php if($_SESSION['errorTitle']) echo 'has-error'; ?>">
            <label for="editPhotoSm" class="col-sm-2 control-label">Small Photo (250x250)</label>
            <div class="col-sm-8">
               <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-picture-o"></i></span>
                  <input type="text" class="form-control" value="<?php echo $trip['strPhoto250']; ?>"  id="editPhotoSm" name="editPhotoSm" placeholder="URL of Small  Image">
                </div>
            </div>
          </div>

          <div class="form-group <?php if($_SESSION['errorTitle']) echo 'has-error'; ?>">
            <label for="editPhotoMd" class="col-sm-2 control-label">Medium Photo (400x400)</label>
            <div class="col-sm-8">
              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-picture-o"></i></span>
                  <input type="text" class="form-control" value="<?php echo $trip['strPhoto400']; ?>"  id="editPhotoMd" name="editPhotoMd" placeholder="URL of Medium  Image">
              </div>
            </div>
          </div>

          <div class="form-group <?php if($_SESSION['errorTitle']) echo 'has-error'; ?>">
            <label for="editPhotoLg" class="col-sm-2 control-label">Banner Photo (1900x500)</label>
            <div class="col-sm-8">
              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-picture-o"></i></span>
                  <input type="text" class="form-control" value="<?php echo $trip['strPhoto600']; ?>"  id="editPhotoLg" name="editPhotoLg" placeholder="URL of Large  Image">
              </div>
            </div>
          </div>

          <div class="form-group <?php if($_SESSION['errorVideo']) echo 'has-error'; ?>">
            <label for="video" class="col-sm-2 control-label">Video</label>
            <div class="col-sm-8">

              <a class="btn btn-default btn-block" data-toggle="modal" data-target="#addVideo">
                <i class="fa fa-plus"></i> Add Video
              </a>

              <br />

              <table class="table table-striped table-hover">
                <?php
                  $sql = "SELECT * FROM tblTripVideo WHERE intTripID=" . (int) $_GET["id"];
                  $result = mysql_query($sql);
                  $numVideos = mysql_num_rows($result);

                  if($numVideos > 0) {
                    while($video = mysql_fetch_array($result)) {
                  ?>
                      <tr>
                        <td style="vertical-align:middle"><i class='fa fa-video-camera'></i> &nbsp;<a target="_blank" href="<?= $video["strVideoURL"] ?>" rel='tooltip' title="<?= $video["strVideoDescription"] ?>"><?= $video["strVideoTitle"] ?></a></td>
                        <td><a class="pull-right btn btn-xs btn-default" href="functions/doTrip.php?action=deleteVideo&id=<?= $video["intTripVideoID"]?>&tripID=<?= (int) $_GET["id"] ?>" rel="tooltip" title="Delete"><i class='fa fa-times'></i></a></td>
                      </tr>

                <?php } }
                else { ?>
                  <tr>
                    <td style="text-align: center;"><em>No videos added. <a data-toggle="modal" data-target="#addVideo" href="#">Add a video</a>.</em></td>
                  </tr>
                <?php } ?>
              </table>

            </div>
          </div>


          <?php
          ($trip["isBanner"]) ? $isBanner = "<input name='isBanner' type='checkbox' value='1' checked='checked'> Add this zipTrip to the rotating banner" : $isBanner = "<input name='isBanner' type='checkbox' value='1'> Add this zipTrip to the rotating banner";
          ?>

          <div class="form-group <?php if($_SESSION['errorIsBanner']) echo 'has-error'; ?>">
            <label class="col-sm-2 control-label">Options</label>
            <div class="col-sm-8">
              <div class="checkbox">
                <label>
                  <?= $isBanner; ?>
                </label>
              </div>
            </div>
          </div>

          <div class="form-group">
            <div class="col-sm-offset-2 col-sm-8">
              <input name="action" value="edit" type="hidden" />
              <input name="tripID" value="<?= (int) $_GET["id"] ?>" type="hidden" />
              <button type="submit" class="btn btn-success">Edit Trip</button>
            </div>
          </div>
        </form>
      </div>
      <div class="col-lg-6 col-md-6">

      <?php
        $sql = "SELECT * FROM tblTripShow WHERE intTripID=" . (int) $_GET["id"] . " ORDER BY dateBegin DESC";
        $result = mysql_query($sql);
        $numShows = mysql_num_rows($result);


        if(!empty($numShows)){
          while($show = mysql_fetch_array($result)) {


    			echo "<a name='" . $show["intTripShowID"] . "' ></a>";


          if ($_SESSION["error" . $show["intTripShowID"]] != "") {
            echo "<div class='panel panel-danger'>";
          }
          else {
            echo "<div class='panel panel-default'>";
          }

          echo "<div class='panel-heading'>";
            echo "<h3>" . date("F j, Y", strtotime($show["dateBegin"])) . " at " .  date("g:i a", strtotime($show["dateBegin"])) . "</h3>";
          echo "</div>";
          echo "<div class='panel-body'>";

			  if ($_SESSION["success" . $show["intTripShowID"]] != "") {
                echo "<div class='alert alert-success alert-dismissable'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button><p><strong>Success!</strong> " . $_SESSION["success"] . "</p></div>";
                $_SESSION["success" . $_SESSION["tripShowID"]]  = "";
          }

            if ($_SESSION["error" . $show["intTripShowID"]] != "") {
                echo "<div class='alert alert-danger alert-dismissable'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button><p><strong>Error!</strong> " . $_SESSION["error" . $_SESSION["tripShowID"]]  . "</p></div>";
                $_SESSION["error" . $_SESSION["tripShowID"]]  = "";
          }


	// as long as there are shows, parse the date from the dateBegin field and the dateEnd field
			$_SESSION["dateStart"] = date("Y-m-d", strtotime($show["dateBegin"]));
			$_SESSION["dateEnd"] = date("Y-m-d", strtotime($show["dateEnd"]));

	// as long as there are shows, parse the time from the dateBegin field and the dateEnd field
			$_SESSION["timeStart"] = date("g:i A", strtotime($show["dateBegin"]));
			$_SESSION["timeEnd"] = date("g:i A", strtotime($show["dateEnd"]));

	// put the dates in with the two numbers before the colon, hh:mm
			$_SESSION["lengthStart"] = strlen($_SESSION["timeStart"]);
			$_SESSION["lengthEnd"] = strlen($_SESSION["timeEnd"]);

			if ($_SESSION["lengthStart"] < "8") {
				$_SESSION["timeStart"] = ('0' . $_SESSION["timeStart"]);
			}

			if ($_SESSION["lengthEnd"] < "8") {
				$_SESSION["timeEnd"] = ('0' . $_SESSION["timeEnd"]);
			}


          ?>

          <!-- ########### EDIT SHOW ########### -->

<!--start EDIT Show here -->

            <form class="form-horizontal" action="functions/doShow.php" method="post" enctype="multipart/form-data" role="form">
      <div class="form-group <?php if($show["intTripShowID"]==$_SESSION["tripShowID"] && $_SESSION['errorDateStart'] ==1) echo 'has-error'; ?>">

            <input type="hidden" id="tripShowID" name="tripShowID" value="<?= $show["intTripShowID"];?>" />

            <label for="dateStart" class="col-sm-2 control-label">Begins*</label>
            <div class="col-sm-4">
               <div class="input-group date datePicker" id="dp3" data-date-format="yyyy-mm-dd">
                 <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                    <input class="form-control" name="dateStart" id="dateStart" type="text" style="border-top-right-radius:5px; border-bottom-right-radius:5px; background-color:#ffffff"; size="30" value="<?php echo $_SESSION["dateStart"]; ?>"readonly>
                       <span class="add-on"></span>

              </div>
                    <span inline-help style="font-size:10px">YYYY - MM - DD</span>
            </div>


<!-- BEGIN start time -->

        <div class="form-group <?php if($show["intTripShowID"]==$_SESSION["tripShowID"] && $_SESSION['errorTimeStart']==1) echo 'has-error'; ?>">
            <div class="col-sm-4">
              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
                  <input class="form-control" name="timeStart" type="text" placeholder="hh:mm AM/PM" maxlength="8" value="<?php echo $_SESSION["timeStart"]; ?>">
              </div>
                    <span inline-help style="font-size:10px">hh:mm AM/PM</span></div>
        </div>
      </div> <!-- close the form group for START date and time -->

<!-- Start END date -->
<!-- using 2.3 Bootstratp calendar -->
<!-- if($show["intTripShowID"]==$_SESSION["tripShowID"] && $_SESSION['errorDateEnd'] ==1) keeps the errors showing uniquely per field -->
        <div class="form-group <?php if($show["intTripShowID"]==$_SESSION["tripShowID"] && $_SESSION['errorDateEnd'] ==1) echo 'has-error'; ?>">
             <label for="dateEnd" class="col-sm-2 control-label">Ends*</label>
               <div class="col-sm-4">
                 <div class="input-group date datePicker" id="dp3" data-date-format="yyyy-mm-dd" >
                   <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                      <input class="form-control" name="dateEnd" id="dateEnd" type="text" style="border-top-right-radius:5px; border-bottom-right-radius:5px; background-color:#ffffff"; size="30" value="<?php echo $_SESSION["dateEnd"]; ?>"readonly>
                       <span class="add-on"></span>
                 </div>
                    <span inline-help style="font-size:10px">YYYY - MM - DD</span>
               </div>

 <!-- Begin END time -->

          <div class="form-group <?php if($show["intTripShowID"]==$_SESSION["tripShowID"] && $_SESSION['errorTimeEnd'] == 1) echo 'has-error'; ?>">
               <div class="col-sm-4">
                 <div class="input-group">
                   <span class="input-group-addon"><i class="fa fa-clock-o"></i></span><input class="form-control" name="timeEnd" type="text" placeholder="hh:mm AM/PM" maxlength="8" value="<?php echo $_SESSION["timeEnd"]; ?>">
                 </div>
                     <span inline-help style="font-size:10px">hh:mm AM/PM</span>               </div>
          </div>
        </div> <!-- Close the form group for END date and time -->


        <div class="form-group <?php if($_SESSION['errorLiveURL']) echo 'has-error'; ?>">
             <label for="liveURL" class="col-sm-2 control-label">Live Show URL</label>
               <div class="col-sm-8">
                      <input type="text" class="form-control" value="<?php echo $show['strLiveURL']; ?>"  id="liveURL" name="liveURL" placeholder="URL for Live Stream">
               </div>
        </div>


        <div class="form-group <?php if($_SESSION['errorArchiveURL']) echo 'has-error'; ?>">
             <label for="archiveURL" class="col-sm-2 control-label">Archived Show URL</label>
               <div class="col-sm-8">
                      <input type="text" class="form-control" placeholder="URL for Live Stream" value="<?php echo $show['strArchiveURL']; ?>"  id="archiveURL" name="archiveURL" placeholder="URL for Archived Media File" >
            </div>
        </div>

           <?php
              ($show["isVideoConference"]) ? $video = "<input name='isVideoConference' type='checkbox' value='1' checked='checked'> Video Conference Available" : $video = "<input name='isVideoConference' type='checkbox' value='1'> Video Conference Available";
              ($show["isHotSeat"]) ? $hotseat = "<input name='isHotSeat' type='checkbox' value='1' checked='checked'> Hotseat Available" : $hotseat = "<input name='isHotSeat' type='checkbox' value='1'> Hotseat Available";

            ?>

           <div class="form-group <?php if($_SESSION['errorDescription']) echo 'has-error'; ?>">
            <label class="col-sm-2 control-label">Options</label>
            <div class="col-sm-8">
               <div class="checkbox" name="isVideoConference">
                <label>
                  <?= $video; ?>
                </label>
              </div>
               <div class="checkbox">
                <label>
                 <?= $hotseat; ?>
                </label>
              </div>
            </div>
          </div>

<!-- Edit the show -->
          <div class="form-group">
            <div class="col-sm-offset-2 col-sm-8">
              <input name="action" value="edit" type="hidden" />
              <input name="showID" value="<?= $_GET['id'] ?>" type="hidden" />
              <button type="submit" name ="submit" class="btn btn-success">Edit Show</button>
            </div>
          </div>
        </form>
        <?php
             echo "</div>";
            echo "</div>";
          }
        }
      ?>



      </div>


    </div>

<!-- Video Add Modal -->
<div class="modal fade" id="addVideo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Add a Video to <?= $trip["strTitle"]?></h4>
      </div>
      <div class="modal-body">
          <form class="form-horizontal" action="functions/doTrip.php" method="post" role="form">
          <div class="form-group <?php if($_SESSION['errorVidTitle']) echo 'has-error'; ?>">
            <label for="videoTitle" class="col-sm-2 control-label">Title</label>
            <div class="col-sm-8">
              <input class="form-control" placeholder="Title of video" name="videoTitle" id="videoTitle" type="text" value="<?php echo $_SESSION["videoTitle"]; ?>">
            </div>
          </div>

          <div class="form-group <?php if($_SESSION['errorURL']) echo 'has-error'; ?>">
            <label for="videoURL" class="col-sm-2 control-label">Embed URL</label>
            <div class="col-sm-8">
              <input class="form-control" placeholder="Youtube video URL" name="videoURL" id="videoURL" type="text" value="<?php echo $_SESSION["videoURL"]; ?>">
            </div>
          </div>

          <div class="form-group <?php if($_SESSION['errorVidDescription']) echo 'has-error'; ?>">
            <label for="videoDescription" class="col-sm-2 control-label">Description</label>
            <div class="col-sm-8">
             <textarea type="text" rows="4" class="form-control" id="videoDescription" name="videoDescription" placeholder="Provide a description about the video"><?= $_SESSION["videoDescription"] ?></textarea>
            </div>
          </div>

      <input name="action" value="addVideo" type="hidden" />
      <input name="tripID" value="<?= $_GET['id'] ?>" type="hidden" />
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-success">Add Video</button>
      </form>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->



<?php } else { ?>

      <div class="row">
        <div class="col-md-4 hidden-sm hidden-xs">
          <?php if(!empty($trip["strPhoto400"])) { ?>
          <img class="img-responsive" src="<?= $trip["strPhoto400"] ?>">
          <?php } else {  ?>
          <a href="?action=edit&id=<?= $trip["intTripID"] ?>"><img src="http://www.placehold.it/400x400&text=Add a Picture" class="img-responsive" /></a>
          <?php } ?>
        </div>

        <div class="col-md-8 col-sm-12">
           <?php
            if ($_SESSION["success"] != "") {
                  echo "<div class='alert alert-success alert-dismissable'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button><p><strong>Success!</strong> " . $_SESSION["success"] . "</p></div>";
                  $_SESSION["success"] = "";
            }

              if ($_SESSION["error"] != "") {
                  echo "<div class='alert alert-danger alert-dismissable'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button><p><strong>Error!</strong> " . $_SESSION["error"] . "</p></div>";
                  $_SESSION["error"] = "";
            }

            ($trip["isArchived"]) ? $hide = "<a class='btn btn-default' rel='tooltip' title='Make public' href='functions/doTrip.php?action=unhide&id=" . (int) $_GET["id"] . "'><i class='fa fa-eye'></i></a>" : $hide = "<a rel='tooltip' title='Hide' class='btn btn-default' href='functions/doTrip.php?action=hide&id=" . (int) $_GET["id"] . "'><i class='fa fa-eye-slash'></i></a>"
          ?>

          <span class='pull-right'><a class='btn btn-default' rel="tooltip" title="Edit" href="viewTrip.php?action=edit&id=<?= (int) $_GET["id"] ?>"><i class='fa fa-edit'></i></a> <?= $hide ?></span>
          <h3><i class="fa fa-rocket"></i> <?= $trip["strTitle"] ?> <small><?= $featured ?></small></h3>
          <p><?= $trip["strDescription"] ?></p>

          <h3><i class='fa fa-calendar'></i> Available Shows</h3>



          <?php

            // TODO: Remove names from dropdown if they are already selected.

            if($numShows > 0) {
              $today = mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"));
              while($show = mysql_fetch_array($result)) {
                $sql = "SELECT * FROM tblUserShow LEFT JOIN tblUser ON tblUserShow.intUserID = tblUser.intUserID WHERE intTripShowID=" . $show["intTripShowID"] . " AND isHotSeat=1";
                $resultHotseat = mysql_query($sql);
                $numHotseat = mysql_num_rows($resultHotseat);

                $sql = "SELECT * FROM tblUserShow LEFT JOIN tblUser ON tblUserShow.intUserID = tblUser.intUserID WHERE intTripShowID=" . $show["intTripShowID"]  . " AND isVideoConference=1";
                $resultConference = mysql_query($sql);
                $numConference = mysql_num_rows($resultConference);


                (strtotime($show["dateEnd"])  < $today) ? $statusColor = "990000" : $statusColor = "009900";
                (strtotime($show["dateEnd"])  < $today) ? $statusTitle = "Past Show" : $statusTitle = "Upcoming Show";


                echo "<div class='panel panel-default'>";
                echo "<div class='panel-heading'>";
                echo "<span rel='tooltip' title='Edit this show' class='pull-right'><a href='?action=edit&id=" . $trip["intTripID"] . "#" . $show["intTripShowID"] . "'><i class='fa fa-edit'></i></a></span>";
                echo "<h3><i class='fa fa-clock-o' rel='tooltip' title='" . $statusTitle . "' style='color: #" . $statusColor . "'></i> " . date("F j, Y", strtotime($show["dateBegin"])) . " at " . date("g:i a", strtotime($show["dateBegin"])) . "</h3>";
                echo "</div>";
                echo "<div class='panel-body'>";

                echo "<blockquote><strong>Live Show</strong>: <a href='" . $show["strLiveURL"] . "'>" . $show["strLiveURL"] . "</a><br />";
                echo "<strong>Archived Show</strong>: <a href='" . $show["strArchiveURL"] . "'>" . $show["strArchiveURL"] . "</a></blockquote>";
                echo "<h4>Select Schools for Participation</h4>";


                if($show["isHotSeat"]) {
                  echo "<div class='row'>";
                    echo "<form role='form' action='functions/doParticipate.php' method='post'>";
                    echo "<div class='form-group'>";
                      echo "<label class='col-sm-2 control-label'>Hotseat</label>";
                        echo "<div class='col-sm-6'>";
                          echo "<select name='userID' class='form-control'>";

                          if($numHotseat > 0) {
                            while($hotseat = mysql_fetch_array($resultHotseat)) {
                              echo "<option value='" . $hotseat["intUserID"] . "'>" . $hotseat["strSchool"] . " (" . $hotseat["strLastName"] . ")</option>";
                            }
                          }
                          else {
                            echo "<option value='NULL'>No schools have selected to participate</option>";
                          }

                          echo "</select>";
                        echo "</div>";
                          if($numHotseat > 0) {
                            echo "<div class='col-sm-1'><button type='submit' class='btn btn-default'><i class='fa fa-plus'></i> Add School</button></div>";
                          }
                          else {
                           echo "<div class='col-sm-1'><button type='submit' class='disabled btn btn-default'><i class='fa fa-plus'></i> Add School</button></div>";
                          }
                    echo "<input type='hidden' value='add' name='action' />";
                    echo "<input type='hidden' value='" . $show["intTripShowID"] . "' name='showID' />";
                    echo "<input type='hidden' value='" . (int) $_GET["id"] . "' name='tripID' />";
                    echo "<input type='hidden' value='1' name='hotseat' />";
                    echo "</div></form>";
                  echo "</div>";
                }

                if($show["isVideoConference"]) {
                  echo "<div class='row'>";
                    echo "<form role='form' action='functions/doParticipate.php' method='post'>";

                    echo "<div class='form-group'>";
                      echo "<label class='col-sm-2 control-label'>Video Conference</label>";
                        echo "<div class='col-sm-6'>";
                          echo "<select name='userID' class='form-control'>";

                            if($numConference > 0) {
                              while($conference = mysql_fetch_array($resultConference)) {
                                echo "<option value='" . $conference["intUserID"] . "'>" . $conference["strSchool"] . " (" . $conference["strLastName"] . ")</option>";
                              }
                            }
                              else {
                                echo "<option value='NULL'>No schools have selected to participate</option>";
                              }

                          echo "</select>";
                        echo "</div>";
                          if($numConference > 0) {
                            echo "<div class='col-sm-1'><button type='submit' class='btn btn-default'><i class='fa fa-plus'></i> Add School</button></div>";
                          }
                          else {
                           echo "<div class='col-sm-1'><button type='submit' class='disabled btn btn-default'><i class='fa fa-plus'></i> Add School</button></div>";
                          }
                    echo "<input type='hidden' value='add' name='action' />";
                    echo "<input type='hidden' value='" . $show["intTripShowID"] . "' name='showID' />";
                    echo "<input type='hidden' value='" . (int) $_GET["id"] . "' name='tripID' />";
                    echo "<input type='hidden' value='1' name='video' />";
                    echo "</div></form>";
                  echo "</div>";
                }

                echo "<hr class='clearfix' />";

                echo "<div class='row'>";
                  echo "<div class='col-sm-6'>";
                    echo "<h5><strong>Hotseat Schools</strong></h5>";
                    echo "<blockquote>";

                    $sql = "SELECT * FROM tblTripShowHotseat INNER JOIN tblUser ON tblTripShowHotseat.intUserID = tblUser.intUserID WHERE intTripShowID=" . $show["intTripShowID"];
                    $resultHotseatSelected = mysql_query($sql);
                    $numHotseatSelected = mysql_num_rows($resultHotseatSelected);

                    if($numHotseatSelected > 0) {
                      while($hotseatSelected = mysql_fetch_array($resultHotseatSelected)) {
                        echo "<i class='fa fa-building'></i> " . $hotseatSelected["strSchool"] . " <a  href='functions/doParticipate.php?showID=" . $show["intTripShowID"] . "&userID=" . $hotseatSelected["intUserID"] . "&action=remove&type=hotseat&tripID=" . (int) $_GET["id"] . "' class='btn btn-default btn-xs' href='#'><i class='fa fa-times'></i></a><br />";
                      }
                    }
                    else {
                      echo "No schools have been selected.";
                    }

                    echo "</blockquote>";
                  echo "</div>";
                  echo "<div class='col-sm-6'>";
                    echo "<h5><strong>Video Conference Schools</strong></h5>";
                    echo "<blockquote>";

                    $sql = "SELECT * FROM tblTripShowConference INNER JOIN tblUser ON tblTripShowConference.intUserID = tblUser.intUserID WHERE intTripShowID=" . $show["intTripShowID"];
                    $resultConferenceSelected = mysql_query($sql);
                    $numConferenceSelected = mysql_num_rows($resultConferenceSelected);

                    if($numConferenceSelected > 0) {
                      while($conferenceSelected = mysql_fetch_array($resultConferenceSelected)) {
                        echo "<i class='fa fa-building'></i> " . $conferenceSelected["strSchool"] . " <a class='btn btn-default btn-xs' href='functions/doParticipate.php?showID=" . $show["intTripShowID"] . "&userID=" . $conferenceSelected["intUserID"] . "&action=remove&type=video&tripID=" . (int) $_GET["id"] . "'><i class='fa fa-times'></i></a><br />";
                      }
                    }
                    else {
                      echo "No schools have been selected.";
                    }
                    echo "</blockquote>";
                  echo "</div>";
                echo "</div>";
                echo "</div>";
                echo "</div>"; // end panel
              }
            }
            else {
              echo "<div class='alert alert-info'>There have been no shows created for this zipTrip. <a href='addShow.php?id=" . $trip["intTripID"] . "' class='alert-link'>Create one now</a>.</div>";
            }
          ?>

        </div>


<?php } ?>


<?php
//for veiwTrip/edit page errors
$_SESSION["errorDateStart"] = 0;
$_SESSION["errorDateEnd"] = 0;
$_SESSION["errorTimeStart"] = 0;
$_SESSION["errorTimeEnd"] = 0;

?>

<?php
include_once("includes/footer.php");
?>
