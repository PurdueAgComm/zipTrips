<?php
session_start();
include_once("user-functions.php");
include_once("db.php");

$_SESSION["signInEmail"] = mysql_real_escape_string($_POST["signInEmail"]);
$_SESSION["signInPassword"] = $_POST["signInPassword"]; // will be encrypted no escaping needed
$_SESSION["errorEmail"] = 0;
$_SESSION["errorPassword"] = 0;
$emailTest = preg_match("/^[a-zA-Z0-9]+@[a-zA-Z0-9_-]+\.[a-zA-Z0-9_-]/", mysql_real_escape_string($_SESSION["signInEmail"]));


///################## Action = UPDATE password #####################///
  if($_POST["action"] == "updatePass") {
	  
	  $getEmail = mysql_real_escape_string($_SESSION['getEmail']);
	  $getKey = mysql_real_escape_string($_SESSION['getKey']);
	  $_SESSION['confirmPass'] = mysql_real_escape_string($_POST["confirmPass"]);
	  $_SESSION['newPass'] = mysql_real_escape_string($_POST["newPass"]);
	  $_SESSION["errorNewPass"] = 0;
	  $_SESSION["errorConfirmPass"] = 0;	

  
	  $sql = "SELECT * FROM tblUser WHERE strEmail='$getEmail' AND strForgotPass='$getKey' LIMIT 1";
	  $result = mysql_query($sql);
	  $userNum = mysql_num_rows($result);

	  // Check the newPass and confirmPass are NOT empty, they match, they are at least 6 characters long, and the key matches the strForgotPass
	  if($userNum == 1)  {
		  if(!empty($_SESSION["confirmPass"]) && !empty($_SESSION["newPass"]))  { 
			  if($_SESSION['confirmPass'] == $_SESSION['newPass'])  {
				  if(strlen($_SESSION["newPass"]) && strlen($_SESSION["confirmPass"]) >= 5)   {
					if($isFound !== 1)  {
  
						  $sql = "SELECT * FROM tblUser WHERE strEmail='" . $_SESSION["recoverEmail"] . "'";
						  $result = mysql_query($sql);
						  $user = mysql_fetch_array($result);
						  $isFound = mysql_num_rows($result);
					      $date = date('m/d/Y h:i:s a', time());
				    	  $encryptedPassword = setPassword($_SESSION["confirmPass"]);

						  $sql = "UPDATE tblUser SET strPassword = ('" . $encryptedPassword . "') WHERE strEmail = '$getEmail' LIMIT 1";
					      mysql_query($sql);
					  
						  $sql = "UPDATE tblUser SET strForgotPass = CONCAT(strForgotPass, '" . $date. "') WHERE strEmail = '$getEmail' LIMIT 1";
					      mysql_query($sql);
						
					      $_SESSION["success"] = "Congratulations, you reset your password. Please login.";
						  header("Location: ../signin.php?action=login");	
					}

			      } // end if the password is less than 5 characters
				  else {
						$_SESSION["error"] = "Your password seems to be less than 5 characters. Please reenter a password <strong>5</strong> characters or longer.";
						$_SESSION["errorNewPass"] = 1;
						$_SESSION["errorConfirmPass"] = 1;
						header("Location: ../recover.php?email=" . $_SESSION["getEmail"]."&key=". $_SESSION["getKey"]);	
					} 					  
 
			  } // end if new and confirm passwords pass
			  else {
					$_SESSION["error"] = "The password fields did not match. Please reenter your password in the two fields below.";
					$_SESSION["errorNewPass"] = 1;
					$_SESSION["errorConfirmPass"] = 1;
					header("Location: ../recover.php?email=" . $_SESSION["getEmail"]."&key=". $_SESSION["getKey"]);	
				} 
			  			  
		  } // end if new and confirm password fields are empty
		  else {
				$_SESSION["error"] = "The password fields seem to be empty. Please reenter your password in the two fields below.";
				$_SESSION["errorNewPass"] = 1;
				$_SESSION["errorConfirmPass"] = 1;
				header("Location: ../recover.php?email=" . $_SESSION["getEmail"]."&key=". $_SESSION["getKey"]);	
			} 
			
	  } // end IF user is in the database
	  else {
	       $_SESSION["error"] = "The temporary passcode has expired. Please request another temporary URL to create a new password.";
		   $_SESSION["errorNewPass"] = 1;
		   $_SESSION["errorConfirmPass"] = 1;
		   header("Location: ../recover.php?matching");	
		}
	 
  } // end UPDATE PASS section
  
  ///################## Action = RECOVER password via Email #####################///
  else if($_POST["action"] == "recover") {
	  
	  $_SESSION['recoverEmail'] = mysql_real_escape_string($_POST["recoverEmail"]);
	  $recoverEmail = $_SESSION['recoverEmail'];
	  $_SESSION['getEmail'] = mysql_real_escape_string ($_GET['email']);
	  $_SESSION["errorRecoverEmail"] = 0;	  
	  
	  	  	  
	  	  // if the email field is blank, throw this error
	  if(empty($_SESSION['recoverEmail'])) {

	  			$_SESSION["error"] = "The email field is empty. Please reenter your email associated with your zipTrips account.";
				$_SESSION["errorRecoverEmail"] = 1;
				header("Location: ../recover.php");	
				  
	  }  
	    
		  $sql = "SELECT * FROM tblUser WHERE strEmail='" . $_SESSION["recoverEmail"] . "'";
		  $result = mysql_query($sql);
		  $user = mysql_fetch_array($result);
		  $isFound = mysql_num_rows($result);
  
  
	  if(mysql_num_rows($result)) {
	  
		  $randCode=rand(10000,99999);
		  $emailcut=substr($recoverEmail, 0, 4);
		  $tempPass= $emailcut . $randCode;
		  $code = substr ( md5(uniqid(rand(),1)), 3, 10);
		  $hashTempPass="$tempPass$code";

			$to = $user["strEmail"];
			$subject = "Forgot Password Help";
			$message = "<html><body style='background-color: #fafafa;'>";
			$message .= "<table align='center' width='650' cellpadding='5' cellspacing='5' style='font-family: arial; border: 1px solid #a4a4a4; background-color: #FFF;'>";
			$message .= "<tr><td colspan='4' style='height: 80px; width: 610px; background-color:#FFF;'><img src='http://www.purdue.edu/ziptrips/images/email/alertEmailBanner.jpg' alt='Reset your password quickly - follow these steps.' /></td></tr>";
			$message .= "<tr><td colspan='3' width='75%'>&nbsp;</td> <td colspan='1' width='25%' align='right'>" . date("M d, Y") . "</td></tr>";
			$message .= "<tr><td colspan='4' width='100%'>Hello fellow zipTripper!<br/><br/><p>This is an automated message from zipTrips. If you did not recently initiate the Forgot Password process, please disregard this email.</p>";
			$message .= "<p><a href='https://www.purdue.edu/ziptrips/recover.php?email=" . $recoverEmail. "&key=" . $hashTempPass . "'>Reset Your Password</a></p><p>Or, copy and paste the following the link into your browser address bar to recover your password: https://www.purdue.edu/ziptrips/recover.php?email=" . $recoverEmail . "&key=" . $hashTempPass . "</p>";
			$message .= "<br/><br />Thanks,<br />Purdue zipTrips</td></tr>";
			$message .= "</table>";
			$message .= "</body></html>";
			$message = chunk_split(base64_encode($message));
			$headers = "From:ziptrips@purdue.edu\r\n";
			$headers .= "MIME-Version: 1.0\r\n";
			$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
			$headers .= "Content-Transfer-Encoding: base64\r\n\r\n";
				
			if (mail($to, $subject, $message, $headers)) {
				$_SESSION["success"] = "You've successfully requested password help. An automated email will be sent to your zipTrips email account.";
				header("Location: ../recover.php");
			}
 
      	    $query = mysql_query("SELECT * FROM tblUser WHERE strEmail=('" . $_SESSION["recoverEmail"] . "')");

  
		if(mysql_num_rows($query) != 0) {
		     
		     // Add the temporary number to the email in the database
		     $sql = "UPDATE tblUser SET strForgotPass = '$hashTempPass' WHERE strEmail = '$recoverEmail' LIMIT 1";
		     mysql_query($sql);

	  	}
	    		
	} // if the emails match, send an email to the user

  // error if the email is not in the database
  	else  {		
		  $_SESSION["error"] = "We could not find this email in our records. Please reenter the email address associated with your zipTrips account. Or <a href='signup.php' class='alert-link'>register</a> for an account if you do not already have one.";
		  $_SESSION["errorRecoverEmail"] = 1;
		  header("Location: ../recover.php");		

     }  

}  // end RECOVER action


  ///################## Action = REGISTER #####################///

