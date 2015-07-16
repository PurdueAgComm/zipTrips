<?php


	function setPassword($userPass) {
		$salt = md5("5s1dsd^v8#$%aB$^^$568/*3^2#S");
		$userPass = sha1($userPass . $salt);
		return $userPass;

	}

	function checkPassword($userPass, $dbPass) {
		$salt = md5("5s1dsd^v8#$%aB$^^$568/*3^2#S");
		$userPass = sha1($userPass . $salt);
		return ($userPass == $dbPass);
	}

	function showFullName($id) {
		$sql = "SELECT strFirstName, strLastName FROM tblUser WHERE intUserID=" . $id;
		$result = mysql_query($sql) or die(mysql_error());
		$user = mysql_fetch_array($result);
		$name = (empty($user["strFirstName"]) || empty($user["strLastName"])) ? "Account" : $user["strFirstName"] . " " . $user["strLastName"];
		echo $name;

	}

	function checkAdmin($id) {
		$sql = "SELECT strRole FROM tblUser WHERE intUserID=" . $id;
		$result = mysql_query($sql);
		$user = mysql_fetch_array($result);
		return ($user["strRole"] == "admin") ? 1 : 0;

	}

	function isProfileComplete($id) {
		$sql = "SELECT * FROM tblUser WHERE intUserID=" . $id;
		$result = mysql_query($sql);
		$user = mysql_fetch_array($result);

		return ($user["isProfileComplete"]) ? 1 : 0;

	}


?>