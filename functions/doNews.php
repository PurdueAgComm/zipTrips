<?php
session_start();
include_once("includes/auth-admin.php");
include_once("db.php");

if($_POST["action"] == "send") {

	$sql = "SELECT * FROM tblUser JOIN tblUserShow ON tblUser.intUserID=tblUserShow.intUserID WHERE ";

	if(!empty($_POST["showID"])){
		$sql .= "intTripShowID=" . (int) $_POST["showID"];
		$andSignal = 1;
	}
	//TODO: SORT BY TRIP
	// else if(!empty($_POST["tripID"])) {
	// 	$sql .= "intTripID=" . (int) $_POST["tripID"];
	// 	$andSignal = 1;
	// }

	if($_POST["grade"] != "Choose a Grade") {
		if($andSignal == 1) {
			$sql .= " AND";
		}

		$sql .= " strGrade='" . mysql_escape_string($_POST["grade"]) . "'";
	}

	// everyone selected rewrite sql statement
	if($_POST["tripID"] == "everyone") {
		$sql = "SELECT * FROM tblUser";
	}

	$resultUserRelevant = mysql_query($sql);
	$numUserRelevant = mysql_num_rows($resultUserRelevant);

	// are there results?
	if($numUserRelevant < 1) {
		$_SESSION["error"] = "Your audience target returned no users. The message was cancelled.";
		header("Location: ../addNews.php");
	}
	else {
		$_SESSION["timeDelay"] == 0;

			if(strtotime("now") > $_SESSION["sendAcceptable"]) {
			// add news to database
			$sqlNews = "INSERT INTO tblNews (strSubject, strMessage, isArchived) VALUES ('" . mysql_escape_string($_POST["subject"]) . "', '" . mysql_escape_string($_POST["message"]) . "', 0)";
			mysql_query($sqlNews) or die(mysql_error());

			$sqlMaxNews = "SELECT MAX(intNewsID) as maxID FROM tblNews";
			$resultNews = mysql_query($sqlMaxNews);
			$maxID = mysql_fetch_array($resultNews);


			while($user = mysql_fetch_array($resultUserRelevant)) {	

				$sqlUser = "INSERT INTO tblUserNews (intNewsID, intUserID) VALUES (" . $maxID["maxID"] . ", " . $user["intUserID"] . ")";
				mysql_query($sqlUser);

				// add them to tblUserNews

				$to = $user["strEmail"];
				$subject = "zipTrips Alert: " . $_POST["subject"];
				$message = "<html><body style='background-color: #fafafa;'>";
				$message .= "<table align='center' width='650' cellpadding='5' cellspacing='5' style='font-family: arial; border: 1px solid #a4a4a4; background-color: #FFF;'>";
				$message .= "<tr><td colspan='4' style='height: 80px; width: 610px; background-color:#FFF;'><img src='http://dev.www.purdue.edu/ziptrips/images/email/alertEmailBanner.jpg' alt='There is an important message from Purdue zipTrips!' /></td></tr>";
				$message .= "<tr><td colspan='3' width='75%'>&nbsp;</td> <td colspan='1' width='25%' align='right'>" . date("M d, Y") . "</td></tr>";
				$message .= "<tr><td colspan='4' width='100%'>Hello " . $user["strFirstName"] . ",<br/><br/><p>" . $_POST["message"] . "</p>";
				$message .= "<br/><br />Thanks,<br />Purdue zipTrips</td></tr>";
				$message .= "</table>";
				$message .= "</body></html>";
				$message = chunk_split(base64_encode($message));
				$headers = "From:ziptrips@purdue.edu\r\n";
				//$headers .= "CC:knwilson@purdue.edu\r\n";
				$headers .= "MIME-Version: 1.0\r\n";
				$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
				$headers .= "Content-Transfer-Encoding: base64\r\n\r\n";
				mail($to,$subject,$message,$headers);

				$_SESSION["success"] = "Your message has been successfully saved and sent. It went to " . $numUserRelevant . " users.";
				$_SESSION["sendAcceptable"] = strtotime("now")+(600); // 10 minutes until another message can be sent
				header("Location: ../addNews.php");
			} //while
		} // end if time delay
		else {
			$_SESSION["error"] = "You have attempted to send messages too quickly. You cannot send another message until " . date("h:i A", $_SESSION["sendAcceptable"]);
			header("Location: ../addNews.php");
		}
	} // else
}
else if($_POST["action"] == "edit") {

	$sql = "UPDATE tblNews SET strSubject='" . mysql_escape_string($_POST["subject"])  . "', strMessage='" .  mysql_escape_string($_POST["message"]) . "' WHERE intNewsID=" . (int) $_POST["newsID"];
	mysql_query($sql);
	$_SESSION["success"] = "Your message has been successfully updated.";
	header("Location: ../addNews.php?id=" . (int) $_POST["newsID"]);

}
else if($_GET["action"] == "archive") {
	$sql = "SELECT * FROM tblNews WHERE intNewsID=" . (int) $_GET["id"];
	$result = mysql_query($sql);
	$news = mysql_fetch_array($result);

	$sql = "UPDATE tblNews SET isArchived=1 WHERE intNewsID=" . (int) $_GET["id"];
	mysql_query($sql);

	$_SESSION["success"] = "You have archived " . $news["strSubject"] . ". It will no longer appear on anyone's newsfeed.";
	header("Location: ../addNews.php");

}
else {
	header("Location: ../addNews.php");
}

















?>