else if($_POST["action"] == "register") {
	if($_SESSION["isRegistrationEnabled"]) {
		if(!empty($_SESSION["signInEmail"])) {
			// if($emailTest != 0) {
				if(!empty($_SESSION["signInPassword"])) {
					if(strlen($_SESSION["signInPassword"]) >= 5) {
						$sql = "SELECT strEmail FROM tblUser WHERE strEmail='" . $_SESSION["signInEmail"] . "';";
						$result = mysql_query($sql);
						$isFound = mysql_num_rows($result);

						if($isFound == 0) {
							$encrypyedPassword = setPassword($_SESSION["signInPassword"]);
							$sql = "INSERT INTO tblUser (strEmail, strPassword, strRole) VALUES ('" . $_SESSION["signInEmail"] . "', '" . $encrypyedPassword . "', 'user');"; 
							mysql_query($sql);

							$sql = "SELECT * FROM tblUser WHERE strEmail='" . $_SESSION["signInEmail"] . "'";
							$result = mysql_query($sql);
							$user = mysql_fetch_array($result);

							$_SESSION["strRole"] = $user["strRole"];
							$_SESSION["userID"] =  $user["intUserID"];

							$to = $user["strEmail"];
							$subject = "Thanks for joining Purdue zipTrips!";
							$message = "<html><body style='background-color: #fafafa;'>";
							$message .= "<table align='center' width='650' cellpadding='5' cellspacing='5' style='font-family: arial; border: 1px solid #a4a4a4; background-color: #FFF;'>";
							$message .= "<tr><td colspan='4' style='height: 80px; width: 610px; background-color:#FFF;'><img src='http://dev.www.purdue.edu/ziptrips/images/email/welcomeEmailBanner.jpg' alt='You have registered for a zipTrips account!' /></td></tr>";
							$message .= "<tr><td colspan='3' width='75%'>&nbsp;</td> <td colspan='1' width='25%' align='right'>" . date("M d, Y") . "</td></tr>";
							$message .= "<tr><td colspan='4' width='100%'>Hello fellow zipTriper!<br/><br/><p><strong>Finish Your Profile</strong> - In order to join a zipTrip we need you to complete your profile. Log in and you'll automatically be prompted to complete this task.</p>";
							$message .= "<p><strong>Find a zipTrip</strong> - We have several zipTrips in our archive that you can watch anytime or, if available, join an upcoming live show.</p>";
							$message .= "<p><strong>Participate</strong> - If there is an upcoming live show, when you sign up volunteer to be a school that participates in the live show either through video conferencing or through Hotseat. Visit our site to learn more about the options.</p>";
							$message .= "<p><strong>Sit back and enjoy</strong> - Use our week of scientist videoes, teaching guide, and other resources to make zipTrips come to life. Access these resources by logging in and visiting the zipTrip's page.</p>";
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

							$_SESSION["success"] = "Welcome to Purdue zipTrips! Please complete your profile below.";
							header("Location: ../account.php?action=edit");
						}
						else {
							$_SESSION["error"] = "There is an account tied to this email address already. <a href='signin.php' class='alert-link'>Log in</a> or register with a different email account.";
							header("Location: ../signup.php");
						}
					}
					else {
						$_SESSION["error"] = "Your password needs to be 5 characters or longer.";
						$_SESSION["errorPassword"] = 1;
						header("Location: ../signup.php");
					}

				}
				else {
						$_SESSION["error"] = "The password field is blank. Please provide us with a valid password.";
						$_SESSION["errorPassword"] = 1;
						header("Location: ../signup.php");
				}
			// }
			// else {
			// 	$_SESSION["error"] = "The email address provided wasn't valid, please provid us with a valid email address. We don't sell or give away email addresses and will only use your email address to update you on zipTrips. You will use your email address to log in.";
			// 	$_SESSION["errorEmail"] = 1;
			// 	header("Location: ../signup.php");
			//}
		}
		else {
			$_SESSION["error"] = "The email field is blank. Please provide us with a valid email address.";
			$_SESSION["errorEmail"] = 1;

			header("Location: ../signup.php");

		}
	}
	else {
		$_SESSION["error"] = "Registration is currently disabled.";
		header("Location: ../signup.php");

	}
}

  ///################## Action = LOGIN #####################///

