<?php
session_start();
include_once("includes/auth-admin.php");
include_once("db.php");

$_SESSION["registration"] = $_POST["isRegistrationEnabled"];
$_SESSION["login"] = $_POST["isLoginEnabled"];

($_SESSION["registration"]) ? $_SESSION["registration"] = 1 : $_SESSION["registration"] = 0;
($_SESSION["login"]) ? $_SESSION["login"] = 1 : $_SESSION["login"] = 0;


$sql = "UPDATE tblSettings SET isRegistrationEnabled=" . $_SESSION["registration"] . ", isLoginEnabled=" . $_SESSION["login"] . " WHERE intSettingsID=1";
mysql_query($sql) or die(mysql_error());
$_SESSION["success"] = "You have updated the site settings.";
header("Location: ../settings.php");





?>