<?php session_start(); 

include_once("functions/db.php");

 $sqlSettings = "SELECT * FROM tblSettings WHERE intSettingsID=1;";
 $resultsSettings = mysql_query($sqlSettings) or die(mysql_error());
 $settings = mysql_fetch_array($resultsSettings);

 ($settings["isRegistrationEnabled"]) ? $_SESSION["isRegistrationEnabled"] = "1" : $_SESSION["isRegistrationEnabled"] = "0";
 ($settings["isLoginEnabled"]) ? $_SESSION["isLoginEnabled"] = "1" : $_SESSION["isLoginEnabled"] = "0";

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta http-equiv="X-UA-Compatible" content="IE=9">

    <title>zipTrips&trade; at Purdue University</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <!-- Add custom CSS here -->
    <link href="css/style.css" rel="stylesheet">
    <link id="base-css" href="css/modern-business.css" rel="stylesheet">
    <link href="font-awesome/css/font-awesome.css" rel="stylesheet">
    <!-- IE FONT AWESOME HACK -->
    <link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css" rel="stylesheet">
    



  </head>

  <body>

    <nav class="navbar navbar-default navbar-fixed-top" style="border-bottom: 3px solid #8cc739; background-color: #fff;" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <!-- You'll want to use a responsive image option so this logo looks good on devices - I recommend using something like retina.js (do a quick Google search for it and you'll find it) -->
          <?php (empty($_SESSION["strRole"])) ? $logo = "<a class='navbar-brand' href='index.php'><img alt='Purdue zipTrips' src='images/logo.png' /></a>" : $logo = "<a class='navbar-brand' href='dashboard.php'><img alt='Purdue zipTrips' src='images/logo.png' /></a>"; 
                echo $logo;
          ?>

        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse navbar-ex1-collapse">
          <ul class="nav navbar-nav navbar-right" style="margin-top: 20px;">
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-book"></i> Learn <b class="caret"></b></a>
              <ul class="dropdown-menu">
                <li><a href="what.php">What is zipTrips&trade;?</a></li>
                <li><a href="upcoming.php">When is the next zipTrip&trade;?</a></li>
                <li><a href="sponsoring.php">How do I sponsor zipTrips&trade;?</a></li>
              </ul>
            </li>
            <li><a href="contact.php"><i class="fa fa-envelope"></i> Contact Us</a></li>

            <?php if(!empty($_SESSION["strRole"])) { 
              include_once("functions/db.php");
              include_once("functions/user-functions.php");

              // alert administrators if site settings are changed from normal
              $warning = "";
              if(checkAdmin($_SESSION["userID"])) {
                ($_SESSION["isRegistrationEnabled"]) ? NULL : $warning = "<span data-placement='bottom' rel='tooltip' title='Registration is currently disabled.' class='label label-warning'><i class='fa fa-warning'></i></span>";
                ($_SESSION["isLoginEnabled"]) ? NULL : $warning = "<span data-placement='bottom' rel='tooltip' title='Maintenance mode is currently enabled.' class='label label-warning'><i class='fa fa-warning'></i></span>";
                (!$_SESSION["isRegistrationEnabled"] && !$_SESSION["isLoginEnabled"]) ? $warning = "<span data-placement='bottom' rel='tooltip' title='Maintenance mode is enabled and registration is disabled.' class='label label-warning'>2 <i class='fa fa-warning'></i></span>" : NULL;
              }

              ?>
              <li><a href="myTrips.php"><i class="fa fa-rocket"></i> My zipTrips</a></li>
              <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#"><?= $warning ?> <i class="fa fa-user"></i> <?php showFullName($_SESSION["userID"])  ?> <b class="caret"></b></a>
              <ul class="dropdown-menu">
                <li><a href="account.php?action=edit"><i class="fa fa-edit"></i> Edit Profile</a></li>
                <?php if(checkAdmin($_SESSION["userID"])) { echo "<li><a href='cPanel.php'><i class='fa fa-cog'></i> Admin Panel</a></li>";} ?>
                <li><a href="functions/doAccount.php?action=logout"><i class="fa fa-sign-out"></i> Log out</a></li>
              </ul>
              </li>


            <?php } else { ?>
              <li><a href="signup.php"><i class="fa fa-plus"></i> Register</a></li>
             <li><a href="signin.php"><i class="fa fa-sign-in"></i> Log In</a></li>
            <?php } ?>
          </ul>
        </div><!-- /.navbar-collapse -->
      </div><!-- /.container -->
    </nav>

   
