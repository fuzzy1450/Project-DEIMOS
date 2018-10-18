<?php
session_start();

include("../php/mySQLVariables.php");
include("../php/genericPHPVariables.php");
	
$conn = new mysqli($sqlServer, $sqlUsername, $sqlPassword, $dbname);
if (mysqli_connect_errno()) {
	$result = false;
	$errors = 'Failed to connect to login database';
	die(json_encode(array("success"=>$result, "message"=>$errors)));
}

#check if username exists
#if so, get salt to add to password
#if not, give generic error

$stmt = $conn->prepare("SELECT * FROM users WHERE UserName = ?");
$stmt->bind_param("s", $_POST["UserName"]);
$stmt->execute();
$QueryResult = $stmt->get_result();

if($QueryResult->num_rows === 0) {
	$result = false;
	$errors = 'Incorrect Username or Password';
	die( json_encode(array("success"=>$result, "message"=>$errors)));
	
} else {
	$salt = $QueryResult->fetch_array()['pw_salt'];
}


	
$stmt = $conn->prepare("SELECT * FROM users WHERE UserName = ? AND Password = ?");
$hashPW = hash("sha256", $salt. $_POST["Password"] . $salt);
$stmt->bind_param("ss", $_POST["UserName"], $hashPW);
$stmt->execute();
$QueryResult = $stmt->get_result();

if($QueryResult->num_rows === 0) {
	$result = false;
	$errors = 'Incorrect Username or Password';
	die( json_encode(array("success"=>$result, "message"=>$errors)));
		
} else {// user found, password correct.
	$_SESSION['login'] = $_POST["UserName"];
	$_SESSION['loginID'] = $QueryResult->fetch_array()["ID"];
	$_SESSION['LoginExpire'] = (time() + ($LoginDays*(60*60*24)) + ($LoginHours*(60*60)) + ($LoginMinutes*(60)) + ($LoginSeconds));
	
	$result = true;
	$errors = 'Login Successful';
	echo json_encode(array("success"=>$result, "message"=>$errors));
}


?>
