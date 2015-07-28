<?php
include_once("includes/header.php");
include_once("includes/auth.php");

$sql = "SELECT * FROM tblTrip JOIN tblTripShow ON tblTrip.intTripID = tblTripShow.intTripID WHERE tblTrip.intTripID=" . (int) $_GET["tripID"];
$result = mysql_query($sql);
$trip = mysql_fetch_array($result);

?>
<!-- start body -->
     <div class="section">
      <div class="container">
      	<div class="row">
        <div class="col-lg-12">
          <h1 class="page-header"><?= $trip["strTitle"]; ?></h1>
          <ol class="breadcrumb">
            <li><a href="dashboard.php">Dashboard</a></li>
            <li class="active"><?= $trip["strTitle"] ?></li>
          </ol>
        </div>
      </div>

 <div class="row">

        <div class="col-md-4">
          <img class="img-responsive img-thumbnail" src="<?= $trip["strPhoto400"] ?>" alt="<?= $trip["strTitle"] ?>">
        </div>

        <div class="col-md-8">
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

          <h3><i class='fa fa-rocket'></i> About <?= $trip["strTitle"] ?></h3>
          <p><?= $trip["strDescription"]?></p>

          <p>Program includes:</p>

          <ul>
          	<li>Live show (approximately 50 minutes)</li>
          	<li>Supporting classroom materials</li>
          	<ul>
          		<li>teacher's guide</li>
          		<li>short videos featuring Purdue scientists discussing their work</li>
          	</ul>
          </ul>

          <h3><i class='fa fa-calendar'></i> Available Shows</h3>
          <?php
            $sql = "SELECT * FROM tblTripShow WHERE intTripID=" . (int) $_GET["tripID"] . " ORDER BY tblTripShow.dateBegin DESC";
	        $result = mysql_query($sql);
	        $numShows = mysql_num_rows($result);

		        if(!empty($numShows)){
		          while($show = mysql_fetch_array($result)) {

			          $sql = "SELECT * FROM tblUserShow WHERE intUserID=" . $_SESSION["userID"] . " AND intTripShowID=" . $show["intTripShowID"];
				      $resultUserShows = mysql_query($sql);
				      $userShow = mysql_fetch_array($resultUserShows);

	            		($show["isVideoConference"]) ? $video = "<input id='isVideoConference' name='isVideoConference' type='checkbox' value='1'> I would like to volunteer to participate in <a href='#' data-toggle='modal' rel='tooltip' title='Lean more about what video conferencing entails.' data-target='#video'>video conferencing <i class='fa fa-external-link'></i></a>." : $video = "";
	            		($show["isHotSeat"]) ? $hotseat = "<input id='isHotSeat' name='isHotSeat' type='checkbox' value='1'> I would like to volunteer to participate in <a href='#' data-toggle='modal' data-target='#hotseat' rel='tooltip' title='Learn more about what Hotseat entails.'>Hotseat <i class='fa fa-external-link'></i></a>." : $hotseat = "";


	            		echo "<div class='panel panel-default'>";
						echo "<div class='panel-heading'>";
			            echo "<h3><i class='fa fa-clock-o'></i> " . date("F j, Y,", strtotime($show["dateBegin"])) . " at " . date("g:i A", strtotime($show["dateBegin"])) . " EST</h3>";
						echo "</div>";
						echo "<div class='panel-body'>";

						$today = mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"));


			            if(empty($userShow["intUserID"])) {



			       	?>
							<form method="post" role="form" class="form-horizontal" action="functions/doShow.php">
							<?php
							if(strtotime($show["dateBegin"]) > $today) {

			            		if($show["isVideoConference"] || $show["isHotseat"]) {
			            			echo "<h4>Volunteer for Interactivity</h4>";
			            			echo "<p>We select students to interact with during each live show. If you would like to volunteer, please select from the following options: ";
			            		}
			            		else {
			            			echo "<h4>No Participation Availabile</h4>";
			            			echo "<p>You may still watch the stream of this show; however, live video conferencing and HotSeat is unavailable.</p>";
			            		}

								if($show["isVideoConference"] || $show["isHotseat"]) { ?>
					           <div class="form-group <?php if($_SESSION['errorParticipate']) echo 'has-error'; ?>">
					            <label class="col-sm-1 control-label"></label>
					            <div class="col-sm-8">
					               <div class="checkbox">
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
					         <?php } } else {
					         	echo "<p><span class='label label-default'><i class='fa fa-video-camera'></i> Archived</span> This show has been recorded. Sign up now to watch the archive.</p>";
					         } ?>
					          <div class="form-group">
					            <div class="col-sm-12">
					              <input name="action" value="join" type="hidden" />
					              <input name="showID" value="<?= $show['intTripShowID'] ?>" type="hidden" />
					              <button id="joinTripSignUp" type="submit" class="btn btn-block btn-success"><i class='fa fa-plus'></i> Sign up now</button>
					            </div>
					          </div>
							</form>
		        <?php
		         }
		         else {
		         	($userShow["isVideoConference"]) ? $video = "<i class='fa fa-check'></i> You have signed up to participate in <strong>video conferencing</strong>." : $video = "<i class='fa fa-times'></i> You did not sign up to participate in video conferencing.";
	            	($userShow["isHotSeat"]) ? $hotseat = "<i class='fa fa-check'></i> You have signed up to participate in <strong>Hotseat</strong>." : $hotseat = "<i class='fa fa-times'></i> You did not sign up to participate in Hotseat.";
		         	?>

					<h4><span style="color: #47a447;"><i class='fa fa-check-square'></i></span> You've joined this zipTrip show!</h4>
					<p>Get resources, participate in discussion, and view all of your zipTrips by visiting <a href="myTrips.php">My zipTrips</a>.
					<blockquote>
					<ul style='list-style-type: none; padding-left: 5px;'>
						<li><?= $video ?></li>
						<li><?= $hotseat ?></li>
					</ul>
					</blockquote>
				<?php
				}
		            echo "</div>"; // end panel body
					echo "</div>"; // end panel
		        } // while
		    } // if
          ?>



        </div>
