<?php
session_start();
include_once("includes/auth-admin.php");
include_once("db.php");


if($_POST["action"] == "ask") {
	
	$_SESSION["question"] = mysql_escape_string($_POST["question"]);

	$sql = "INSERT INTO tblQuestions (strQuestion, intTripShowID, intUserID) VALUES ('" . $_SESSION["question"] . "', " . $_POST["showID"] . ", " . $_SESSION["userID"] . ");";
	mysql_query($sql) or die(mysql_error());
	$_SESSION["success"] = "Your question has been asked. Check back after the show to review the scientists' answers to all questions.";
	header("Location: ../myTrips.php");
}
else if($_POST["action"] == "answer") {
	$sql = "SELECT * FROM tblQuestions WHERE intTripShowID=" . (int) $_POST["showID"];
	$result = mysql_query($sql);
	$numQuestions = mysql_num_rows($result);

	for($i=0; $i<$numQuestions; $i++) {
		// echo $_SESSION["questionIDs"][$i];
		// echo "<br>";
		// echo mysql_escape_string($_POST["answer" . $i]);
		// echo "<hr>";

		$sql = "UPDATE tblQuestions SET strAnswer='" . mysql_escape_string($_POST["answer" . $i]) ."' WHERE intQuestionID=" . $_SESSION["questionIDs"][$i];
		mysql_query($sql) or die(mysql_error());
		$_SESSION["success"] = "The answers were saved. Now, teachers can log in and view the scientists' answers by visiting their <em>My zipTrips</em> page.";
		header("Location: ../questions.php?showID=" . (int) $_POST["showID"]);
	}
	


}
else {
	die("no");
	header("Location: ../dashboard.php");
}















?>