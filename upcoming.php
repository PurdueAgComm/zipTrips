<?php
include_once("includes/header.php");

$sqlFeatured = "SELECT * FROM tblTrip WHERE isFeatured=1";
$resultFeatured = mysql_query($sqlFeatured);
$featured = mysql_fetch_array($resultFeatured);

$sqlShowUpcoming = "SELECT * FROM tblTripShow WHERE intTripID=" . $featured["intTripID"] . " AND dateEnd > NOW() ORDER BY dateBegin ASC LIMIT 1";
$resultShowUpcoming = mysql_query($sqlShowUpcoming);
$numShowsUpcoming = mysql_num_rows($resultShowUpcoming);
$showUpcoming = mysql_fetch_array($resultShowUpcoming);


?>


<!-- start body -->
     <div class="section">
      <div class="container">
      	<div class="row">
        <div class="col-lg-12">
          <h1 class="page-header">When is the next zipTrip&trade;?</h1>
          <ol class="breadcrumb">
  			<li><a href="index.php">Home</a></li>
            <li class="active">When is the next zipTrip?</li> 
           </ol>
        </div>
     	</div>

		<div class="row">
			<div class="col-xs-12">
        <?php if($numShowsUpcoming > 0) { ?>
           <div class="panel panel-default">
              <div class="panel-heading">
                <h2>Upcoming zipTrip: <strong><?= $featured["strTitle"] ?></strong></h2>
              </div>
              <div class="panel-body">
                <div class="col-md-5 pull-left">
                <img class="img-responsive" src="<?= $featured["strPhoto400"] ?>" alt="<?= $featured["strTitle"] ?>" />
              </div>
              <div class="col-md-7 pull-left">
                <h4><i class="fa fa-clock-o"></i> <?= date("F d, Y,", strtotime($showUpcoming["dateBegin"])) ?> at <?= date("h:i A", strtotime($showUpcoming["dateBegin"])) ?> EST</h4>
                  <p><?= $featured["strDescription"] ?></p>
                  <a href="joinTrip.php?tripID=<?= $featured["intTripID"]?>" class="btn btn-lg btn-block btn-success">Learn more!</a>
                </div>
              </div>
            </div>
          <?php } else { ?>
            
            <h2>We're hard at work...</h2>
            <p>We're working hard developing the next live zipTrip; unfortunately, we do not have a date set yet. Check back often for information on our upcoming zipTrip. While you're waiting, watch an archived zipTrip from the list below.</p>

            <div class="section">
      <div class="container">
         <div class="col-lg-12">
            <h4>Watch our archived zipTrips</h4>
            <hr>
          </div>

        <?php 
        $sql = "SELECT * FROM tblTrip WHERE isArchived=0";
        $result = mysql_query($sql);
        $numTrips = mysql_num_rows($result);
        $i = 0;
        $count = 0;
        while($trip = mysql_fetch_array($result)) { 
          ($i == 0) ? $row = "<div class='row'>" : $row = "";
          echo $row;
        ?>
          <div class="col-xs-11 col-md-4 hidden-sm hidden-xs">
            <div class="thumbnail pull-left col-xs-12">
               <img src="<?=$trip["strPhoto400"] ?>" alt="<?= $trip["strTitle"]?>" >
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
                 <img src="<?=$trip["strPhoto250"] ?>" class="img-responsive" alt="<?= $trip["strTitle"]?>" >
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
                  <p><a href="joinTrip.php?tripID=<?= $trip["intTripID"]?>" class="btn btn-default btn-block" role="button">Learn more</a></p>
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
    </div>

          <?php } ?>
      </div>
    </div>


<?php
include_once("includes/footer.php");
?>