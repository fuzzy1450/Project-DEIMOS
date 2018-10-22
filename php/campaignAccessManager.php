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
 * Gets a list of Campaign IDs that are avaliable to a specified user
 * @param int $id the ID of the user who's campaign list you want to be returned
 * @return int[] list of Campaign IDs
 *
 */
function getCampaignsList($id){
	include "mySQLVariables.php";
	$conn = new mysqli($sqlServer, $sqlUsername, $sqlPassword, $dbname);
	if (mysqli_connect_errno()) {
		$result = false;
		$errors = 'Failed to connect to login database';
		die(json_encode(array("success"=>$result, "message"=>$errors)));
	}

	$stmt = $conn->prepare("SELECT `AccessedCampaigns` FROM `users` WHERE ID=?");
	$stmt->bind_param("i", $id);
	$stmt->execute();
	$QueryResult = $stmt->get_result();
	
	return json_decode($QueryResult->fetch_array()[0]);
}

/*
 * Checks if the user has access to a certain campaign
 * @param int $id the id of the campaign you want to check
 * @return bool true or false, depending if the user has access to it
 */
function hasCampaignAccess($CampaignID, $UserID){
	return in_array($CampaignID, getCampaignsList($UserID));
}

/*
 * Gets the details of a campaign if the user has access to it
 * @param int $id the id of the campaign you want the details of
 * @return mixed[] array of details about the campaign
 */
function getCampaignDetails($id){
	if(!hasCampaignAccess($id, $_SESSION["loginID"])){
		return [false, "You do not have access to this campaign"];
	}
	
	include "mySQLVariables.php";
	$conn = new mysqli($sqlServer, $sqlUsername, $sqlPassword, $dbname);
	if (mysqli_connect_errno()) {
		return [false, "Failed to connect to database"];
	}

	$stmt = $conn->prepare("SELECT * FROM `campaigns` WHERE ID=?");
	$stmt->bind_param("i", $id);
	$stmt->execute();
	return $stmt->get_result()->fetch_array();
}

/**
 *  Grant a user access to a campaign given a user ID and a campaign ID
 *  
 *  @param int $UserID Internal ID of the user you want access given to
 *  @param int $CampaignID Internal ID of the campaign you want to give access to
 *  
 *  @return mixed[] An array that contains a boolean and a string. The boolean will be true if the opperation completed successfully. The string will be empty unless the opperation fails, in which case it will contain a reason for failure.
 */
function grantCampaignAccess($UserID, $CampaignID){
	if(hasCampaignAccess($CampaignID, $UserID)){
		return [false, "User Already has access to this campaign"];
	}
	
	// Get array of campaigns user already has access to
	$userCampaignList = getCampaignsList($UserID);
	
	// Add the campaign id to the list of campaigns that the user already has access to
	array_push($userCampaignList, $CampaignID);
	
	// Update the database with the new list
	include "mySQLVariables.php";
	$conn = new mysqli($sqlServer, $sqlUsername, $sqlPassword, $dbname);
	if (mysqli_connect_errno()) {
		return [false, "Failed to connect to database"];
	}

	$stmt = $conn->prepare("UPDATE `users` SET `AccessedCampaigns`=? WHERE ID=?");
	$UserCampaignJSON = json_encode($userCampaignList);
	$stmt->bind_param("si", $UserCampaignJSON, $UserID);
	$stmt->execute();
	
	$QueryResult = $stmt->get_result();
	
	if($stmt->affected_rows == 1){
		return [true, ""];
	} else {
		return [false, "Something went wrong: stmt->affected_rows != 1"];
	}
}


/**
 *  Revokes a user's access to a campaign given a user ID and a campaign ID
 *  
 *  @param int $UserID Internal ID of the user you want to revoke access from
 *  @param int $CampaignID Internal ID of the campaign who's access is to be revoked
 *  
 *  @return mixed[] An array that contains a boolean and a string. The boolean will be true if the opperation completed successfully. The string will be empty unless the opperation fails, in which case it will contain a reason for failure.
 */
function revokeCampaignAccess($UserID, $CampaignID){ //TODO: properly test this function
	if(!hasCampaignAccess($CampaignID, $UserID)){
		return [false, "User already does not have access to this campaign"];
	}
	
	// Get array of campaigns user already has access to
	$userCampaignList = getCampaignsList($UserID);
	
	
	// Remove the id from the user's list
	$i = 0;
	foreach($userCampaignList as $Camp_id){
		if($Camp_id == $CampaignID){
			array_splice($userCampaignList, $i, 1);
		}
		$i++;
	}
	
	// Update the database with the new list
	include "mySQLVariables.php";
	$conn = new mysqli($sqlServer, $sqlUsername, $sqlPassword, $dbname);
	if (mysqli_connect_errno()) {
		return [false, "Failed to connect to database"];
	}

	$stmt = $conn->prepare("UPDATE `users` SET `AccessedCampaigns`=? WHERE ID=?");
	$UserCampaignJSON = json_encode($userCampaignList);
	$stmt->bind_param("si", $UserCampaignJSON, $UserID);
	$stmt->execute();
	
	$QueryResult = $stmt->get_result();
	
	if($stmt->affected_rows == 1){
		return [true, ""];
	} else {
		return [false, "Something went wrong: stmt->affected_rows != 1"];
	}
	
}

?>
