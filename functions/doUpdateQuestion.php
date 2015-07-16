<?php
session_start();
include_once("includes/auth-admin.php");
include_once("db.php");

    $pk = $_POST['pk']; //primary key
    $name = $_POST['name']; // name of field
    $value = $_POST['value']; // value of field


    if(!empty($value)) {

    	$sql = "UPDATE tblQuestions SET strQuestion='" . mysql_escape_string($value) ."' WHERE intQuestionID=" . (int) $pk;
    	mysql_query($sql);
 

    } else {
        /* 
        In case of incorrect value or error you should return HTTP status != 200. 
        Response body will be shown as error message in editable form.
        */

        header('HTTP 400 Bad Request', true, 400);
        echo "You can't leave the question blank.";
    }

?>