<?php

	/**
		Creator 	: Jim Karlo Jamero
		Company		: Appsolutely Inc.
		File Name 	: config.php
		Description : This PHP file handles the global config of the API.	
	**/
	
	/********** Turn off all error reporting **********/
	// error_reporting(0);

	/********** Front-End **********/
	define('MERCHANT', 'Starbucks Coffee');


	/********** Front-End **********/
	define('MERCHANT_APPNAME', 'STARBUCKS');


	/********** MERCHAT-Website **********/
	define('MERCHANT_LINK', 'http://domain.ph/');
	define('MERCHANT_WEBLABEL', 'www.domain.ph');

	/********** Web Service Path **********/
	if (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1) || isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') {
		$protocol = 'https://';
	}
	else {
		$protocol = 'http://';
	}

	define('ENV', 'DEV');
	$dir = "";

	if (ENV == 'DEV') {
		$pathArray = explode("/", "$_SERVER[REQUEST_URI]");
		$dir = implode("/", array($pathArray[0], $pathArray[1], $pathArray[2]));
	} elseif (ENV == 'PROD') {		
		$pathArray = explode("/", "$_SERVER[REQUEST_URI]");
		$dir = implode("/", array($pathArray[0], $pathArray[1]));
	}

	define('PATH', $protocol."$_SERVER[HTTP_HOST]".$dir."/");
	define('DOMAIN_PATH', $protocol."$_SERVER[HTTP_HOST]");

	/********** Apple Push **********/
	define('CERTIFICATE', 'starbucks-dev.pem');
	define('ENVIRONMENT', 'development');
	
	/********** Date **********/
	define('DATE_TIME', date('Y-m-d H:i:s'));
	define('DATE_Y', date('Y'));
	define('DATE_M', date('m'));
	define('DATE_D', date('d'));
	define('TIME_ONLY', date('His'));
	define('TIME_HIS', date('H:i:s'));
	define('TIME_H', date('H'));
	define('TIME_I', date('i'));
	define('TIME_S', date('s'));

	/********** Error Codes **********/
	$error000 = json_encode(array(array("response"=>"Error", "errorCode"=>"000", "description"=>"Unable to complete process.")));
	$error400 = json_encode(array(array("response"=>"Error", "errorCode"=>"400", "description"=>"Bad Request.")));
	$error1329 = json_encode(array(array("response"=>"Error", "errorCode"=>"1329", "description"=>"No Data Found.")));

	/********** Logs **********/
	$file_name = substr(strtolower(basename($_SERVER['PHP_SELF'])),0,strlen(basename($_SERVER['PHP_SELF'])));

	if (file_exists('../logs/logs.class.php')) {
	    include_once('../logs/logs.class.php');
	} elseif (file_exists('../../logs/logs.class.php')) {
		include_once('../../logs/logs.class.php');
	} elseif (file_exists('php_api/api/logs/logs.class.php')) {
		include_once('php_api/api/logs/logs.class.php');
	} elseif (file_exists('../php_api/api/logs/logs.class.php')) {
		include_once('../php_api/api/logs/logs.class.php');
	} elseif (file_exists($baseURL.'logs/logs.class.php')) {
		include_once($baseURL.'logs/logs.class.php');
	} else {
		die("Log Error on file: ".$file_name);
		return;
	}

	$logs = NEW logs;

	/********** Invalid Access Checker **********/
	if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {
		$logs->write_logs('Invalid Access', $file_name, 'Illegal access attempt.');
		die('Access denied'); 
	}

	/********** MySQLi Config **********/
	$mysqli = new mysqli("localhost", "root", "28rskad08dwR", "work_starbucks");

	if ($mysqli->connect_errno) {
	    $message = "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
	    $logs->write_logs('MySQLi Connection', $file_name, $message);
	    die($message);
	    return;
	}

?>