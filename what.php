<?php
include_once("includes/header.php");
?>


<!-- start body -->
     <div class="section">
      <div class="container">
      	<div class="row">
        <div class="col-lg-12">
          <h1 class="page-header">What is zipTrips&trade;?</h1>
          <ol class="breadcrumb">
  			<li><a href="index.php">Home</a></li>
            <li class="active">What is zipTrips?</li> 
           </ol>
        </div>
     	</div>


		<div class="row">
			<div class="col-md-4">
      <p>Purdue zipTrips are virtual electronic field trips that bring Purdue University scientists into your classroom. Through the wonders of technology, students interactively visit labs, greenhouses, aquaculture facilities, Discovery Park, the veterinary school, and other amazing places that are off limits to your students even in a real-life field trip.</p>

      <p>The centerpiece of each zipTrip is a live webcast featuring factual, unbiased scientific information presented in an entertaining way. Your students will be able to email questions during the show for the scientists to answer. And each trip includes supplementary online videos that feature the work of Purdue scientists. 

      <p>There's nothing like Purdue zipTrips when it comes to introducing your students to cutting-edge research, scientific inquiry, and science careers. And best of all, it’s free! </p>
      </div>

      <div class="col-md-8">
       <?php
        $sql = "SELECT * FROM tblTrip JOIN tblTripShow ON tblTrip.intTripID = tblTripShow.intTripID WHERE isArchived=0 AND tblTrip.isFeatured=1 ORDER BY tblTripShow.dateBegin LIMIT 1";
        $result = mysql_query($sql);
        $featured = mysql_fetch_array($result);
      ?>
      
      <div class="panel panel-default">
        <div class="panel-heading">
          <h2>Featured Trip: <strong><?= $featured["strTitle"] ?></strong></h2>
        </div>
        <div class="panel-body">
          <div class="col-md-5 pull-left">
          <img class="img-responsive" src="<?= $featured["strPhoto250"] ?>" alt="<?= $featured["strTitle"] ?>" />
        </div>
        <div class="col-md-7 pull-left">
          <h4>Learn About <?= $featured["strTitle"] ?></h4>
            <p><?= $featured["strDescription"] ?></p>
            <a href="joinTrip.php?tripID=<?= $featured["intTripID"]?>" class="btn btn-lg btn-block btn-success">Learn more</a>
          </div>
        </div>
      </div>
      </div>
    </div>
<?php
include_once("includes/footer.php");
?>