<?php
include_once("includes/header.php");
include_once("includes/auth.php");

function timeDiff($firstTime,$lastTime) {
	//split minutes and seconds
	list($min_one, $sec_one) = preg_split('{:}',$firstTime,2, PREG_SPLIT_NO_EMPTY);
	list($min_two, $sec_two) = preg_split('{:}',$lastTime ,2, PREG_SPLIT_NO_EMPTY);
	//convert times into seconds
	$time_one = $min_one * 60 + $sec_one;
	$time_two = $min_two * 60 + $sec_two;
	//return times
	return ($time_two - $time_one);
	}


?>




<!-- start body -->
     <div class="section">
      <div class="container">
      	<div class="row">
        <div class="col-lg-12">
          <h1 class="page-header">My zipTrips <small>Interact and manage your zipTrips here</small></h1>
          <ol class="breadcrumb">
  		    <li><a href="dashboard.php">Dashboard</a></li>
            <li class="active">My zipTrips</li>
           </ol>
        </div>
     	</div>



<?php
  $sql = "SELECT * FROM tblUserShow INNER JOIN tblTripShow ON tblUserShow.intTripShowID = tblTripShow.intTripShowID WHERE tblUserShow.intUserID=" . $_SESSION["userID"] . " GROUP BY intTripID";
  $result = mysql_query($sql);
  $numShows = mysql_num_rows($result);
  ?>

