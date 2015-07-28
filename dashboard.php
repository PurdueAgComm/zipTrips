<?php
include_once("includes/header.php");
include_once("includes/auth.php");

$sql = "SELECT * FROM tblTrip JOIN tblTripShow ON tblTrip.intTripID = tblTripShow.intTripID WHERE isArchived=0 AND tblTrip.isFeatured=1 ORDER BY tblTripShow.dateBegin LIMIT 1";
$result = mysql_query($sql);
$featured = mysql_fetch_array($result);

$sql = "SELECT * FROM tblTrip WHERE isArchived=0";
$result = mysql_query($sql) or die("<hr>" . mysql_error()); ;
$numTrips = mysql_num_rows($result);
?>


<!-- start body -->
     <div class="section">
      <div class="container">
      	<div class="row">
        <div class="col-lg-12">
          <h1 class="page-header">Dashboard <small>Your one-stop for all things zipTrips&trade;!</small></h1>
          <ol class="breadcrumb">
            <li class="active">Dashboard</li>
          </ol>
        </div>
      </div>

    <div class="row">
        <div class="col-lg-8 col-md-8">
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

			 	<div class="col-sm-5 pull-left">
			 		<img class="img-thumbnail hidden-xs" src="<?= $featured["strPhoto250"] ?>" alt="<?= $featured["strTitle"] ?>" />
			 	</div>
			 	<div class="col-sm-7 pull-left">
			    	<h2><?= $featured["strTitle"] ?></h2>
			    	<p><?= $featured["strBlurb"] ?></p>
			    	<a id="dashboardLearnMore" href="joinTrip.php?tripID=<?= $featured["intTripID"]?>" class="btn btn-lg btn-block btn-success">Learn more</a>
			    </div>
			</div>

			<hr>

			<h1>zipTrips Archive</h1>
			<p>Past zipTrips are archived and waiting for your class to experience at your convenience! Why not sign up for all of them?</p>
			<p>&nbsp;</p>
			<?php
			$i = 0;
			$count = 0;
			while($trip = mysql_fetch_array($result)) {
				($i == 0) ? $row = "<div class='row'>" : $row = "";
				echo $row;
			?>

	 			<div class="col-xs-11 col-md-4 hidden-sm hidden-xs">
					<div class="thumbnail pull-left col-xs-12">
					   <img src="<?=$trip["strPhoto250"] ?>" alt="<?= $trip["strTitle"]?>" >
					    	<div class="caption">
					    		<h4 style="text-align: center;"><?=  $trip["strTitle"] ?></h4>
					    		<?php
					    			$sqlShow = "SELECT * FROM tblTripShow WHERE intTripID=" . $trip["intTripID"] . " ORDER BY dateBegin";
					    			$resultShow = mysql_query($sqlShow) or die("sdf");
					    			$show = mysql_fetch_array($resultShow);
					    		if(!empty($show["intTripShowID"])) {
					    		?>
					    		<p><a href="joinTrip.php?tripID=<?= $trip["intTripID"]?>" class="btn btn-default btn-block" role="button"><span style="color: #2a6496;">Learn more</span></a></p>
					    		<?php } else { ?>
								<p><a href="#" class="btn btn-default disabled btn-block" role="button"><i class="fa fa-times"></i> No Shows Available</a></p>
					    		<?php } ?>
					   		</div>
					</div>
				</div>

				<div class="panel panel-default hidden-md hidden-lg hidden-xl">
				  <div class="panel-heading">
				    <h2 class="panel-title"><i class="fa fa-rocket"></i> <strong><?= $trip["strTitle"]?></strong></h2>
				  </div>
				  <div class="panel-body">
				  	<div class="col-sm-5 hidden-xs">
				    	 <img src="<?=$trip["strPhoto250"] ?>" alt="<?= $trip["strTitle"]?>" >
				 	</div>
				 	<div class="col-xs-12 col-sm-7">
				 		<h4>About <?= $trip["strTitle"];?></h4>
				 		<p><?= $trip["strDescription"] ?></p>
					   <?php
		    			$sqlShow = "SELECT * FROM tblTripShow WHERE intTripID=" . $trip["intTripID"] . " ORDER BY dateBegin";
		    			$resultShow = mysql_query($sqlShow) or die("sdf");
		    			$show = mysql_fetch_array($resultShow);
				    		if(!empty($show["intTripShowID"])) {
				    		?>
				    		<p><a href="joinTrip.php?tripID=<?= $trip["intTripID"]?>" class="btn btn-default btn-block" role="button">Join Now <i class="fa fa-long-arrow-right"></i></a></p>
				    		<?php } else { ?>
							<p><a href="#" class="btn btn-default disabled btn-block" role="button"><i class="fa fa-times"></i> No Shows Available</a></p>
				    		<?php } ?>
					</div>
				  </div>
				</div>

			<?php
			$count++;
				if($i==0) {
					$i=3;
				} else if ($i == 1) {
				echo "</div>";
				echo "<br />";
				}

			$i--;
			$count++;

			}

			if($numTrips%3 != 0) {
				echo "</div>";
			}

			?>

		</div>

		<div class="col-lg-4 col-md-4">


		<?php

			$sqlNews = "SELECT * FROM tblNews JOIN tblUserNews ON tblNews.intNewsID = tblUserNews.intNewsID WHERE tblUserNews.intUserID=" . $_SESSION["userID"] . " AND tblNews.isArchived=0 ORDER BY dateCreated DESC LIMIT 3;";
			$resultNews = mysql_query($sqlNews);
			$numNews = mysql_num_rows($resultNews);

			echo "<h1>News Alerts</h1>";
			echo "<ul class='media-list'>";

			if($numNews > 0) {
				while($news = mysql_fetch_array($resultNews)) {
		?>
					<li class="media">
					    <div class="media-body">
					      <h4 class="media-heading"><?= $news["strSubject"]?></h4>
					      <?php
							if(strlen($news["strMessage"]) > 300) {
						  ?>
					      	<p><?= stripslashes(substr($news["strMessage"], 0, 300)) ?>...</p>
					      <?php } else { ?>
							<p><?= stripslashes(substr($news["strMessage"], 0, 300)) ?></p>
					      <?php } ?>
					      <a class='pull-right btn btn-default btn-link' href='news.php?newsID=<?= $news["intNewsID"] ?>'>Read more <i class="fa fa-arrow-circle-o-right"></i></a>
					    </div>
					</li>

		<?php } // while
		} // if
		else {
		?>
			<li class="media">
					    <div class="media-body">

					      <p>News about zipTrips, shows, and participation will appear here. Currently, you have no alerts.</p>
					    </div>
					</li>

		<?php } //else ?>
			</ul>

		</div>
	</div>





<?php
include_once("includes/footer.php");
?>