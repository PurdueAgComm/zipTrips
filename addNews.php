<?php
include_once("includes/header.php");
include_once("includes/auth-admin.php");


if(!empty($_GET)) {

  $sql = "SELECT * FROM tblNews WHERE intNewsID=" . (int) $_GET["id"];
  $result = mysql_query($sql);
  $newsDB = mysql_fetch_array($result);

}

?>


<!-- start body -->
     <div class="section">
      <div class="container">
        <div class="row">
          <div class="col-lg-12">
            <h1 class="page-header">Manage News <small>Send out alerts by audience</small></h1>
            <ol class="breadcrumb">
    			<li><a href="dashboard.php">Dashboard</a></li>
              <li><a href="cPanel.php">Control Panel</a></li> 
              <li class="active">Manage News</li>
             </ol>
          </div>
       	</div>

        <div class="row">
           
           <div class="col-xs-4">
            <ul class="nav nav-pills nav-stacked">
              <li><a href="addNews.php"><strong>Create New Message</strong></a></li>
              <?php 
                $sql = "SELECT * FROM tblNews WHERE isArchived=0 ORDER BY dateCreated DESC LIMIT 10";
                $result = mysql_query($sql);
                while($news = mysql_fetch_array($result)) {

                  if($news["intNewsID"] == (int) $_GET["id"]) {
              ?>
                  <li class="active"><a href="addNews.php?id=<?= $news["intNewsID"] ?>"><?= $news["strSubject"]?><br/>Sent <small><?=  date("F j, Y", strtotime($news["dateCreated"])) ?></small></a></li>
            <?php
                }
                else {
            ?>
                  <li><a href="addNews.php?id=<?= $news["intNewsID"] ?>"><?= $news["strSubject"]?><br/><small>Sent <?=  date("F j, Y", strtotime($news["dateCreated"])) ?></small></a></li>

            <?php } }// end while
            ?>

        </ul>
      </div>


          <div class="col-xs-8">
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

            <div class="alert alert-info"><h3>Hey, you &mdash; listen up!</h3><p>Due to the volume of messages being sent out, this page may "stall" once you <code><i class="fa fa-envelope"></i> Send Message</code>. Please click once and wait until the success message displays before leaving this page. We don't have spiffy mail servers so we have to do it the old fashioned way.</p></div>

            <form class="form-horizontal" action="functions/doNews.php" method="post" role="form">
            <div class="panel panel-default">
              <div class="panel-heading">
                <h3 class="panel-title">Step 1: Target Your Audience</h3>
              </div>
              <div class="panel-body">
                <?php
                if(empty($_GET["id"])) {
                ?>
                
                  
                  <div class="form-group <?php if($_SESSION['errorTrip']) echo 'has-error'; ?>">
                    <label for="tripID" class="col-sm-2 control-label">Global</label>
                    <div class="col-sm-8">
                        <select class="form-control" id="tripID" name="tripID">
                          <?php

                          $sql = "SELECT * FROM tblTrip WHERE isArchived=0";
                          $result = mysql_query($sql);
                          $i=0;
                          while($trip = mysql_fetch_array($result)) {                          
                            if($i==0) {
                              echo "<option value='0'>Select an Option</option>";
                              echo "<option value='everyone'>Everyone</option>";
                            }

                            // TODO: #### FUTURE FEATURE: SORT BY TRIP

                          //   if ($_SESSION['tripIDNews'] == $trip["intTripID"]) {
                          //     echo "<option selected='selected' value='".$trip["intTripID"]."'> " .$trip["strTitle"]. "</option>";
                          //   } else {
                          //     echo "<option value='".$trip["intTripID"]."'> " .$trip["strTitle"]. "</option>";
                          //   }
                            $i++;
                          }

                        ?>
                      </select>
                      <div class="help-block"><small><i class="fa fa-info-circle"></i> &nbsp;Selecting Everyone will ignore all other filters.</small></div>
                    </div>
                  </div>

                   <div class="form-group <?php if($_SESSION['errorShow']) echo 'has-error'; ?>">
                    <label for="showID" class="col-sm-2 control-label">Show</label>
                    <div class="col-sm-8">
                        <select class="form-control" id="showID" name="showID">
                          <?php

                          $sqlShow = "SELECT * FROM tblTripShow";
                          $resultShow = mysql_query($sqlShow);
                          
                          $i=0;
                          while($show = mysql_fetch_array($resultShow)) {       

                            $sql = "SELECT * FROM tblTrip WHERE isArchived=0 AND intTripID=" . $show["intTripID"];
                            $result = mysql_query($sql);
                            $trip = mysql_fetch_array($result);

                            if($i==0) {
                              echo "<option value='0'>Select a Show</option>";
                            }

                            if ($_SESSION['showIDNews'] == $show["intTripID"]) {
                              echo "<option selected='selected' value='".$show["intTripShowID"]."'> " . $trip["strTitle"] . " - " . $show["dateBegin"] . "</option>";
                            } else {
                              echo "<option value='".$show["intTripShowID"]."'> " . $trip["strTitle"] . " - " .  date("F j, Y", strtotime($show["dateBegin"])) . "</option>";
                            }
                            $i++;
                          }


                        ?>
                      </select>
                      <div class="help-block"><small><i class="fa fa-info-circle"></i> &nbsp;A show selection will override any selection of Trip.</small></div>
                    </div>
                  </div>


                  <div class="form-group <?php if($_SESSION['errorGrade']) echo 'has-error'; ?>">
                    <label for="grade" class="col-sm-2 control-label">Grade</label>
                    <div class="col-sm-8">
                        <select class="form-control" id="grade" name="grade">
                          <?php
                          $gradeArray = array("Choose a Grade", "6th",  "7th", "8th");
                          foreach($gradeArray as $value) {
                            if ($_SESSION['addGrade'] == $value) {
                              echo "<option selected='selected' value='".$value."'> " .$value. "</option>";
                            } else {
                              echo "<option value='".$value."'> ".$value."</option>";
                            }
                          }
                        ?>
                      </select>
                    </div>
                  </div>
                  <?php } // end if 
                  else {?>

                    <div class="alert alert-info">This section is not editable when managing older messages.<br/>In addition, your message will only be updated on the website - a new email will <strong>not</strong> be sent out.</div>

                  <?php } // end else ?>
                        
              </div>
            </div>

            <div class="panel panel-default">
              <div class="panel-heading">
                <h3 class="panel-title">Step 2: Tailor Your Message</h3>
              </div>
              <div class="panel-body">
                
                  <div class="form-group <?php if($_SESSION['errorSubject']) echo 'has-error'; ?>">
                    <label for="subject" class="col-sm-2 control-label">Subject</label>
                    <div class="col-sm-8">
                      <input type="text" class="form-control" value="<?php echo $newsDB['strSubject']; ?>"  id="subject" name="subject" placeholder="Subject">
                    </div>
                  </div>

                  <div class="form-group <?php if($_SESSION['errorDescription']) echo 'has-error'; ?>">
                    <label for="message" class="col-sm-2 control-label">Message</label>
                    <div class="col-sm-8">
                      <textarea type="text" rows="8" class="form-control" id="message" name="message" placeholder="Type your message here. Currently, this does not support HTML"><?php echo $newsDB['strMessage']; ?></textarea>
                    </div>
                  </div>

                  <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-8">
                      <?php if(empty($_GET["id"])) { ?>
                        <input name="action" value="send" type="hidden" />
                        <button type="submit" class="btn btn-success"><i class="fa fa-envelope"></i> Send Message</button>
                      <?php } else { ?>
                        <input name="action" value="edit" type="hidden" />
                        <input name="newsID" value="<?= (int) $_GET["id"]?>" type="hidden" />
                        <button type="submit" class="btn btn-success"><i class="fa fa-pencil"></i> Update Message</button>
                        <a href="functions/doNews.php?action=archive&id=<?= (int) $_GET["id"]?>" class="btn btn-danger"><i class="fa fa-archive"></i> Archive Message</a>
                      <?php } ?>
                    
                    </div>
                  </div>
                </form>
                        
              </div>
            </div> <!-- end panel-->

          </div>
        </div>

       



       </div>
     </div>




<?php
include_once("includes/footer.php");
?>