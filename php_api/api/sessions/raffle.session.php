<?php

	/********** Base Name **********/
	$basename = substr(strtolower(basename($_SERVER['PHP_SELF'])),0,strlen(basename($_SERVER['PHP_SELF']))-4);

	/********** Session **********/
	if (!isset($_SESSION)) { session_start(); }

	/********** File Handler **********/
	$file_name = substr(strtolower(basename($_SERVER['PHP_SELF'])),0,strlen(basename($_SERVER['PHP_SELF'])));

	if (file_exists('../settings/config.php')) {
	    include_once('../settings/config.php');
	} elseif (file_exists('../../settings/config.php')) {
		include_once('../../settings/config.php');
	} elseif (file_exists('php/api/settings/config.php')) {
		include_once('php/api/settings/config.php');
	} elseif (file_exists($baseURL.'settings/config.php')) {
		include_once($baseURL.'settings/config.php');
	} else {
		die("Log Error on file: ".$file_name);
		return;
	}

	if (isset($_SESSION[MERCHANT_APPNAME.'_RAFFLE_ACCOUNT_ID'])) {
		$session_checker = session_checker($_SESSION[MERCHANT_APPNAME.'_RAFFLE_ACCOUNT_ID'], $_SESSION[MERCHANT_APPNAME.'_RAFFLE_ACCOUNT_SESSION']);

		if ($session_checker == 'Expired') {
			unset($_SESSION[MERCHANT_APPNAME.'_RAFFLE_ACCOUNT_ID']);
			unset($_SESSION[MERCHANT_APPNAME.'_RAFFLE_ACCOUNT_SESSION']);
			$_SESSION = array();
			session_unset();
			session_destroy();
			header('Location: login.php');
		}
	}
	
	if ($basename == 'login') {
		if (isset($_SESSION[MERCHANT_APPNAME.'_RAFFLE_ACCOUNT_ID'])) {
			header('Location: profile.php');
		} else {
			$accountID = "";
			$my_session_id = "";
		}
	} else {
		if (($basename != '404') && ($basename != '500')) {
			if (!$_SESSION[MERCHANT_APPNAME.'_RAFFLE_ACCOUNT_ID']) {
				unset($_SESSION[MERCHANT_APPNAME.'_RAFFLE_ACCOUNT_ID']);
				unset($_SESSION[MERCHANT_APPNAME.'_RAFFLE_ACCOUNT_SESSION']);
				$_SESSION = array();
				session_unset();
				session_destroy();
				header('Location: login.php');
				$accountID = "";
				$my_session_id = "";
			} else {
				$accountID = $_SESSION[MERCHANT_APPNAME.'_RAFFLE_ACCOUNT_ID'];
				$my_session_id = $_SESSION[MERCHANT_APPNAME.'_RAFFLE_ACCOUNT_SESSION'];
			}
		}
	}

	/********** Session Checker **********/
	function session_checker($accountID, $my_session_id) {
		require_once('php_api/api/cipher/cipher.class.php');

		$key = randomizer(32);
		$iv = randomizer(16);
		$error400 = json_encode(array(array("response"=>"Error", "errorCode"=>"400", "description"=>"Bad Request.")));

		$cipher = NEW cipher($key, $iv);

		if ((!isset($accountID) )|| (!$accountID)) {
			echo $error400;
			die();
			return;
		} else {
			$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$accountID = $cipher->encrypt($accountID);
		}

		if ((!isset($my_session_id) )|| (!$my_session_id)) {
			echo $error400;
			die();
			return;
		} else {
			$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = $cipher->encrypt($my_session_id);
		}

		$function = $cipher->encrypt("session_checker");
		$params = "oauth=".urlencode($key)."&token=".urlencode($iv)."&accountID=".urlencode($accountID)."&my_session_id=".urlencode($my_session_id);
		$url = PATH."raffle.php?function=".urlencode($function);
		$cURL = curl_init();
		curl_setopt($cURL, CURLOPT_URL, $url);
		curl_setopt($cURL, CURLOPT_POST, 1);
		curl_setopt($cURL, CURLOPT_POSTFIELDS, $params);
		curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
		$server_output = curl_exec ($cURL);
		curl_close ($cURL);
		// echo $server_output;
		$output = json_decode($server_output);
		return $output[0]->data->message;
		die();
	}

	/********** Randomizer **********/
	function randomizer($len, $norepeat = true) {
	    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
	    $max = strlen($chars) - 1;

	    if ($norepeat && $len > $max + 1) {
	        throw new Exception("Non repetitive random string can't be longer than charset");
	    }

	    $rand_chars = array();

	    while ($len) {
	        $picked = $chars[mt_rand(0, $max)];

	        if ($norepeat) {
	            if (!array_key_exists($picked, $rand_chars)) {
	                $rand_chars[$picked] = true;
	                $len--;
	            }
	        }
	        else {
	            $rand_chars[] = $picked;
	            $len--;
	        }
	    }

	    return implode('', $norepeat ? array_keys($rand_chars) : $rand_chars);   
	}

	/********** Invalid Access Checker **********/
	if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {
		// $logs->write_logs('Invalid Access', $file_name, 'Illegal access attempt.');
		die('Access denied'); 
	}

?>