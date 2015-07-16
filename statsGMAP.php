<?php
include_once("includes/header.php");
include_once("includes/auth-admin.php");
?>
<script type="text/javascript"
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCZRNU2LKASwmzpvnMX1SCBwuPZMM2DDi0&sensor=false">
</script>



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
            <div class="col-lg-12">
              <div class="alert alert-warning"><i class="fa fa-warning"></i> This page is not completed yet. Please do not use any actions on this page.</div>
            </div>

            <div class="col-xs-3">
              <ul class="nav nav-pills nav-stacked">
                <? if($_GET["mode"] != "") { ?>
                  <li><a href="stats.php"><i class="fa fa-arrow-circle-o-left"></i> Back</a></li>
                <? } 
                  if($_GET["mode"] == "1") {

                    if($_GET["mode"] > 0) {
                      $sql2 = "SELECT * FROM tblTripShow JOIN tblTrip ON tblTrip.intTripID = tblTripShow.intTripID WHERE tblTrip.isArchived=0 AND tblTripShow.intTripID =" . (int) $_GET["id"];
                      $result2 = mysql_query($sql2);

                      while($show = mysql_fetch_array($result2)) {
                        $sql3 = "SELECT * FROM tblUserShow JOIN tblTripShow ON tblUserShow.intTripShowID = tblTripShow.intTripShowID WHERE tblUserShow.intTripShowID=" . $show["intTripShowID"];
                        $result3 = mysql_query($sql3);
                        ?>


                        <script type="text/javascript">
                        var geocoder;
                        var map;
                        function initialize() {
                          geocoder = new google.maps.Geocoder();
                          var latlng = new google.maps.LatLng(40.423494, -86.914661);
                          var mapOptions = {
                            zoom: 4,
                            center: latlng
                          }
                          map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);

                          <?php  //Starts while loop so all addresses for the given information will be populated.
                          while($row2 = mysql_fetch_array($result3)) { ?>    

                            
                            <?php 
                            $sqlPerson = "SELECT * FROM tblUser WHERE intUserID=" . $row2["intUserID"];
                            $resultPerson = mysql_query($sqlPerson);
                            $row = mysql_fetch_array($resultPerson);
                            ?>

                            geocoder = new google.maps.Geocoder();
                            var address = '<?php echo $row['strAddress'].', '.$row['strCity'].', '.$row['strState'].', '.$row['strZip'].', '.$row['strCountry']; ?>';
                            geocoder.geocode( { 'address': address}, function(results, status) {
                             if (status == google.maps.GeocoderStatus.OK) {
                            var marker<?php print $row['intUserID']; ?> = new google.maps.Marker({
                            map: map, 
                            position: results[0].geometry.location,
                            title: "Click to view school and teacher."
                            });



                            //var contentString manages what is seen inside of the popup            
                            var contentString = 
                            '<?= "<i class=\'fa fa-building-o\'></i> " . $row["strSchool"]?><br/>'+
                            '<?= "<i class=\'fa fa-user\'></i> " . $row["strFirstName"] . " " . $row["strLastName"]?>';

                            var infowindow = new google.maps.InfoWindow({
                            content: contentString
                            });
                            google.maps.event.addListener(marker<?php print $row['intUserID']; ?>, 'click', function() {
                            infowindow.open(map,marker<?php print $row['intUserID']; ?>);
                            });
                            } else {
                            alert("Geocode was not successful for the following reason: " + status);
                            }
                            });

                          <?php
                            $counterAll++;
                          } //ends while
                          ?>
                        }
                        google.maps.event.addDomListener(window, 'load', initialize);
                        </script>


                        <?php
                      }
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
                        <small><?= $show["dateBegin"] ?></small></a>
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


            <?php if($_GET["mode"] == 1) {           ?>
              <h2>View Users By Trip <small>(<?= $counterAll ?>)</small></h2>
              <div id="map-canvas" style='width: 100%; height: 400px;'/></div>
              <small><abbr class="pull-right" style="cursor: pointer;" rel="tooltip" title="Not seeing all of the marks? Zoom in to a place you think there may be several schools. In addition, if there are several teachers with the same address, the mark will only show the first teacher to sign up and the other teachers are inaccessible behind that mark. This map is intended to show you general population over the world map and not density by location.">There should be more marks!</abbr></small>
            <?php } else if($_GET["mode"] == 2) {

              if(!empty($_GET["id"])) {
                $sqlShows2 = "SELECT * FROM tblTripShow WHERE intTripShowID=" . (int) $_GET["id"] . " ORDER BY dateBegin DESC";
                $resultShows2 = mysql_query($sqlShows2) or die(mysql_error());
                $numShows2 = mysql_num_rows($resultShows2);

              }

              ?>
              <h2>View Users By Show <small>(coming soon)</small></h2>

            <?php } ?>

            </div>
       </div>






<?php
include_once("includes/footer.php");
?>