</div>


<!-- Button trigger modal -->


<!-- Modal -->
<div class="modal fade" id="video" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">What is Video Conferencing?</h4>
      </div>
      <div class="modal-body">
       <p><strong>What is video conferencing?</strong> Video conferencing is the ability to send simultaneous two-way video and audio transmissions over the Internet. It requires special equipment that is available at many—but not all—schools.</p>

       <p><strong>How do students use video conferencing to participate in zipTrips?</strong> Each zipTrip has a live audience that appears virtually in the program via video conferencing. The host of the show will see this audience through a monitor on set and talk to them during the live show.</p>

       <p><strong>Can everyone use video conferencing?</strong> Participation through video conferencing is limited to one site per live show. If you are interested in volunteering, select this option, and those who are chosen to use this feature will be notified via email.</p>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- Modal -->
<div class="modal fade" id="hotseat" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">What is Hotseat?</h4>
      </div>
      <div class="modal-body">
        <p><strong>What is Hotseat?</strong> Hotseat uses social networking to create a collaborative classroom. During the live show, it allows students to ask questions and post comments, see questions and comments posted by other students, and vote on which questions scientists should answer during the live show.</p>

        <p><strong>How do students access Hotseat?</strong> Students can access Hotseat from almost any device with an Internet connection, including:</p>
		<ul>
			<li>desktop or laptop computers</li>
			<li>mobile devices, such as cell phones and tablets</li>
		</ul>

		<p>Students can work individually or in groups, depending on how many devices are available.</p>

		<p><strong>What software is required?</strong> Computer users need an Internet browser to log in through at <a href="http://www.purdue.edu/hotseat">www.purdue.edu/hotseat</a>. Mobile users will need to download the Hotseat app from the Apple App Store or Google Play. All users will need to create a free Hotseat account to get started.</p>

		<p><strong>Can everyone use Hotseat?</strong> Participation through Hotseat is limited, and demand is usually high. If you are interested in volunteering, select this option, and those who are chosen to use this feature will be notified via email.</p>


      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->








<?php
include_once("includes/footer.php");
?>