else if($_POST["action"] == "login") {
	$sql = "SELECT * FROM tblUser WHERE strEmail='" . $_SESSION["signInEmail"] . "' LIMIT 1";
	$result = mysql_query($sql);
	$user = mysql_fetch_array($result);
	$isFound = mysql_num_rows($result);

	if($_SESSION["isLoginEnabled"] || $user["strRole"] == "admin") {
		if(!empty($_SESSION["signInEmail"])) {
			// if($emailTest != 0) {
				if(!empty($_SESSION["signInPassword"])) {
					$sql = "SELECT * FROM tblUser WHERE strEmail='" . $_SESSION["signInEmail"] . "' LIMIT 1";
					$result = mysql_query($sql);
					$user = mysql_fetch_array($result);
					$isFound = mysql_num_rows($result);

					if($isFound) {
						if(checkPassword($_SESSION["signInPassword"], $user["strPassword"])) {
							$_SESSION["strRole"] = $user["strRole"];
							$_SESSION["userID"] = $user["intUserID"];
							$_SESSION["userEmail"] = $user["strEmail"];
							$_SESSION["userName"] = $user["strFirstName"] . " " . $user["strLastName"];
							header("Location: ../dashboard.php");
						}
						else {
							$_SESSION["error"] = "The password provided for this email address was incorrect.";
							$_SESSION["errorPassword"] = 1;
							header("Location: ../signin.php");
						}

					}
					else {
						$_SESSION["error"] = "That email address hasn't been registered.<br /> Please <a href='signup.php' class='alert-link'>sign up</a> first to join a zipTrip.";
						header("Location: ../signin.php");
					}
				}
				else {
						$_SESSION["error"] = "The password field is blank. Please provide us with a valid password.";
						$_SESSION["errorPassword"] = 1;
						header("Location: ../signin.php");
				}
			// }
			// else {
			// 	$_SESSION["error"] = "The email address provided wasn't valid, please provid us with a valid email address. We don't sell or give away email addresses and will only use your email address to update you on zipTrips. You will use your email address to log in.";
			// 	$_SESSION["errorEmail"] = 1;
			// 	header("Location: ../signin.php");
			// }
		}
		else {
			$_SESSION["error"] = "The email field is blank. Please provide us with a valid email address.";
			$_SESSION["errorEmail"] = 1;
			header("Location: ../signin.php");

		}
	}
	else {
		$_SESSION["error"] = "The site is currently in maintenance mode and can only be accessed by administrators. Please try again later.";
		header("Location: ../signin.php");
	}

}

