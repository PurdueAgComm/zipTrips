<?php
session_start();
include_once("includes/auth-admin.php");
include_once("db.php");

$_SESSION["errorTitle"] = 0;
$_SESSION["errorDescription"] = 0;
$_SESSION["errorGrade"] = 0;



if($_POST["action"] == "create") {

	$_SESSION["addTitle"] = mysql_real_escape_string($_POST["addTitle"]);
	$_SESSION["addDescription"] = mysql_real_escape_string($_POST["addDescription"]);
	$_SESSION["addGrade"] = mysql_real_escape_string($_POST["addGrade"]);

	if(!empty($_SESSION["addTitle"])) {
		if(!empty($_SESSION["addDescription"])) {
			if($_SESSION["addGrade"] != "Choose a Grade") {

				$sql = "INSERT INTO tblTrip (strTitle, strDescription, strGrade, isArchived) VALUES ('" . $_SESSION["addTitle"] . "', '" . $_SESSION["addDescription"] . "', '" . $_SESSION["addGrade"] . "', 1)";
				mysql_query($sql);
				$_SESSION["success"] = "The zipTrip titled <em>" . $_SESSION["addTitle"] . "</em> has been created. If available, you can now add images, guides, and other resources to the trip.";

				$_SESSION["addTitle"] = "";
				$_SESSION["addDescription"] = "";
				$_SESSION["addGrade"] = "";

				$sql = "SELECT MAX(intTripID) as maxNum FROM tblTrip";
				$result = mysql_query($sql);
				$max = mysql_fetch_array($result);
				header("Location: ../viewTrip.php?action=edit&id=" . $max["maxNum"]);
			}
			else {
				$_SESSION["error"] = "A target grade is needed in order to create a new zipTrip. Please add one and try again.";
				$_SESSION["errorGrade"] = 1;
				header("Location: ../addTrip.php");
			}
		}
		else {
			$_SESSION["error"] = "A description is needed in order to create a new zipTrip. Please add one and try again.";
			$_SESSION["errorDescription"] = 1;
			header("Location: ../addTrip.php");
		}
	}
	else {
		$_SESSION["error"] = "A title is needed in order to create a new zipTrip. Please add one and try again.";
		$_SESSION["errorTitle"] = 1;
		header("Location: ../addTrip.php");
	}
}
else if($_POST["action"] == "edit") {

	$id = $_POST["tripID"];
	$_SESSION["editTitle"] = mysql_real_escape_string($_POST["editTitle"]);
	$_SESSION["editDescription"] = mysql_real_escape_string($_POST["editDescription"]);
	$_SESSION["editGrade"] = mysql_real_escape_string($_POST["editGrade"]);
	$_SESSION["editBlurb"] = mysql_real_escape_string($_POST["editBlurb"]);
	$_SESSION["editPhotoSm"] = mysql_real_escape_string($_POST["editPhotoSm"]);
	$_SESSION["editPhotoMd"] = mysql_real_escape_string($_POST["editPhotoMd"]);
	$_SESSION["editPhotoLg"] = mysql_real_escape_string($_POST["editPhotoLg"]);
	$_SESSION["editTeacherGuide"] = mysql_real_escape_string($_POST["editTeacherGuide"]);



	($_POST["isBanner"]) ? $_SESSION["isBanner"] = 1 : $_SESSION["isBanner"] = 0;

	if(empty($_SESSION["editPhotoLg"]) && $_SESSION["isBanner"]) {
		$_SESSION["error"] = "You cannot add this trip to the rotating banner because you have not provided a banner image. If you made any additional changes, those have been saved, but this trip was not added to the rotating banner.";
		$_SESSION["isBanner"] = 0;
	}

	$sql = "UPDATE tblTrip SET strTitle='" . $_SESSION["editTitle"] . "', strDescription='" . $_SESSION["editDescription"] . "', strBlurb='" . $_SESSION["editBlurb"] . "', strPhoto250='" . $_SESSION["editPhotoSm"] . "', strPhoto400='" . $_SESSION["editPhotoMd"] . "', strPhoto600='" . $_SESSION["editPhotoLg"] . "', strGrade='" . $_SESSION["editGrade"] . "', isBanner=" . $_SESSION["isBanner"] . ", strTeacherGuide='" . $_SESSION["editTeacherGuide"] . "' WHERE intTripID=" . $id;
	mysql_query($sql);

	$_SESSION["success"] = "You've updated " . $_SESSION["editTitle"]; 
	header("Location: ../viewTrip.php?id=" . $id . "&action=edit");

}
else if($_GET["action"] == "feature") {
	$tripID = (int) $_GET["id"];

	$sql = "SELECT * FROM tblTrip WHERE isFeatured=1;";
	$result = mysql_query($sql);
	$oldFeature = mysql_fetch_array($result);

	$sql = "SELECT * FROM tblTrip WHERE intTripID=" . $tripID;;
	$result = mysql_query($sql);
	$newFeature = mysql_fetch_array($result);

	if($newFeature["isArchived"] != 1) {
		$sql = "UPDATE tblTrip SET isFeatured=0 WHERE intTripID=" . $oldFeature["intTripID"];
		mysql_query($sql);

		$sql = "UPDATE tblTrip SET isFeatured=1 WHERE intTripID=" . $tripID;
		mysql_query($sql);

	}
	else {
		$_SESSION["error"] = "You cannot feature a hidden zipTrip. First, make it public then try featuring it again.";
		header("Location: ../trips.php");
		die();
	}

	$_SESSION["success"] = "<em>" . $newFeature["strTitle"] . "</em> has replaced <em>" . $oldFeature["strTitle"] . "</em> as the featured zipTrip.";
	header("Location: ../trips.php");
}
else if($_GET["action"] == "hide") {
	$tripID = (int) $_GET["id"];

	$sql = "SELECT isFeatured FROM tblTrip WHERE intTripID=" . $tripID;
	$result = mysql_query($sql);
	$trip = mysql_fetch_array($result);

	if($trip["isFeatured"] == 0) {
		$sql = "UPDATE tblTrip SET isArchived=1 WHERE intTripID=" . $tripID;
		mysql_query($sql);
		$_SESSION["success"] = "This zipTrip is no longer public.";
		header("Location: ../trips.php");
	}
	else {
		$_SESSION["error"] = "You cannot hide a zipTrip that is featured. Select a new zipTrip to feature, then try hiding this zipTrip again.";
		header("Location: ../trips.php");
	}

}
else if($_GET["action"] == "unhide") {
	$tripID = (int) $_GET["id"];

	$sql = "UPDATE tblTrip SET isArchived=0 WHERE intTripID=" . $tripID;
	mysql_query($sql);
	$_SESSION["success"] = "This zipTrip is now public.";
	header("Location: ../trips.php");

}
else if($_POST["action"] == "addVideo") {

	$tripID = $_POST["tripID"];

	$_SESSION["videoTitle"] =  mysql_real_escape_string($_POST["videoTitle"]);
	$_SESSION["videoURL"] =  mysql_real_escape_string($_POST["videoURL"]);
	$_SESSION["videoDescription"] =  mysql_real_escape_string($_POST["videoDescription"]);

	if(!empty($_SESSION["videoTitle"])) {
		if(!empty($_SESSION["videoURL"])) {
			if(!empty($_SESSION["videoDescription"])) {

				$sql = "INSERT INTO tblTripVideo (strVideoTitle, strVideoURL, strVideoDescription, intTripID) VALUES ('" . $_SESSION["videoTitle"] . "', '" . $_SESSION["videoURL"] . "', '" . $_SESSION["videoDescription"] . "', " . $tripID . ");";
				mysql_query($sql);

				$_SESSION["success"] = "You've added the video titled " . $_SESSION["videoTitle"] . ".";
				$_SESSION["videoTitle"] = "";
				$_SESSION["videoURL"] = "";
				$_SESSION["videoDescription"] =  "";

				header("Location: ../viewTrip.php?action=edit&id=" . $tripID);
			}
			else {
				$_SESSION["error"] = "The description field was left blank and is needed to add a video to this zipTrip.";
				header("Location: ../viewTrip.php?action=edit&id=" . $tripID);
			}
		}
		else {
			$_SESSION["error"] = "The video URL field was left blank and is needed to add a video to this zipTrip.";
			header("Location: ../viewTrip.php?action=edit&id=" . $tripID);
		}
	}
	else {
		$_SESSION["error"] = "The title field was left blank and is needed to add a video to this zipTrip.";
		header("Location: ../viewTrip.php?action=edit&id=" . $tripID);
	}




}
else if($_GET["action"] == "deleteVideo") {

	$sql = "DELETE FROM tblTripVideo WHERE intTripVideoID=" . (int) $_GET["id"] . " AND intTripID=" . (int) $_GET["tripID"] . " LIMIT 1";
	mysql_query($sql);
	$_SESSION["success"] = "The video has been permanently deleted.";
	header("Location: ../viewTrip.php?action=edit&id=" . (int) $_GET["tripID"]);

}
else {

	die("redirect");
	header("Location: ../index.php");
}

?>