<div class="row">
<div class="col-lg-12 col-md-12">
        <?php
              if ($_SESSION["success"] != "") {
                echo "<div class='alert alert-success alert-dismissable'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button><p><strong>Success!</strong> " . $_SESSION["success"] . "</p></div>";
                $_SESSION["success"] = "";
          }

            if ($_SESSION["error"] != "") {
                echo "<div class='alert alert-danger alert-dismissable'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button><p><strong>Error!</strong> " . $_SESSION["error"] . "</p></div>";
                $_SESSION["error"] = "";
          }

	        if($numShows > 0) {
	        	while($show = mysql_fetch_array($result)) {
	    			$sql2 = "SELECT * FROM tblTrip WHERE intTripID=" . $show["intTripID"] . " LIMIT 1";
	    			$result2 = mysql_query($sql2);
	    			$trip = mysql_fetch_array($result2);
	    			($trip["isFeatured"]) ? $featured = "<i rel='tooltip' title='This zipTrip is featured!' class='fa fa-star'></i>" : $featured = "";

	    			if($trip["isArchived"]) { ?>

	    			<div class='panel panel-default'>
					<div class='panel-heading'>
						<h3><?= $trip["strTitle"] ?></h3>
					</div>
					<div class='panel-body'>
						<div class="alert alert-info"><i class="fa fa-eye-slash"></i> This show has been archived; therefore, it cannot be accessed at this time.</div>
					</div>
					</div>
	    <?php 		}
	    			else {

					$sqlShowUpcoming = "SELECT * FROM tblTripShow WHERE intTripID=" . $trip["intTripID"] . " AND dateEnd > NOW() ORDER BY dateBegin ASC LIMIT 1";
					$resultShowUpcoming = mysql_query($sqlShowUpcoming);
					$numShowsUpcoming = mysql_num_rows($resultShowUpcoming);
					$showUpcoming = mysql_fetch_array($resultShowUpcoming);

					$showLength = strtotime($showUpcoming["dateEnd"]) - strtotime($showUpcoming["dateBegin"]);
					$difference = strtotime($showUpcoming["dateEnd"]) - strtotime('now');
					$ratio = $difference/$showLength;

					$today = mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"));
        ?>
        			<!-- ##############################
							BEGIN SHOW DIV
        			################################-->

						<div class='panel panel-default'>
						<div style="background-color: #fff;" class='panel-heading'>
							<h2><i class="fa fa-rocket"></i> <?= $trip["strTitle"] ?></h2>
						</div>
						<div class='panel-body'>
							<div class="row">
								<div class="col-lg-3">
									<img src="<?= $trip["strPhoto250"] ?>" alt="<?= $trip["strTitle"] ?>" class="hidden-xs hidden-sm hidden-md img-thumbnail" />

								<?php if(strtotime($showUpcoming["dateBegin"]) < $today && strtotime($showUpcoming["dateEnd"]) > $today) { ?>
								<br /><br />
								<div class="panel panel-success">
									<div class="panel-heading">
										<h4 class="text-center">Submit Your Questions for Scientists to Answer!</h4>
									</div>
									<div class="panel-body">
										<p><small>Ask your question below for a Purdue scientist to answer after the show.</small></p>
										<form class="has-success form" action="functions/doQuestion.php" method="post" role="form">
										  <div class="form-group col-xs-12">
										    <label class="sr-only" for="question">Ask a scientist a question</label>
										    <textarea type="text" class="form-control input-lg" name="question" id="question" placeholder="Ask your question here."></textarea>
										  </div>
										  <input type="hidden" name="action" value="ask" />
										  <input type="hidden" name="showID" value="<?= $showUpcoming["intTripShowID"]?>" />
										  <button type="submit" class="btn btn-success btn-block">Ask!</button>
										</form>
										<br />
										<div class="progress progress-striped" rel="tooltip" title="<?= number_format($ratio*100, 0) ?>% of the show remains to answer questions.">
										  <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="<? echo (1-$ratio)*100; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <? echo (1-$ratio)*100; ?>%">
										  	<?= number_format(100-$ratio*100, 0) ?>%
										    <span class="sr-only"><?= 1-$ratio; ?> Complete</span>
										  </div>
										</div>

									</div>
								</div>
								<?php } ?>
								</div> <!-- / .col-lg-3 -->
								<div class="col-lg-9">

									<?php
									if(!empty($showUpcoming["dateBegin"])) {
									?>
									<div class="alert alert-info">
									<strong>Next Show</strong>: <?= date("l, F j, Y,", strtotime($showUpcoming["dateBegin"])) . " at " . date("g:i A", strtotime($showUpcoming["dateBegin"])) ?> EST (Duration: <?= timeDiff( date("G:i", strtotime($showUpcoming["dateBegin"])), date("G:i", strtotime($showUpcoming["dateEnd"]))) ?> minutes)
									<?php if(!empty($showUpcoming["strLiveURL"])) { echo "<br /><strong>Live URL:</strong> <a class='alert-link' href='" . $showUpcoming["strLiveURL"] . "'>" . $showUpcoming["strLiveURL"] . "</a>";} ?>
									</div>
									<?php } ?>

									<h4>Learn About <?= $trip["strTitle"] ?></h4>
									<p><?= $trip["strDescription"] ?></p>
							<div class="row">
								<div class="col-lg-6">
									<h4>Resources</h4>
									<ul>
									<?php if(!empty($trip["strTeacherGuide"])) {?>
										<li><a target="_blank" href="<?= $trip["strTeacherGuide"]?>">Teacher Guide</a></li>
									<?php } ?>
										<li><a href="assets/techGuide.pdf">Technical Guide</a></li>
									</ul>

									<?php
										$sqlShow = "SELECT * FROM tblTripShow JOIN tblUserShow ON tblTripShow.intTripShowID = tblUserShow.intTripShowID WHERE intTripID=" . $trip["intTripID"] . " AND intUserID=" . $_SESSION["userID"] . " AND dateEnd < NOW() ORDER BY dateEnd DESC;";
										$resultShow = mysql_query($sqlShow) or die(mysql_error());
										$numShows2 = mysql_num_rows($resultShow);

										if($numShows2 > 0) {
											echo "<h4>Your Shows</h4>";
											echo "<ul class='list-group'>";
											while($show = mysql_fetch_array($resultShow)) {

												$sql = "SELECT * FROM tblQuestions WHERE intTripShowID=" . $show["intTripShowID"] . " AND strAnswer <>''";
												$resultQuestion = mysql_query($sql);
												$numQuestions = mysql_num_rows($resultQuestion);

												(empty($show["strArchiveURL"])) ? $archiveText = "<i class='fa fa-video-camera'></i> &nbsp;Archive Video Coming Soon<br />" : $archiveText = "<i class='fa fa-video-camera'></i> &nbsp;<a target='_blank' href='" . $show["strArchiveURL"] . "'>Watch Archive</a><br />";
												($numQuestions > 0) ? $questionShow = "<i class='fa fa-flask'></i> &nbsp;<a href='viewQuestions.php?showID=" . $show["intTripShowID"] . "'>Read scientists' answers to student questions</a><br />" : $questionShow = "<i class='fa fa-flask'></i> &nbsp;Read scientists' answers to student questions.";
										?>
												<li class='list-group-item'>
													<h5><i class="fa fa-clock-o"></i> <strong><?= date("F j, Y", strtotime($show["dateBegin"]))?></strong></h5>
													<blockquote>
														<?= $archiveText ?>
														<?= $questionShow ?>
													</blockquote>
												</li>
										<?php
											} // end while
											echo "</ul>";
										}// end if

									?>

								</div>
								<div class="col-lg-6">
									<h4>Week of Scientists Videos</h4>
									<ul class='list-group'>
										<?php
											$sqlVideo = "SELECT * FROM tblTripVideo WHERE intTripID=" . $trip["intTripID"];
											$resultVideo = mysql_query($sqlVideo);
											$numVideo = mysql_num_rows($resultVideo);
											if($numVideo > 0) {
												while($video = mysql_fetch_array($resultVideo)) {
										?>
													<li class='list-group-item'>
														<h5><i style="color: #990000;" class='fa fa-youtube-play'></i> &nbsp;<strong><a target="_blank" href="<?= $video["strVideoURL"]?>"><?= $video["strVideoTitle"];?></strong></a></h5>
														<small><?= $video["strVideoDescription"] ?></small>
													</li>
										<?php }
											}
											else {
												echo "<li class='list-group-item'><small>There are currently no videos for this zipTrip.</small>.</li>";
											}
									?>
									</ul>
								</div>

								</div>
							</div>
							</div>


						</div>
						</div>



        <?php } } } else {
			$sql = "SELECT * FROM tblTrip JOIN tblTripShow ON tblTrip.intTripID = tblTripShow.intTripID WHERE isArchived=0 AND tblTrip.isFeatured=1 ORDER BY tblTripShow.dateBegin LIMIT 1";
			$result = mysql_query($sql);
			$featured = mysql_fetch_array($result);
        	?>

			<div class="alert alert-info">
			<h2><i class="fa fa-info-circle"></i> Let's get started...</h2>
			<p>Ready to sign up for a zipTrip? Head to your <a href="dashboard.php" class="alert-link">dashboard</a> to get started or take a look at our featured trip below! Then visit My zipTrips to manage your selections.</p>
			</div>
			<div class="panel panel-default">
			  <div class="panel-heading">
			    <h2>Featured Trip: <strong><?= $featured["strTitle"] ?></strong></h2>
			  </div>
			  <div class="panel-body">
			    <div class="col-md-5 pull-left">
			 		<img src="<?= $featured["strPhoto400"] ?>" alt="<?= $featured["strTitle"] ?>" />
			 	</div>
			 	<div class="col-md-7 pull-left">
			 		<h4>Learn About <?= $featured["strTitle"] ?></h4>
			    	<p><?= $featured["strDescription"] ?></p>
			    	<a href="joinTrip.php?tripID=<?= $featured["intTripID"]?>" class="btn btn-lg btn-block btn-success">Learn more</a>
			    </div>
			  </div>
			  <div class="panel-footer">
			  	<small><i class="fa fa-info-circle"></i> Not interested in <?= $featured["strTitle"]?>? We have <a href="dashboard.php">other zipTtrips</a>, too.</small>
			  </div>
			</div>

        <?php } ?>


	</div>
</div>

<?php
include_once("includes/footer.php");
?>