///################## Action = DELETE #####################///

else if($_GET["action"] == "delete") { 

	$userID = (int) $_GET["id"];

	if($_SESSION["strRole"] == "admin" && $userID != "1") {

		$sql = "DELETE FROM tblUser WHERE intUserID=" . $userID . " LIMIT 1";
		mysql_query($sql);

		$_SESSION["success"] = "That user has been deleted.";
		header("Location: ../users.php");

	} 
	else {
		$_SESSION["error"] = "I'm sorry Dave, I'm afraid I can't do that. - HAL 9000";
		header("Location: ../users.php");
	}


} // end delete

  ///################## Action = LOGOUT #####################///

else if($_GET["action"] == "logout") {
	session_destroy();
	header("Location: ../index.php");
}

  ///################## Action = UPDATE #####################///

else if($_POST["action"] == "update")
{

	$_SESSION["numErrors"] = 0;
	$_SESSION["errorName"] = 0;
	$_SESSION["errorGrade"] = 0;
	$_SESSION["errorSchool"] = 0;
	$_SESSION["errorSchoolType"] = 0;
	$_SESSION["errorAddress"] = 0;
	$_SESSION["errorCity"] = 0;
	$_SESSION["errorState"] = 0;
	$_SESSION["errorClass"] = 0;

	$_SESSION["profileFirstName"] = mysql_real_escape_string($_POST["profileFirstName"]);
	$_SESSION["profileLastName"] = mysql_real_escape_string($_POST["profileLastName"]);
	$_SESSION["profileGrade"] = mysql_real_escape_string($_POST["profileGrade"]);
	$_SESSION["profileSchool"] = mysql_real_escape_string($_POST["profileSchool"]);
	$_SESSION["profileSchoolType"] = mysql_real_escape_string($_POST["profileSchoolType"]);
	$_SESSION["profileAddress"] = mysql_real_escape_string($_POST["profileAddress"]);
	$_SESSION["profileCity"] = mysql_real_escape_string($_POST["profileCity"]);
	$_SESSION["profileState"] = mysql_real_escape_string($_POST["profileState"]);
	$_SESSION["profileCountry"] = mysql_real_escape_string($_POST["profileCountry"]);
	$_SESSION["profileEmail"] = mysql_real_escape_string($_POST["profileEmail"]);
	$_SESSION["profilePassword"] = mysql_real_escape_string($_POST["profilePassword"]);
	$_SESSION["profilePasswordEncrpyt"] = setPassword($_SESSION["profilePassword"]);
	$_SESSION["profileClass"] = mysql_real_escape_string($_POST["profileClass"]);

	if(empty($_SESSION["profileFirstName"])) {
		$_SESSION["errorName"] = 1;

		$_SESSION["numErrors"]++;
	}

	if(empty($_SESSION["profileClass"]) || !is_numeric($_SESSION["profileClass"])) {
		$_SESSION["errorClass"] = 1;
		$_SESSION["error"] = "Your class size must be larger than 0 and be a number, please.";
		$_SESSION["numErrors"]++;
	}

	if(empty($_SESSION["profileLastName"])) {
		$_SESSION["errorName"] = 1;
		$_SESSION["numErrors"]++;

	}

	if(empty($_SESSION["profileGrade"])) {
		$_SESSION["errorGrade"] = 1;
		$_SESSION["numErrors"]++;
	}

	if(empty($_SESSION["profileSchool"])) {
		$_SESSION["errorSchool"] = 1;
		$_SESSION["numErrors"]++;

	}

	if($_SESSION["profileSchoolType"] == "Choose School Type") {
		$_SESSION["errorSchoolType"] = 1;
		$_SESSION["numErrors"]++;
	}

	if(empty($_SESSION["profileAddress"])) {
		$_SESSION["errorAddress"] = 1;
		$_SESSION["numErrors"]++;

	}

	if(empty($_SESSION["profileCity"])) {
		$_SESSION["errorCity"] = 1;
		$_SESSION["numErrors"]++;

	}

	if(empty($_SESSION["profileState"])) {
		$_SESSION["errorState"] = 1;
		$_SESSION["numErrors"]++;
	}

	if($_SESSION["numErrors"] < 1) {
		if(!empty($_SESSION["profileEmail"])) {

			$sql = "SELECT strEmail FROM tblUser WHERE strEmail='" . $_SESSION["profileEmail"] . "' AND intUserID <> " . $_SESSION["userID"];
			$result = mysql_query($sql);
			$numResults = mysql_num_rows($result);


			if($numResults < 1) {		
				if(empty($_SESSION["profilePassword"])) {
					$sql = "UPDATE tblUser SET  isProfileComplete=1, strFirstName='" . $_SESSION["profileFirstName"] . "', strLastName='" . $_SESSION["profileLastName"] . "', strGrade='" . $_SESSION["profileGrade"] . "', strSchool='" . $_SESSION["profileSchool"] . "', strSchoolType='" . $_SESSION["profileSchoolType"] . "', strAddress='" . $_SESSION["profileAddress"] . "', strCity='" . $_SESSION["profileCity"] . "', strState='" . $_SESSION["profileState"] . "', strCountry='" . $_SESSION["profileCountry"] . "', strEmail='" . $_SESSION["profileEmail"] . "', intClass=" . $_SESSION["profileClass"] . " WHERE intUserID=" . $_SESSION["userID"];
					mysql_query($sql);
					
					$_SESSION["errorName"] = 0;
					$_SESSION["errorGrade"] = 0;
					$_SESSION["errorSchool"] = 0;
					$_SESSION["errorSchoolType"] = 0;
					$_SESSION["errorAddress"] = 0;
					$_SESSION["errorCity"] = 0;
					$_SESSION["errorClass"] = 0;
					$_SESSION["errorState"] = 0;

					$_SESSION["success"] = "Your profile has been updated.";
					header("Location: ../account.php?action=edit");

				}
				else {

					if(strlen($_SESSION["profilePassword"]) > 5) {
						$sql = "UPDATE tblUser SET isProfileComplete=1, strFirstName='" . $_SESSION["profileFirstName"] . "', strLastName='" . $_SESSION["profileLastName"] . "', strGrade='" . $_SESSION["profileGrade"] . "', strSchool='" . $_SESSION["profileSchool"] . "', strSchoolType='" . $_SESSION["profileSchoolType"] . "', strAddress='" . $_SESSION["profileAddress"] . "', strCity='" . $_SESSION["profileCity"] . "', strState='" . $_SESSION["profileState"] . "', strCountry='" . $_SESSION["profileCountry"] . "', strEmail='" . $_SESSION["profileEmail"] . "', strPassword='" . $_SESSION["profilePasswordEncrpyt"] . "', intClass=" . $_SESSION["profileClass"] . "  WHERE intUserID=" . $_SESSION["userID"];
						mysql_query($sql);

						$_SESSION["errorName"] = 0;
						$_SESSION["errorGrade"] = 0;
						$_SESSION["errorSchool"] = 0;
						$_SESSION["errorSchoolType"] = 0;
						$_SESSION["errorAddress"] = 0;
						$_SESSION["errorCity"] = 0;
						$_SESSION["errorState"] = 0;

						$_SESSION["success"] = "Your profile has been updated successfully. In addition, your password has been changed.";
						header("Location: ../account.php?action=edit");
					}
					else {
						$_SESSION["error"] = "Your password must be at least 5 characters.";
						$_SESSION["errorPassword"] = 1;
						header("Location: ../account.php?action=edit");	
					}
				}
			}
			else {
				$_SESSION["error"] = "This email address is already in use by another account. Please log in to that account or use another email address.";
				$_SESSION["errorEmail"] = 1;
				header("Location: ../account.php?action=edit");	
			}
		}
		else {
			$_SESSION["error"] = "An email address is required to log in to your profile. We will never sell or give away your email address.";
			$_SESSION["errorEmail"] = 1;
			header("Location: ../account.php?action=edit");
		}
	}
	
else {
	header("Location: ../account.php?action=edit");
}


}
else if($_POST["action"] == "adminUpdate") {

	$userID = (int) $_POST["id"];

	$_SESSION["numErrors"] = 0;
	$_SESSION["errorName"] = 0;
	$_SESSION["errorGrade"] = 0;
	$_SESSION["errorSchool"] = 0;
	$_SESSION["errorSchoolType"] = 0;
	$_SESSION["errorAddress"] = 0;
	$_SESSION["errorCity"] = 0;
	$_SESSION["errorState"] = 0;
	$_SESSION["errorClass"] = 0;

	$_SESSION["profileFirstName"] = mysql_real_escape_string($_POST["profileFirstName"]);
	$_SESSION["profileLastName"] = mysql_real_escape_string($_POST["profileLastName"]);
	$_SESSION["profileGrade"] = mysql_real_escape_string($_POST["profileGrade"]);
	$_SESSION["profileSchool"] = mysql_real_escape_string($_POST["profileSchool"]);
	$_SESSION["profileSchoolType"] = mysql_real_escape_string($_POST["profileSchoolType"]);
	$_SESSION["profileAddress"] = mysql_real_escape_string($_POST["profileAddress"]);
	$_SESSION["profileCity"] = mysql_real_escape_string($_POST["profileCity"]);
	$_SESSION["profileState"] = mysql_real_escape_string($_POST["profileState"]);
	$_SESSION["profileCountry"] = mysql_real_escape_string($_POST["profileCountry"]);
	$_SESSION["profileEmail"] = mysql_real_escape_string($_POST["profileEmail"]);
	$_SESSION["profilePassword"] = mysql_real_escape_string($_POST["profilePassword"]);
	$_SESSION["profilePasswordEncrpyt"] = setPassword($_SESSION["profilePassword"]);
	$_SESSION["profileClass"] = mysql_real_escape_string($_POST["profileClass"]);
	$_SESSION["profileRole"] = mysql_real_escape_string($_POST["profileRole"]);


	if(empty($_SESSION["profileFirstName"])) {
		$_SESSION["errorName"] = 1;

		$_SESSION["numErrors"]++;
	}

	if(empty($_SESSION["profileClass"]) || !is_numeric($_SESSION["profileClass"])) {
		$_SESSION["errorClass"] = 1;
		$_SESSION["error"] = "Your class size must be larger than 0 and be a number, please.";
		$_SESSION["numErrors"]++;
	}

	if(empty($_SESSION["profileLastName"])) {
		$_SESSION["errorName"] = 1;
		$_SESSION["numErrors"]++;

	}

	if(empty($_SESSION["profileGrade"])) {
		$_SESSION["errorGrade"] = 1;
		$_SESSION["numErrors"]++;
	}

	if(empty($_SESSION["profileSchool"])) {
		$_SESSION["errorSchool"] = 1;
		$_SESSION["numErrors"]++;

	}

	if($_SESSION["profileSchoolType"] == "Choose School Type") {
		$_SESSION["errorSchoolType"] = 1;
		$_SESSION["numErrors"]++;
	}

	if(empty($_SESSION["profileAddress"])) {
		$_SESSION["errorAddress"] = 1;
		$_SESSION["numErrors"]++;

	}

	if(empty($_SESSION["profileCity"])) {
		$_SESSION["errorCity"] = 1;
		$_SESSION["numErrors"]++;

	}

	if(empty($_SESSION["profileState"])) {
		$_SESSION["errorState"] = 1;
		$_SESSION["numErrors"]++;
	}

	if($_SESSION["numErrors"] < 1) {
		if(!empty($_SESSION["profileEmail"])) {

			$sql = "SELECT strEmail, strFirstName, strLastName FROM tblUser WHERE strEmail='" . $_SESSION["profileEmail"] . "' AND intUserID <> " . $userID;
			$result = mysql_query($sql);
			$numResults = mysql_num_rows($result);


			if($numResults < 1) {		
				if(empty($_SESSION["profilePassword"])) {
					$sql = "UPDATE tblUser SET  isProfileComplete=1, strFirstName='" . $_SESSION["profileFirstName"] . "', strLastName='" . $_SESSION["profileLastName"] . "', strGrade='" . $_SESSION["profileGrade"] . "', strSchool='" . $_SESSION["profileSchool"] . "', strSchoolType='" . $_SESSION["profileSchoolType"] . "', strAddress='" . $_SESSION["profileAddress"] . "', strCity='" . $_SESSION["profileCity"] . "', strState='" . $_SESSION["profileState"] . "', strCountry='" . $_SESSION["profileCountry"] . "', strEmail='" . $_SESSION["profileEmail"] . "', intClass=" . $_SESSION["profileClass"] . ", strRole='" . $_SESSION["profileRole"] . "' WHERE intUserID=" . $userID;
					mysql_query($sql);
					
					$_SESSION["errorName"] = 0;
					$_SESSION["errorGrade"] = 0;
					$_SESSION["errorSchool"] = 0;
					$_SESSION["errorSchoolType"] = 0;
					$_SESSION["errorAddress"] = 0;
					$_SESSION["errorCity"] = 0;
					$_SESSION["errorClass"] = 0;
					$_SESSION["errorState"] = 0;


					$sql = "SELECT strFirstName, strLastName FROM tblUser WHERE intUserID=" . $userID;
					$result = mysql_query($sql);
					$userEmail = mysql_fetch_array($result);

					$_SESSION["success"] = $userEmail["strFirstName"] . " " . $userEmail["strLastName"] . "'s profile has been updated.";

					$to = "ziptrips@Purdue.edu";
					$subject = "zipTrips Alert: User Account Updated";
					$message = "<html><body style='background-color: #fafafa;'>";
					$message .= "<table align='center' width='650' cellpadding='5' cellspacing='5' style='font-family: arial; border: 1px solid #a4a4a4; background-color: #FFF;'>";
					$message .= "<tr><td colspan='4' style='height: 80px; width: 610px; background-color:#FFF;'><img src='http://dev.www.purdue.edu/ziptrips/images/email/alertEmailBanner.jpg' alt='There is an important message from Purdue zipTrips!' /></td></tr>";
					$message .= "<tr><td colspan='3' width='75%'>&nbsp;</td> <td colspan='1' width='25%' align='right'>" . date("M d, Y") . "</td></tr>";
					$message .= "<tr><td colspan='4' width='100%'>Hello,</p>";
					$message .= "<p>This email is to notify you that " . $userEmail["strFirstName"] . " " . $userEmail["strLastName"] . "'s account has been modified by " . $_SESSION["userName"] . ". Please verify that this action was authorized; otherwise, disregard this message.</p>";
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


					header("Location: ../viewUser.php?action=edit&id=" . $userID);

				}
				else {

					if(strlen($_SESSION["profilePassword"]) > 5) {
						$sql = "UPDATE tblUser SET isProfileComplete=1, strFirstName='" . $_SESSION["profileFirstName"] . "', strLastName='" . $_SESSION["profileLastName"] . "', strGrade='" . $_SESSION["profileGrade"] . "', strSchool='" . $_SESSION["profileSchool"] . "', strSchoolType='" . $_SESSION["profileSchoolType"] . "', strAddress='" . $_SESSION["profileAddress"] . "', strCity='" . $_SESSION["profileCity"] . "', strState='" . $_SESSION["profileState"] . "', strCountry='" . $_SESSION["profileCountry"] . "', strEmail='" . $_SESSION["profileEmail"] . "', strPassword='" . $_SESSION["profilePasswordEncrpyt"] . "', intClass=" . $_SESSION["profileClass"] . ", strRole='" . $_SESSION["profileRole"] . "' WHERE intUserID=" . $userID;
						mysql_query($sql);

						$_SESSION["errorName"] = 0;
						$_SESSION["errorGrade"] = 0;
						$_SESSION["errorSchool"] = 0;
						$_SESSION["errorSchoolType"] = 0;
						$_SESSION["errorAddress"] = 0;
						$_SESSION["errorCity"] = 0;
						$_SESSION["errorState"] = 0;



						$sql = "SELECT strFirstName, strLastName FROM tblUser WHERE intUserID=" . $userID;
						$result = mysql_query($sql);
						$userEmail = mysql_fetch_array($result);

						$_SESSION["success"] = $userEmail["strFirstName"] . " " . $userEmail["strLastName"] . "'s profile has been updated. You have changed their password. Please communicate their password to them securely, encourage them to change it upon log in, and do not email it to them as this is not a secure method.";

						$to = "ziptrips@Purdue.edu";
						$subject = "zipTrips Alert: User Account Updated";
						$message = "<html><body style='background-color: #fafafa;'>";
						$message .= "<table align='center' width='650' cellpadding='5' cellspacing='5' style='font-family: arial; border: 1px solid #a4a4a4; background-color: #FFF;'>";
						$message .= "<tr><td colspan='4' style='height: 80px; width: 610px; background-color:#FFF;'><img src='http://dev.www.purdue.edu/ziptrips/images/email/alertEmailBanner.jpg' alt='There is an important message from Purdue zipTrips!' /></td></tr>";
						$message .= "<tr><td colspan='3' width='75%'>&nbsp;</td> <td colspan='1' width='25%' align='right'>" . date("M d, Y") . "</td></tr>";
						$message .= "<tr><td colspan='4' width='100%'>Hello,</p>";
						$message .= "<p>This email is to notify you that " . $userEmail["strFirstName"] . " " . $userEmail["strLastName"] . "'s account has been modified by " . $_SESSION["userName"] . ". Please verify that this action was authorized; otherwise, disregard this message.</p>";
						$message .= "<p><strong>Important:</strong> Their password was also changed. Please notify the user through a secure communication. Do NOT email the user their password.</strong></p>";
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




						header("Location: ../viewUser.php?action=edit&id=" . $userID);
					}
					else {
						$_SESSION["error"] = "The password must be at least 5 characters.";
						$_SESSION["errorPassword"] = 1;
						header("Location: ../viewUser.php?action=edit&id=" . $userID);
					}
				}
			}
			else {
				$_SESSION["error"] = "This email address is already in use by another account. Please use another email address.";
				$_SESSION["errorEmail"] = 1;
				header("Location: ../viewUser.php?action=edit&id=" . $userID);
			}
		}
		else {
			$_SESSION["error"] = "An email address is required to log in to their profile.";
			$_SESSION["errorEmail"] = 1;
			header("Location: ../viewUser.php?action=edit&id=" . $userID);
		}
	}
	
else {
	header("Location: ../account.php?action=edit");
}


}
else {
	header("Location: ../index.php");
}


?>