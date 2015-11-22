<?php
	
// Website Content Recommendation Engine
// Developed by Naxrun - available for everyone
// This script contains some functions, that are used several times, to avoid recursive code

// Database
function &createDatabaseConnection() {
	include_once('includes/c_db.php');
	$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

	return($mysqli);
}

// Sanitizing
function protocolRemoval($url) {

	$url	= str_replace('https://', '', $url);
	$url	= str_replace('http://', '', $url);
	
	return $url;
}

// Responses
function &createResponse() {
	$response = array('code' => 501 /*501 -> Not Implemented*/, 'data' => array(), 'errors' => array());

	return($response);
}

function pushResponseError(&$response, $error) {
	array_push($response['errors'], $error);
}

function pushResponseData(&$response, $field_name, $field_value) {
	while(isset($response['data'][$field_name])) $field_name .= '_COPY';

	$response['data'][$field_name] = $field_value;
}

function setResponseCode(&$response, $code) {
	$response['code'] = $code;
}

function outputResponse(&$response) {
	http_response_code($response['code']);

	if($response['code'] != 204)
	{
		unset($response['code']);
		echo(json_encode($response));
	}

	exit();
}