<?php
include_once("includes/header.php");
include_once("includes/auth.php");

?>


<!-- start body -->
     <div class="section">
      <div class="container">
      	<div class="row">
        <div class="col-lg-12">
          <h1 class="page-header">Questions <small>View answers by scientists</small></h1>
          <ol class="breadcrumb">
  		    <li><a href="dashboard.php">Dashboard</a></li>
  		    <li><a href="myTrips.php">My zipTrips</a></li>
            <li class="active">Questions</li>
           </ol>
        </div>
     	</div>

     	<div class="row">
       		<div class="col-lg-12">
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


      		<?php
					$sql = "SELECT * FROM tblQuestions WHERE strAnswer <> '' AND intTripShowID=" . (int) $_GET["showID"];
					$resultQuestions = mysql_query($sql);
					$numQuestions = mysql_num_rows($resultQuestions);
					if($numQuestions > 0) {
						$i = 1;
						echo "<p>These questions were asked during the live showing and not every question that was asked will appear. These questions were answered by the scientists featured on the show. There are " . $numQuestions . " questions that have been answered. To see the answer, click on the question below.</p>";
						echo "<div class='panel-group' id='accordion'>";
						while($questions = mysql_fetch_array($resultQuestions)) {
				?>

						<div class='panel panel-default'>
						    <div class='panel-heading'>
						      <h4 class='panel-title'>
						        <a data-toggle='collapse' data-parent='#accordion' href='#collapsed<?= $i ?>' data-target='#collapse<?= $i ?>'>
						          Question <?= $i ?>: <strong><?= $questions["strQuestion"]; ?></strong>
						        </a>
						      </h4>
						    </div>
						    <div id="collapse<?= $i ?>" class="panel-collapse collapse">
							    <div class="panel-body">
							        <?= $questions["strAnswer"]; ?>
							    </div>
						    </div>
						</div>
							

				<?php
						$i++;
						}
					echo "</div> <!-- / accordian-->";
					}
					else {
						if($_GET["showID"] == 0) {
							echo "<div class='alert alert-danger'><strong>Houston, we have a problem.</strong> We don't have all of the necessary information to show you the questions - like which show it's from. Please try viewing the questions again.</div>";
						}
						else {
							echo $numQuestions;
							echo "This show has no questions. Shows only allow questions to be asked during the show time. A few reasons why there may not be any questions are";
							echo "<ul>";
							echo "<li>Your show is in the future. It's currently " .  date("g:i a", strtotime('now')) . " on " . date("F j, Y", strtotime('now')) . ".</li>";
							echo "<li>Your show was so informative there were no questions.</li>";
							echo "<li>There was a problem with the system and no questions were recorded.</li></ul>";
						}
					}
				?>
       			

			</div>
			
       		
       	</div>












<?php
include_once("includes/footer.php");
?>
