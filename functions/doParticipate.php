<?php
session_start();
include_once("includes/auth-admin.php");
include_once("db.php");




if($_POST["action"] == "add") {
	$showID = (int) $_POST["showID"];
	$userID = (int) $_POST["userID"];
	$tripID= (int) $_POST["tripID"];

	if($_POST["hotseat"]) {

		$sql = "SELECT intUserID FROM tblTripShowHotseat WHERE intUserID=" . $userID;
		$result = mysql_query($sql);
		$numResults = mysql_num_rows($result);

		if($numResults < 1) {
			$sql = "INSERT INTO tblTripShowHotseat (intTripShowID, intUserID) VALUES (" . $showID . ", " . $userID . ");";			
			mysql_query($sql);

			$sql = "SELECT strSchool, strFirstName, strLastName, strEmail FROM tblUser WHERE intUserID=" . $userID;
			$result = mysql_query($sql);
			$user = mysql_fetch_array($result);

			$to = $user["strEmail"];
			$subject = "zipTrips Alert: You've been selected to participate in Hotseat!";
			$message = "<html><body style='background-color: #fafafa;'>";
			$message .= "<table align='center' width='650' cellpadding='5' cellspacing='5' style='font-family: arial; border: 1px solid #a4a4a4; background-color: #FFF;'>";
			$message .= "<tr><td colspan='4' style='height: 80px; width: 610px; background-color:#FFF;'><img src='http://dev.www.purdue.edu/ziptrips/images/email/alertEmailBanner.jpg' alt='There is an important message from Purdue zipTrips!' /></td></tr>";
			$message .= "<tr><td colspan='3' width='75%'>&nbsp;</td> <td colspan='1' width='25%' align='right'>" . date("M d, Y") . "</td></tr>";
			$message .= "<tr><td colspan='4' width='100%'>Hello " . $user["strFirstName"] . ",<br/><br/><p>";
			$message .= "<p>Congratulations! Your class has been accepted as one of our Hotseat participants!</p>";
 			$message .= "<p>Hotseat is our social networking Web application that creates a collaborative classroom, allowing students to provide real-time feedback during class and enabling an improved the learning experience. During the show your students will be able to post messages using their Facebook or Twitter accounts, or by logging in to the Hotseat Web site.</p>";
 			$message .= "<p>You can access HotSeat from almost any device with an Internet connection.  This could be a desktop computer or mobile devices such as laptops, cell phones, iPads, iPods, etc.  The idea is for each student in your classroom (either individually or by working in groups) to have access to a device that is connected to HotSeat. While you can log on to the Hotseat website through an Internet browser, there is also a Hotseat app you can download for Android phones from Google Play or iPhones, iPads, and iPods from the App Store.</p>";
 			$message .= "<p>View <a href='https://www.purdue.edu/ziptrips/hotseat.php'>Teacher and Technology Tips</a> to help you prepare to use Hotseat for your upcoming zipTrip!</p>";
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


			$_SESSION["success"] = $user["strSchool"] . " is now participating in Hotseat for this show. " . $user["strFirstName"] . " " . $user["strLastName"] . " has been notified that their class has been chosen to use Hotseat. Their email address is <a class='alert-link' href='mailto:" . $user["strEmail"] . "'>" . $user["strEmail"] . "</a> if you wish to send them additional information.";
			header("Location: ../viewTrip.php?id=" . $tripID);
		}
		else {
			$_SESSION["error"] = "That school has already been selected to participate in Hotseat for this show.";
			header("Location: ../viewTrip.php?id=" . $tripID);
		}
		
	}
	else if($_POST["video"]) {

		$sql = "SELECT intUserID FROM tblTripShowConference WHERE intUserID=" . $userID . " AND intTripShowID=" . $showID;
		$result = mysql_query($sql);
		$numResults = mysql_num_rows($result);

		if($numResults < 1) {
			$sql = "INSERT INTO tblTripShowConference (intTripShowID, intUserID) VALUES (" . $showID . ", " . $userID . ");";			
			mysql_query($sql);

			$sql = "SELECT strSchool, strFirstName, strLastName, strEmail FROM tblUser WHERE intUserID=" . $userID;
			$result = mysql_query($sql);
			$user = mysql_fetch_array($result);

			// EMAIL INFORMATION // 
			$to = $user["strEmail"];
			$subject = "zipTrips Alert: You've been selected to participate in Video Conferencing!";
			$message = "<html><body style='background-color: #fafafa;'>";
			$message .= "<table align='center' width='650' cellpadding='5' cellspacing='5' style='font-family: arial; border: 1px solid #a4a4a4; background-color: #FFF;'>";
			$message .= "<tr><td colspan='4' style='height: 80px; width: 610px; background-color:#FFF;'><img src='http://dev.www.purdue.edu/ziptrips/images/email/alertEmailBanner.jpg' alt='There is an important message from Purdue zipTrips!' /></td></tr>";
			$message .= "<tr><td colspan='3' width='75%'>&nbsp;</td> <td colspan='1' width='25%' align='right'>" . date("M d, Y") . "</td></tr>";
			$message .= "<tr><td colspan='4' width='100%'>Hello " . $user["strFirstName"] . ",<br/><br/><p>";
			$message .= "Congratulations! Your class will be appearing live in our upcoming zipTrip as our virtual in-studio class via IP videoconferencing. This means that, at certain times throughout the program, your class will appear in the show LIVE! Your students will get a chance to personally ask our Purdue scientists questions, and will will be seen by thousands of students all across the country on a large screen in the studio. Videoconferencing is a telecommunication technology which allows simultaneous two-way video and audio transmissions over the internet. It requires special videoconferencing equipment, and not all schools have this technology. We will be contacting you to make sure your system is ready to go on the day of the show. In the meantime, if you have any questions about how your system will connect with zipTrips, you can contact our information technology Help Desk at Purdue:<br /><br />";
			$message .= "<strong>Phone:</strong> (800) 246-7615 (toll-free) or (317) 263-8999<br />";
			$message .= "<strong>Email:</strong> helpdesk@ihets.org";
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

			$_SESSION["success"] = $user["strSchool"] . " is now participating in Video Conferencing for this show. " . $user["strFirstName"] . " " . $user["strLastName"] . " has been notified that their class has been chosen to video conference. Their email address is <a class='alert-link' href='mailto:" . $user["strEmail"] . "'>" . $user["strEmail"] . "</a> if you wish to send them additional information.";
			header("Location: ../viewTrip.php?id=" . $tripID);
		}
		else {
			$_SESSION["error"] = "That school has already been selected to participate in video conferencing for this show.";
			header("Location: ../viewTrip.php?id=" . $tripID);
		}
		
	}


}
else if($_GET["action"] == "remove") {
	$showID = (int) $_GET["showID"];
	$userID = (int) $_GET["userID"];
	$tripID= (int) $_GET["tripID"];

	$type = $_GET["type"];

	if($type == "hotseat") {
		$sql = "DELETE FROM tblTripShowHotseat WHERE intUserID=" . $userID . " AND intTripShowID=" . $showID . " LIMIT 1";
		mysql_query($sql);

		$_SESSION["success"] = "This school has been removed from participating in Hotseat for this show. Be sure to contact them regarding this change.";
		header("Location: ../viewTrip.php?id=" . $tripID);
	}
	else if($type == "video") {
		$sql = "DELETE FROM tblTripShowConference WHERE intUserID=" . $userID . " AND intTripShowID=" . $showID . " LIMIT 1";
		mysql_query($sql);
		$_SESSION["success"] = "This school has been removed from participating in Video Conferencing for this show. Be sure to contact them regarding this change.";
		header("Location: ../viewTrip.php?id=" . $tripID);
	}



}
else {

}















?>