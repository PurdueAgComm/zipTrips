<?php
session_start();

include_once("includes/auth-admin.php");
include_once("db.php");

//for addShow errors
$_SESSION["errorAddDateStart"] = 0;
$_SESSION["errorAddDateEnd"] = 0;
$_SESSION["errorAddTimeStart"] = 0;
$_SESSION["errorAddTimeEnd"] = 0;

//for veiwTrip/edit page errors
$_SESSION["errorDateStart". $_SESSION["tripShowID"]] = 0;
$_SESSION["errorDateEnd". $_SESSION["tripShowID"]] = 0;
$_SESSION["errorTimeStart". $_SESSION["tripShowID"]] = 0;
$_SESSION["errorTimeEnd". $_SESSION["tripShowID"]] = 0;

$_SESSION["showID"] = (int) $_POST["showID"];

//SESSION variables for addShow
$_SESSION["dateStartAdd"] =  mysql_real_escape_string($_POST["dateStartAdd"]);
$_SESSION["dateEndAdd"] =  mysql_real_escape_string($_POST["dateEndAdd"]);
$_SESSION["timeStartAdd"] =  mysql_real_escape_string($_POST["timeStartAdd"]);
$_SESSION["timeEndAdd"] = mysql_real_escape_string($_POST["timeEndAdd"]);
$_SESSION["liveURLAdd"] = mysql_real_escape_string($_POST["liveURLAdd"]);
$_SESSION["archiveURLAdd"] = mysql_real_escape_string($_POST["archiveURLAdd"]);
$_SESSION["tripShowIDAdd"] = mysql_real_escape_string($_POST["tripShowIDAdd"]);
$_SESSION["isVideoConferenceAdd"] = mysql_real_escape_string($_POST["isVideoConferenceAdd"]);
$_SESSION["isHotSeatAdd"] = mysql_real_escape_string($_POST["isHotSeatAdd"]);


//SESSION variables for editShow
$_SESSION["dateStart"] =  mysql_real_escape_string($_POST["dateStart"]);
$_SESSION["dateEnd"] =  mysql_real_escape_string($_POST["dateEnd"]);
$_SESSION["timeStart"] =  mysql_real_escape_string($_POST["timeStart"]);
$_SESSION["timeEnd"] = mysql_real_escape_string($_POST["timeEnd"]);
$_SESSION["liveURL"] = mysql_real_escape_string($_POST["liveURL"]);
$_SESSION["archiveURL"] = mysql_real_escape_string($_POST["archiveURL"]);
$_SESSION["tripShowID"] = mysql_real_escape_string($_POST["tripShowID"]);
$_SESSION["isVideoConference"] = mysql_real_escape_string($_POST["isVideoConference"]);
$_SESSION["isHotSeat"] = mysql_real_escape_string($_POST["isHotSeat"]);


