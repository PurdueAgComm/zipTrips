<?php
session_start();

$_SESSION["contactName"] = $_POST["contactName"];
$_SESSION["contactEmail"] = $_POST["contactEmail"];
$_SESSION["contactSubject"] = $_POST["contactSubject"];
$_SESSION["contactMessage"] = $_POST["contactMessage"];
$_SESSION["botChecker"] = $_POST["botChecker"];

if(empty($_SESSION["botChecker"])) {
	$_SESSION["error"] = "You've been identified as a bot because you do not have Javascript enabled. If this is a false positve, you can contact us by emailing <a href='mailto:ziptrips@purdue.edu'>ziptrips@purdue.edu</a>.";
	header("Location: ../contact.php");
}
else if(empty($_SESSION["contactName"])) {
	$_SESSION["error"] = "Please let us know your name.";
	header("Location: ../contact.php");
}
else if(empty($_SESSION["contactEmail"])) {
	$_SESSION["error"] = "Please provide an email address so that we can contact you.";
	header("Location: ../contact.php");
}
else if($_SESSION["contactSubject"] == "Choose a Subject") {
	$_SESSION["error"] = "Please identify a subject of your message.";
	header("Location: ../contact.php");
}
else if(empty($_SESSION["contactMessage"])) {
	$_SESSION["error"] = "Please enter a message.";
	header("Location: ../contact.php");
}
else {

	$to = "ziptrips@purdue.edu";
	$subject = "Website Contact: " . $_SESSION["contactSubject"];
	$message = "<html><body style='background-color: #fafafa;'>";
	$message .= "<table align='center' width='650' cellpadding='5' cellspacing='5' style='font-family: arial; border: 1px solid #a4a4a4; background-color: #FFF;'>";
	$message .= "<tr><td colspan='4' style='height: 80px; width: 610px; background-color:#FFF;'><img src='http://dev.www.purdue.edu/ziptrips/images/email/alertEmailBanner.jpg' alt='There is an important message from Purdue zipTrips!' /></td></tr>";
	$message .= "<tr><td colspan='3' width='75%'>&nbsp;</td> <td colspan='1' width='25%' align='right'>" . date("M d, Y") . "</td></tr>";
	$message .= "<tr><td colspan='4' width='100%'>This message is from the <a href='http://www.purdue.edu/ziptrips/contact.php'>Purdue zipTrips' website.</a><br/><br/>";
	$message .= "<p><strong>Name</strong>: " . $_POST["contactName"] . "</p>";
	$message .= "<p><strong>Email</strong>: " . $_POST["contactEmail"] . "</p>";
	$message .= "<p><strong>Message:</strong>: " . $_POST["contactMessage"] . "</p>";
	$message .= "<br/></td></tr>";
	$message .= "</table>";
	$message .= "</body></html>";
	$message = chunk_split(base64_encode($message));
	$headers = "From:" . $_SESSION["contactEmail"] . "\r\n";
	//$headers .= "CC:knwilson@purdue.edu\r\n";
	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
	$headers .= "Content-Transfer-Encoding: base64\r\n\r\n";
	mail($to,$subject,$message,$headers);


	$_SESSION["contactName"] = "";
	$_SESSION["contactEmail"] = "";
	$_SESSION["contactSubject"] = "";
	$_SESSION["contactMessage"] = "";

	$_SESSION["success"] = "Thanks for contacting us. We will reply as soon as possible.";
	header("Location: ../contact.php");
}


?>