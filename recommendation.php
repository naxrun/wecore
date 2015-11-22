<?php

// Website Content Recommendation Engine
// Developed by Naxrun - available for everyone
// This script returning the recommendation based on IP or UID

// Initial configuration
require_once('includes/g_functions.php');
header('Content-type: application/json');
$response = createResponse();

// Connect to database
$db_con = createDatabaseConnection();
$db_con->set_charset("utf8");

// Post data
$visitor_ip			= $_POST['v_ip'];
$visitor_unique_id	= $_POST['v_uid'];

// Initial sanetization
if(isset($visitor_ip) && !empty($visitor_ip) && !filter_var($visitor_ip, FILTER_VALIDATE_IP) === FALSE) {
	$todb_visitor_ip		= $visitor_ip;
} else {
	$todb_visitor_ip		= NULL;
}

if(isset($visitor_unique_id) && !empty($visitor_unique_id)) {
	$todb_visitor_unique_id	= $visitor_unique_id;
} else {
	$todb_visitor_unique_id	= NULL;
}

// Recommendation request
if($todb_visitor_ip != NULL || $todb_visitor_unique_id != NULL) {

	if($todb_visitor_ip != NULL) {	
		$prep = $db_con->prepare('SELECT p_cat, COUNT( * ) AS p_cat_count FROM site_log WHERE v_ip = ? GROUP BY p_cat ORDER BY p_cat_count DESC LIMIT 1');
		$prep->bind_param('s', $todb_visitor_ip);
		$prep->execute();
		$prep->store_result();
		$prep->bind_result($fromdb_visitor_ip_page_category_recommendation, $fromdb_visitor_ip_page_category_recommendation_count);
		$prep->fetch();
		$fromdb_affected_rows	= $prep->num_rows;
		$prep->close();
		
		if($fromdb_affected_rows > 0) {
			$system_allgood	= TRUE;
		} else {
			$system_allgood	= FALSE;
		}
	}
	
	if($todb_visitor_unique_id != NULL) {	
		$prep = $db_con->prepare('SELECT p_cat, COUNT( * ) AS p_cat_count FROM site_log WHERE v_uid = ? GROUP BY p_cat ORDER BY p_cat_count DESC LIMIT 1');
		$prep->bind_param('s', $todb_visitor_unique_id);
		$prep->execute();
		$prep->store_result();
		$prep->bind_result($fromdb_visitor_uid_page_category_recommendation, $fromdb_visitor_uid_page_category_recommendation_count);
		$prep->fetch();
		$fromdb_affected_rows	= $prep->num_rows;
		$prep->close();
		
		if($fromdb_affected_rows > 0) {
			$system_allgood	= TRUE;
		} else {
			$system_allgood	= FALSE;
		}
	}
	
	// Prepare output
	if($system_allgood == TRUE) {	
		setResponseCode($response, 200);
		
		if(isset($fromdb_visitor_ip_page_category_recommendation) && !empty($fromdb_visitor_ip_page_category_recommendation)) {
			pushResponseData($response, 'site_log_v_ip_p_cat_recommendation', $fromdb_visitor_ip_page_category_recommendation);
		}
		
		if(isset($fromdb_visitor_uid_page_category_recommendation) && !empty($fromdb_visitor_uid_page_category_recommendation)) {
			pushResponseData($response, 'site_log_v_uid_p_cat_recommendation', $fromdb_visitor_uid_page_category_recommendation);
		}
		
	} else {
		setResponseCode($response, 400);
		pushResponseError($response, 'No rows returned.');
	}
	
} else {
	setResponseCode($response, 400);
	pushResponseError($response, 'No input was given.');
}

// Close the database connection
$db_con->close();

// Output response
outputResponse($response);