// if we are ADDING a show
if($_POST['action'] == "add")
{

if(!empty($_POST["isStreaming"])) {
	$_SESSION["isStreamingAdd"] = mysql_real_escape_string($_POST["isStreaming"]);
}
	else {
	$_SESSION["isStreamingAdd"] = 0;
	}

if(!empty($_POST["isVideoConference"])) {
	$_SESSION["isVideoConferenceAdd"] = mysql_real_escape_string($_POST["isVideoConference"]);
}
	else {
	$_SESSION["isVideoConferenceAdd"] = 0;
	}

if(!empty($_POST["isHotSeat"])) {
	$_SESSION["isHotSeatAdd"] = mysql_real_escape_string($_POST["isHotSeat"]);
}
	else {
	$_SESSION["isHotSeatAdd"] = 0;
	}


// LOOKING FOR SUCCESS IN THE IF STATEMENTS BELOW

// are the date and time fields empty?
   if(!empty($_SESSION["dateStartAdd"]) && !empty($_SESSION["timeStartAdd"])) {
      if(!empty($_SESSION["dateEndAdd"]) && !empty($_SESSION["timeEndAdd"])) {

// Are the times in the 12 hour time format?
        if(preg_match("/^(00|0[0-9]|1[012])(:[0-5][0-9][ ][AP][M]){1}/", $_SESSION["timeStartAdd"]) && (preg_match("/^(00|0[0-9]|1[012])(:[0-5][0-9][ ][AP][M]){1}/", $_SESSION["timeEndAdd"]))){

// Are the timess NOT in 00:00 AM/PM format?
          if ($_SESSION["timeStartAdd"] !=='00:00 AM' && $_SESSION["timeStartAdd"] !=='00:00 PM'
	  && ($_SESSION["timeEndAdd"] !=='00:00 AM' && $_SESSION["timeEndAdd"] !=='00:00 PM')){

	 		if ($_SESSION["dateStartAdd"] == $_SESSION["dateEndAdd"] ||  $_SESSION["dateStartAdd"] < $_SESSION["dateEndAdd"]){
              if( $_SESSION["dateStartAdd"] < $_SESSION["dateEndAdd"] || ($_SESSION["dateStartAdd"] == $_SESSION["dateEndAdd"] && (strtotime($_SESSION["timeStartAdd"]) < strtotime($_SESSION["timeEndAdd"]))) ){

//if the above falls to success put the times and dates in SQL format for inserting
	$_SESSION['convertTimeStart'] = date('H:i:s', strtotime($_SESSION['timeStartAdd']));
	$_SESSION["finalDateStart"]  = ($_SESSION["dateStartAdd"] . ' ' . ($_SESSION["convertTimeStart"]));

	$_SESSION['convertTimeEnd'] = date('H:i:s', strtotime($_SESSION['timeEndAdd']));
	$_SESSION["finalDateEnd"]  = ($_SESSION["dateEndAdd"] . ' ' . ($_SESSION["convertTimeEnd"]));

//Validate for an existing Show
$query = mysql_query("SELECT * FROM tblTripShow WHERE dateBegin=('" . $_SESSION["finalDateStart"] . "') AND dateEnd=('" . $_SESSION["finalDateEnd"] . "') AND intTripID = ('". $_SESSION["showID"] . "')");

if(mysql_num_rows($query) <= 0)
 {

// Add the information into the database
$sql = "INSERT INTO tblTripShow (intTripID, dateBegin, dateEnd, strLiveURL, strArchiveURL, isStreaming, isVideoConference, isHotSeat) VALUES (" . $_SESSION["showID"] . ", '" . $_SESSION["finalDateStart"] . "', '" . $_SESSION["finalDateEnd"] . "', '" . $_SESSION["liveURL"] . "', '" . $_SESSION["archiveURL"] . "', " . $_SESSION["isStreamingAdd"] . ", " . $_SESSION["isVideoConferenceAdd"] . ", " . $_SESSION["isHotSeatAdd"] . ");";
		mysql_query($sql);

// Send a success message. Message goes to the "success" placeholder in the addShow page
$_SESSION["success"] = "You've successfully created the new showing. You can add another showing below, or <a class='alert-link' href='viewTrip.php?id=" . $_SESSION["showID"] . "''>view your trip</a>.";
		header("Location: ../addShow.php?id=" . $_SESSION["showID"]);

		$_SESSION["dateStartAdd"] =  "";
		$_SESSION["dateEndAdd"] =  "";
		$_SESSION["timeStartAdd"] =  "";
		$_SESSION["timeEndAdd"] = "";
		$_SESSION["liveURLAdd"] = "";
		$_SESSION["archiveURLAdd"] = "";
		$_SESSION["isStreamingAdd"] = "";
		$_SESSION["isVideoConferenceAdd"] = "";
		$_SESSION["isHotSeatAdd"] = "";


	 			} // Fall out below for errors. Above are success scenarios

// Check for duplicate Show entry.
	 			else
	 			{
	 	     		$_SESSION["error"] = "A Show with the same date and time exists.";
	  	        	$_SESSION["errorAddTimeStart"] = 1;
	  	        	$_SESSION["errorAddTimeEnd"] = 1;
	  	        	$_SESSION["errorAddDateStart"] = 1;
	  	        	$_SESSION["errorAddDateEnd"] = 1;
	  	        	header("Location: ../addShow.php?id=" . $_SESSION["showID"]);
	 			}

              } // The last IF check is here. Error triggered If the dates are equal and the end time is less than the start time.
              else
              {
	 			  $_SESSION["error"] = "Please review the end time. Since the show is starting and ending on the same day, the end time must be AFTER the start time.";
        		  $_SESSION["errorAddTimeEnd"] = 1;
	              header("Location: ../addShow.php?id=" . $_SESSION["showID"]);
              }

            } // Error triggered If the END date is less than the START date.
            else
            {
	       	     $_SESSION["error"] = "Please review the end date and enter a date AFTER the start date.";
    		     $_SESSION["errorAddDateEnd"] = 1;
	             header("Location: ../addShow.php?id=" . $_SESSION["showID"]);
            }

          } // Error triggered If the START and/or END times equal "00:00 AM/PM".
          else
          {
	     	  $_SESSION["error"] = "Please review you times and enter a time other than '00:00 AM/PM'.";
	          $_SESSION["errorAddTimeStart"] = 1;
	    	  $_SESSION["errorAddTimeEnd"] = 1;
	    	  header("Location: ../addShow.php?id=" . $_SESSION["showID"]);
          }

        } // Error triggered If the START and/or END times do NOT match the regular expression.
        else
		{
	    	$_SESSION["error"] = "Please enter your time in the 12 hour format: 'hh:mm AM/PM'.";
		    $_SESSION["errorAddTimeStart"] = 1;
	     	$_SESSION["errorAddTimeEnd"] = 1;
		    header("Location: ../addShow.php?id=" . $_SESSION["showID"]);
		}

      } // Error triggered If the END times/dates are empty.
      else
      {
           $_SESSION["error"] = "An end date and time must be specified in order to create a showing.";
           $_SESSION["errorAddTimeEnd"] = 1;
           $_SESSION["errorAddDateEnd"] = 1;
    	   header("Location: ../addShow.php?id=" . $_SESSION["showID"]);
      }

   } // Error triggered If the START times/dates are empty.
   else
   {
       $_SESSION["error"] = "A start date and time must be specified in order to create a showing.";
       $_SESSION["errorAddTimeStart"] = 1;
       $_SESSION["errorAddDateStart"] = 1;
       header("Location: ../addShow.php?id=" . $_SESSION["showID"]);
   }



// ########################## EDIT - End up here if the POST("action") is "edit". ##################

} // end "add" if statement
else if($_POST["action"] == "edit")
	{
	    $_SESSION["tripShowID"] = mysql_real_escape_string($_POST["tripShowID"]);

  if(!empty($_POST["isVideoConference"])) {
	$_SESSION["isVideoConference"] = mysql_real_escape_string($_POST["isVideoConference"]);

  }
    else
    {
      $_SESSION["isVideoConference"] = 0;

    }

  if(!empty($_POST["isHotSeat"])) {
	$_SESSION["isHotSeat"] = mysql_real_escape_string($_POST["isHotSeat"]);
  }
    else
	{
	  $_SESSION["isHotSeat"] = 0;

    }


	// LOOKING FOR SUCCESS IN THE IF STATEMENTS BELOW

// are the date and time fields empty?
   if(!empty($_SESSION["dateStart"]) && !empty($_SESSION["timeStart"])) {
      if(!empty($_SESSION["dateEnd"]) && !empty($_SESSION["timeEnd"])) {

// Are the times in the 12 hour time format?
        if(preg_match("/^(00|0[0-9]|1[012])(:[0-5][0-9][ ][AP][M]){1}/", $_SESSION["timeStart"]) && (preg_match("/^(00|0[0-9]|1[012])(:[0-5][0-9][ ][AP][M]){1}/", $_SESSION["timeEnd"]))){

// Are the timess NOT in 00:00 AM/PM format?
          if ($_SESSION["timeStart"] !=='00:00 AM' && $_SESSION["timeStart"] !=='00:00 PM'
	  && ($_SESSION["timeEnd"] !=='00:00 AM' && $_SESSION["timeEnd"] !=='00:00 PM')){

            if ($_SESSION["dateStart"] == $_SESSION["dateEnd"] ||  $_SESSION["dateStart"] < $_SESSION["dateEnd"]){
              if( $_SESSION["dateStart"] < $_SESSION["dateEnd"] || ($_SESSION["dateStart"] == $_SESSION["dateEnd"] && (strtotime($_SESSION["timeStart"]) < strtotime($_SESSION["timeEnd"]))) ){

//if the above falls to success put the times and dates in SQL format for inserting
	$_SESSION['convertTimeStart'] = date('H:i:s', strtotime($_SESSION['timeStart']));
	$_SESSION["finalDateStart"]  = ($_SESSION["dateStart"] . ' ' . ($_SESSION["convertTimeStart"]));

	$_SESSION['convertTimeEnd'] = date('H:i:s', strtotime($_SESSION['timeEnd']));
	$_SESSION["finalDateEnd"]  = ($_SESSION["dateEnd"] . ' ' . ($_SESSION["convertTimeEnd"]));


//Validate for an existing Show
$query = mysql_query("SELECT * FROM tblTripShow WHERE dateBegin=('" . $_SESSION["finalDateStart"] . "') AND dateEnd=('" . $_SESSION["finalDateEnd"] . "') AND intTripID = ('". $_SESSION["showID"] . "' ");

if(mysql_num_rows($query) <= 0)
 {

//success, enter the topic
	$sql = "UPDATE tblTripShow SET strArchiveURL = ('" . $_SESSION["archiveURL"] . "'), strLiveURL = ('" . $_SESSION["liveURL"] . "'), dateBegin = ('" . $_SESSION["finalDateStart"] . "'), dateEnd = ('" . $_SESSION["finalDateEnd"] . "'), isVideoConference = ('" . $_SESSION["isVideoConference"] . "'), isHotSeat = ('" . $_SESSION["isHotSeat"] . "') WHERE tblTripShow.intTripShowID = ('" . $_SESSION["tripShowID"] . "')";
		mysql_query($sql) or die(mysql_error());


// Send a success message. Message goes to the "success" placeholder in the addShow page
	$_SESSION["success"] = "You've successfully created the new showing. You can add another showing below, or <a class='alert-link' href='viewTrip.php?id=" . $_SESSION["showID"] . "''>view your trip</a>.";
		header("Location: ../viewTrip.php?id=" . $_SESSION["showID"]);


		$_SESSION["dateStart"] =  "";
		$_SESSION["dateEnd"] =  "";
		$_SESSION["timeStart"] =  "";
		$_SESSION["timeEnd"] = "";
		$_SESSION["liveURL"] = "";
		$_SESSION["archiveURL"] = "";
		$_SESSION["isStreaming"] = "";
		$_SESSION["isVideoConference"] = "";
		$_SESSION["isHotSeat"] = "";


              }// Fall out below for errors. Above are success scenarios

// Check for duplicate Show entry.
              else
              {
				  $_SESSION["error". $_SESSION["tripShowID"]] = "A Show with the same date and time exists.";
	        	  $_SESSION["errorDateStart"] . $_SESSION["tripShowID"] = 1;
			      $_SESSION["errorDateEnd"] . $_SESSION["tripShowID"] = 1;
	    	      $_SESSION["errorTimeStart"] . $_SESSION["tripShowID"] = 1;
	     	      $_SESSION["errorTimeEnd"] . $_SESSION["tripShowID"] = 1;
	              header("Location: ../viewTrip.php?action=edit&id=" . $_SESSION["showID"].'#'.$_SESSION["tripShowID"]);
              }

            }// The last IF check is here. Error triggered If the dates are equal and the end time is less than the start time.
            else
            {
	 	         $_SESSION["error". $_SESSION["tripShowID"]] = "Please review the end time. Since the show is starting and ending on the same day, the end time must be AFTER the start time.";
    		     $_SESSION["errorTimeEnd"] . $_SESSION["tripShowID"] = 1;
	             header("Location: ../viewTrip.php?action=edit&id=" . $_SESSION["showID"].'#'.$_SESSION["tripShowID"]);
            }

          }// Error triggered If the END date is less than the START date.
          else
          {
	 	      $_SESSION["error" . $_SESSION["tripShowID"]] = "Please review the end date and enter a date AFTER the start date.";
    		  $_SESSION["errorDateEnd"] = 1;
	          header("Location: ../viewTrip.php?action=edit&id=" . $_SESSION["showID"].'#'.$_SESSION["tripShowID"]);
          }

        }// Error triggered If the START and/or END times equal "00:00 AM/PM".
        else
        {
	 	    $_SESSION["error". $_SESSION["tripShowID"]] = "Please review you times and enter a time other than '00:00 AM/PM'.";
	    	$_SESSION["errorTimeStart"] = 1;
	    	$_SESSION["errorTimeEnd"] = 1;
		    header("Location: ../viewTrip.php?action=edit&id=" . $_SESSION["showID"].'#'.$_SESSION["tripShowID"]);
        }

        }// Error triggered If the START and/or END times do NOT match the regular expression.
        else
        {
		    $_SESSION["error". $_SESSION["tripShowID"]] = "Please review your time format and enter in the 12 hour format: 'hh:mm AM/PM'.";
	    	$_SESSION["errorTimeStart"] = 1;
	    	$_SESSION["errorTimeEnd"] = 1;
			header("Location: ../viewTrip.php?action=edit&id=" . $_SESSION["showID"].'#'.$_SESSION["tripShowID"]);
        }

      } // Error triggered If the END times/dates are empty.
      else
      {
		   $_SESSION["error". $_SESSION["tripShowID"]] = "An end date and time must be specified in order to create a showing.";
	       $_SESSION["errorDateEnd"] = 1;
	       $_SESSION["errorTimeEnd"] = 1;
		   header("Location: ../viewTrip.php?action=edit&id=" . $_SESSION["showID"].'#'.$_SESSION["tripShowID"]);
      }

   } // Error triggered If the START times/dates are empty.
   else
   {
	    $_SESSION["error". $_SESSION["tripShowID"]] = "A start date and time must be specified in order to create a showing.";
	    $_SESSION["errorDateStart"] = 1;
	   	$_SESSION["errorTimeStart"] = 1;
		header("Location: ../viewTrip.php?action=edit&id=" . $_SESSION["showID"].'#'.$_SESSION["tripShowID"]);
   }
}
// if JOINING show
else if($_POST['action'] == "join") {

	$_SESSION["showID"] = $_POST["showID"];
	($_POST["isVideoConference"]) ? $_SESSION["isVideoConference"] = 1 : $_SESSION["isVideoConference"] = 0;
	($_POST["isHotSeat"]) ? $_SESSION["isHotSeat"] = 1 : $_SESSION["isHotSeat"] = 0;

	$sql = "INSERT INTO tblUserShow (intUserID, intTripShowID, isHotSeat, isVideoConference) VALUES (" . $_SESSION["userID"] . ", " . $_SESSION["showID"] . ", " . $_SESSION["isHotSeat"] . ", " . $_SESSION["isVideoConference"] . ")";
	mysql_query($sql);

	$sql = "SELECT * FROM tblTripShow JOIN tblTrip ON tblTripShow.intTripID = tblTrip.intTripID WHERE tblTripShow.intTripShowID=" . $_SESSION["showID"];
	$result = mysql_query($sql);
	$show = mysql_fetch_array($result);


	$_SESSION["success"] = "Congratulations, you've signed up for " . $show["strTitle"] . ". Please remember: <ul>";

	$today = mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"));

	if(strtotime($show["dateBegin"]) < $today) {

		$_SESSION["success"] .= "<li>This show has already been recorded. You can watch the recording by clicking the <em>Watch Archive</em> link below.</li>";

	}
	else {
		$_SESSION["success"] .= "<li>This show will be live on " . date("F j, Y,", strtotime($show["dateBegin"])) . " at " . date("g:i A", strtotime($show["dateBegin"]))  . " EST.</li>";

		if($_SESSION["isVideoConference"]) {
			$_SESSION["success"] .= "<li>You may be selected to participate via video conference. You will receive an email if you are selected.</li>";
		}

		if($_SESSION["isHotSeat"]) {
			$_SESSION["success"] .= "<li>You may be selected to participate in HotSeat during the show. You will receive an email if you are selected.</li>";
		}


	}

		$_SESSION["success"] .= "<li>For the best experience, please review the Teacher Guide prior to the show.</li>";
		$_SESSION["success"] .= "<li>Test your connection one week prior to the live show (see the Technical Guide for more information).</li>";
		$_SESSION["success"] .= "<li>Add ziptrips@purdue.edu to your email contact list and watch for more information via email.</li>";
		$_SESSION["success"] .= "<li>Visit your My zipTrips page during the show to send email questions or interact via Hotseat.</li></ul>";
		header("Location: ../myTrips.php");

}
else if($_POST['action'] == "updateParticipation") {

    $showID = (int) mysql_real_escape_string($_POST["showID"]);

    if(!$_SESSION["isVideoConference"]) {
      $_SESSION["isVideoConference"] = 0;
    }

    if(!$_SESSION["isHotSeat"]) {
      $_SESSION["isHotSeat"] = 0;
    }

    $sql = "UPDATE tblUserShow SET isVideoConference = " . $_SESSION["isVideoConference"] . ", isHotSeat = " . $_SESSION["isHotSeat"] . " WHERE intTripShowID = " . $showID . " AND intUserID=" . $_SESSION["userID"] . ";";
    mysql_query($sql);
    // echo "<br>" . $sql;
    // die("<br>done");
    $_SESSION["success"] = "Your participation preferences have been saved.";
    header("Location: ../myTrips.php");


}
else {
	// what happened?!
	header("Location: ../dashboard.php");
}

?>