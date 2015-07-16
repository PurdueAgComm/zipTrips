<?php
include_once("includes/header.php");
include_once("includes/auth-admin.php");

$sql = "SELECT * FROM tblTripShow JOIN tblQuestions ON tblQuestions.intTripShowID=tblTripShow.intTripShowID GROUP BY tblTripShow.intTripShowID ORDER BY tblTripShow.dateBegin DESC;";
$resultShow = mysql_query($sql);
?>

<script>
	var reloading;

	function checkReloading() {
	    if (window.location.hash=="#autoreload") {
	        reloading=setTimeout("window.location.reload();", 30000);
	        document.getElementById("reloadCB").checked=true;
	    }
	}

	function toggleAutoRefresh(cb) {
	    if (cb.checked) {
	        window.location.replace("#autoreload");
	        reloading=setTimeout("window.location.reload();", 30000);
	    } else {
	        window.location.replace("#");
	        clearTimeout(reloading);
	    }
	}

	window.onload=checkReloading;
</script>

<!-- start body -->
     <div class="section">
      <div class="container">
      	<div class="row">
        <div class="col-lg-12">
          <h1 class="page-header">Questions <small>Answer and publish questions by show</small></h1>
          <ol class="breadcrumb">
  		    <li><a href="dashboard.php">Dashboard</a></li>
  		    <li><a href="cPanel.php">Control Panel</a></li>
            <li class="active">Questions</li>
           </ol>
        </div>
     	</div>

     	<div class="row">
       		<div class="col-xs-4">
       			<ul class="nav nav-pills nav-stacked">
       				<?php 
       					while($show = mysql_fetch_array($resultShow)) {

       						$sql = "SELECT * FROM tblTrip WHERE intTripID=" . $show["intTripID"];
       						$resultTrip = mysql_query($sql);
       						$trip = mysql_fetch_array($resultTrip);

       						if($show["intTripShowID"] == (int) $_GET["showID"]) {
       				?>
  								<li class="active"><a href="questions.php?showID=<?= $show["intTripShowID"] ?>"><?= $trip["strTitle"]?><br/><small><?=  date("F j, Y @ g:i a", strtotime($show["dateBegin"])) ?></small></a></li>
  					<?php
  							}
  							else {
  					?>
  								<li><a href="questions.php?showID=<?= $show["intTripShowID"] ?>"><?= $trip["strTitle"]?><br/><small><?=  date("F j, Y", strtotime($show["dateBegin"])) . " at " . date("g:i a", strtotime($show["dateBegin"]))  ?></small></a></li>

  					<?php	} }// end while
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

      			<div class="well well-sm pull-right" style="width: 100%;">
      				<div class="checkbox pull-right"><label><input type="checkbox" onclick="toggleAutoRefresh(this);" id="reloadCB"> <a rel="popover" data-trigger='hover' data-html='true' data-placement="bottom" data-title="Auto Refresh Control" data-content="<p><strong>Refresh Time</strong>: 30 seconds</p><p>Check the box next to the auto-refresh control to have the page automatically refresh every 30 seconds. To undo this feature, unselect the checkbox.</p><p><div class='alert alert-warning'>Refreshing the page will lose data that is not saved.</div>"><i class="fa fa-refresh"></i> Auto Refresh</a></label></div>
      			</div>
	



				<p>Teachers can view questions related to a show by visiting their My Trips page. This is how the Questions section works:</p>



      			<ul>
      				<li>Questions will not appear to the teacher until at least one question has been answered.</li>
      				<li>Questions that have their answers left blank will not appear on the page for the teachers.</li>
      				<li>To remove a question and answer appearing on a teacher's page, remove the answer from the question.</li>
      			</ul>

       		<form class="form" action="functions/doQuestion.php" method="post" role="form">
				<?php
					$sql = "SELECT * FROM tblQuestions WHERE intTripShowID=" . (int) $_GET["showID"];
					$resultQuestions = mysql_query($sql);
					$numQuestions = mysql_num_rows($resultQuestions);
					if($numQuestions > 0) {
						echo "<button class='pull-right btn btn-success' type='submit'><i class='fa fa-save'></i> Save Answers</button>";
						echo "<br class='clearfix' />";
						echo "<br class='clearfix' />";
						echo "<table class='table table-striped table-hover table-bordered'>";
						$i = 0;
						while($questions = mysql_fetch_array($resultQuestions)) {

							$sqlPeople = "SELECT * FROM tblUser WHERE intUserID=" . $questions["intUserID"];
							$resultPeople = mysql_query($sqlPeople);
							$person = mysql_fetch_array($resultPeople);

							//this session variable was created so that on the functions/doQuestion.php page
							// we can associate the answer to the respective question. It's Friday afternoon
							// and I'm running on empty, so there might be a better way of doing this. Right now, there's not.
							$_SESSION["questionIDs"][$i] = $questions["intQuestionID"];
				?>
							<tr>
								<td style="width: 50%"><a href="#" data-url="functions/doUpdateQuestion.php" data-pk="<?= $questions["intQuestionID"] ?>" id="question<?= $i ?>" data-type="textarea" data-placement="right" data-title="Edit Question"><?= $questions["strQuestion"]?></a><br /><small>Asked by <a rel='tooltip' title='Email the teacher: <?= $person["strFirstName"] . ' ' . $person["strLastName"] ?>' href="mailto:<?= $person["strEmail"] ?>"><?= $person["strSchool"] . "</a> from <abbr title='This is a state initial.'>" . $person["strState"] ?></abbr></small></td>
								<td style="width: 50%"><textarea tabindex="<?= $i+1 ?>" class="form-control" name="answer<?= $i ?>" placeholder="Type answer here."><?= $questions["strAnswer"]?></textarea></td>
							</tr>
				<?php
						$i++;
						}
					echo "</table>";
					echo "<button class='pull-right btn btn-success' type='submit'><i class='fa fa-save'></i> Save Answers</button>";
					}
					else {
						if($_GET["showID"] == 0) {
							echo "<div class='alert alert-info'>Select a show from the list on the left.</div>";
						}
						else {
							echo "This show has no questions. Shows only allow questions to be asked during the show time. A few reasons why there may not be any questions are";
							echo "<ul>";
							echo "<li>Your show is in the future. It's currently " .  date("g:i a", strtotime('now')) . " on " . date("F j, Y", strtotime('now')) . ".</li>";
							echo "<li>Your show was so informative there were no questions.</li>";
							echo "<li>Your show was so boring that everyone fell asleep.</li>";
							echo "<li>There was a problem with the system and no questions were recorded.</li></ul>";
						}
					}
				?>

			<input type="hidden" name="action" value="answer" />
			<input type="hidden" name="showID" value="<?= (int) $_GET["showID"]?>" />
			</form>
       		</div>
       	</div>












<?php
include_once("includes/footer.php");
?>

<link href="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/css/bootstrap-editable.css" rel="stylesheet"/>
<script src="js/bootstrap-editable.min.js"></script>



<script>
$(document).ready(function() {
    //toggle `popup` / `inline` mode
    $.fn.editable.defaults.mode = 'inline';     
    
    //make username editable
    for(count=0; count<=<?= $i ?>;count++) {
    	$('#question'+count).editable();
    }
    
    //make status editable
    $('#status').editable({
        type: 'select',
        title: 'Select status',
        placement: 'right',
        value: 2,
        source: [
            {value: 1, text: 'status 1'},
            {value: 2, text: 'status 2'},
            {value: 3, text: 'status 3'}
        ]
        /*
        //uncomment these lines to send data on server
        ,pk: 1
        ,url: '/post'
        */
    });
});
</script>