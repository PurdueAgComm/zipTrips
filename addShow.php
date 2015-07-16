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
    
<!-- general alerts -->     

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

<!-- start form -->
        <form id="dateTime" name="dateTime" class="form-horizontal" method="post" action="functions/doShow.php" role="form">

<!--Begin Start Date--> 
       <div class="form-group <?php if($_SESSION['errorAddDateStart'] ==1) echo 'has-error'; ?>">     
               <label for="dateStartAdd" class="col-sm-2 control-label">Begins*</label>
                 <div class="col-sm-5">
                    <div class="input-group">
                       <div class="input-group date datePicker" id="dp3" data-date-format="yyyy-mm-dd" >
                          <span class="input-group-addon"><i class="fa fa-calendar"></i></span>     
                              <input class="form-control" name="dateStartAdd" id="dateStartAdd" type="text" placeholder="YYYY/MM/DD" style="border-top-right-radius:5px; border-bottom-right-radius:5px; background-color:#ffffff"; size="30" onchange="this.form.dateEndAdd.value=this.value" value="<?= $_SESSION['dateStartAdd'];?>" readonly>
                                 <span class="add-on"></span>                     
                       </div>
                                 <span inline-help style="font-size:10px">Year - Month - Day</span>
                    </div>          
                 </div>

<!-- BEGIN start time -->                
  
           <div class="form-group <?php if($_SESSION['errorAddTimeStart'] ==1) echo 'has-error'; ?>">     
                 <div class="col-sm-5">
                    <div class="input-group">
                          <span class="input-group-addon"><i class="fa fa-clock-o"></i></span><input class="form-control" name="timeStartAdd" id="timeStartAdd" type="text" placeholder="hh:mm AM/PM" maxlength="8" value="<?php echo $_SESSION['timeStartAdd']; ?>">

                    </div>
                                 <span inline-help style="font-size:10px">12 Hour Time format - hh:mm AM/PM</span>
                 </div>
           </div>
       </div> <!-- close the START date and time form group -->


<!-- Start END date -->
<!-- using 2.3 Bootstratp calendar -->
       <div class="form-group <?php if($_SESSION['errorAddDateEnd']) echo 'has-error'; ?>">
          <label for="dateEndAdd" class="col-sm-2 control-label">Ends*</label>
            <div class="col-sm-5">
              <div class="input-group">         
                <div class="input-group date datePicker" id="dp4" data-date-format="yyyy-mm-dd">           
                   <span class="input-group-addon"><i class="fa fa-calendar"></i></span>  
          <input class="form-control" name="dateEndAdd" id="dateEndAdd" placeholder="YYYY/MM/DD" type="text" style="border-top-right-radius:5px; border-bottom-right-radius:5px; background-color:#ffffff"; size="30" onchange="this.value=this.form.dateStartAdd.value" value="<?= $_SESSION["dateEndAdd"]; ?>" readonly>

                                 <span class="add-on"></span>    
                </div>     
                                 <span inline-help style="font-size:10px">Year - Month - Day</span>     
              </div>
            </div>

<!-- Begin END time -->    
       <div class="form-group <?php if($_SESSION['errorAddTimeEnd']) echo 'has-error'; ?>">           
            <div class="col-sm-5">
              <div class="input-group" >
                <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
                   <input class="form-control" name="timeEndAdd" type="text"  id="timeEndAdd" placeholder="hh:mm AM/PM" maxlength="8" value="<?php echo $_SESSION['timeEndAdd']; ?>" >
              </div>
                                 <span inline-help style="font-size:10px">12 Hour Time format - hh:mm AM/PM</span>            
              </div>            
            </div>
       </div> <!-- close the START date and time form group -->
                 
 
       <div class="form-group <?php if($_SESSION['errorAddLiveURL']) echo 'has-error'; ?>">
          <label for="liveURL" class="col-sm-2 control-label">Live Show URL</label>
             <div class="col-sm-8">
                <input type="text" class="form-control" value="<?php echo $_SESSION['liveURAddL']; ?>"  id="liveURL" name="liveURL" placeholder="URL for Live Stream">
             </div>
       </div>

       <div class="form-group <?php if($_SESSION['errorAddArchiveURL']) echo 'has-error'; ?>">
          <label for="archiveURL" class="col-sm-2 control-label">Archived Show URL</label>
             <div class="col-sm-8">
                <input type="text" class="form-control" value="<?php echo $_SESSION['archiveURLAdd']; ?>"  id="archiveURL" name="archiveURL" placeholder="URL for Archived Media File">
             </div>
       </div>


       <div class="form-group <?php if($_SESSION['errorAddDescription']) echo 'has-error'; ?>">
            <label class="col-sm-2 control-label">Options</label>
               <div class="col-sm-8">
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

<!-- Add the show -->
          <div class="form-group">
            <div class="col-sm-offset-2 col-sm-8">
              <input name="showID" value="<?= $_GET['id'] ?>" type="hidden" />
              <input name="action" value="add" type="hidden" />
              <button type="submit" name ="submit" class="btn btn-success">Add Show</button>
             </div>
          </div>
                    
        </form> <!-- end the entire form -->

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
//for addShow errors
$_SESSION["errorAddDateStart"] = "";
$_SESSION["errorAddDateEnd"] = "";
$_SESSION["errorAddTimeStart"] = "";
$_SESSION["errorAddTimeEnd"] = "";
?>
  

      <?php
      include_once("includes/footer.php");
      ?>  


 