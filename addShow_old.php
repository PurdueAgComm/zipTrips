<?php
include_once("includes/header.php");
include_once("includes/auth-admin.php");

$tripID = (int) $_GET["id"];

if(!empty($tripID)) {
  $sql = "SELECT * FROM tblTrip WHERE intTripID=" . $tripID;
  $result = mysql_query($sql);
  $trip = mysql_fetch_array($result);


?>

<!-- start body -->
     <div class="section">
      <div class="container">
      	<div class="row">
        <div class="col-lg-12">
          <h1 class="page-header">Add a Show <small>Tie shows to individual zipTrips here.</small></h1>
           <ol class="breadcrumb">
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="cPanel.php">Control Panel</a></li> 
            <li><a href="trips.php">Manage zipTrips</a></li>
            <li class="active">Add a Show</li>
           </ol>
        </div>
      </div>

    <div class="row">
        <div class="col-lg-6 col-md-6">
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

            <div class="alert alert-warning"><strong>Attention!</strong> Currently, this page does not save anything to the database. Waiting for date formatting/UI.</div>

        <form class="form-horizontal" action="functions/doShow.php" method="post" role="form">
          <div class="form-group <?php if($_SESSION['errorStart']) echo 'has-error'; ?>">
            <label for="dateStart" class="col-sm-2 control-label">Begins*</label>
            <div class="col-sm-4">
              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-calendar"></i></span><input class="form-control" name="dateStart" id="dateStart" type="text" value="<?php echo $_SESSION['dateStart']; ?>">
              </div>
            </div>
            <div class="col-sm-4">
              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-clock-o"></i></span><input class="form-control" name="timeStart" type="text" value="<?php echo $_SESSION['timeStart']; ?>">
              </div>
            </div>
          </div>
          
          <div class="form-group <?php if($_SESSION['errorEnd']) echo 'has-error'; ?>">
          <label for="dateEnd" class="col-sm-2 control-label">Ends*</label>
            <div class="col-sm-4">
              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-calendar"></i></span><input class="form-control" name="dateEnd" id="dateEnd" type="text" value="<?php echo $_SESSION['dateEnd']; ?>">
              </div>
            </div>
            <div class="col-sm-4">
              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-clock-o"></i></span><input class="form-control" name="timeEnd" type="text" value="<?php echo $_SESSION['timeEnd']; ?>">
              </div>
            </div>
          </div>

          <div class="form-group <?php if($_SESSION['errorLiveURL']) echo 'has-error'; ?>">
            <label for="liveURL" class="col-sm-2 control-label">Live Show URL</label>
            <div class="col-sm-8">
              <input type="text" class="form-control" value="<?php echo $_SESSION['liveURL']; ?>"  id="liveURL" name="liveURL" placeholder="URL for Live Stream">
            </div>
          </div>


          <div class="form-group <?php if($_SESSION['errorArchiveURL']) echo 'has-error'; ?>">
            <label for="archiveURL" class="col-sm-2 control-label">Archived Show URL</label>
            <div class="col-sm-8">
              <input type="text" class="form-control" value="<?php echo $_SESSION['archiveURL']; ?>"  id="archiveURL" name="archiveURL" placeholder="URL for Archived Media File">
            </div>
          </div>



           <div class="form-group <?php if($_SESSION['errorDescription']) echo 'has-error'; ?>">
            <label class="col-sm-2 control-label">Options</label>
            <div class="col-sm-8">
              <div class="checkbox">
                <label>
                  <input name="isStreaming" type="checkbox" value="1"> Streaming Available
                </label>              
              </div>
               <div class="checkbox">
                <label>
                  <input name="isVideoConference" type="checkbox" value="1"> Video Conference Available
                </label>              
              </div>
               <div class="checkbox">
                <label>
                  <input name="isHotseat" type="checkbox" value="1"> Hotseat Available
                </label>              
              </div>
            </div>
          </div>

          <div class="form-group">
            <div class="col-sm-offset-2 col-sm-8">
              <input name="showID" value="<?= $_GET['id'] ?>" type="hidden" />
              <button type="submit" class="btn btn-success">Add Show</button>
            </div>
          </div>
        </form>

          </div>
        <div class="col-lg-6 col-md-6 well">
          <h3>You're creating a show for</h3>
          <?php ($trip["isFeatured"]) ? $featured = "<i class='fa fa-star'></i> Featured Trip" : $featured = "";  ?>
          <h4><i class="fa-rocket fa"></i> <strong><?= $trip["strTitle"]?></strong> <small><?= $featured ?></span></h4>
          
          <blockquote><?= $trip["strDescription"]?></blockquote>

        </div>

  </div>


          
<?php 
}
else { ?>
         <div class="section">
      <div class="container">
        <div class="row">
        <div class="col-lg-12">
          <h1 class="page-header">Add a Show <small>Tie shows to individual zipTrips here.</small></h1>
           <ol class="breadcrumb">
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="cPanel.php">Control Panel</a></li> 
            <li class="active">Add a Show</li>
           </ol>
        </div>
      </div>

    <div class="row">
        <div class="col-lg-6 col-md-6">
          <h2>Ruh Roh, Raggy!</h2>
          <p>Looks like you've wandered into unknown territory.</p>
          <p>Unfortunately, we can't add a show to your zipTrip because we don't know what zipTrip you want to add the show to. Go back, check the link, and try again.</p>


<?php } ?>

  

      <?php
      include_once("includes/footer.php");
      ?>  


 