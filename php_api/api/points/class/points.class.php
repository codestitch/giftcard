<?php

	/**
		Creator 	: Jim Karlo Jamero
		Company		: Appsolutely Inc.
		File Name 	: points.class.php
		Description : This PHP class handles all the points related major transactions involving the Point-of-Sales (P.O.S.).	
	**/

	class points {

		private static $tmp_mysqli;
		private static $tmp_logs;
		private static $tmp_file_name;
		private static $tmp_error000;
		private static $tmp_error400;
		private static $tmp_error1329;
		
		function __construct() {
			include_once('php/api/settings/config.php');
			// include_once('../../settings/config.php');
			self::$tmp_mysqli = $mysqli;
			self::$tmp_logs = $logs;
			self::$tmp_file_name = 'points.class.php';
			self::$tmp_error000 = $error000;
			self::$tmp_error400 = $error400;
			self::$tmp_error1329 = $error1329;
		}

		/********** Check User QR Code **********/
		public function check_user_qr($deviceID, $locID, $qrCode) {
			$mysqli = self::$tmp_mysqli;
			$logs = self::$tmp_logs;
			$file_name = self::$tmp_file_name;
			$error000 = self::$tmp_error000;
			$error400 = self::$tmp_error400;
			$error1329 = self::$tmp_error1329;
			$qry = "CALL check_user_qr(?, ?, ?)";
			$codeType = substr($qrCode, 0, 3);

			if (!isset($deviceID) || (!$deviceID)) {
				$logs->write_logs('Check User QR Code', $file_name, "Bad Request - Invalid/Empty [deviceID: $deviceID]");
				die($error400);
				return;
			}

			if (!isset($locID) || (!$locID)) {
				$logs->write_logs('Check User QR Code', $file_name, "Bad Request - Invalid/Empty [locID: $locID]");
				die($error400);
				return;
			}

			if (!isset($qrCode) || (!$qrCode)) {
				$logs->write_logs('Check User QR Code', $file_name, "Bad Request - Invalid/Empty [qrCode: $qrCode]");
				die($error400);
				return;
			} else {
				if (($codeType != 'APP') && ($codeType != 'CRD')) {
					$logs->write_logs('Check User QR Code', $file_name, "Bad Request - Invalid/Empty [qrCode: $qrCode]");
					die($error400);
					return;
				}
			}

			$deviceID = filter_var($deviceID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$locID = filter_var($locID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$qrCode = filter_var($qrCode, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$validate_device_id = $this->validate_device_id($deviceID);

			if ($validate_device_id == 'Multiple') {
				$logs->write_logs('Check User QR Code', $file_name, "Bad Request - [Message: Multiple assignment has been detected. Please contact System Administrator to resolve this problem.] [Param: $deviceID, $locID, $qrCode]");
				return json_encode(array(array("response"=>"Error", "errorCode"=>"1992", "description"=>"Multiple assignment has been detected. Please contact System Administrator to resolve this problem.")));
			} elseif ($validate_device_id == 'Inactive') {
				$logs->write_logs('Check User QR Code', $file_name, "Bad Request - [Message: This Tablet has not yet been activated. Please contact System Administrator to resolve this problem.] [Param: $deviceID, $locID, $qrCode]");
				return json_encode(array(array("response"=>"Error", "errorCode"=>"0318", "description"=>"This Tablet has not yet been activated. Please contact System Administrator to resolve this problem.")));
			} elseif ($validate_device_id == 'Undeployed') {
				$logs->write_logs('Check User QR Code', $file_name, "Bad Request - [Message: This Tablet has not yet been deployed. Please contact System Administrator to resolve this problem.] [Param: $deviceID, $locID, $qrCode]");
				return json_encode(array(array("response"=>"Error", "errorCode"=>"1989", "description"=>"This Tablet has not yet been deployed. Please contact System Administrator to resolve this problem.")));
			} elseif ($validate_device_id == 'False') {
				$logs->write_logs('Check User QR Code', $file_name, "Bad Request - [Message: Invalid Device ID] [Param: $deviceID, $locID, $qrCode]");
				return json_encode(array(array("response"=>"Error", "errorCode"=>"0326", "description"=>"Invalid Device ID.")));
			}

			if (($this->validate_location_id($locID)) == 'Invalid') {
				$logs->write_logs('Check User QR Code', $file_name, "Bad Request - [Message: Invalid Location ID] [Param: $deviceID, $locID, $qrCode]");
				return json_encode(array(array("response"=>"Error", "errorCode"=>"1990", "description"=>"Invalid Location ID.")));
			}

			if (!($sql = $mysqli->prepare($qry))) {
				$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: check_user_qr] [Query: $qry]");
			    die($error000);
				return;
			}

			if (!$sql->bind_param('sss', $deviceID, $locID, $qrCode)) {
				$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: check_user_qr] [Param: $deviceID, $locID, $qrCode]");
				die($error000);
				return;
			}

			if (!$sql->execute()) {
				$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: check_user_qr] [Query: $qry]");
			    die($error000);
				return;
			}

			$result = $sql->get_result();

			if ($result->num_rows > 0) {
				$row = $result->fetch_assoc();

				if ($row['result'] == "Success") {
					$logs->write_logs('Check User QR Code', $file_name, "[Param: $deviceID, $locID, $qrCode]\t Response : [".$row['result']."]");
					$sql->close();
					return $this->complete_data($row['memberID']);
				} else {
					$logs->write_logs('Check User QR Code', $file_name, "[Param: $deviceID, $locID, $qrCode]\t Response : [".$row['result']."]");
					$sql->close();

					if ($codeType == 'CRD') {
						if ($row['description'] == 'Account does not exist.') {
							return json_encode(array(array("response"=>"Unregistered", "description"=>"Card has not yet been registered.")));
						}
					}

					return json_encode(array(array("response"=>"Error", "description"=>$row['description'])));			
				}
			} else {
				$sql->close();
				return json_encode(array(array("response"=>"Error", "description"=>"Unable to complete process.")));
			}		
		}

		/********** Earn Raffle **********/
		public function earn_raffle($qrApp, $locID, $deviceID) {
			$mysqli = self::$tmp_mysqli;
			$logs = self::$tmp_logs;
			$file_name = self::$tmp_file_name;
			$error000 = self::$tmp_error000;
			$error400 = self::$tmp_error400;
			$error1329 = self::$tmp_error1329;
			$qry = "CALL earn_raffle(?, ?)";

			if (!isset($qrApp) || (!$qrApp)) {
				$logs->write_logs('Earn Raffle', $file_name, "Bad Request - Invalid/Empty [qrApp: $qrApp]");
				die($error400);
				return;
			}

			if (!isset($locID) || (!$locID)) {
				$logs->write_logs('Earn Raffle', $file_name, "Bad Request - Invalid/Empty [locID: $locID]");
				die($error400);
				return;
			}

			if (!isset($deviceID) || (!$deviceID)) {
				$logs->write_logs('Earn Raffle', $file_name, "Bad Request - Invalid/Empty [deviceID: $deviceID]");
				die($error400);
				return;
			}

			$deviceID = filter_var($deviceID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$validate_device_id = $this->validate_device_id($deviceID);

			if ($validate_device_id == 'Multiple') {
				$logs->write_logs('Earn Raffle', $file_name, "Bad Request - [Message: Multiple assignment has been detected. Please contact System Administrator to resolve this problem.] [Param: $deviceID]");
				return json_encode(array(array("response"=>"Error", "errorCode"=>"1992", "description"=>"Multiple assignment has been detected. Please contact System Administrator to resolve this problem.")));
			} elseif ($validate_device_id == 'Inactive') {
				$logs->write_logs('Earn Raffle', $file_name, "Bad Request - [Message: This Tablet has not yet been activated. Please contact System Administrator to resolve this problem.] [Param: $deviceID]");
				return json_encode(array(array("response"=>"Error", "errorCode"=>"0318", "description"=>"This Tablet has not yet been activated. Please contact System Administrator to resolve this problem.")));
			} elseif ($validate_device_id == 'Undeployed') {
				$logs->write_logs('Earn Raffle', $file_name, "Bad Request - [Message: This Tablet has not yet been deployed. Please contact System Administrator to resolve this problem.] [Param: $deviceID]");
				return json_encode(array(array("response"=>"Error", "errorCode"=>"1989", "description"=>"This Tablet has not yet been deployed. Please contact System Administrator to resolve this problem.")));
			} elseif ($validate_device_id == 'False') {
				$logs->write_logs('Earn Raffle', $file_name, "Bad Request - [Message: Invalid Device ID] [Param: $deviceID]");
				return json_encode(array(array("response"=>"Error", "errorCode"=>"0326", "description"=>"Invalid Device ID.")));
			}

			if (($this->validate_location_id($locID)) == 'Invalid') {
				$logs->write_logs('Offline Earn Points', $file_name, "Bad Request - [Message: Invalid Location ID] [Param: $qrApp, $locID, $deviceID]");
				return json_encode(array(array("response"=>"Error", "errorCode"=>"1990", "description"=>"Invalid Location ID.")));
			}

			if (($this->branch_code($locID)) == 'Invalid') {
				$logs->write_logs('Earn Raffle', $file_name, "Bad Request - [Message: Invalid Branch Code] [Param: $qrApp, $locID, $deviceID]");
				return json_encode(array(array("response"=>"Error", "errorCode"=>"1991", "description"=>"Invalid Branch Code.")));
			}

			if (!($sql = $mysqli->prepare($qry))) {
				$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: earn_raffle] [Query: $qry]");
			    die($error000);
				return;
			}

			if (!$sql->bind_param('ss', $qrApp, $locID)) {
				$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: earn_raffle] [Param: $qrApp, $locID]");
				die($error000);
				return;
			}

			$qrApp = filter_var($qrApp, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$locID = filter_var($locID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

			// Execute Prepared Statement
			if (!$sql->execute()) {
				$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: earn_raffle] [Query: $qry]");
			    die($error000);
				return;
			}

			$result = $sql->get_result();

			if ($result->num_rows > 0) {
				$row = $result->fetch_assoc();

				if ($row['result'] == "Existing") {
					$logs->write_logs('Earn Raffle', $file_name, "[Param: $qrApp, $locID]\t Response : [".$row['result']."]");
					$sql->close();
					return json_encode(array(array("response"=>"Error", "description"=>"Sorry, you may only scan your Sole Academy App once to join our raffle.")));
				} elseif ($row['result'] == "Success") {
					$logs->write_logs('Earn Raffle', $file_name, "[Param: $qrApp, $locID]\t Response : [".$row['result']."]");
					$sql->close();
					return json_encode(array(array("response"=>$row['result'])));
				} else {
					$logs->write_logs('Earn Raffle', $file_name, "[Param: $qrApp, $locID]\t Response : [".$row['result']."]");
					$sql->close();
					return json_encode(array(array("response"=>"Error", "description"=>"Unable to complete process.")));
				}
			}
		}

		/********** Offline Earn Points **********/
		public function offline_earn_points($deviceID, $locID, $qrCode, $transactionID, $transactionType, $earn, $sku, $raffleValue, $raffleEntry, $ticket, $version, $branchCode, $terminalID) {
			$mysqli = self::$tmp_mysqli;
			$logs = self::$tmp_logs;
			$file_name = self::$tmp_file_name;
			$error000 = self::$tmp_error000;
			$error400 = self::$tmp_error400;
			$error1329 = self::$tmp_error1329;
			$codeType = substr($qrCode, 0, 3);
			$raffle = "Invalid";
			$empty_sku = 'True';
			$bonus_snap = 0;
			$frequency = 0;
			$output = array();
			$qry = "CALL earn_points(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

			if (!isset($deviceID) || (!$deviceID)) {
				$logs->write_logs('Offline Earn Points', $file_name, "Bad Request - Invalid/Empty [deviceID: $deviceID]");
				die($error400);
				return;
			}

			if (!isset($locID) || (!$locID)) {
				$logs->write_logs('Offline Earn Points', $file_name, "Bad Request - Invalid/Empty [locID: $locID]");
				die($error400);
				return;
			}

			if (!isset($qrCode) || (!$qrCode)) {
				$logs->write_logs('Offline Earn Points', $file_name, "Bad Request - Invalid/Empty [qrCode: $qrCode]");
				die($error400);
				return;
			} else {
				$codeType = substr($qrCode, 0, 3);

				if (($codeType != 'APP') && ($codeType != 'CRD')) {
					$logs->write_logs('Earn Check-In', $file_name, "Bad Request - Invalid/Empty [qrCode: $qrCode]");
					die($error400);
					return;
				}
			}

			if (!isset($transactionID) || (!$transactionID)) {
				$logs->write_logs('Offline Earn Points', $file_name, "Bad Request - Invalid/Empty [transactionID: $transactionID]");
				die($error400);
				return;
			}

			if (!isset($transactionType) || (!$transactionType)) {
				$logs->write_logs('Offline Earn Points', $file_name, "Bad Request - Invalid/Empty [transactionType: $transactionType]");
				die($error400);
				return;
			}

			if (!isset($earn) || (!$earn)) {
				$logs->write_logs('Offline Earn Points', $file_name, "Bad Request - Invalid/Empty [earn: $earn]");
				die($error400);
				return;
			}

			if (!isset($version) || (!$version)) {
				$logs->write_logs('Offline version Points', $file_name, "Bad Request - Invalid/Empty [version: $version]");
				die($error400);
				return;
			}

			$earn_json = json_decode($earn);

			$points = $earn_json[0]->cash[0]->points;
			$amount = $earn_json[0]->cash[0]->amount;
			$baseValue = $earn_json[0]->cash[0]->baseValue;
			$points_nonCash = $earn_json[0]->noncash[0]->points;
			$amount_nonCash = $earn_json[0]->noncash[0]->amount;
			$baseValue_nonCash = $earn_json[0]->noncash[0]->baseValue;

			$total_points = (int)$points + (int)$points_nonCash;
			$total_amount = (int)$amount + (int)$amount_nonCash;

			if (($sku != NULL) && ($sku != '') && $sku != ' ') {
				$empty_sku = 'False';
			}

			$deviceID = filter_var($deviceID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$locID = filter_var($locID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$qrCode = filter_var($qrCode, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$transactionID = filter_var($transactionID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$transactionType = filter_var($transactionType, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$raffleValue = filter_var($raffleValue, FILTER_SANITIZE_NUMBER_INT);
			$raffleEntry = filter_var($raffleEntry, FILTER_SANITIZE_NUMBER_INT);
			$ticket = filter_var($ticket, FILTER_SANITIZE_NUMBER_INT);
			$version = filter_var($version, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$branchCode = filter_var($branchCode, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$terminalID = filter_var($terminalID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$validate_device_id = $this->validate_device_id($deviceID);

			if ($validate_device_id == 'Multiple') {
				$logs->write_logs('Offline Earn Points', $file_name, "Bad Request - [Message: Multiple assignment has been detected. Please contact System Administrator to resolve this problem.] [Param: $deviceID, $locID, $qrCode, $transactionID, $transactionType, $earn, $sku, $raffleValue, $raffleEntry, $ticket, $version, $branchCode, $terminalID]");
				return json_encode(array(array("response"=>"Error", "errorCode"=>"1992", "description"=>"Multiple assignment has been detected. Please contact System Administrator to resolve this problem.")));
			} elseif ($validate_device_id == 'Inactive') {
				$logs->write_logs('Offline Earn Points', $file_name, "Bad Request - [Message: This Tablet has not yet been activated. Please contact System Administrator to resolve this problem.] [Param: $deviceID, $locID, $qrCode, $transactionID, $transactionType, $earn, $sku, $raffleValue, $raffleEntry, $ticket, $version, $branchCode, $terminalID]");
				return json_encode(array(array("response"=>"Error", "errorCode"=>"0318", "description"=>"This Tablet has not yet been activated. Please contact System Administrator to resolve this problem.")));
			} elseif ($validate_device_id == 'Undeployed') {
				$logs->write_logs('Offline Earn Points', $file_name, "Bad Request - [Message: This Tablet has not yet been deployed. Please contact System Administrator to resolve this problem.] [Param: $deviceID, $locID, $qrCode, $transactionID, $transactionType, $earn, $sku, $raffleValue, $raffleEntry, $ticket, $version, $branchCode, $terminalID]");
				return json_encode(array(array("response"=>"Error", "errorCode"=>"1989", "description"=>"This Tablet has not yet been deployed. Please contact System Administrator to resolve this problem.")));
			} elseif ($validate_device_id == 'False') {
				$logs->write_logs('Offline Earn Points', $file_name, "Bad Request - [Message: Invalid Device ID] [Param: $deviceID, $locID, $qrCode, $transactionID, $transactionType, $earn, $sku, $raffleValue, $raffleEntry, $ticket, $version, $branchCode, $terminalID]");
				return json_encode(array(array("response"=>"Error", "errorCode"=>"0326", "description"=>"Invalid Device ID.")));
			}

			if (($this->validate_location_id($locID)) == 'Invalid') {
				$logs->write_logs('Offline Earn Points', $file_name, "Bad Request - [Message: Invalid Location ID] [Param: $deviceID, $locID, $qrCode, $transactionID, $transactionType, $earn, $sku, $raffleValue, $raffleEntry, $ticket, $version, $branchCode, $terminalID]");
				return json_encode(array(array("response"=>"Error", "errorCode"=>"1990", "description"=>"Invalid Location ID.")));
			}

			$memberID = $this->get_member_id($qrCode);

			if ($memberID == 'Invalid') {
				$logs->write_logs('Offline Earn Points', $file_name, "Bad Request - [Message: Invalid Member ID] [$deviceID, $locID, $qrCode, $transactionID, $transactionType, $earn, $sku, $raffleValue, $raffleEntry, $ticket, $version, $branchCode, $terminalID]");
				return json_encode(array(array("response"=>"Error", "errorCode"=>"0707", "description"=>"Invalid Member ID.")));
			}

			$temp_branchCode = $this->branch_code($locID);

			if ((!$temp_branchCode) || ($temp_branchCode == 'Invalid') || ($temp_branchCode != $branchCode)) {
				$logs->write_logs('Offline Earn Points', $file_name, "Bad Request - [Message: Invalid Branch Code] [Param: $locID] [Device ID: $deviceID]");
				return json_encode(array(array("response"=>"Error", "errorCode"=>"1991", "description"=>"Invalid Branch Code. $temp_branchCode | $branchCode")));
			}

			if (!$terminalID){
				$logs->write_logs('Offline Earn Points', $file_name, "Bad Request - [Message: Invalid Terminal ID] []");
				return json_encode(array(array("response"=>"Error", "errorCode"=>"2016", "description"=>"Invalid Terminal ID.")));
			}

			if (!$transactionID){
				$logs->write_logs('Offline Earn Points', $file_name, "Bad Request - [Message: Invalid Transaction ID] [$deviceID, $locID, $qrCode, $transactionID, $transactionType, $earn, $sku, $raffleValue, $raffleEntry, $ticket, $version, $branchCode, $terminalID]");
				return json_encode(array(array("response"=>"Error", "errorCode"=>"1995", "description"=>"Invalid Transaction ID.")));
			}

			if (!$transactionType){
				$logs->write_logs('Offline Earn Points', $file_name, "Bad Request - [Message: Invalid Transaction Type] [$deviceID, $locID, $qrCode, $transactionID, $transactionType, $earn, $sku, $raffleValue, $raffleEntry, $ticket, $version, $branchCode, $terminalID]");
				return json_encode(array(array("response"=>"Error", "errorCode"=>"1996", "description"=>"Invalid Transaction Type.")));
			}

			$transactionStatus = $this->check_transaction_id($transactionID, $locID, $branchCode);

			if ($transactionStatus == 'Invalid'){
				$logs->write_logs('Offline Earn Points', $file_name, "Bad Request - [Message: This receipt has already been used.] [$deviceID, $locID, $qrCode, $transactionID, $transactionType, $earn, $sku, $raffleValue, $raffleEntry, $ticket, $version, $branchCode, $terminalID]");
				return json_encode(array(array("response"=>"Error", "errorCode"=>"1997", "description"=>"This receipt has already been used.")));
			}

			if ($transactionStatus == 'Valid') {
				$qry_insert_transaction = "CALL insert_transaction(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
				$qry_transaction_entry = "CALL get_last_insert('transactiontable')";
				$transID = "";

				// Prepared statement, stage 1: prepare
				if (!($sql = $mysqli->prepare($qry_transaction_entry))) {
					$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: get_last_insert] [Query: $qry_transaction_entry]");
				    die($error000);
					return;
				}			

				// Execute Prepared Statement
				if (!$sql->execute()) {
					$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: get_last_insert] [Query: $qry_transaction_entry]");
				    die($error000);
					return;
				}

				// Get SQL statement result
				$result = $sql->get_result();
				$row = $result->fetch_assoc();

				if ($result->num_rows > 0) {
					$logs->write_logs('Get Last Insert', $file_name, "Device ID: [$deviceID]\t Status: [Success]\t Response : [".substr($row['entry'], 0, 16)."]");
					$transID = substr($row['entry'], 0, 16);
				} else {
					$logs->write_logs('Get Last Insert', $file_name, "Device ID: [$deviceID]\t Status: [Success]\t Response : [Error]");
				}

				$sql->close();

				if ($empty_sku == 'False') {
					$sku_temp = json_decode($sku);

					for ($i=0; $i<count($sku_temp); $i++) {
						// Prepared statement, stage 1: prepare
						if (!($sql_insert_transaction = $mysqli->prepare($qry_insert_transaction))) {
							$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: insert_transaction] [Query: $qry_insert_transaction]");
						    die($error000);
							return;
						}

						if (!$sql_insert_transaction->bind_param('sssssisisss', $transID, $transactionID, $memberID, $locID, $branchCode, $total_amount, $sku_temp_val, $sku_temp_qty, $dateStamp, $timeStamp, $terminalID)) {
							$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: insert_transaction] [Param: $transID, $transactionID, $memberID, $locID, $branchCode, $total_amount, $sku_temp_val, $sku_temp_qty, $dateStamp, $timeStamp, $terminalID]");
						    die($error000);
							return;
						}

						$transID = filter_var($transID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
						$sku_temp_val = filter_var($sku_temp[$i]->sku, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
						$sku_temp_qty = filter_var((int)$sku_temp[$i]->qty, FILTER_SANITIZE_NUMBER_INT);
						$dateStamp = filter_var(DATE_TIME, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
						$timeStamp = filter_var(TIME_HIS, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

						// Execute Prepared Statement
						if (!$sql_insert_transaction->execute()) {
							$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: insert_transaction] [Query: $qry_insert_transaction]");
						    die($error000);
							return;
						}

						$sql_insert_transaction->close();
					}
				} else {
					// Prepared statement, stage 1: prepare
					if (!($sql_insert_transaction = $mysqli->prepare($qry_insert_transaction))) {
						$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: insert_transaction] [Query: $qry_insert_transaction]");
					    die($error000);
						return;
					}

					if (!$sql_insert_transaction->bind_param('sssssisisss', $transID, $transactionID, $memberID, $locID, $branchCode, $total_amount, $sku_temp_val, $sku_temp_qty, $dateStamp, $timeStamp, $terminalID)) {
						$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: insert_transaction] [Param: $transID, $transactionID, $memberID, $locID, $branchCode, $total_amount, $sku_temp_val, $sku_temp_qty, $dateStamp, $timeStamp, $terminalID]");
					    die($error000);
						return;
					}

					$transID = filter_var($transID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
					$sku_temp_val = filter_var("", FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
					$sku_temp_qty = filter_var(0, FILTER_SANITIZE_NUMBER_INT);
					$dateStamp = filter_var(DATE_TIME, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
					$timeStamp = filter_var(TIME_HIS, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

					// Execute Prepared Statement
					if (!$sql_insert_transaction->execute()) {
						$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: insert_transaction] [Query: $qry_insert_transaction]");
					    die($error000);
						return;
					}

					$sql_insert_transaction->close();
				}
			}

			if ($points > 0) {
				// Prepared statement, stage 1: prepare
				if (!($sql = $mysqli->prepare($qry))) {
					$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: earn_points] [Query: $qry]");
				    die($error000);
					return;
				}

				$earnType = "cash";

				if (!$sql->bind_param('ssissssisis', $memberID, $locID, $points, $transactionID, $deviceID, $transactionType, $earnType, $amount, $skuID, $baseValue, $version)) {
					$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: earn_points] [Param: $memberID, $locID, $points, $transactionID, $deviceID, $transactionType, $earnType, $amount, $skuID, $baseValue, $version]");
				    die($error000);
					return;
				}

				$version = filter_var($version, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
				$skuID = filter_var(NULL, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

				// Execute Prepared Statement
				if (!$sql->execute()) {
					$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: earn_points] [Query: $qry]");
				    die($error000);
					return;
				}

				$sql->close();
			}

			if ($points_nonCash > 0) {
				// Prepared statement, stage 1: prepare
				if (!($sql = $mysqli->prepare($qry))) {
					$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: earn_points] [Query: $memberID, $locID, $points, $transactionID, $deviceID, $transactionType, $earnType, $amount, $skuID, $baseValue, $version]");
				    die($error000);
					return;
				}

				$earnType = "noncash";

				if (!$sql->bind_param('ssissssisis', $memberID, $locID, $points_nonCash, $transactionID, $deviceID, $transactionType, $earnType, $amount_nonCash, $skuID, $baseValue_nonCash, $version)) {
					$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: earn_points] [Param: $memberID, $locID, $points_nonCash, $transactionID, $deviceID, $transactionType, $earnType, $amount_nonCash, $skuID, $baseValue_nonCash, $version]");
				    die($error000);
					return;
				}

				$version = filter_var($version, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
				$skuID = filter_var(NULL, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

				// Execute Prepared Statement
				if (!$sql->execute()) {
					$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: earn_points] [Query: $memberID, $locID, $points, $transactionID, $deviceID, $transactionType, $earnType, $amount, $skuID, $baseValue, $version]");
				    die($error000);
					return;
				}

				$sql->close();
			}

			if ($empty_sku == 'False') {
				$sku_result = $this->earn_sku($memberID, $locID, $transactionID, $deviceID, $transactionType, $amount, $sku, $baseValue, $version);
				$bonus_snap = $sku_result['bonus'];
				$frequency = $sku_result['frequency'];
			}

			if ($ticket > 0) {
				$raffle = $this->generate_raffle_entry($memberID, $locID, $transactionID, $amount, $raffleValue, $raffleEntry, $ticket);
			}

			// return $this->complete_data($memberID);
			return json_encode(array(array("response"=>"Success", "data"=>array("acquiredPt"=>((int)$total_points + (int)$bonus_snap), "raffle"=>$raffle, "frequency"=>$frequency))));
		}

		/********** Earn SKU **********/
		public function earn_sku($memberID, $locID, $transactionID, $deviceID, $transactionType, $amount, $sku, $baseValue, $version) {
			$mysqli = self::$tmp_mysqli;
			$logs = self::$tmp_logs;
			$file_name = self::$tmp_file_name;
			$error000 = self::$tmp_error000;
			$error400 = self::$tmp_error400;
			$error1329 = self::$tmp_error1329;
			$bonus_snap = 0;
			$frequency = 0;
			$frequency_count = 0;
			$sku = json_decode($sku);
			$output = array();
			$qry_bonus_snap = "CALL earn_points(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
			$qry_frequency = "CALL earn_frequency(?, ?, ?, ?, ?, ?, ?)";
			$qry_frequency_entry = "CALL get_last_insert('earnskufreq')";

			for ($i=0; $i<count($sku); $i++) {
				if ($amount >= $baseValue) {
					if ($this->check_sku_code_for_bonus($sku[$i]->sku) == 'Valid'){
			        	$skuID = $this->sku_code_to_id($sku[$i]->sku);
			        	if ($skuID != 'Invalid') {
			        		// Prepared statement, stage 1: prepare
							if (!($sql = $mysqli->prepare($qry_bonus_snap))) {
								$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: earn_points] [Query: $qry_bonus_snap]");
							    die($error000);
								return;
							}

							// $memberID, $locID, $points_nonCash, $transactionID, $deviceID, $transactionType, $earnType, $amount_nonCash, $skuID, $baseValue_nonCash, $version
			        		if (!$sql->bind_param('ssissssisis', $memberID, $locID, $points, $transactionID, $deviceID, $transactionType, $earnType, $temp_amount, $skuID, $baseValue, $version)) {
								$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: earn_points] [Param: $memberID, $locID, $points, $transactionID, $deviceID, $transactionType, NULL, $skuID, $baseValue, $version]");
							    die($error000);
								return;
							}

							$memberID = filter_var($memberID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
							$locID = filter_var($locID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
							$points = filter_var($sku[$i]->pts, FILTER_SANITIZE_NUMBER_INT);
							$transactionID = filter_var($transactionID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
							$transactionType = filter_var($transactionType, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
							$earnType = filter_var('sku', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
							$deviceID = filter_var($deviceID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
							$baseValue = filter_var($baseValue, FILTER_SANITIZE_NUMBER_INT);
							$version = filter_var($version, FILTER_SANITIZE_NUMBER_INT);	
							$temp_amount = filter_var(NULL, FILTER_SANITIZE_NUMBER_INT);					

							// Execute Prepared Statement
							if (!$sql->execute()) {
								$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: earn_points] [Query: $qry_bonus_snap]");
							    die($error000);
								return;
							}

							$sql->close();
			        		$bonus_snap += (int)$points;
			        	}					
					}
				}				

				if ($this->check_sku_code_for_frequency($sku[$i]->sku) == 'Valid') {
					$skuArray = array();
					$skuString = $this->sku_code_to_array($sku[$i]->sku);
					$skuArray = explode(',', $skuString);
		        	if ($skuString != 'Invalid') {
		        		$frequency_count += 1;
						$frequency += ((int)$sku[$i]->qty);

						// Prepared statement, stage 1: prepare
						if (!($sql = $mysqli->prepare($qry_frequency_entry))) {
							$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: get_last_insert] [Query: $qry_frequency_entry]");
						    die($error000);
							return;
						}			

						// Execute Prepared Statement
						if (!$sql->execute()) {
							$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: get_last_insert] [Query: $qry_frequency_entry]");
						    die($error000);
							return;
						}

						$earnskufreqID = "Invalid";

						// Get SQL statement result
						$result = $sql->get_result();
						$row = $result->fetch_assoc();

						if ($result->num_rows > 0) {
							$logs->write_logs('Get Last Insert', $file_name, "Device ID: [$deviceID]\t Status: [Success]\t Response : [".substr($row['entry'], 0, 16)."]");
							$earnskufreqID = substr($row['entry'], 0, 16);
						} else {
							$logs->write_logs('Get Last Insert', $file_name, "Device ID: [$deviceID]\t Status: [Success]\t Response : [Error]");
						}

						$sql->close();

						for ($x = 0; $x < ((int)$sku[$i]->qty); $x++) {
							// Prepared statement, stage 1: prepare
							if (!($sql = $mysqli->prepare($qry_frequency))) {
								$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: earn_frequency] [Query: $qry_frequency]");
							    die($error000);
								return;
							}

			        		if (!$sql->bind_param('sssssss', $earnskufreqID, $memberID, $skuID, $loyaltyID, $locID, $transactionID, $earnDate)) {
								$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: earn_frequency] [Param: $earnskufreqID, $memberID, $skuID, ".$skuArray[1].", $locID, $transactionID, ".DATE_TIME."]");
							    die($error000);
								return;
							}

							$earnskufreqID = filter_var($earnskufreqID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
							$memberID = filter_var($memberID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
							$skuID = filter_var($skuArray[0], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
							$loyaltyID = filter_var($skuArray[1], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
							$locID = filter_var($locID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
							$transactionID = filter_var($transactionID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
							$earnDate = filter_var(DATE_TIME, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);					

							// Execute Prepared Statement
							if (!$sql->execute()) {
								$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: earn_frequency] [Query: $qry_frequency]");
							    die($error000);
								return;
							}

							$sql->close();
						}
					}						
				}
			}

			return array("bonus"=>$bonus_snap, "frequency"=>$frequency);
		}

		/********** Validate Device ID **********/
		public function validate_device_id($terminal) {
			$mysqli = self::$tmp_mysqli;
			$logs = self::$tmp_logs;
			$file_name = self::$tmp_file_name;
			$error000 = self::$tmp_error000;
			$error400 = self::$tmp_error400;
			$error1329 = self::$tmp_error1329;
			$return = "False";
			$qry = "CALL validate_device_id(?)";

			// Prepared statement, stage 1: prepare
			if (!($sql = $mysqli->prepare($qry))) {
				$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: validate_device_id] [Query: $qry]");
			    die($error000);
				return;
			}

			if (!$sql->bind_param('s', $terminal)) {
				$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: validate_device_id] [Param: $terminal]");
			    die($error000);
				return;
			}

			$terminal = filter_var($terminal, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

			// Execute Prepared Statement
			if (!$sql->execute()) {
				$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: validate_device_id] [Query: $qry]");
			    die($error000);
				return;
			}

			// Get SQL statement result
			$result = $sql->get_result();

			if ($result->num_rows > 1) {
				$logs->write_logs('Validate Device ID', $file_name, "Device ID: [$terminal]\t Status: [Success]\t Response : [Multiple]");
				$return = "Multiple";
			} elseif ($result->num_rows == 1) {
				$row = $result->fetch_assoc();

				if ($row['status'] == 'active') {
					if ($row['deploy'] == 'true') {
						$logs->write_logs('Validate Device ID', $file_name, "Device ID: [$terminal]\t Status: [Success]\t Response : [True]");
						$return = "True";
					} else {
						$logs->write_logs('Validate Device ID', $file_name, "Device ID: [$terminal]\t Status: [Success]\t Response : [Undeployed]");
						$return = "Undeployed";
					}
				} else {
					$logs->write_logs('Validate Device ID', $file_name, "Device ID: [$terminal]\t Status: [Success]\t Response : [Inactive]");
					$return = "Inactive";
				}
			} else {
				$logs->write_logs('Validate Device ID', $file_name, "Device ID: [$terminal]\t Status: [Success]\t Response : [False]");
				$return = "False";
			}
			
			$sql->close();
			return $return;
		}

		/********** Check Location ID **********/
		public function validate_location_id($locID) {
			$mysqli = self::$tmp_mysqli;
			$logs = self::$tmp_logs;
			$file_name = self::$tmp_file_name;
			$error000 = self::$tmp_error000;
			$error400 = self::$tmp_error400;
			$error1329 = self::$tmp_error1329;
			$return = "Invalid";
			$qry = "CALL validate_location_id(?)";

			// Prepared statement, stage 1: prepare
			if (!($sql = $mysqli->prepare($qry))) {
				$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: validate_location_id] [Query: $qry]");
			    die($error000);
				return;
			}

			if (!$sql->bind_param('s', $locID)) {
				$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: validate_location_id] [Param: $locID]");
			    die($error000);
				return;
			}

			$locID = filter_var($locID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

			// Execute Prepared Statement
			if (!$sql->execute()) {
				$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: validate_location_id] [Query: $qry]");
			    die($error000);
				return;
			}

			// Get SQL statement result
			$result = $sql->get_result();

			if ($result->num_rows > 0) {
				$logs->write_logs('Check Location ID', $file_name, "Location ID: [$locID]\t Status: [Success]\t Response : [Valid]");
				$return = "Valid";
			} else {
				$logs->write_logs('Check Location ID', $file_name, "Location ID: [$locID]\t Status: [Success]\t Response : Invalid Location ID.");
			}

			$sql->close();
			return $return;
		}

		/********** Get Branch Code **********/
		public function branch_code($locID){
			$mysqli = self::$tmp_mysqli;
			$logs = self::$tmp_logs;
			$file_name = self::$tmp_file_name;
			$error000 = self::$tmp_error000;
			$error400 = self::$tmp_error400;
			$error1329 = self::$tmp_error1329;
			$return = "Invalid";
			$qry = "CALL branch_code(?)";

			// Prepared statement, stage 1: prepare
			if (!($sql = $mysqli->prepare($qry))) {
				$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: branch_code] [Query: $qry]");
			    die($error000);
				return;
			}

			if (!$sql->bind_param('s', $locID)) {
				$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: branch_code] [Param: $locID]");
			    die($error000);
				return;
			}

			$locID = filter_var($locID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

			// Execute Prepared Statement
			if (!$sql->execute()) {
				$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: branch_code] [Query: $qry]");
			    die($error000);
				return;
			}

			// Get SQL statement result
			$result = $sql->get_result();

			if ($result->num_rows > 0) {
				$row = $result->fetch_assoc();
				$return = $row['branchCode'];
				$logs->write_logs('Branch Code', $file_name, "Location ID: [$locID]\t Status: [Success]\t Response : [branchCode: $return]");
			} else {
				$logs->write_logs('Branch Code', $file_name, "Location ID: [$locID]\t Status: [Success]\t Response : Invalid Location ID.");
			}

			$sql->close();
			return $return;
		}

		/********** Get Member ID **********/
		public function get_member_id($qrCode) {
			$mysqli = self::$tmp_mysqli;
			$logs = self::$tmp_logs;
			$file_name = self::$tmp_file_name;
			$error000 = self::$tmp_error000;
			$error400 = self::$tmp_error400;
			$error1329 = self::$tmp_error1329;
			$codeType = substr($qrCode, 0, 3);
			$return = "Invalid";
			$qry = "CALL get_member_id(?, ?)";

			if ($codeType != 'CRD' && $codeType != 'APP') {
				$logs->write_logs('Get Member ID', $file_name, "QR-Code: [$qrCode]\t Code Type: [$codeType]");
				die($error000);
				return $return;
			}

			// Prepared statement, stage 1: prepare
			if (!($sql = $mysqli->prepare($qry))) {
				$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: get_member_id] [Query: $qry]");
			    die($error000);
				return;
			}

			if (!$sql->bind_param('ss', $qrCode, $codeType)) {
				$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: get_member_id] [Param: $qrCode]");
			    die($error000);
				return;
			}

			$qrCode = filter_var($qrCode, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

			// Execute Prepared Statement
			if (!$sql->execute()) {
				$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: get_member_id] [Query: $qry]");
			    die($error000);
				return;
			}

			// Get SQL statement result
			$result = $sql->get_result();

			if ($result->num_rows > 0) {
				$row = $result->fetch_assoc();
				if ($row['memberID'] == 'Invalid') {
					$logs->write_logs('Get Member ID', $file_name, "QR-Code: [$qrCode]\t Status: [Success]\t Response : [Invalid]\t Message: [No user found.]");
					$return = "Invalid";
				} else {
					$logs->write_logs('Get Member ID', $file_name, "QR-Code: [$qrCode]\t Status: [Success]\t Response : ".$row['memberID']);
					$return = $row['memberID'];
				}
			} else {
				$logs->write_logs('Get Member ID', $file_name, "QR-Code: [$qrCode]\t Status: [Success]\t Response : [Invalid]\t Message: [No user found.]");
				$return = "Invalid";
			}

			$sql->close();
			return $return;
		}

		/********** Check Transaction ID **********/
		public function check_transaction_id($transactionID, $locID, $branchCode) {
			$mysqli = self::$tmp_mysqli;
			$logs = self::$tmp_logs;
			$file_name = self::$tmp_file_name;
			$error000 = self::$tmp_error000;
			$error400 = self::$tmp_error400;
			$error1329 = self::$tmp_error1329;
			$return = "Invalid";
			$qry = "CALL check_transaction_id(?, ?, ?)";

			// Prepared statement, stage 1: prepare
			if (!($sql = $mysqli->prepare($qry))) {
				$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: check_transaction_id] [Query: $qry]");
			    die($error000);
				return;
			}

			if (!$sql->bind_param('sss', $transactionID, $locID, $branchCode)) {
				$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: check_transaction_id] [Param: $locID]");
			    die($error000);
				return;
			}

			$transactionID = filter_var($transactionID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$locID = filter_var($locID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$branchCode = filter_var($branchCode, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

			// Execute Prepared Statement
			if (!$sql->execute()) {
				$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: check_transaction_id] [Query: $qry]");
			    die($error000);
				return;
			}

			// Get SQL statement result
			$result = $sql->get_result();

			if ($result->num_rows > 0) {
				$row = $result->fetch_assoc();
				if ($row['status'] == 'false') {
					$logs->write_logs('Check Transaction ID', $file_name, "Transaction ID: [$transactionID]\t Branch Code: [$branchCode]\t Status: [Success]\t Response : [Existing]");
					$return = "Existing";
				} elseif ($row['status'] == 'true') {
					$logs->write_logs('Check Transaction ID', $file_name, "Transaction ID: [$transactionID]\t Branch Code: [$branchCode]\t Status: [Success]\t Response : [Invalid]");
					$return = "Invalid";
				} else {
					$logs->write_logs('Check Transaction ID', $file_name, "Transaction ID: [$transactionID]\t Branch Code: [$branchCode]\t Status: [Success]\t Response : [Invalid]");
					$return = "Invalid";
				}
			} else {
				$logs->write_logs('Check Transaction ID', $file_name, "Transaction ID: [$transactionID]\t Branch Code: [$branchCode]\t Status: [Success]\t Response : [Valid]");
				$return = "Valid";
			}

			$sql->close();
			return $return;
		}

		/********** Generate Raffle Entry **********/
		public function generate_raffle_entry($memberID, $locID, $transactionID, $amount, $raffleValue, $raffleEntry, $ticket) {
			$mysqli = self::$tmp_mysqli;
			$logs = self::$tmp_logs;
			$file_name = self::$tmp_file_name;
			$error000 = self::$tmp_error000;
			$error400 = self::$tmp_error400;
			$error1329 = self::$tmp_error1329;
			$return = "Invalid";
			$qry = "CALL generate_raffle_entry(?, ?, ?, ?)";

			// Prepared statement, stage 1: prepare
			if (!($sql = $mysqli->prepare($qry))) {
				$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: generate_raffle_entry] [Query: $qry]");
			    die($error000);
				return;
			}

			if (!$sql->bind_param('sssi', $memberID, $locID, $transactionID, $ticket)) {
				$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: generate_raffle_entry] [Param: $memberID, $locID, $transactionID, $ticket]");
			    die($error000);
				return;
			}

			$memberID = filter_var($memberID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$locID = filter_var($locID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$transactionID = filter_var($transactionID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$amount = filter_var($amount, FILTER_SANITIZE_NUMBER_INT);
			$raffleValue = filter_var($raffleValue, FILTER_SANITIZE_NUMBER_INT);
			$raffleEntry = filter_var($raffleEntry, FILTER_SANITIZE_NUMBER_INT);
			$ticket = filter_var($ticket, FILTER_SANITIZE_NUMBER_INT);

			$temp_ticket = (floor($amount / $raffleValue) * $raffleEntry);

			if ($temp_ticket != $ticket) {
				$ticket = $temp_ticket;
			}

			if ($ticket <= 0) {
				$return = "Invalid";
				return $return;
				die();
			}

			// Execute Prepared Statement
			if (!$sql->execute()) {
				$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: generate_raffle_entry] [Query: $qry]");
			    die($error000);
				return;
			}

			// Get SQL statement result
			$result = $sql->get_result();

			if ($result->num_rows > 0) {
				$row = $result->fetch_assoc();
				if ($row['result'] == 'Success') {
					$logs->write_logs('Generate Raffle Entry', $file_name, "Member ID: [$memberID]\t Location ID: [$locID]\t Transaction ID: [$transactionID]\t Entry: [$raffleEntry]\t Raffle Value: [$raffleValue]\t Status: [Success]\t Response : [Success]");
					$return = "Success";
				} else {
					$logs->write_logs('Generate Raffle Entry', $file_name, "Member ID: [$memberID]\t Location ID: [$locID]\t Transaction ID: [$transactionID]\t Entry: [$raffleEntry]\t Raffle Value: [$raffleValue]\t Status: [Success]\t Response : [Invalid]\t Message: [Unable to generate raffle entry.]");
					$return = "Invalid";
				}
			} else {
				$logs->write_logs('Generate Raffle Entry', $file_name, "Member ID: [$memberID]\t Location ID: [$locID]\t Transaction ID: [$transactionID]\t Entry: [$raffleEntry]\t Raffle Value: [$raffleValue]\t Response : [Invalid]\t Message: [Unable to generate raffle entry.]");
				$return = "Invalid";
			}

			$sql->close();
			return $return;
		}

		/********** Check SKU Code for BONUS **********/
		public function check_sku_code_for_bonus($skuCode) {
			$mysqli = self::$tmp_mysqli;
			$logs = self::$tmp_logs;
			$file_name = self::$tmp_file_name;
			$error000 = self::$tmp_error000;
			$error400 = self::$tmp_error400;
			$error1329 = self::$tmp_error1329;
			$return = "Invalid";
			$qry = "CALL check_sku_code_for_bonus(?)";

			// Prepared statement, stage 1: prepare
			if (!($sql = $mysqli->prepare($qry))) {
				$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: check_sku_code_for_bonus] [Query: $qry]");
			    die($error000);
				return;
			}

			if (!$sql->bind_param('s', $skuCode)) {
				$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: check_sku_code_for_bonus] [Param: $skuCode]");
			    die($error000);
				return;
			}

			$skuCode = filter_var($skuCode, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

			// Execute Prepared Statement
			if (!$sql->execute()) {
				$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: check_sku_code_for_bonus] [Query: $qry]");
			    die($error000);
				return;
			}

			// Get SQL statement result
			$result = $sql->get_result();

			if ($result->num_rows > 0) {
				$logs->write_logs('Check SKU Code for BONUS', $file_name, "SKU Code: [$skuCode]\t Status: [Success]\t Response : [Valid]");
				$return = "Valid";
			} else {
				$logs->write_logs('Check SKU Code for BONUS', $file_name, "SKU Code: [$skuCode]\t Status: [Success]\t Response : [Invalid]\t Message: [No data found.]");
				$return = "Invalid";
			}

			$sql->close();
			return $return;
		}

		/********** Check SKU Code for FREQUENCY **********/
		public function check_sku_code_for_frequency($skuCode) {
			$mysqli = self::$tmp_mysqli;
			$logs = self::$tmp_logs;
			$file_name = self::$tmp_file_name;
			$error000 = self::$tmp_error000;
			$error400 = self::$tmp_error400;
			$error1329 = self::$tmp_error1329;
			$return = "Invalid";
			$qry = "CALL check_sku_code_for_frequency(?)";

			// Prepared statement, stage 1: prepare
			if (!($sql = $mysqli->prepare($qry))) {
				$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: check_sku_code_for_frequency] [Query: $qry]");
			    die($error000);
				return;
			}

			if (!$sql->bind_param('s', $skuCode)) {
				$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: check_sku_code_for_frequency] [Param: $skuCode]");
			    die($error000);
				return;
			}

			$skuCode = filter_var($skuCode, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

			// Execute Prepared Statement
			if (!$sql->execute()) {
				$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: check_sku_code_for_frequency] [Query: $qry]");
			    die($error000);
				return;
			}

			// Get SQL statement result
			$result = $sql->get_result();

			if ($result->num_rows > 0) {
				$logs->write_logs('Check SKU Code for FREQUENCY', $file_name, "SKU Code: [$skuCode]\t Status: [Success]\t Response : [Valid]");
				$return = "Valid";
			} else {
				$logs->write_logs('Check SKU Code for FREQUENCY', $file_name, "SKU Code: [$skuCode]\t Status: [Success]\t Response : [Invalid]\t Message: [No data found.]");
				$return = "Invalid";
			}

			$sql->close();
			return $return;
		}

		/********** SKU Code to SKU ID **********/
		public function sku_code_to_id($skuCode) {
			$mysqli = self::$tmp_mysqli;
			$logs = self::$tmp_logs;
			$file_name = self::$tmp_file_name;
			$error000 = self::$tmp_error000;
			$error400 = self::$tmp_error400;
			$error1329 = self::$tmp_error1329;
			$return = "Invalid";
			$qry = "CALL sku_code_to_id(?)";

			// Prepared statement, stage 1: prepare
			if (!($sql = $mysqli->prepare($qry))) {
				$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: sku_code_to_id] [Query: $qry]");
			    die($error000);
				return;
			}

			if (!$sql->bind_param('s', $skuCode)) {
				$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: sku_code_to_id] [Param: $skuCode]");
			    die($error000);
				return;
			}

			$skuCode = filter_var($skuCode, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

			// Execute Prepared Statement
			if (!$sql->execute()) {
				$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: sku_code_to_id] [Query: $qry]");
			    die($error000);
				return;
			}

			// Get SQL statement result
			$result = $sql->get_result();

			if ($result->num_rows > 0) {
				$row = $result->fetch_assoc();
				$logs->write_logs('SKU Code to SKU ID', $file_name, "SKU Code: [$skuCode]\t Status: [Success]\t Response : [".$row['skuID']."]");
				$return = $row['skuID'];
			} else {
				$logs->write_logs('SKU Code to SKU ID', $file_name, "SKU Code: [$skuCode]\t Status: [Success]\t Response : [Invalid]\t Message: [No data found.]");
				$return = "Invalid";
			}

			$sql->close();
			return $return;
		}

		/********** SKU Code to SKU ID and Loyalty ID for Frequency **********/
		public function sku_code_to_array($skuCode) {
			$mysqli = self::$tmp_mysqli;
			$logs = self::$tmp_logs;
			$file_name = self::$tmp_file_name;
			$error000 = self::$tmp_error000;
			$error400 = self::$tmp_error400;
			$error1329 = self::$tmp_error1329;
			$return = "Invalid";
			$qry = "CALL sku_code_to_array(?)";

			// Prepared statement, stage 1: prepare
			if (!($sql = $mysqli->prepare($qry))) {
				$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: sku_code_to_array] [Query: $qry]");
			    die($error000);
				return;
			}

			if (!$sql->bind_param('s', $skuCode)) {
				$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: sku_code_to_array] [Param: $skuCode]");
			    die($error000);
				return;
			}

			$skuCode = filter_var($skuCode, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

			// Execute Prepared Statement
			if (!$sql->execute()) {
				$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: sku_code_to_array] [Query: $qry]");
			    die($error000);
				return;
			}

			// Get SQL statement result
			$result = $sql->get_result();

			if ($result->num_rows > 0) {
				$row = $result->fetch_assoc();
				$logs->write_logs('SKU Code to SKU ID and Loyalty ID for Frequency', $file_name, "SKU Code: [$skuCode]\t Status: [Success]\t Response : [".$row['skuID'].",".$row['loyaltyID']."]");
				$return = $row['skuID'].",".$row['loyaltyID'];
			} else {
				$logs->write_logs('SKU Code to SKU ID and Loyalty ID for Frequency', $file_name, "SKU Code: [$skuCode]\t Status: [Success]\t Response : [Invalid]\t Message: [No data found.]");
				$return = "Invalid";
			}

			$sql->close();
			return $return;
		}

		/********** Fetch Complete User Data **********/
		public function complete_data($memberID) {
			$mysqli = self::$tmp_mysqli;
			$logs = self::$tmp_logs;
			$file_name = self::$tmp_file_name;
			$error000 = self::$tmp_error000;
			$error400 = self::$tmp_error400;
			$error1329 = self::$tmp_error1329;
			$profile = array();
			$campaign = array();
			$redeemable_sku = array();
			$output = array();
			$level = array();
			$qry = "CALL core_complete_data(?)";

			if (!isset($memberID) || (!$memberID)) {
				$logs->write_logs('Fetch Complete User Data', $file_name, "Bad Request - Invalid/Empty [memberID: $memberID]");
				die($error400);
				return;
			}

			// Prepared statement, stage 1: prepare
			if (!($sql = $mysqli->prepare($qry))) {
				$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: core_complete_data] [Query: $qry]");
			    die($error000);
				return;
			}

			if (!$sql->bind_param('s', $memberID)) {
				$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: core_complete_data] [Param: $memberID]");
			    die($error000);
				return;
			}

			$memberID = filter_var($memberID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

			// Execute Prepared Statement
			if (!$sql->execute()) {
				$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: core_complete_data] [Query: $qry]");
			    die($error000);
				return;
			}

			// Get SQL statement result
			$result = $sql->get_result();

			if ($result->num_rows > 0) {
				$row = $result->fetch_assoc();
				if (isset($row['result'])) {
					$logs->write_logs('Fetch Complete User Data', $file_name, "[Member ID: $memberID]\t [Message : No User found.] [Param: $memberID]");
					$sql->close();
					return json_encode(array(array("response"=>"Error", "errorCode"=>"0101", "description"=>"No user found.")));
				} else {
					$row['dateReg'] = date('Y-m-d', strtotime($row['dateReg']));
					$profile = $row;
				}							
			} else {
				$logs->write_logs('Fetch Complete User Data', $file_name, "[Member ID: $memberID]\t [Message : No User found.] [Param: $memberID]");
				$sql->close();
				return json_encode(array(array("response"=>"Error", "errorCode"=>"0101", "description"=>"No user found.")));
			}

			$sql->close();

			$qry = "CALL fetch_json('loyaltytable')";
			
			if (!($sql = $mysqli->prepare($qry))) {
				$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Query: $qry]");
			    die($error000);
				return;
			}

			if (!$sql->execute()) {
				$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Query: $qry]");
			    die($error000);
				return;
			}

			// Get SQL statement result
			$result = $sql->get_result();

			if ($result->num_rows > 0) {
				while ($row = $result->fetch_assoc()) {
					foreach ($row as $array_key => $array_value) {
				       $row[$array_key] = html_entity_decode($array_value, ENT_QUOTES, 'UTF-8');
				    }
					array_push($campaign, $row);
				}
			}

			$sql->close();

			for ($i=0; $i<count($campaign); $i++) {
				if ($campaign[$i]['promoType'] == 'frequency') {
					$qry = "CALL sku_frequency(?, ?)";

					// Prepared statement, stage 1: prepare
					if (!($sql = $mysqli->prepare($qry))) {
						$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: sku_frequency] [Query: $qry]");
					    die($error000);
						return;
					}

					if (!$sql->bind_param('ss', $campaign[$i]['loyaltyID'], $memberID)) {
						$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: sku_frequency] [Param: ".$campaign[$i]['loyaltyID'].", $memberID]");
					    die($error000);
						return;
					}

					// Execute Prepared Statement
					if (!$sql->execute()) {
						$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: sku_frequency] [Query: $qry]");
					    die($error000);
						return;
					}

					// Get SQL statement result
					$result = $sql->get_result();

					if ($result->num_rows > 0) {
						$row = $result->fetch_assoc();
						array_push($redeemable_sku, array("loyaltyID"=>$campaign[$i]['loyaltyID'], "count"=>$row['count'], "group"=>$row['group']));
					}

					$sql->close();
				}
			}
			$profile["redeemable_sku_promo"] = $redeemable_sku;
			$profile["lastSync"] = date('M d,Y');
			// array_push($output, array("lastSync" => date('M d,Y')));
			array_push($output, array("profile" => $profile));
			return json_encode(array(array("response"=>"Success", "data"=>$output)));
		}

		/********** Redeem Points **********/
		public function redeem_points($deviceID, $locID, $loyaltyID, $memberID, $points, $transactionType, $version) {
			$mysqli = self::$tmp_mysqli;
			$logs = self::$tmp_logs;
			$file_name = self::$tmp_file_name;
			$error000 = self::$tmp_error000;
			$error400 = self::$tmp_error400;
			$error1329 = self::$tmp_error1329;
			$qry = "CALL redeem_points(?, ?, ?, ?, ?, ?, ?)";

			if (!isset($deviceID) || (!$deviceID)) {
				$logs->write_logs('Redeem Points', $file_name, "Bad Request - Invalid/Empty [deviceID: $deviceID]");
				die($error400);
				return;
			}

			if (!isset($locID) || (!$locID)) {
				$logs->write_logs('Redeem Points', $file_name, "Bad Request - Invalid/Empty [locID: $locID]");
				die($error400);
				return;
			}

			if (!isset($loyaltyID) || (!$loyaltyID)) {
				$logs->write_logs('Redeem Points', $file_name, "Bad Request - Invalid/Empty [loyaltyID: $loyaltyID]");
				die($error400);
				return;
			}

			if (!isset($memberID) || (!$memberID)) {
				$logs->write_logs('Redeem Points', $file_name, "Bad Request - Invalid/Empty [memberID: $memberID]");
				die($error400);
				return;
			}

			if (!isset($points) || (!$points) || ($points < 1)) {
				$logs->write_logs('Redeem Points', $file_name, "Bad Request - Invalid/Empty [points: $points]");
				die($error400);
				return;
			}

			if (!isset($transactionType) || (!$transactionType)) {
				$logs->write_logs('Redeem Points', $file_name, "Bad Request - Invalid/Empty [transactionType: $transactionType]");
				die($error400);
				return;
			} else {
				$transactionType = substr($transactionType, 0, 3);

				if (($transactionType != 'APP') && ($transactionType != 'CRD')) {
					$logs->write_logs('Redeem Points', $file_name, "Bad Request - Invalid/Empty [transactionType: $transactionType]");
					die($error400);
					return;
				}
			}

			if (!isset($version) || (!$version)) {
				$logs->write_logs('Redeem Points', $file_name, "Bad Request - Invalid/Empty [version: $version]");
				die($error400);
				return;
			}

			$deviceID = filter_var($deviceID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$locID = filter_var($locID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$loyaltyID = filter_var($loyaltyID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$memberID = filter_var($memberID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$points = filter_var($points, FILTER_SANITIZE_NUMBER_INT);
			$transactionType = filter_var($transactionType, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$version = filter_var($version, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$validate_device_id = $this->validate_device_id($deviceID);

			if ($validate_device_id == 'Multiple') {
				$logs->write_logs('Redeem Points', $file_name, "Bad Request - [Message: Multiple assignment has been detected. Please contact System Administrator to resolve this problem.] [Param: $deviceID, $locID, $loyaltyID, $memberID, $points, $transactionType, $version]");
				return json_encode(array(array("response"=>"Error", "errorCode"=>"1992", "description"=>"Multiple assignment has been detected. Please contact System Administrator to resolve this problem.")));
			} elseif ($validate_device_id == 'Inactive') {
				$logs->write_logs('Redeem Points', $file_name, "Bad Request - [Message: This Tablet has not yet been activated. Please contact System Administrator to resolve this problem.] [Param: $deviceID, $locID, $loyaltyID, $memberID, $points, $transactionType, $version]");
				return json_encode(array(array("response"=>"Error", "errorCode"=>"0318", "description"=>"This Tablet has not yet been activated. Please contact System Administrator to resolve this problem.")));
			} elseif ($validate_device_id == 'Undeployed') {
				$logs->write_logs('Redeem Points', $file_name, "Bad Request - [Message: This Tablet has not yet been deployed. Please contact System Administrator to resolve this problem.] [Param: $deviceID, $locID, $loyaltyID, $memberID, $points, $transactionType, $version]");
				return json_encode(array(array("response"=>"Error", "errorCode"=>"1989", "description"=>"This Tablet has not yet been deployed. Please contact System Administrator to resolve this problem.")));
			} elseif ($validate_device_id == 'False') {
				$logs->write_logs('Redeem Points', $file_name, "Bad Request - [Message: Invalid Device ID] [Param: $deviceID, $locID, $loyaltyID, $memberID, $points, $transactionType, $version]");
				return json_encode(array(array("response"=>"Error", "errorCode"=>"0326", "description"=>"Invalid Device ID.")));
			}

			if (($this->validate_location_id($locID)) == 'Invalid') {
				$logs->write_logs('Redeem Points', $file_name, "Bad Request - [Message: Invalid Location ID] [Param: $deviceID, $locID, $loyaltyID, $memberID, $points, $transactionType, $version]");
				return json_encode(array(array("response"=>"Error", "errorCode"=>"1990", "description"=>"Invalid Location ID.")));
			}

			$loyaltyType = $this->validate_loyalty_id($loyaltyID);

			if ($loyaltyType == 'Invalid') {
				$logs->write_logs('Redeem Points', $file_name, "Bad Request - [Message: Invalid Loyalty ID] [Param: $deviceID, $locID, $loyaltyID, $memberID, $points, $transactionType, $version]");
				return json_encode(array(array("response"=>"Error", "errorCode"=>"0991", "description"=>"Campaign does not exist."))); //"Invalid Loyalty ID."
			}

			if ($loyaltyType == 'frequency') {
				$qry = "CALL redeem_frequency(?, ?, ?, ?)";

				if (!($sql = $mysqli->prepare($qry))) {
					$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: redeem_frequency] [Query: $qry]");
				    die($error000);
					return;
				}

				if (!$sql->bind_param('ssss', $memberID, $loyaltyID, $locID, $deviceID)) {
					$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: redeem_frequency] [Param: $memberID, $loyaltyID, $locID, $deviceID]");
				    die($error000);
					return;
				}

				if (!$sql->execute()) {
					$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: redeem_frequency] [Query: $qry]");
				    die($error000);
					return;
				}

				$result = $sql->get_result();

				if ($result->num_rows > 0) {
					$row = $result->fetch_assoc();
					if ($row['result'] == "Success") {
						$logs->write_logs('Redeem Frequency', $file_name, "[Response: Success]\t [Param: $memberID, $loyaltyID, $locID, $deviceID]");
						$sql->close();
						return $this->complete_data($memberID);
					} elseif ($row['result'] == "Invalid") {
						$logs->write_logs('Redeem Frequency', $file_name, "Bad Request - [Message: Invalid ".$row['column']."]\t [Param: $memberID, $loyaltyID, $locID, $deviceID]");
						$sql->close();
						return json_encode(array(array("response"=>"Error", "errorCode"=>"9999", "description"=>"Invalid ".$row['column'].".")));
					} else {
						$logs->write_logs('Redeem Frequency', $file_name, "Bad Request - [Message: Unable to complete process.]\t [Device ID: $memberID, $loyaltyID, $locID, $deviceID]");
						$sql->close();
						return json_encode(array(array("response"=>"Error", "errorCode"=>"9998", "description"=>"Unable to complete process.")));
					}							
				} else {
					$logs->write_logs('Redeem Frequency', $file_name, "Bad Request - [Message: Unable to complete process.]\t [Device ID: $memberID, $loyaltyID, $locID, $deviceID]");
					$sql->close();
					return json_encode(array(array("response"=>"Error", "errorCode"=>"9998", "description"=>"Unable to complete process.")));
				}
			} else {
				if (!($sql = $mysqli->prepare($qry))) {
					$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: redeem_points] [Query: $qry]");
				    die($error000);
					return;
				}

				if (!$sql->bind_param('ssssiss', $deviceID, $locID, $loyaltyID, $memberID, $points, $transactionType, $version)) {
					$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: redeem_points] [Param: $deviceID, $locID, $loyaltyID, $memberID, $points, $transactionType, $version]");
					die($error000);
					return;
				}

				if (!$sql->execute()) {
					$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: redeem_points] [Query: $qry]");
				    die($error000);
					return;
				}

				$result = $sql->get_result();

				if ($result->num_rows > 0) {
					$row = $result->fetch_assoc();

					if ($row['result'] == "Success") {
						$logs->write_logs('Redeem Points', $file_name, "[Param: $deviceID, $locID, $loyaltyID, $memberID, $points, $transactionType, $version]\t Response : [".$row['result']."]");
						$sql->close();
						return $this->complete_data($memberID);
					} else {
						$logs->write_logs('Redeem Points', $file_name, "[Param: $deviceID, $locID, $loyaltyID, $memberID, $points, $transactionType, $version]\t Response : [".$row['result']."]");
						$sql->close();
						return json_encode(array(array("response"=>"Error", "description"=>$row['description'])));
					}
				} else {
					$sql->close();
					return json_encode(array(array("response"=>"Error", "description"=>"Unable to complete process.")));
					return;
				}
			}
		}

		/********** Redeem Voucher **********/
		public function redeem_voucher($deviceID, $locID, $voucherID, $memberID, $transactionType, $version) {
			$mysqli = self::$tmp_mysqli;
			$logs = self::$tmp_logs;
			$file_name = self::$tmp_file_name;
			$error000 = self::$tmp_error000;
			$error400 = self::$tmp_error400;
			$error1329 = self::$tmp_error1329;
			$qry = "CALL redeem_voucher(?, ?, ?, ?, ?, ?)";

			if (!isset($deviceID) || (!$deviceID)) {
				$logs->write_logs('Redeem Voucher', $file_name, "Bad Request - Invalid/Empty [deviceID: $deviceID]");
				die($error400);
				return;
			}

			if (!isset($locID) || (!$locID)) {
				$logs->write_logs('Redeem Voucher', $file_name, "Bad Request - Invalid/Empty [locID: $locID]");
				die($error400);
				return;
			}

			if (!isset($voucherID) || (!$voucherID)) {
				$logs->write_logs('Redeem Voucher', $file_name, "Bad Request - Invalid/Empty [voucherID: $voucherID]");
				die($error400);
				return;
			}

			if (!isset($memberID) || (!$memberID)) {
				$logs->write_logs('Redeem Voucher', $file_name, "Bad Request - Invalid/Empty [memberID: $memberID]");
				die($error400);
				return;
			}

			if (!isset($transactionType) || (!$transactionType)) {
				$logs->write_logs('Redeem Voucher', $file_name, "Bad Request - Invalid/Empty [transactionType: $transactionType]");
				die($error400);
				return;
			} else {
				$transactionType = substr($transactionType, 0, 3);

				if (($transactionType != 'APP') && ($transactionType != 'CRD')) {
					$logs->write_logs('Redeem Voucher', $file_name, "Bad Request - Invalid/Empty [transactionType: $transactionType]");
					die($error400);
					return;
				}
			}

			if (!isset($version) || (!$version)) {
				$logs->write_logs('Redeem Voucher', $file_name, "Bad Request - Invalid/Empty [version: $version]");
				die($error400);
				return;
			}

			$deviceID = filter_var($deviceID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$locID = filter_var($locID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$voucherID = filter_var($voucherID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$memberID = filter_var($memberID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$transactionType = filter_var($transactionType, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$version = filter_var($version, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$validate_device_id = $this->validate_device_id($deviceID);

			if ($validate_device_id == 'Multiple') {
				$logs->write_logs('Redeem Voucher', $file_name, "Bad Request - [Message: Multiple assignment has been detected. Please contact System Administrator to resolve this problem.] [Param: $deviceID, $locID, $voucherID, $memberID, $transactionType, $version]");
				return json_encode(array(array("response"=>"Error", "errorCode"=>"1992", "description"=>"Multiple assignment has been detected. Please contact System Administrator to resolve this problem.")));
			} elseif ($validate_device_id == 'Inactive') {
				$logs->write_logs('Redeem Voucher', $file_name, "Bad Request - [Message: This Tablet has not yet been activated. Please contact System Administrator to resolve this problem.] [Param: $deviceID, $locID, $voucherID, $memberID, $transactionType, $version]");
				return json_encode(array(array("response"=>"Error", "errorCode"=>"0318", "description"=>"This Tablet has not yet been activated. Please contact System Administrator to resolve this problem.")));
			} elseif ($validate_device_id == 'Undeployed') {
				$logs->write_logs('Redeem Voucher', $file_name, "Bad Request - [Message: This Tablet has not yet been deployed. Please contact System Administrator to resolve this problem.] [Param: $deviceID, $locID, $voucherID, $memberID, $transactionType, $version]");
				return json_encode(array(array("response"=>"Error", "errorCode"=>"1989", "description"=>"This Tablet has not yet been deployed. Please contact System Administrator to resolve this problem.")));
			} elseif ($validate_device_id == 'False') {
				$logs->write_logs('Redeem Voucher', $file_name, "Bad Request - [Message: Invalid Device ID] [Param: $deviceID, $locID, $voucherID, $memberID, $transactionType, $version]");
				return json_encode(array(array("response"=>"Error", "errorCode"=>"0326", "description"=>"Invalid Device ID.")));
			}

			if (($this->validate_location_id($locID)) == 'Invalid') {
				$logs->write_logs('Redeem Voucher', $file_name, "Bad Request - [Message: Invalid Location ID] [Param: $deviceID, $locID, $voucherID, $memberID, $transactionType, $version]");
				return json_encode(array(array("response"=>"Error", "errorCode"=>"1990", "description"=>"Invalid Location ID.")));
			}

			if (($this->validate_voucher_id($voucherID, $memberID)) == 'Invalid') {
				$logs->write_logs('Redeem Voucher', $file_name, "Bad Request - [Message: Invalid Location ID] [Param: $deviceID, $locID, $voucherID, $memberID, $transactionType, $version]");
				return json_encode(array(array("response"=>"Error", "errorCode"=>"0991", "description"=>"Voucher does not exist."))); //Invalid Voucher ID.
			}

			if (!($sql = $mysqli->prepare($qry))) {
				$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: redeem_voucher] [Query: $qry]");
			    die($error000);
				return;
			}

			if (!$sql->bind_param('ssssss', $deviceID, $locID, $voucherID, $memberID, $transactionType, $version)) {
				$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: redeem_voucher] [Param: $deviceID, $locID, $voucherID, $memberID, $transactionType, $version]");
				die($error000);
				return;
			}

			if (!$sql->execute()) {
				$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: redeem_voucher] [Query: $qry]");
			    die($error000);
				return;
			}

			$result = $sql->get_result();

			if ($result->num_rows > 0) {
				$row = $result->fetch_assoc();

				if ($row['result'] == "Success") {
					$logs->write_logs('Redeem Voucher', $file_name, "[Param: $deviceID, $locID, $voucherID, $memberID, $transactionType, $version]\t Response : [".$row['result']."]");
					$sql->close();
					return $this->complete_data($memberID);
				} else {
					$logs->write_logs('Redeem Voucher', $file_name, "[Param: $deviceID, $locID, $voucherID, $memberID, $transactionType, $version]\t Response : [".$row['result']."]");
					$sql->close();
					return json_encode(array(array("response"=>"Error", "description"=>$row['description'])));
				}
			} else {
				$sql->close();
				return json_encode(array(array("response"=>"Error", "description"=>"Unable to complete process.")));
				return;
			}
		}

		/********** Check Voucher ID **********/
		public function validate_voucher_id($voucherID, $memberID) {
			$mysqli = self::$tmp_mysqli;
			$logs = self::$tmp_logs;
			$file_name = self::$tmp_file_name;
			$error000 = self::$tmp_error000;
			$error400 = self::$tmp_error400;
			$error1329 = self::$tmp_error1329;
			$return = "Invalid";
			$qry = "CALL validate_voucher_id(?, ?)";

			if (!($sql = $mysqli->prepare($qry))) {
				$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: validate_voucher_id] [Query: $qry]");
			    die($error000);
				return;
			}

			if (!$sql->bind_param('ss', $voucherID, $memberID)) {
				$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: validate_voucher_id] [Param: $voucherID, $memberID]");
			    die($error000);
				return;
			}

			$voucherID = filter_var($voucherID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$memberID = filter_var($memberID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

			if (!$sql->execute()) {
				$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: validate_voucher_id] [Query: $qry]");
			    die($error000);
				return;
			}

			$result = $sql->get_result();

			if ($result->num_rows > 0) {
				$logs->write_logs('Check Voucher ID', $file_name, "Param: [$voucherID, $memberID]\t Status: [Success]\t Response : [Valid]");
				$return = "Valid";
			} else {
				$logs->write_logs('Check Voucher ID', $file_name, "Param: [$voucherID, $memberID]\t Status: [Success]\t Response : Invalid Voucher ID.");
			}

			$sql->close();
			return $return;
		}

		/********** Check Loyalty ID **********/
		public function validate_loyalty_id($loyaltyID) {
			$mysqli = self::$tmp_mysqli;
			$logs = self::$tmp_logs;
			$file_name = self::$tmp_file_name;
			$error000 = self::$tmp_error000;
			$error400 = self::$tmp_error400;
			$error1329 = self::$tmp_error1329;
			$return = "Invalid";
			$qry = "CALL validate_loyalty_id(?)";

			if (!($sql = $mysqli->prepare($qry))) {
				$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: validate_loyalty_id] [Query: $qry]");
			    die($error000);
				return;
			}

			if (!$sql->bind_param('s', $loyaltyID)) {
				$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: validate_loyalty_id] [Param: $loyaltyID]");
			    die($error000);
				return;
			}

			$loyaltyID = filter_var($loyaltyID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

			if (!$sql->execute()) {
				$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: validate_loyalty_id] [Query: $qry]");
			    die($error000);
				return;
			}

			$result = $sql->get_result();

			if ($result->num_rows > 0) {
				$row = $result->fetch_assoc();
				$logs->write_logs('Check Loyalty ID', $file_name, "Param: [$loyaltyID]\t Status: [Success]\t Response : [Valid]");
				$return = $row['promoType']; //"Valid";
			} else {
				$logs->write_logs('Check Loyalty ID', $file_name, "Param: [$loyaltyID]\t Status: [Success]\t Response : Invalid Loyalty ID.");
			}

			$sql->close();
			return $return;
		}

	}

	/********** Invalid Access Checker **********/
	if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {
		include_once('../../logs/logs.class.php');
		$logs = NEW logs();
		// $file_name = substr(strtolower(basename($_SERVER['PHP_SELF'])),0,strlen(basename($_SERVER['PHP_SELF'])));
		$logs->write_logs('Invalid Access', 'points.class.php', 'Illegal access attempt.');
		die('Access denied');
	} 

?>