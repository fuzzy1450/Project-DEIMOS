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
	
$stmt = $conn->prepare("SELECT * FROM users WHERE UserName = ? AND Password = ?");
$hashPW = hash("sha256", $_POST["Password"]);
$stmt->bind_param("ss", $_POST["UserName"], $hashPW);
$stmt->execute();
$QueryResult = $stmt->get_result();

if($QueryResult->num_rows === 0) {
	$result = false;
	$errors = 'Incorrect Username or Password';
	echo json_encode(array("success"=>$result, "message"=>$errors));
		
} else {// user found, password correct.
	$resultArray = $QueryResult->fetch_array(MYSQLI_ASSOC);
	$result = true;
	$errors = 'Login Successful';
	echo json_encode(array("success"=>$result, "message"=>$errors));
	$_SESSION['login'] = $_POST["UserName"];
	$_SESSION['LoginExpire'] = time();
}
?>