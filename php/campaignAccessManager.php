<?php
session_start();
include "loginTimeCheck.php";
include "mySQLVariables.php";

if(!isset($_SESSION["login"])){
	die("Log in to access this page's functions.");
}

$conn = new mysqli($sqlServer, $sqlUsername, $sqlPassword, $dbname);
if (mysqli_connect_errno()) {
	$result = false;
	$errors = 'Failed to connect to login database';
	die(json_encode(array("success"=>$result, "message"=>$errors)));
}

/**
 * Gets a list of Campaign IDs that are avaliable to the logged in user
 * @return int[] list of Campaign IDs
 */
function getCampaignsList(){
	include "mySQLVariables.php";
	$conn = new mysqli($sqlServer, $sqlUsername, $sqlPassword, $dbname);
	if (mysqli_connect_errno()) {
		$result = false;
		$errors = 'Failed to connect to login database';
		die(json_encode(array("success"=>$result, "message"=>$errors)));
	}

	$stmt = $conn->prepare("SELECT `AccessedCampaigns` FROM `users` WHERE username=?");
	$stmt->bind_param("s", $_SESSION["login"]);
	$stmt->execute();
	$QueryResult = $stmt->get_result();
	
	return json_decode($QueryResult->fetch_array()[0]);
}

/*
 * Checks if the user has access to a certain campaign
 * @param int $id the id of the campaign you want to check
 * @return bool true or false, depending if the user has access to it
 */
function hasCampaignAccess($id){
	return in_array($id, getCampaignsList());
}

/*
 * Gets the details of a campaign if the user has access to it
 * @param int $id the id of the campaign you want the details of
 * @return mixed[] array of details about the campaign
 */
function getCampaignDetails($id){
	if(!hasCampaignAccess($id)){
		return [false, "You do not have access to this campaign"];
	}
	
	include "mySQLVariables.php";
	$conn = new mysqli($sqlServer, $sqlUsername, $sqlPassword, $dbname);
	if (mysqli_connect_errno()) {
		$result = false;
		$errors = 'Failed to connect to login database';
		die(json_encode(array("success"=>$result, "message"=>$errors)));
	}

	$stmt = $conn->prepare("SELECT * FROM `campaigns` WHERE ID=?");
	$stmt->bind_param("s", $_SESSION["login"]);
	$stmt->execute();
	return $stmt->get_result()->fetch_array();
}


?>
