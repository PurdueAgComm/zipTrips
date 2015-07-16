<?php
include_once("includes/header.php");
include_once("includes/auth-admin.php");

$sql = "SELECT * FROM tblUserNews JOIN tblNews ON tblUserNews.intNewsID = tblNews.intNewsID WHERE tblUserNews.intUserID=" . $_SESSION["userID"] . " AND isArchived=0 ORDER BY dateCreated DESC LIMIT 10";
$resultNews = mysql_query($sql);

if(!empty($_GET["newsID"])) {
	$sql = "SELECT * FROM tblNews WHERE isArchived=0 AND intNewsID=" . (int) $_GET["newsID"];
	$result = mysql_query($sql);
	$newsStory = mysql_fetch_array($result);
	$numStories = mysql_num_rows($result);
}
?>


<!-- start body -->
     <div class="section">
      <div class="container">
      	<div class="row">
        <div class="col-lg-12">
          <h1 class="page-header">News <small>Your news in one place</small></h1>
          <ol class="breadcrumb">
  		    <li><a href="dashboard.php">Dashboard</a></li>
            <li class="active">News</li>
           </ol>
        </div>
     	</div>

     	<div class="row">
       		<div class="col-xs-4">
       			<ul class="nav nav-pills nav-stacked">
       				<?php 
       					while($news = mysql_fetch_array($resultNews)) {

       						if($news["intNewsID"] == (int) $_GET["newsID"]) {
       				?>
  								<li class="active"><a href="news.php?newsID=<?= $news["intNewsID"] ?>"><?= $news["strSubject"]?><br/><small><?=  date("F j, Y @ g:i a", strtotime($news["dateCreated"])) ?></small></a></li>
  					<?php
  							}
  							else {
  					?>
  								<li><a href="news.php?newsID=<?= $news["intNewsID"] ?>"><?= $news["strSubject"]?><br/><small><?=  date("F j, Y", strtotime($news["dateCreated"])) ?></small></a></li>

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

      			<h2><?= $newsStory["strSubject"];?></h2>
      			<p><?= $newsStory["strMessage"];?></p>

      			<?php if($numStories < 1) {
      				echo "<h2>Story Not Found</h2>";
      				echo "<p>This story no longer exists. Please select a story from the list to the left.</p>";
      			}?>


				
       		</div>
       	</div>












<?php
include_once("includes/footer.php");
?>
