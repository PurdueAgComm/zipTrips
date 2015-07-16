<?php
include_once("includes/header.php");
include_once("includes/auth-admin.php");

$sql = "SELECT * FROM tblUser WHERE intUserID=" . (int) $_GET["id"];
$result = mysql_query($sql);
$user = mysql_fetch_array($result);

$sql = "SELECT * from tblUserShow WHERE intUserID=" . (int) $_GET["id"] . " ORDER BY dateJoined DESC";
$result = mysql_query($sql);
$numShowsUser = mysql_num_rows($result);

?>


<!-- start body -->
     <div class="section">
      <div class="container">
      	<div class="row">
        <div class="col-lg-12">
          <h1 class="page-header">Manage User <small> <?= $user["strFirstName"] . " " . $user["strLastName"] ?></small></h1>
          <ol class="breadcrumb">
  		    	<li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="cPanel.php">Control Panel</a></li>
            <li class="active">Manage User: <?= $user["strFirstName"] . " " . $user["strLastName"] ?></li> 
           </ol>
        </div>
     	</div>

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

	
	    <div class="row">
            <div class="col-md-6">
            <form class="form-horizontal" action="functions/doAccount.php" method="post" role="form">
              <h3>Personal Information</h3>
                <div class="form-group <?php if($_SESSION['errorName']) echo 'has-error'; ?>">
                  <label for="profileFirstName" class="col-sm-2 control-label">Name</label>
                  <div class="col-lg-4 col-md-4 col-sm-8">
                    <input type="text" class="form-control" value="<?php echo $user["strFirstName"] ?>" id="profileFirstName" name="profileFirstName" placeholder="First Name">
                  </div>
                <div class="col-lg-4 col-md-4 col-sm-8 col-sm-offset-2 col-md-offset-0">
                  <input type="text" class="form-control" value="<?php echo $user['strLastName']; ?>"  name="profileLastName" placeholder="Last Name">
                </div>
                </div>

                <div class="form-group <?php if($_SESSION['errorGrade']) echo 'has-error'; ?>">
                  <label for="profileGrade" class="col-sm-2 control-label">I teach</label>
                  <div class="col-sm-8">
                    <div class="input-group">
                      <select class="form-control" id="profileGrade" name="profileGrade">
                        <?php
                        $gradeArray = array("Choose a Grade", "6th",  "7th", "8th");
                        foreach($gradeArray as $value) {
                          echo $grade;
                          if ($user['strGrade'] == $value) {
                            echo "<option selected='selected' value='".$value."'> " .$value. "</option>";
                          } else {
                            echo "<option value='".$value."'> ".$value."</option>";
                          }
                        }
                      ?>
                    </select>
                    <span class="input-group-addon">grade</span>
                  </div>
                  </div>
                </div>

              <h3>School Information</h3>
                <div class="form-group <?php if($_SESSION['errorSchool']) echo 'has-error'; ?>">
                  <label for="profileSchool" class="col-sm-2 control-label">School Name</label>
                  <div class="col-sm-8">
                    <input type="text" class="form-control" value="<?php echo $user['strSchool']; ?>" name="profileSchool" id="profileSchool" placeholder="School Name">
                  </div>
                </div>

                <div class="form-group <?php if($_SESSION['errorClass']) echo 'has-error'; ?>">
                  <label for="profileSchool" class="col-sm-2 control-label">Class Size</label>
                  <div class="col-sm-8">
                    <? (empty($user["intClass"])) ? $num = "" : $num = $user["intClass"]; ?>
                    <input type="text" class="form-control" value="<?php echo $num ?>" name="profileClass" id="profileClass" placeholder="Class Size">
                  </div>
                </div>

                <div class="form-group <?php if($_SESSION['errorSchoolType']) echo 'has-error'; ?>">
                  <label for="profileSchoolType" class="col-sm-2 control-label">My school is</label>
                  <div class="col-sm-8">
                    <select class="form-control" id="profileSchoolType" name="profileSchoolType">
                      <?php
                        $gradeArray = array("Choose School Type", "a public school", "a private school", "a home school");
                        foreach($gradeArray as $value) {
                          echo $grade;
                          if ($user['strSchoolType'] == $value) {
                            echo "<option selected='selected' value='".$value."'> " .$value. "</option>";
                          } else {
                            echo "<option value='".$value."'> ".$value."</option>";
                          }
                        }
                      ?>
                    </select>
                  </div>
                </div>

                <div class="form-group <?php if($_SESSION['errorAddress']) echo 'has-error'; ?>">
                  <label for="profileAddress" class="col-sm-2 control-label">Street Address</label>
                  <div class="col-sm-8">
                    <input type="text" class="form-control" value="<?php echo $user['strAddress']; ?>" id="profileAddress" name="profileAddress" placeholder="Street Address">
                  </div>
                </div>

                <div class="form-group <?php if($_SESSION['errorCity']) echo 'has-error'; ?>">
                  <label for="profileCity" class="col-sm-2 control-label">City</label>
                  <div class="col-sm-8">
                    <input type="text" class="form-control" value="<?php echo $user['strCity']; ?>" id="profileCity" name="profileCity" placeholder="City">
                  </div>
                </div>

                <div class="form-group <?php if($_SESSION['errorState']) echo 'has-error'; ?>">
                  <label for="profileState" class="col-sm-2 control-label">State or Province</label>
                  <div class="col-sm-8">
                    <input type="text" class="form-control" value="<?php echo $user['strState']; ?>" id="profileState" name="profileState" placeholder="State or Province">
                  </div>
                </div>

                <div class="form-group <?php if($_SESSION['errorCountry']) echo 'has-error'; ?>">
                  <label for="profileCountry" class="col-sm-2 control-label">Country</label>
                  <div class="col-sm-8">
                    <select class="form-control" id="profileCountry" name="profileCountry">
                      <?php

                        $countries = array(
                                "US" => "United States",
                                "GB" => "United Kingdom",                       
                                "AF" => "Afghanistan",
                                "AL" => "Albania",
                                "DZ" => "Algeria",
                                "AS" => "American Samoa",
                                "AD" => "Andorra",
                                "AO" => "Angola",
                                "AI" => "Anguilla",
                                "AQ" => "Antarctica",
                                "AG" => "Antigua And Barbuda",
                                "AR" => "Argentina",
                                "AM" => "Armenia",
                                "AW" => "Aruba",
                                "AU" => "Australia",
                                "AT" => "Austria",
                                "AZ" => "Azerbaijan",
                                "BS" => "Bahamas",
                                "BH" => "Bahrain",
                                "BD" => "Bangladesh",
                                "BB" => "Barbados",
                                "BY" => "Belarus",
                                "BE" => "Belgium",
                                "BZ" => "Belize",
                                "BJ" => "Benin",
                                "BM" => "Bermuda",
                                "BT" => "Bhutan",
                                "BO" => "Bolivia",
                                "BA" => "Bosnia And Herzegowina",
                                "BW" => "Botswana",
                                "BV" => "Bouvet Island",
                                "BR" => "Brazil",
                                "IO" => "British Indian Ocean Territory",
                                "BN" => "Brunei Darussalam",
                                "BG" => "Bulgaria",
                                "BF" => "Burkina Faso",
                                "BI" => "Burundi",
                                "KH" => "Cambodia",
                                "CM" => "Cameroon",
                                "CA" => "Canada",
                                "CV" => "Cape Verde",
                                "KY" => "Cayman Islands",
                                "CF" => "Central African Republic",
                                "TD" => "Chad",
                                "CL" => "Chile",
                                "CN" => "China",
                                "CX" => "Christmas Island",
                                "CC" => "Cocos (Keeling) Islands",
                                "CO" => "Colombia",
                                "KM" => "Comoros",
                                "CG" => "Congo",
                                "CD" => "Congo, The Democratic Republic Of The",
                                "CK" => "Cook Islands",
                                "CR" => "Costa Rica",
                                "CI" => "Cote D'Ivoire",
                                "HR" => "Croatia (Local Name: Hrvatska)",
                                "CU" => "Cuba",
                                "CY" => "Cyprus",
                                "CZ" => "Czech Republic",
                                "DK" => "Denmark",
                                "DJ" => "Djibouti",
                                "DM" => "Dominica",
                                "DO" => "Dominican Republic",
                                "TP" => "East Timor",
                                "EC" => "Ecuador",
                                "EG" => "Egypt",
                                "SV" => "El Salvador",
                                "GQ" => "Equatorial Guinea",
                                "ER" => "Eritrea",
                                "EE" => "Estonia",
                                "ET" => "Ethiopia",
                                "FK" => "Falkland Islands (Malvinas)",
                                "FO" => "Faroe Islands",
                                "FJ" => "Fiji",
                                "FI" => "Finland",
                                "FR" => "France",
                                "FX" => "France, Metropolitan",
                                "GF" => "French Guiana",
                                "PF" => "French Polynesia",
                                "TF" => "French Southern Territories",
                                "GA" => "Gabon",
                                "GM" => "Gambia",
                                "GE" => "Georgia",
                                "DE" => "Germany",
                                "GH" => "Ghana",
                                "GI" => "Gibraltar",
                                "GR" => "Greece",
                                "GL" => "Greenland",
                                "GD" => "Grenada",
                                "GP" => "Guadeloupe",
                                "GU" => "Guam",
                                "GT" => "Guatemala",
                                "GN" => "Guinea",
                                "GW" => "Guinea-Bissau",
                                "GY" => "Guyana",
                                "HT" => "Haiti",
                                "HM" => "Heard And Mc Donald Islands",
                                "VA" => "Holy See (Vatican City State)",
                                "HN" => "Honduras",
                                "HK" => "Hong Kong",
                                "HU" => "Hungary",
                                "IS" => "Iceland",
                                "IN" => "India",
                                "ID" => "Indonesia",
                                "IR" => "Iran (Islamic Republic Of)",
                                "IQ" => "Iraq",
                                "IE" => "Ireland",
                                "IL" => "Israel",
                                "IT" => "Italy",
                                "JM" => "Jamaica",
                                "JP" => "Japan",
                                "JO" => "Jordan",
                                "KZ" => "Kazakhstan",
                                "KE" => "Kenya",
                                "KI" => "Kiribati",
                                "KP" => "Korea, Democratic People's Republic Of",
                                "KR" => "Korea, Republic Of",
                                "KW" => "Kuwait",
                                "KG" => "Kyrgyzstan",
                                "LA" => "Lao People's Democratic Republic",
                                "LV" => "Latvia",
                                "LB" => "Lebanon",
                                "LS" => "Lesotho",
                                "LR" => "Liberia",
                                "LY" => "Libyan Arab Jamahiriya",
                                "LI" => "Liechtenstein",
                                "LT" => "Lithuania",
                                "LU" => "Luxembourg",
                                "MO" => "Macau",
                                "MK" => "Macedonia, Former Yugoslav Republic Of",
                                "MG" => "Madagascar",
                                "MW" => "Malawi",
                                "MY" => "Malaysia",
                                "MV" => "Maldives",
                                "ML" => "Mali",
                                "MT" => "Malta",
                                "MH" => "Marshall Islands",
                                "MQ" => "Martinique",
                                "MR" => "Mauritania",
                                "MU" => "Mauritius",
                                "YT" => "Mayotte",
                                "MX" => "Mexico",
                                "FM" => "Micronesia, Federated States Of",
                                "MD" => "Moldova, Republic Of",
                                "MC" => "Monaco",
                                "MN" => "Mongolia",
                                "MS" => "Montserrat",
                                "MA" => "Morocco",
                                "MZ" => "Mozambique",
                                "MM" => "Myanmar",
                                "NA" => "Namibia",
                                "NR" => "Nauru",
                                "NP" => "Nepal",
                                "NL" => "Netherlands",
                                "AN" => "Netherlands Antilles",
                                "NC" => "New Caledonia",
                                "NZ" => "New Zealand",
                                "NI" => "Nicaragua",
                                "NE" => "Niger",
                                "NG" => "Nigeria",
                                "NU" => "Niue",
                                "NF" => "Norfolk Island",
                                "MP" => "Northern Mariana Islands",
                                "NO" => "Norway",
                                "OM" => "Oman",
                                "PK" => "Pakistan",
                                "PW" => "Palau",
                                "PA" => "Panama",
                                "PG" => "Papua New Guinea",
                                "PY" => "Paraguay",
                                "PE" => "Peru",
                                "PH" => "Philippines",
                                "PN" => "Pitcairn",
                                "PL" => "Poland",
                                "PT" => "Portugal",
                                "PR" => "Puerto Rico",
                                "QA" => "Qatar",
                                "RE" => "Reunion",
                                "RO" => "Romania",
                                "RU" => "Russian Federation",
                                "RW" => "Rwanda",
                                "KN" => "Saint Kitts And Nevis",
                                "LC" => "Saint Lucia",
                                "VC" => "Saint Vincent And The Grenadines",
                                "WS" => "Samoa",
                                "SM" => "San Marino",
                                "ST" => "Sao Tome And Principe",
                                "SA" => "Saudi Arabia",
                                "SN" => "Senegal",
                                "SC" => "Seychelles",
                                "SL" => "Sierra Leone",
                                "SG" => "Singapore",
                                "SK" => "Slovakia (Slovak Republic)",
                                "SI" => "Slovenia",
                                "SB" => "Solomon Islands",
                                "SO" => "Somalia",
                                "ZA" => "South Africa",
                                "GS" => "South Georgia, South Sandwich Islands",
                                "ES" => "Spain",
                                "LK" => "Sri Lanka",
                                "SH" => "St. Helena",
                                "PM" => "St. Pierre And Miquelon",
                                "SD" => "Sudan",
                                "SR" => "Suriname",
                                "SJ" => "Svalbard And Jan Mayen Islands",
                                "SZ" => "Swaziland",
                                "SE" => "Sweden",
                                "CH" => "Switzerland",
                                "SY" => "Syrian Arab Republic",
                                "TW" => "Taiwan",
                                "TJ" => "Tajikistan",
                                "TZ" => "Tanzania, United Republic Of",
                                "TH" => "Thailand",
                                "TG" => "Togo",
                                "TK" => "Tokelau",
                                "TO" => "Tonga",
                                "TT" => "Trinidad And Tobago",
                                "TN" => "Tunisia",
                                "TR" => "Turkey",
                                "TM" => "Turkmenistan",
                                "TC" => "Turks And Caicos Islands",
                                "TV" => "Tuvalu",
                                "UG" => "Uganda",
                                "UA" => "Ukraine",
                                "AE" => "United Arab Emirates",
                                "UM" => "United States Minor Outlying Islands",
                                "UY" => "Uruguay",
                                "UZ" => "Uzbekistan",
                                "VU" => "Vanuatu",
                                "VE" => "Venezuela",
                                "VN" => "Viet Nam",
                                "VG" => "Virgin Islands (British)",
                                "VI" => "Virgin Islands (U.S.)",
                                "WF" => "Wallis And Futuna Islands",
                                "EH" => "Western Sahara",
                                "YE" => "Yemen",
                                "YU" => "Yugoslavia",
                                "ZM" => "Zambia",
                                "ZW" => "Zimbabwe"
                              );

                        foreach($countries as $value) {
                          if ($user['strCountry'] == $value) {
                            echo "<option selected='selected' value='".$value."'> " .$value. "</option>";
                          } else {
                            echo "<option value='".$value."'> ".$value."</option>";
                          }
                        }
                      ?>
                    </select>
                  </div>
                </div>



              <h3>Account Information</h3>
                <div class="form-group <?php if($_SESSION['errorEmail']) echo 'has-error'; ?>">
                  <label for="profileEmail" class="col-sm-2 control-label">Email</label>
                  <div class="col-sm-8">
                    <input type="email" class="form-control" value="<?php echo $user['strEmail']; ?>" id="profileEmail" name="profileEmail" placeholder="Email">
                  </div>
                </div>

                <div class="form-group <?php if($_SESSION['errorPassword']) echo 'has-error'; ?>">
                  <label for="signInPassword" class="col-sm-2 control-label">Change Password</label>
                  <div class="col-sm-8">
                    <div class="input-group">
                        <input type="password" class="form-control" id="signInPassword" name="profilePassword" placeholder="Change Password">
                        <div class="input-group-btn">
                          <button class='btn btn-default add-on' type="button" id="togglePasswordField" style="display:none;"><i rel="tooltip" title="Show password" class='fa fa-eye'></i></button>
                        </div>
                      </div>
                  <span class="help-block"><i class="fa fa-info-circle"></i> You only need to fill out this field  if you want to change your password.</span>
                  </div>
                </div>


                <div class="form-group <?php if($_SESSION['errorProfileType']) echo 'has-error'; ?>">
                  <label for="profileRole" class="col-sm-2 control-label">Account</label>
                  <div class="col-sm-8">
                    <select class="form-control" id="profileRole" name="profileRole">
                      <?php
                        $roleArray = array("user", "admin", "scientist");
                        foreach($roleArray as $value) {
                          if ($user['strRole'] == $value) {
                            echo "<option selected='selected' value='".$value."'> " .$value. "</option>";
                          } else {
                            echo "<option value='".$value."'> ".$value."</option>";
                          }
                        }
                      ?>
                    </select>
                  </div>
                </div>




                <div class="form-group">
                  <div class="col-sm-offset-2 col-sm-8">
                    <input name="action" value="adminUpdate" type="hidden" />
                    <input name="id" value="<?= (int) $_GET["id"]?>" type="hidden" />
                    <button type="submit" class="btn btn-default btn-block"><i class="fa fa-save"></i> Update Profile</button>
                  </div>
                </div>
              </form>
           

         </div>
         <div class="col-md-6">
          <h2>Shows</h2>
          <ul class="list-group">
            <?php 
            echo $numShows;
            if($numShowsUser > 0) {
              while($showUser = mysql_fetch_array($result)) { 
                $sql2 = "SELECT * FROM tblTrip JOIN tblTripShow ON tblTrip.intTripID = tblTripShow.intTripID WHERE tblTripShow.intTripShowID=" . $showUser["intTripShowID"];
                $result2  = mysql_query($sql2);
                $trip = mysql_fetch_array($result2);


                ?>
                <li class="list-group-item">
                  <?= $trip["strTitle"] ?> <br />
                  <small><i title="Trip Date" rel="tooltip" class="fa fa-calendar"></i> <?= date("F d, Y", strtotime($trip["dateBegin"])) ?></small><br />
                  <small><i title="Joined" rel="tooltip" class="fa fa-sign-in"></i>  <?= date("F d, Y", strtotime($showUser["dateJoined"])) ?></small>

                </li>
            <?php } } else { ?>
            <div class="alert alert-info">This user has not signed up for any shows.</div>
            <?php } ?>
          </ul>
         </div>
       </div>







<?php
include_once("includes/footer.php");
?>



<script>
(function() {
  try {

    // switch the password field to text, then back to password to see if it supports
    // changing the field type (IE9+, and all other browsers do). then switch it back.
    var passwordField = document.getElementById('signInPassword');
    passwordField.type = 'text';
    passwordField.type = 'password';
    
    // if it does support changing the field type then add the event handler and make
    // the button visible. if the browser doesn't support it, then this is bypassed
    // and code execution continues in the catch() section below
    var togglePasswordField = document.getElementById('togglePasswordField');
    togglePasswordField.addEventListener('click', togglePasswordFieldClicked, false);
    togglePasswordField.style.display = 'inline';
    
  }
  catch(err) {

  }
})();
function togglePasswordFieldClicked() {
  var passwordField = document.getElementById('signInPassword');
  var value = passwordField.value;
  if(passwordField.type == 'password') {
    passwordField.type = 'text';
    togglePasswordField.innerHTML = "<i rel='tooltip' title='Hide password' class='fa fa-eye-slash'></i>";
  }
  else {
    passwordField.type = 'password';
    togglePasswordField.innerHTML = "<i rel='tooltip' title='Show password' class='fa fa-eye'></i>";
    
  }
  passwordField.value = value;
} 
</script>
