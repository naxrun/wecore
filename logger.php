<?php

// Website Content Recommendation Engine
// Developed by Naxrun - available for everyone
// This script is logging website traffic to a MySQL database

// Initial configuration
require_once('includes/g_functions.php');
header('Content-type: application/json');
$response = createResponse();

// Connect to database
$db_con = createDatabaseConnection();
$db_con->set_charset("utf8");

// Post data
$visitor_ip			= $_POST['v_ip'];
$visitor_referer	= $_POST['v_ref'];
$visitor_unique_id	= $_POST['v_uid'];
$page_category		= $_POST['p_cat'];
$page_url			= $_POST['p_url'];

// Initial sanetization
if(isset($visitor_ip) && !empty($visitor_ip) && !filter_var($visitor_ip, FILTER_VALIDATE_IP) === FALSE) {
	$todb_visitor_ip		= $visitor_ip;
} else {
	$todb_visitor_ip		= NULL;
}

if(isset($visitor_referer) && !empty($visitor_referer)) {
	$visitor_referer		= protocolRemoval($visitor_referer);
	$todb_visitor_referer	= $visitor_referer;
} else {
	$todb_visitor_refferer	= NULL;
}

if(isset($visitor_unique_id) && !empty($visitor_unique_id)) {
	$todb_visitor_unique_id	= $visitor_unique_id;
} else {
	$todb_visitor_unique_id	= NULL;
}

if(isset($page_category) && !empty($page_category) && !filter_var($page_category, FILTER_VALIDATE_INT) === FALSE) {
	$todb_page_category		= $page_category;
} else {
	$todb_page_category		= 1;
}

if(isset($page_url) && !empty($page_url)) {
	$page_url				= protocolRemoval($page_url);
	$todb_page_url			= $page_url;
} else {
	$todb_page_url			= NULL;
}

// Add data to the database
$prep = $db_con->prepare('INSERT INTO site_log (v_ip, v_ref, v_uid, p_cat, p_url) VALUES (?, ?, ?, ?, ?)');
$prep->bind_param('sssis', $todb_visitor_ip, $todb_visitor_refferer, $todb_visitor_unique_id, $todb_page_category, $todb_page_url);
$prep->execute();
$fromdb_affected_rows	= $prep->affected_rows;
$fromdb_site_log_id		= $db_con->insert_id;
$prep->close();

if($fromdb_affected_rows > 0) {
	setResponseCode($response, 200);
	pushResponseData($response, 'site_log_id', $fromdb_site_log_id);
	pushResponseData($response, 'site_log_v_ip', $todb_visitor_ip);
	pushResponseData($response, 'site_log_v_ref', $todb_visitor_refferer);
	pushResponseData($response, 'site_log_v_uid', $todb_visitor_unique_id);
	pushResponseData($response, 'site_log_p_cat', $todb_page_category);
	pushResponseData($response, 'site_log_p_url', $todb_page_url);
} else {
	setResponseCode($response, 400);
	pushResponseError($response, 'Something went wrong when the database was queried.');
}

// Close the database connection
$db_con->close();

// Output response
outputResponse($response);