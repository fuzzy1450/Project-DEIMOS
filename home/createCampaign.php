<?php
session_start();

include "../php/loginTimeCheck.php";
include "../php/mySQLVariables.php";
include "../php/campaignAccessManager.php";
include "../php/gameSystemManager.php";

if(!isset($_SESSION["login"])){
	$result = false;
	$errors = 'User is not logged in.';
	die( json_encode(array("success"=>$result, "message"=>$errors)));
}

$GameName = urldecode($_POST["CampaignName"]);
$Description = urldecode($_POST["Description"]);
$gameSys = urldecode($_POST["gameSys"]);

if($gameSys == "default"){
	$result = false;
	$errors = 'You must choose a game system.';
	die( json_encode(array("success"=>$result, "message"=>$errors)));
}

$conn = new mysqli($sqlServer, $sqlUsername, $sqlPassword, $dbname);
if (mysqli_connect_errno()) {
	$result = false;
	$errors = 'Failed to connect to login database';
	die(json_encode(array("success"=>$result, "message"=>$errors)));
}

$stmt = $conn->prepare("INSERT INTO `campaigns`(`gameTypeID`, `CampaignName`, `Description`) VALUES (?,?,?)");
$stmt->bind_param("iss", $gameSys, $GameName, $Description);
$stmt->execute();
$QueryResult = $stmt->get_result();

if($stmt->affected_rows == 1){
	$result = true;
	$errors = 'Campaign created successfully';
	die( json_encode(array("success"=>$result, "message"=>$errors)));
	
} else {
	$result = false;
	$errors = 'Something went wrong?';
	die( json_encode(array("success"=>$result, "message"=>$errors)));
}

