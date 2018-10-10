<?php
session_start();

include("../php/mySQLVariables.php");
	
$conn = new mysqli($sqlServer, $sqlUsername, $sqlPassword, $dbname);
if (mysqli_connect_errno()) {
	$errors = "Connection failed: " . $conn->connect_error;
	$result = false;
	$errors = 'Failed to connect to login database';
	die(json_encode(array("success"=>$result, "message"=>$errors)));
	
}
	
#check if account with this username already exists
$stmt = $conn->prepare("SELECT * FROM users WHERE UserName = ?");
$stmt->bind_param("s", $_POST["UserName"]);
$stmt->execute();
$QueryResult = $stmt->get_result();

if($QueryResult->num_rows > 0){ #if so, die with error message
	$result = false;
	$errors = 'Account already exists with that username';
	die( json_encode(array("success"=>$result, "message"=>$errors)));
}

#TODO - check if username entered is a valid email


#create random salt
$salt = substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil(10/strlen($x)) )),1,10);
$saltyPW = $salt . $_POST["Password"] . $salt;
$hashPW = hash("sha256", $saltyPW); #add random salt to password, hash new string
	

#create account
$stmt = $conn->prepare("INSERT INTO `users`(`username`, `password`, `pw_salt`, `AccessedCampaigns`) VALUES (?,?,?,'[]')");
$stmt->bind_param("sss", $_POST["UserName"], $hashPW, $salt);
$stmt->execute();
$QueryResult = $stmt->get_result();

if($stmt->affected_rows == 1){
	$result = true;
	$errors = 'Account created successfully';
	die( json_encode(array("success"=>$result, "message"=>$errors)));
	
} else {
	$result = false;
	$errors = 'Something went wrong?';
	die( json_encode(array("success"=>$result, "message"=>$errors)));
}
?>
