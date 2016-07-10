<?php

	/**
		Creator 	: Jim Karlo Jamero
		Company		: Appsolutely Inc.
		File Name 	: dashboard.class.php
		Description : This PHP class handles all the dashboard related major functions.	
	**/

	class dashboard {

		private static $tmp_mysqli;
		private static $tmp_logs;
		private static $tmp_file_name;
		private static $tmp_error000;
		private static $tmp_error400;
		private static $tmp_error1329;
		
		function __construct() {
			include_once('php_api/api/settings/config.php');
			self::$tmp_mysqli = $mysqli;
			self::$tmp_logs = $logs;
			self::$tmp_file_name = 'dashboard.class.php';
			self::$tmp_error000 = $error000;
			self::$tmp_error400 = $error400;
			self::$tmp_error1329 = $error1329;
		}

		/********** Session Checker **********/
		public function session_checker($accountID, $my_session_id) {
			$mysqli = self::$tmp_mysqli;
			$logs = self::$tmp_logs;
			$file_name = self::$tmp_file_name;
			$error000 = self::$tmp_error000;
			$error400 = self::$tmp_error400;
			$error1329 = self::$tmp_error1329;
			$qry = "CALL dashboard_session_checker(?, ?)";

			if (!isset($accountID) || (!$accountID)) {
				$logs->write_logs('Dashboard Session Checker', $file_name, "Bad Request - Invalid/Empty [accountID: $accountID]");
				die($error400);
				return;
			}

			if (!isset($my_session_id) || (!$my_session_id)) {
				$logs->write_logs('Dashboard Session Checker', $file_name, "Bad Request - Invalid/Empty [my_session_id: $my_session_id]");
				die($error400);
				return;
			}

			// Prepared statement, stage 1: prepare
			if (!($sql = $mysqli->prepare($qry))) {
				$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: dashboard_session_checker] [Query: $qry]");
			    die($error000);
				return;
			}

			if (!$sql->bind_param('ss', $accountID, $my_session_id)) {
				$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: dashboard_session_checker] [Param: $accountID, $my_session_id]");
			    die($error000);
				return;
			}

			$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

			// Execute Prepared Statement
			if (!$sql->execute()) {
				$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: dashboard_session_checker] [Query: $qry]");
			    die($error000);
				return;
			}

			// Get SQL statement result
			$result = $sql->get_result();

			if ($result->num_rows > 0) {
				$row = $result->fetch_assoc();
				$result = $row['result'];
				$sql->close();
				return json_encode(array(array("response"=>"Success", "data"=>array("message"=>$row['result']))));
			} else {
				$sql->close();
				return json_encode(array(array("response"=>"Error", "data"=>array("message"=>"Expired"))));
			}
		}

		/********** Login **********/
		public function login($username, $password) {
			$mysqli = self::$tmp_mysqli;
			$logs = self::$tmp_logs;
			$file_name = self::$tmp_file_name;
			$error000 = self::$tmp_error000;
			$error400 = self::$tmp_error400;
			$error1329 = self::$tmp_error1329;
			$qry = "CALL dashboard_login(?, ?)";

			if (!isset($username) || (!$username)) {
				$logs->write_logs('Dashboard Login', $file_name, "Bad Request - Invalid/Empty [username: $username]");
				die($error400);
				return;
			}

			if (!isset($password) || (!$password)) {
				$logs->write_logs('Dashboard Login', $file_name, "Bad Request - Invalid/Empty [password: $password]");
				die($error400);
				return;
			}

			// Prepared statement, stage 1: prepare
			if (!($sql = $mysqli->prepare($qry))) {
				$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: dashboard_login] [Query: $qry]");
			    die($error000);
				return;
			}

			if (!$sql->bind_param('ss', $username, $password)) {
				$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: dashboard_login] [Param: $username, $password]");
			    die($error000);
				return;
			}

			$username = filter_var($username, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$password = filter_var($password, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);



			// Execute Prepared Statement
			if (!$sql->execute()) {
				$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: dashboard_login] [Query: $qry]");
			    die($error000);
				return;
			}

			// Get SQL statement result
			$result = $sql->get_result();

			if ($result->num_rows > 0) {
				$row = $result->fetch_assoc();
				if ($row['result'] == 'Failed') {
					$sql->close();
					return json_encode(array(array("response"=>"Error", "description"=>"Invalid Username/Password.")));
					die();
				} else {
					$accountID = $row['result'];
					$loginSession = $row['loginSession'];
					$role = $row['role'];
					$sql->close();
					return json_encode(array(array("response"=>"Success", "data"=>array("accountID"=>$accountID, "loginSession"=>$loginSession, "role"=>$role))));
				}
			} else {
				$sql->close();
				return json_encode(array(array("response"=>"Error", "description"=>"Invalid Username/Password.")));
			}
		}

		/********** Password **********/
		public function password($accountID, $my_session_id, $old_password, $new_password) {
			$mysqli = self::$tmp_mysqli;
			$logs = self::$tmp_logs;
			$file_name = self::$tmp_file_name;
			$error000 = self::$tmp_error000;
			$error400 = self::$tmp_error400;
			$error1329 = self::$tmp_error1329;
			$qry = "CALL dashboard_password(?, ?, ?, ?)";
			$output = array();

			if (!isset($accountID) || (!$accountID)) {
				$logs->write_logs('Dashboard Password Update', $file_name, "Bad Request - Invalid/Empty [accountID: $accountID]");
				die($error400);
				return;
			}

			if (!isset($my_session_id) || (!$my_session_id)) {
				$logs->write_logs('Dashboard Password Update', $file_name, "Bad Request - Invalid/Empty [my_session_id: $my_session_id]");
				die($error400);
				return;
			}

			if (!isset($old_password) || (!$old_password)) {
				$logs->write_logs('Dashboard Password Update', $file_name, "Bad Request - Invalid/Empty [old_password: $old_password]");
				die($error400);
				return;
			}

			if (!isset($new_password) || (!$new_password)) {
				$logs->write_logs('Dashboard Password Update', $file_name, "Bad Request - Invalid/Empty [new_password: $new_password]");
				die($error400);
				return;
			}

			// Prepared statement, stage 1: prepare
			if (!($sql = $mysqli->prepare($qry))) {
				$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: dashboard_password] [Query: $qry]");
			    die($error000);
				return;
			}

			if (!$sql->bind_param('ssss', $accountID, $my_session_id, $old_password, $new_password)) {
				$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: dashboard_password] [Param: $accountID, $my_session_id, $old_password, $new_password]");
			    die($error000);
				return;
			}

			$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$old_password = filter_var($old_password, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$new_password = filter_var($new_password, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

			// Execute Prepared Statement
			if (!$sql->execute()) {
				$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: dashboard_password] [Query: $qry]");
			    die($error000);
				return;
			}

			// Get SQL statement result
			$result = $sql->get_result();

			if ($result->num_rows > 0) {
				$row = array();
				$row = $result->fetch_assoc();
				$sql->close();
				return json_encode(array(array("response"=>$row['result'])));
			} else {
				$sql->close();
				return json_encode(array(array("response"=>"Error")));
				die();
			}
		}

		/********** JSON **********/
		public function json($accountID, $my_session_id, $table) {
			$mysqli = self::$tmp_mysqli;
			$logs = self::$tmp_logs;
			$file_name = self::$tmp_file_name;
			$error000 = self::$tmp_error000;
			$error400 = self::$tmp_error400;
			$error1329 = self::$tmp_error1329;
			$qry = "CALL dashboard_json(?, ?, ?)";
			$output = array();

			if (!isset($accountID) || (!$accountID)) {
				$logs->write_logs('Dashboard JSON Fetch', $file_name, "Bad Request - Invalid/Empty [accountID: $accountID]");
				die($error400);
				return;
			}

			if (!isset($my_session_id) || (!$my_session_id)) {
				$logs->write_logs('Dashboard JSON Fetch', $file_name, "Bad Request - Invalid/Empty [my_session_id: $my_session_id]");
				die($error400);
				return;
			}

			if (!isset($table) || (!$table)) {
				$logs->write_logs('Dashboard JSON Fetch', $file_name, "Bad Request - Invalid/Empty [table: $table]");
				die($error400);
				return;
			}

			// echo "CALL dashboard_json('$accountID', '$my_session_id', '$table')";
			// Prepared statement, stage 1: prepare
			if (!($sql = $mysqli->prepare($qry))) {
				$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: dashboard_json] [Query: $qry]");
			    die($error000);
				return;
			}

			if (!$sql->bind_param('sss', $accountID, $my_session_id, $table)) {
				$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: dashboard_json] [Param: $accountID, $my_session_id, $table]");
			    die($error000);
				return;
			}

			$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$table = filter_var($table, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

			// Execute Prepared Statement
			if (!$sql->execute()) {
				$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: dashboard_json] [Query: $qry]");
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
					array_push($output, $row);
				}
				$logs->write_logs('Dashboard JSON Fetch', $file_name, "Table: [$table]\t Status: [Success]");
 
				if (!isset($row['result'])) { 
					return json_encode(array(array("response"=>"Success", "data"=>$output)));
				} else {
					return json_encode(array(array("response"=>$row['result'])));
				}
			} else {
				return json_encode(array(array("response"=>"Error")));
			}			
		}

		/********** Send Push Notification **********/
		public function send_push($accountID, $my_session_id, $message, $type) {
			include("php/api/push/push.class.php");
			$push = new push_notification;
			$mysqli = self::$tmp_mysqli;
			$logs = self::$tmp_logs;
			$file_name = self::$tmp_file_name;
			$error000 = self::$tmp_error000;
			$error400 = self::$tmp_error400;
			$error1329 = self::$tmp_error1329;
			$qry = "CALL dashboard_push(?, ?)";

			if (!isset($accountID) || (!$accountID)) {
				$logs->write_logs('Dashboard Send Push Notification', $file_name, "Bad Request - Invalid/Empty [accountID: $accountID]");
				die($error400);
				return;
			}

			if (!isset($my_session_id) || (!$my_session_id)) {
				$logs->write_logs('Dashboard Send Push Notification', $file_name, "Bad Request - Invalid/Empty [my_session_id: $my_session_id]");
				die($error400);
				return;
			}

			if (!isset($message) || (!$message)) {
				$logs->write_logs('Dashboard Send Push Notification', $file_name, "Bad Request - Invalid/Empty [message: $message]");
				die($error400);
				return;
			}

			// echo "CALL dashboard_push('$accountID', '$my_session_id')";
			// Prepared statement, stage 1: prepare
			if (!($sql = $mysqli->prepare($qry))) {
				$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: dashboard_push] [Query: $qry]");
			    die($error000);
				return;
			}

			if (!$sql->bind_param('ss', $accountID, $my_session_id)) {
				$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: dashboard_push] [Param: $accountID, $my_session_id]");
			    die($error000);
				return;
			}

			$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

			// Execute Prepared Statement
			if (!$sql->execute()) {
				$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: dashboard_push] [Query: $qry]");
			    die($error000);
				return;
			}

			// Get SQL statement result
			$result = $sql->get_result();

			if ($result->num_rows > 0) {
				$pushID = array();
				$error_flag = false;
				$return = "";

				while ($row = $result->fetch_assoc()) {

					if (isset($row['result'])) {
						$error_flag = true;
						$return = $row['result'];
					} else {
						array_push($pushID, $row['pushID']);
					}

				}

				$sql->close();

				if (!$error_flag) {
					$pushID_chunk = array_chunk($pushID, 1000);

					for ($i=0; $i<count($pushID_chunk); $i++) { 
						$push->push($pushID_chunk[$i], $message, $type);
					}

					return json_encode(array(array("response"=>"Success")));
				} else {
					return json_encode(array(array("response"=>$return)));
				}				
			}
			
			$sql->close();
			return json_encode(array(array("response"=>"Success")));
		}
 


		/********** Add Voucher **********/
		public function add_voucher( $accountID, $my_session_id, $name, $description, $limit, $type, $status, $startdate, $enddate, $image ) {
			include("php/api/push/push.class.php");
			$push = new push_notification;
			$mysqli = self::$tmp_mysqli;
			$logs = self::$tmp_logs;
			$file_name = self::$tmp_file_name;
			$error000 = self::$tmp_error000;
			$error400 = self::$tmp_error400;
			$error1329 = self::$tmp_error1329;
			$qry = "CALL dashboard_add_voucher(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
 
			if (!isset($accountID) || (!$accountID)) {
				$logs->write_logs('Dashboard Add Voucher', $file_name, "Bad Request - Invalid/Empty [accountID: $accountID]");
				die($error400);
				return;
			}

			if (!isset($my_session_id) || (!$my_session_id)) {
				$logs->write_logs('Dashboard Add Voucher', $file_name, "Bad Request - Invalid/Empty [my_session_id: $my_session_id]");
				die($error400);
				return;
			} 

			if (!isset($name) ) {
				$logs->write_logs('Dashboard Add Voucher', $file_name, "Bad Request - Invalid/Empty [name: $name]");
				die($error400);
				return;
			}

			if (!isset($description) ) {
				$logs->write_logs('Dashboard Add Voucher', $file_name, "Bad Request - Invalid/Empty [description: $description]");
				die($error400);
				return;
			}

			if (!isset($limit) ) {
				$logs->write_logs('Dashboard Add Voucher', $file_name, "Bad Request - Invalid/Empty [limit: $limit]");
				die($error400);
				return;
			}

			if (!isset($type) ) {
				$logs->write_logs('Dashboard Add Voucher', $file_name, "Bad Request - Invalid/Empty [type: $type]");
				die($error400);
				return;
			} 

			if (!isset($image) ) {
				$logs->write_logs('Dashboard Add Voucher', $file_name, "Bad Request - Invalid/Empty [image: $image]");
				die($error400);
				return;
			} 

			if (!isset($status) || (!$status)) {
				$logs->write_logs('Dashboard Add Voucher', $file_name, "Bad Request - Invalid/Empty [status: $status]");
				die($error400);
				return;
			}  

			if (!isset($startdate)  ) {
				$logs->write_logs('Dashboard Add Voucher', $file_name, "Bad Request - Invalid/Empty [startdate: $startdate]");
				die($error400);
				return;
			} 

			if (!isset($enddate) ) {
				$logs->write_logs('Dashboard Add Voucher', $file_name, "Bad Request - Invalid/Empty [enddate: $enddate]");
				die($error400);
				return;
			} 
                
			// Prepared statement, stage 1: prepare
			if (!($sql = $mysqli->prepare($qry))) {
				$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: dashboard_add_voucher] [Query: $qry]");
			    die($error000);
				return;
			}

			if (!$sql->bind_param('ssssisssss',  $accountID, $my_session_id, $name, $description, $limit, $type, $status, $startdate, $enddate, $image)) {
				$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: dashboard_add_voucher] [Param:   $accountID, $my_session_id, $name, $description, $limit, $type, $status, $startdate, $enddate, $image]");
			    die($error000);
				return;
			}

			$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH); 
			$name = filter_var($name, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$description = filter_var($description, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$limit = filter_var($limit, FILTER_SANITIZE_NUMBER_INT);
			$type = filter_var($type, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH); 
			$status = filter_var($status, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);  
			$startdate = date('Y-m-d', strtotime($startdate));
			$enddate = date('Y-m-d', strtotime($enddate));
			$image = filter_var($image, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);  

			// Execute Prepared Statement
			if (!$sql->execute()) {
				$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: dashboard_add_voucher] [Query: $qry]");
			    die($error000);
				return;
			}

			// Get SQL statement result
			$result = $sql->get_result();

			if ($result->num_rows > 0) {
				$row = array();
				$row = $result->fetch_assoc();
				$sql->close();
				return json_encode(array(array("response"=>$row['result'])));
			} else {
				$sql->close();
				return json_encode(array(array("response"=>"Error")));
				die();
			}
		}


		/********** Update Voucher **********/
		public function update_voucher( $accountID, $my_session_id, $voucherID, $name, $description, $limit, $type, $status, $startdate, $enddate, $image ) {
			include("php/api/push/push.class.php");
			$push = new push_notification;
			$mysqli = self::$tmp_mysqli;
			$logs = self::$tmp_logs;
			$file_name = self::$tmp_file_name;
			$error000 = self::$tmp_error000;
			$error400 = self::$tmp_error400;
			$error1329 = self::$tmp_error1329;
			$qry = "CALL dashboard_update_voucher(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ? )";

			if (!isset($accountID) || (!$accountID)) {
				$logs->write_logs('Dashboard Add Voucher', $file_name, "Bad Request - Invalid/Empty [accountID: $accountID]");
				die($error400);
				return;
			}

			if (!isset($my_session_id) || (!$my_session_id)) {
				$logs->write_logs('Dashboard Add Voucher', $file_name, "Bad Request - Invalid/Empty [my_session_id: $my_session_id]");
				die($error400);
				return;
			}

			if (!isset($voucherID) || (!$voucherID)) {
				$logs->write_logs('Dashboard Add Voucher', $file_name, "Bad Request - Invalid/Empty [voucherID: $voucherID]");
				die($error400);
				return;
			} 

			if (!isset($name) ) {
				$logs->write_logs('Dashboard Add Voucher', $file_name, "Bad Request - Invalid/Empty [name: $name]");
				die($error400);
				return;
			}

			if (!isset($description) ) {
				$logs->write_logs('Dashboard Add Voucher', $file_name, "Bad Request - Invalid/Empty [description: $description]");
				die($error400);
				return;
			}

			if (!isset($limit) ) {
				$logs->write_logs('Dashboard Add Voucher', $file_name, "Bad Request - Invalid/Empty [limit: $limit]");
				die($error400);
				return;
			}

			if (!isset($type) ) {
				$logs->write_logs('Dashboard Add Voucher', $file_name, "Bad Request - Invalid/Empty [type: $type]");
				die($error400);
				return;
			} 

			if (!isset($image) ) {
				$logs->write_logs('Dashboard Add Voucher', $file_name, "Bad Request - Invalid/Empty [image: $image]");
				die($error400);
				return;
			} 

			if (!isset($status) || (!$status)) {
				$logs->write_logs('Dashboard Add Voucher', $file_name, "Bad Request - Invalid/Empty [status: $status]");
				die($error400);
				return;
			}  

			if (!isset($startdate)  ) {
				$logs->write_logs('Dashboard Add Voucher', $file_name, "Bad Request - Invalid/Empty [startdate: $startdate]");
				die($error400);
				return;
			} 

			if (!isset($enddate) ) {
				$logs->write_logs('Dashboard Add Voucher', $file_name, "Bad Request - Invalid/Empty [enddate: $enddate]");
				die($error400);
				return;
			} 
                
			// Prepared statement, stage 1: prepare
			if (!($sql = $mysqli->prepare($qry))) {
				$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: dashboard_update_voucher] [Query: $qry]");
			    die($error000);
				return;
			}

			if (!$sql->bind_param('ssssssissss',  $accountID, $my_session_id, $voucherID, $name, $description, $limit, $type, $status, $startdate, $enddate, $image)) {
				$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: dashboard_update_voucher] [Param:   $accountID, $my_session_id, $voucherID, $name, $description, $limit, $type, $status, $startdate, $enddate, $image]");
			    die($error000);
				return;
			}

			$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$voucherID = filter_var($voucherID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH); 
			$name = filter_var($name, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$description = filter_var($description, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$limit = filter_var($limit, FILTER_SANITIZE_NUMBER_INT);
			$type = filter_var($type, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH); 
			$status = filter_var($status, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);  
			$startdate = date('Y-m-d', strtotime($startdate));
			$enddate = date('Y-m-d', strtotime($enddate)); 

			if (!isset($image) || (!$image)) {
				$image = NULL;
			} else {
				$image = filter_var($image, FILTER_VALIDATE_URL, FILTER_FLAG_PATH_REQUIRED);
			}

			// Execute Prepared Statement
			if (!$sql->execute()) {
				$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: dashboard_update_voucher] [Query: $qry]");
			    die($error000);
				return;
			}

			// Get SQL statement result
			$result = $sql->get_result();

			if ($result->num_rows > 0) {
				$row = array();
				$row = $result->fetch_assoc();  
				$sql->close();

				if ($row['result'] != 'Success') {
					$this->unlink_image($image);
				} else {
					if (isset($row['old_image'])) {
						if ($row['old_image'] != 'None') {
							$this->unlink_image($row['old_image']);
						}
					}
				}

				return json_encode(array(array("response"=>$row['result'])));
			} else {
				$sql->close();
				return json_encode(array(array("response"=>"Error")));
				die();
			}
		}



		/********** Add Promo **********/
		public function add_promo($accountID, $my_session_id, $name, $description, $image, $status) {
			include("php/api/push/push.class.php");
			$push = new push_notification;
			$mysqli = self::$tmp_mysqli;
			$logs = self::$tmp_logs;
			$file_name = self::$tmp_file_name;
			$error000 = self::$tmp_error000;
			$error400 = self::$tmp_error400;
			$error1329 = self::$tmp_error1329;
			$qry = "CALL dashboard_add_promo(?, ?, ?, ?, ?, ?)";

			if (!isset($accountID) || (!$accountID)) {
				$logs->write_logs('Dashboard Add Promo', $file_name, "Bad Request - Invalid/Empty [accountID: $accountID]");
				die($error400);
				return;
			}

			if (!isset($my_session_id) || (!$my_session_id)) {
				$logs->write_logs('Dashboard Add Promo', $file_name, "Bad Request - Invalid/Empty [my_session_id: $my_session_id]");
				die($error400);
				return;
			}

			if (!isset($name) || (!$name)) {
				$logs->write_logs('Dashboard Add Promo', $file_name, "Bad Request - Invalid/Empty [name: $name]");
				die($error400);
				return;
			}

			if (!isset($image) || (!$image)) {
				$logs->write_logs('Dashboard Add Promo', $file_name, "Bad Request - Invalid/Empty [image: $image]");
				die($error400);
				return;
			}

			if (!isset($description) ) {
				$logs->write_logs('Dashboard Add Promo', $file_name, "Bad Request - Invalid/Empty [description: $description]");
				die($error400);
				return;
			}

			if (!isset($status) || (!$status)) {
				$logs->write_logs('Dashboard Add Promo', $file_name, "Bad Request - Invalid/Empty [status: $status]");
				die($error400);
				return;
			}

			// Prepared statement, stage 1: prepare
			if (!($sql = $mysqli->prepare($qry))) {
				$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: dashboard_add_promo] [Query: $qry]");
			    die($error000);
				return;
			}

			if (!$sql->bind_param('ssssss', $accountID, $my_session_id, $name, $description, $image, $status)) {
				$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: dashboard_add_promo] [Param: $accountID, $my_session_id, $name, $description, $image, $status]");
			    die($error000);
				return;
			}

			$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$name = filter_var($name, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$description = filter_var($description, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$image = filter_var($image, FILTER_VALIDATE_URL, FILTER_FLAG_PATH_REQUIRED);
			$status = filter_var($status, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

			// Execute Prepared Statement
			if (!$sql->execute()) {
				$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: dashboard_add_promo] [Query: $qry]");
			    die($error000);
				return;
			}

			// Get SQL statement result
			$result = $sql->get_result();

			if ($result->num_rows > 0) {
				$row = array();
				$row = $result->fetch_assoc();
				$sql->close();

				if ($row['result'] != 'Success') {
					$this->unlink_image($image);
				}

				return json_encode(array(array("response"=>$row['result'])));
			} else {
				$sql->close();
				$this->unlink_image($image);
				return json_encode(array(array("response"=>"Error")));
				die();
			}
		}

		/********** Delete Promo **********/
		public function delete_promo($accountID, $my_session_id, $promoID) {
			include("php/api/push/push.class.php");
			$push = new push_notification;
			$mysqli = self::$tmp_mysqli;
			$logs = self::$tmp_logs;
			$file_name = self::$tmp_file_name;
			$error000 = self::$tmp_error000;
			$error400 = self::$tmp_error400;
			$error1329 = self::$tmp_error1329;
			$qry = "CALL dashboard_delete_promo(?, ?, ?)";

			if (!isset($accountID) || (!$accountID)) {
				$logs->write_logs('Dashboard Delete Promo', $file_name, "Bad Request - Invalid/Empty [accountID: $accountID]");
				die($error400);
				return;
			}

			if (!isset($my_session_id) || (!$my_session_id)) {
				$logs->write_logs('Dashboard Delete Promo', $file_name, "Bad Request - Invalid/Empty [my_session_id: $my_session_id]");
				die($error400);
				return;
			}

			if (!isset($promoID) || (!$promoID)) {
				$logs->write_logs('Dashboard Delete Promo', $file_name, "Bad Request - Invalid/Empty [promoID: $promoID]");
				die($error400);
				return;
			}

			// Prepared statement, stage 1: prepare
			if (!($sql = $mysqli->prepare($qry))) {
				$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: dashboard_delete_promo] [Query: $qry]");
			    die($error000);
				return;
			}

			if (!$sql->bind_param('sss', $accountID, $my_session_id, $promoID)) {
				$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: dashboard_delete_promo] [Param: $accountID, $my_session_id, $promoID]");
			    die($error000);
				return;
			}

			$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$promoID = filter_var($promoID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

			// Execute Prepared Statement
			if (!$sql->execute()) {
				$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: dashboard_delete_promo] [Query: $qry]");
			    die($error000);
				return;
			}

			// Get SQL statement result
			$result = $sql->get_result();

			if ($result->num_rows > 0) {
				$row = array();
				$row = $result->fetch_assoc();
				$sql->close();

				if ($row['result'] == 'Success') {
					$this->unlink_image($row['image']);
				}

				return json_encode(array(array("response"=>$row['result'])));
			} else {
				$sql->close();
				return json_encode(array(array("response"=>"Error")));
				die();
			}
		}

		/********** Update Promo **********/
		public function update_promo($accountID, $my_session_id, $promoID, $name, $description, $image, $status) {
			include("php/api/push/push.class.php");
			$push = new push_notification;
			$mysqli = self::$tmp_mysqli;
			$logs = self::$tmp_logs;
			$file_name = self::$tmp_file_name;
			$error000 = self::$tmp_error000;
			$error400 = self::$tmp_error400;
			$error1329 = self::$tmp_error1329;
			$qry = "CALL dashboard_update_promo(?, ?, ?, ?, ?, ?, ?)";


			if (!isset($accountID) || (!$accountID)) {
				$logs->write_logs('Dashboard Update Promo', $file_name, "Bad Request - Invalid/Empty [accountID: $accountID]");
				die($error400);
				return;
			}

			if (!isset($my_session_id) || (!$my_session_id)) {
				$logs->write_logs('Dashboard Update Promo', $file_name, "Bad Request - Invalid/Empty [my_session_id: $my_session_id]");
				die($error400);
				return;
			}

			if (!isset($promoID) || (!$promoID)) {
				$logs->write_logs('Dashboard Update Promo', $file_name, "Bad Request - Invalid/Empty [promoID: $promoID]");
				die($error400);
				return;
			}


			if (!isset($name) || (!$name)) {
				$logs->write_logs('Dashboard Update Promo', $file_name, "Bad Request - Invalid/Empty [name: $name]");
				die($error400);
				return;
			}


			if (!isset($description) ) {
				$logs->write_logs('Dashboard Update Promo', $file_name, "Bad Request - Invalid/Empty [description: $description]");
				die($error400);
				return;
			}

			if (!isset($image) || (!$image)) { 
				$image = NULL;
			}

			if (!isset($status) || (!$status)) {
				$logs->write_logs('Dashboard Update Promo', $file_name, "Bad Request - Invalid/Empty [status: $status]");
				die($error400);
				return;
			}

			// Prepared statement, stage 1: prepare
			if (!($sql = $mysqli->prepare($qry))) {
				$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: dashboard_update_promo] [Query: $qry]");
			    die($error000);
				return;
			}

			if (!$sql->bind_param('sssssss', $accountID, $my_session_id, $promoID, $name, $description, $image, $status)) {
				$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: dashboard_update_promo] [Param: $accountID, $my_session_id, $promoID, $name, $description, $image, $status]");
			    die($error000);
				return;
			}

			$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$promoID = filter_var($promoID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$name = filter_var($name, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$description = filter_var($description, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

			if (!isset($image) || (!$image)) {
				$image = NULL;
			} else {
				$image = filter_var($image, FILTER_VALIDATE_URL, FILTER_FLAG_PATH_REQUIRED);
			}
			
			$status = filter_var($status, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

			// Execute Prepared Statement
			if (!$sql->execute()) {
				$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: dashboard_update_promo] [Query: $qry]");
			    die($error000);
				return;
			}


			// Get SQL statement result
			$result = $sql->get_result();


			if ($result->num_rows > 0) {
				$row = array();
				$row = $result->fetch_assoc();
				$sql->close();

				if ($row['result'] != 'Success') {
					$this->unlink_image($image);
				} else {
					if (isset($row['old_image'])) {
						if ($row['old_image'] != 'None') {
							$this->unlink_image($row['old_image']);
						}
					}
				}

				return json_encode(array(array("response"=>$row['result'])));

			} else {
				$sql->close();
				$this->unlink_image($image);
				return json_encode(array(array("response"=>"Error")));
				die();
			}
		}

		/********** Add Loyalty **********/
		public function add_loyalty($accountID, $my_session_id, $name, $points, $promo, $terms, $description, $status) {
			include("php/api/push/push.class.php");
			$push = new push_notification;
			$mysqli = self::$tmp_mysqli;
			$logs = self::$tmp_logs;
			$file_name = self::$tmp_file_name;
			$error000 = self::$tmp_error000;
			$error400 = self::$tmp_error400;
			$error1329 = self::$tmp_error1329;
			$qry = "CALL dashboard_add_loyalty(?, ?, ?, ?, ?, ?, ?, ?)";
 
			if (!isset($accountID) || (!$accountID)) {
				$logs->write_logs('Dashboard Add Loyalty', $file_name, "Bad Request - Invalid/Empty [accountID: $accountID]");
				die($error400);
				return;
			}

			if (!isset($my_session_id) || (!$my_session_id)) {
				$logs->write_logs('Dashboard Add Loyalty', $file_name, "Bad Request - Invalid/Empty [my_session_id: $my_session_id]");
				die($error400);
				return;
			}

			if (!isset($name) || (!$name)) {
				$logs->write_logs('Dashboard Add Loyalty', $file_name, "Bad Request - Invalid/Empty [name: $name]");
				die($error400);
				return;
			}

			if (!isset($points) ) {
				$logs->write_logs('Dashboard Add Loyalty', $file_name, "Bad Request - Invalid/Empty [points: $points]");
				die($error400);
				return;
			}

			if (!isset($promo) || (!$promo)) {
				$logs->write_logs('Dashboard Add Loyalty', $file_name, "Bad Request - Invalid/Empty [name: $promo]");
				die($error400);
				return;
			}

			if (!isset($terms) ) {
				$logs->write_logs('Dashboard Add Loyalty', $file_name, "Bad Request - Invalid/Empty [terms: $terms]");
				die($error400);
				return;
			}

			if (!isset($description) ) {
				$logs->write_logs('Dashboard Add Loyalty', $file_name, "Bad Request - Invalid/Empty [description: $description]");
				die($error400);
				return;
			}

			if (!isset($status) || (!$status)) {
				$logs->write_logs('Dashboard Add Loyalty', $file_name, "Bad Request - Invalid/Empty [status: $status]");
				die($error400);
				return;
			}
 
                
			// Prepared statement, stage 1: prepare
			if (!($sql = $mysqli->prepare($qry))) {
				$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: dashboard_add_loyalty] [Query: $qry]");
			    die($error000);
				return;
			}

			if (!$sql->bind_param('sssissss', $accountID, $my_session_id, $name, $points, $promo, $terms, $description, $status)) {
				$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: dashboard_add_loyalty] [Param: $accountID, $my_session_id, $name, $points, $promo, $terms, $description, $status]");
			    die($error000);
				return;
			}

			$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$name = filter_var($name, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$points = filter_var($points, FILTER_SANITIZE_NUMBER_INT);
			$promo = filter_var($promo, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$terms = filter_var($terms, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$description = filter_var($description, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$status = filter_var($status, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH); 

			// Execute Prepared Statement
			if (!$sql->execute()) {
				$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: dashboard_add_loyalty] [Query: $qry]");
			    die($error000);
				return;
			}

			// Get SQL statement result
			$result = $sql->get_result();

			if ($result->num_rows > 0) {
				$row = array();
				$row = $result->fetch_assoc();
				$sql->close();
				return json_encode(array(array("response"=>$row['result'])));
			} else {
				$sql->close();
				return json_encode(array(array("response"=>"Error")));
				die();
			}
		}

		/********** Delete Loyalty **********/
		public function delete_loyalty($accountID, $my_session_id, $loyaltyID) {
			include("php/api/push/push.class.php");
			$push = new push_notification;
			$mysqli = self::$tmp_mysqli;
			$logs = self::$tmp_logs;
			$file_name = self::$tmp_file_name;
			$error000 = self::$tmp_error000;
			$error400 = self::$tmp_error400;
			$error1329 = self::$tmp_error1329;
			$qry = "CALL dashboard_delete_loyalty(?, ?, ?)";

			if (!isset($accountID) || (!$accountID)) {
				$logs->write_logs('Dashboard Delete Loyalty', $file_name, "Bad Request - Invalid/Empty [accountID: $accountID]");
				die($error400);
				return;
			}

			if (!isset($my_session_id) || (!$my_session_id)) {
				$logs->write_logs('Dashboard Delete Loyalty', $file_name, "Bad Request - Invalid/Empty [my_session_id: $my_session_id]");
				die($error400);
				return;
			}

			if (!isset($loyaltyID) || (!$loyaltyID)) {
				$logs->write_logs('Dashboard Delete Loyalty', $file_name, "Bad Request - Invalid/Empty [loyaltyID: $loyaltyID]");
				die($error400);
				return;
			}

			// Prepared statement, stage 1: prepare
			if (!($sql = $mysqli->prepare($qry))) {
				$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: dashboard_delete_loyalty] [Query: $qry]");
			    die($error000);
				return;
			}

			if (!$sql->bind_param('sss', $accountID, $my_session_id, $loyaltyID)) {
				$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: dashboard_delete_loyalty] [Param: $accountID, $my_session_id, $loyaltyID]");
			    die($error000);
				return;
			}

			$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$loyaltyID = filter_var($loyaltyID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

			// Execute Prepared Statement
			if (!$sql->execute()) {
				$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: dashboard_delete_loyalty] [Query: $qry]");
			    die($error000);
				return;
			}

			// Get SQL statement result
			$result = $sql->get_result();

			if ($result->num_rows > 0) {
				$row = array();
				$row = $result->fetch_assoc();
				$sql->close();
				return json_encode(array(array("response"=>$row['result'])));
			} else {
				$sql->close();
				return json_encode(array(array("response"=>"Error")));
				die();
			}
		}

		/********** Update Loyalty **********/
		public function update_loyalty($accountID, $my_session_id, $loyaltyID, $name, $points, $promo, $terms, $description, $status) {
			include("php/api/push/push.class.php");
			$push = new push_notification;
			$mysqli = self::$tmp_mysqli;
			$logs = self::$tmp_logs;
			$file_name = self::$tmp_file_name;
			$error000 = self::$tmp_error000;
			$error400 = self::$tmp_error400;
			$error1329 = self::$tmp_error1329;
			$qry = "CALL dashboard_update_loyalty(?, ?, ?, ?, ?, ?, ?, ?, ?)";

			if (!isset($accountID) || (!$accountID)) {
				$logs->write_logs('Dashboard Update Loyalty', $file_name, "Bad Request - Invalid/Empty [accountID: $accountID]");
				die($error400);
				return;
			}

			if (!isset($my_session_id) || (!$my_session_id)) {
				$logs->write_logs('Dashboard Update Loyalty', $file_name, "Bad Request - Invalid/Empty [my_session_id: $my_session_id]");
				die($error400);
				return;
			}

			if (!isset($loyaltyID) || (!$loyaltyID)) {
				$logs->write_logs('Dashboard Update Loyalty', $file_name, "Bad Request - Invalid/Empty [loyaltyID: $loyaltyID]");
				die($error400);
				return;
			}

			if (!isset($name) || (!$name)) {
				$logs->write_logs('Dashboard Update Loyalty', $file_name, "Bad Request - Invalid/Empty [name: $name]");
				die($error400);
				return;
			}

			if (!isset($points) ) {
				$logs->write_logs('Dashboard Update Loyalty', $file_name, "Bad Request - Invalid/Empty [points: $points]");
				die($error400);
				return;
			}

			if (!isset($promo) || (!$promo)) {
				$logs->write_logs('Dashboard Add Loyalty', $file_name, "Bad Request - Invalid/Empty [name: $promo]");
				die($error400);
				return;
			}

			if (!isset($terms) ) {
				$logs->write_logs('Dashboard Update Loyalty', $file_name, "Bad Request - Invalid/Empty [terms: $terms]");
				die($error400);
				return;
			}

			if (!isset($description)  ) {
				$logs->write_logs('Dashboard Update Loyalty', $file_name, "Bad Request - Invalid/Empty [description: $description]");
				die($error400);
				return;
			}

			if (!isset($status) || (!$status)) {
				$logs->write_logs('Dashboard Update Loyalty', $file_name, "Bad Request - Invalid/Empty [status: $status]");
				die($error400);
				return;
			}
             
            

			// Prepared statement, stage 1: prepare
			if (!($sql = $mysqli->prepare($qry))) {
				$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: dashboard_update_loyalty] [Query: $qry]");
			    die($error000);
				return;
			}

			if (!$sql->bind_param('ssssissss', $accountID, $my_session_id, $loyaltyID, $name, $points, $promo, $terms, $description, $status)) {
				$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: dashboard_update_loyalty] [Param: $accountID, $my_session_id, $loyaltyID, $name, $points, $promo, $terms, $description, $status]");
			    die($error000);
				return;
			}

			$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$loyaltyID = filter_var($loyaltyID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$name = filter_var($name, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$points = filter_var($points, FILTER_SANITIZE_NUMBER_INT);
			$promo = filter_var($promo, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$terms = filter_var($terms, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$description = filter_var($description, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$status = filter_var($status, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

			// Execute Prepared Statement
			if (!$sql->execute()) {
				$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: dashboard_update_loyalty] [Query: $qry]");
			    die($error000);
				return;
			}

			// Get SQL statement result
			$result = $sql->get_result();

			if ($result->num_rows > 0) {
				$row = array();
				$row = $result->fetch_assoc();
				$sql->close();
				return json_encode(array(array("response"=>$row['result'])));
			} else {
				$sql->close();
				return json_encode(array(array("response"=>"Error")));
				die();
			}
		}

		
		/********** Add FAQ **********/
		public function add_faq($accountID, $my_session_id, $question, $answer, $status) {
			include("php/api/push/push.class.php");
			$push = new push_notification;
			$mysqli = self::$tmp_mysqli;
			$logs = self::$tmp_logs;
			$file_name = self::$tmp_file_name;
			$error000 = self::$tmp_error000;
			$error400 = self::$tmp_error400;
			$error1329 = self::$tmp_error1329;
			$qry = "CALL dashboard_add_faq(?, ?, ?, ?, ?)";
 
			if (!isset($accountID) || (!$accountID)) {
				$logs->write_logs('Dashboard Add FAQ', $file_name, "Bad Request - Invalid/Empty [accountID: $accountID]");
				die($error400);
				return;
			}

			if (!isset($my_session_id) || (!$my_session_id)) {
				$logs->write_logs('Dashboard Add FAQ', $file_name, "Bad Request - Invalid/Empty [my_session_id: $my_session_id]");
				die($error400);
				return;
			} 

			if (!isset($question) ) {
				$logs->write_logs('Dashboard Add FAQ', $file_name, "Bad Request - Invalid/Empty [question: $question]");
				die($error400);
				return;
			}

			if (!isset($answer) ) {
				$logs->write_logs('Dashboard Add FAQ', $file_name, "Bad Request - Invalid/Empty [answer: $answer]");
				die($error400);
				return;
			} 

			if (!isset($status) || (!$status)) {
				$logs->write_logs('Dashboard Add Voucher', $file_name, "Bad Request - Invalid/Empty [status: $status]");
				die($error400);
				return;
			}    

			// Prepared statement, stage 1: prepare
			if (!($sql = $mysqli->prepare($qry))) {
				$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: dashboard_add_faq] [Query: $qry]");
			    die($error000);
				return;
			}

			if (!$sql->bind_param('sssss', $accountID, $my_session_id,  $question, $answer, $status )) {
				$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: dashboard_add_faq] [Param:  $accountID, $my_session_id,  $question, $answer, $status]");
			    die($error000);
				return;
			}

			$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH); 
			$question = filter_var($question, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$answer = filter_var($answer, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH); 
			$status = filter_var($status, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);    

			// Execute Prepared Statement
			if (!$sql->execute()) {
				$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: dashboard_add_faq] [Query: $qry]");
			    die($error000);
				return;
			}

			// Get SQL statement result
			$result = $sql->get_result();

			if ($result->num_rows > 0) {
				$row = array();
				$row = $result->fetch_assoc();
				$sql->close();


				$logs->write_logs('Dashboard', $file_name, "\t [Procedure: dashboard_add_faq] [Param:  $accountID, $my_session_id,  $question, $answer, $status]");  

				return json_encode(array(array("response"=>$row['result'])));
			} else {
				$sql->close();
				return json_encode(array(array("response"=>"Error")));
				die();
			}
		} 

		/********** Update FAQ **********/
		public function update_faq($accountID, $my_session_id, $faqID, $question, $answer, $status) {
			include("php/api/push/push.class.php");
			$push = new push_notification;
			$mysqli = self::$tmp_mysqli;
			$logs = self::$tmp_logs;
			$file_name = self::$tmp_file_name;
			$error000 = self::$tmp_error000;
			$error400 = self::$tmp_error400;
			$error1329 = self::$tmp_error1329;
			$qry = "CALL dashboard_update_faq(?, ?, ?, ?, ?, ?)";

			if (!isset($accountID) || (!$accountID)) {
				$logs->write_logs('Dashboard Update FAQ', $file_name, "Bad Request - Invalid/Empty [accountID: $accountID]");
				die($error400);
				return;
			}

			if (!isset($my_session_id) || (!$my_session_id)) {
				$logs->write_logs('Dashboard Update FAQ', $file_name, "Bad Request - Invalid/Empty [my_session_id: $my_session_id]");
				die($error400);
				return;
			}

			if (!isset($faqID) || (!$faqID)) {
				$logs->write_logs('Dashboard Update FAQ', $file_name, "Bad Request - Invalid/Empty [faqID: $faqID]");
				die($error400);
				return;
			} 

			if (!isset($question) ) {
				$logs->write_logs('Dashboard Add FAQ', $file_name, "Bad Request - Invalid/Empty [question: $question]");
				die($error400);
				return;
			}

			if (!isset($answer) ) {
				$logs->write_logs('Dashboard Add FAQ', $file_name, "Bad Request - Invalid/Empty [answer: $answer]");
				die($error400);
				return;
			} 

			if (!isset($status) || (!$status)) {
				$logs->write_logs('Dashboard Add Voucher', $file_name, "Bad Request - Invalid/Empty [status: $status]");
				die($error400);
				return;
			} 

			// Prepared statement, stage 1: prepare
			if (!($sql = $mysqli->prepare($qry))) {
				$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: dashboard_update_faq] [Query: $qry]");
			    die($error000);
				return;
			}

			if (!$sql->bind_param('ssssss', $accountID, $my_session_id, $faqID,  $question, $answer, $status)) {
				$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: dashboard_update_faq] [Param: $accountID, $my_session_id, $faqID,  $question, $answer, $status]");
			    die($error000);
				return;
			}

			$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$faqID = filter_var($faqID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH); 
			$question = filter_var($question, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$answer = filter_var($answer, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);   
			$status = filter_var($status, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

			// Execute Prepared Statement
			if (!$sql->execute()) {
				$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: dashboard_update_faq] [Query: $qry]");
			    die($error000);
				return;
			}

			// Get SQL statement result
			$result = $sql->get_result();

			if ($result->num_rows > 0) {
				$row = array();
				$row = $result->fetch_assoc();
				$sql->close(); 

				$logs->write_logs('Dashboard', $file_name, "\t [Procedure: dashboard_update_faq] [Param: $accountID, $my_session_id, $faqID,  $question, $answer, $status]");
				
				return json_encode(array(array("response"=>$row['result'])));
			} else {
				$sql->close();
				return json_encode(array(array("response"=>"Error")));
				die();
			}
		}

 
		/********** Add Post **********/
		public function add_post($accountID, $my_session_id, $title, $description, $image, $status) {
			include("php/api/push/push.class.php");
			$push = new push_notification;
			$mysqli = self::$tmp_mysqli;
			$logs = self::$tmp_logs;
			$file_name = self::$tmp_file_name;
			$error000 = self::$tmp_error000;
			$error400 = self::$tmp_error400;
			$error1329 = self::$tmp_error1329;
			$qry = "CALL dashboard_add_post(?, ?, ?, ?, ?, ?)";

			if (!isset($accountID) || (!$accountID)) {
				$logs->write_logs('Dashboard Add Post', $file_name, "Bad Request - Invalid/Empty [accountID: $accountID]");
				die($error400);
				return;
			}

			if (!isset($my_session_id) || (!$my_session_id)) {
				$logs->write_logs('Dashboard Add Post', $file_name, "Bad Request - Invalid/Empty [my_session_id: $my_session_id]");
				die($error400);
				return;
			}

			if (!isset($title) || (!$title)) {
				$logs->write_logs('Dashboard Add Post', $file_name, "Bad Request - Invalid/Empty [title: $title]");
				die($error400);
				return;
			}

			if (!isset($description) ) {
				$logs->write_logs('Dashboard Add Post', $file_name, "Bad Request - Invalid/Empty [description: $description]");
				die($error400);
				return;
			}

			if (!isset($image) || (!$image)) {
				$logs->write_logs('Dashboard Add Post', $file_name, "Bad Request - Invalid/Empty [image: $image]");
				die($error400);
				return;
			}

			if (!isset($status) || (!$status)) {
				$logs->write_logs('Dashboard Add Post', $file_name, "Bad Request - Invalid/Empty [status: $status]");
				die($error400);
				return;
			}

			// Prepared statement, stage 1: prepare
			if (!($sql = $mysqli->prepare($qry))) {
				$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: dashboard_add_post] [Query: $qry]");
			    die($error000);
				return;
			}

			if (!$sql->bind_param('ssssss', $accountID, $my_session_id, $title, $description, $image, $status)) {
				$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: dashboard_add_post] [Param: $accountID, $my_session_id, $title, $description, $image, $status]");
			    die($error000);
				return;
			}

			$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$title = filter_var($title, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$description = filter_var($description, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$image = filter_var($image, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$status = filter_var($status, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

			// Execute Prepared Statement
			if (!$sql->execute()) {
				$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: dashboard_add_post] [Query: $qry]");
			    die($error000);
				return;
			}

			// Get SQL statement result
			$result = $sql->get_result();

			if ($result->num_rows > 0) {
				$row = array();
				$row = $result->fetch_assoc();
				$sql->close();

				if ($row['result'] != 'Success') {
					$this->unlink_image($image);
				}

				return json_encode(array(array("response"=>$row['result'])));
			} else {
				$sql->close();
				return json_encode(array(array("response"=>"Error")));
				die();
			}
		}

		/********** Delete Post **********/
		public function delete_post($accountID, $my_session_id, $postID) {
			include("php/api/push/push.class.php");
			$push = new push_notification;
			$mysqli = self::$tmp_mysqli;
			$logs = self::$tmp_logs;
			$file_name = self::$tmp_file_name;
			$error000 = self::$tmp_error000;
			$error400 = self::$tmp_error400;
			$error1329 = self::$tmp_error1329;
			$qry = "CALL dashboard_delete_post(?, ?, ?)";

			if (!isset($accountID) || (!$accountID)) {
				$logs->write_logs('Dashboard Delete Post', $file_name, "Bad Request - Invalid/Empty [accountID: $accountID]");
				die($error400);
				return;
			}

			if (!isset($my_session_id) || (!$my_session_id)) {
				$logs->write_logs('Dashboard Delete Post', $file_name, "Bad Request - Invalid/Empty [my_session_id: $my_session_id]");
				die($error400);
				return;
			}

			if (!isset($postID) || (!$postID)) {
				$logs->write_logs('Dashboard Delete Post', $file_name, "Bad Request - Invalid/Empty [postID: $postID]");
				die($error400);
				return;
			}

			// Prepared statement, stage 1: prepare
			if (!($sql = $mysqli->prepare($qry))) {
				$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: dashboard_delete_post] [Query: $qry]");
			    die($error000);
				return;
			}

			if (!$sql->bind_param('sss', $accountID, $my_session_id, $postID)) {
				$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: dashboard_delete_post] [Param: $accountID, $my_session_id, $postID]");
			    die($error000);
				return;
			}

			$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$postID = filter_var($postID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

			// Execute Prepared Statement
			if (!$sql->execute()) {
				$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: dashboard_delete_post] [Query: $qry]");
			    die($error000);
				return;
			}

			// Get SQL statement result
			$result = $sql->get_result();

			if ($result->num_rows > 0) {
				$row = array();
				$row = $result->fetch_assoc();
				$sql->close();

				if ($row['result'] == 'Success') {
					$this->unlink_image($row['image']);
				}

				return json_encode(array(array("response"=>$row['result'])));
			} else {
				$sql->close();
				return json_encode(array(array("response"=>"Error")));
				die();
			}
		}

		/********** Update Post **********/
		public function update_post($accountID, $my_session_id, $postID, $title, $description, $image, $status) {
			include("php/api/push/push.class.php");
			$push = new push_notification;
			$mysqli = self::$tmp_mysqli;
			$logs = self::$tmp_logs;
			$file_name = self::$tmp_file_name;
			$error000 = self::$tmp_error000;
			$error400 = self::$tmp_error400;
			$error1329 = self::$tmp_error1329;
			$qry = "CALL dashboard_update_post(?, ?, ?, ?, ?, ?, ?)";

			if (!isset($accountID) || (!$accountID)) {
				$logs->write_logs('Dashboard Update Post', $file_name, "Bad Request - Invalid/Empty [accountID: $accountID]");
				die($error400);
				return;
			}

			if (!isset($my_session_id) || (!$my_session_id)) {
				$logs->write_logs('Dashboard Update Post', $file_name, "Bad Request - Invalid/Empty [my_session_id: $my_session_id]");
				die($error400);
				return;
			}

			if (!isset($postID) || (!$postID)) {
				$logs->write_logs('Dashboard Update Post', $file_name, "Bad Request - Invalid/Empty [postID: $postID]");
				die($error400);
				return;
			}

			if (!isset($title) || (!$title)) {
				$logs->write_logs('Dashboard Update Post', $file_name, "Bad Request - Invalid/Empty [title: $title]");
				die($error400);
				return;
			}

			if (!isset($description) ) {
				$logs->write_logs('Dashboard Update Post', $file_name, "Bad Request - Invalid/Empty [description: $description]");
				die($error400);
				return;
			}

			if (!isset($image) || (!$image)) {
				// $logs->write_logs('Dashboard Update Post', $file_name, "Bad Request - Invalid/Empty [image: $image]");
				// die($error400);
				// return;
				$image = NULL;
			}

			if (!isset($status) || (!$status)) {
				$logs->write_logs('Dashboard Update Post', $file_name, "Bad Request - Invalid/Empty [status: $status]");
				die($error400);
				return;
			}

			// Prepared statement, stage 1: prepare
			if (!($sql = $mysqli->prepare($qry))) {
				$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: dashboard_update_post] [Query: $qry]");
			    die($error000);
				return;
			}

			if (!$sql->bind_param('sssssss', $accountID, $my_session_id, $postID, $title, $description, $image, $status)) {
				$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: dashboard_update_post] [Param: $accountID, $my_session_id, $postID, $title, $description, $image, $status]");
			    die($error000);
				return;
			}

			$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$postID = filter_var($postID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$title = filter_var($title, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$description = filter_var($description, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			// $image = filter_var($image, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

			if (!isset($image) || (!$image)) {
				$image = NULL;
			} else {
				$image = filter_var($image, FILTER_VALIDATE_URL, FILTER_FLAG_PATH_REQUIRED);
			}

			$status = filter_var($status, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

			// Execute Prepared Statement
			if (!$sql->execute()) {
				$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: dashboard_update_post] [Query: $qry]");
			    die($error000);
				return;
			}

			// Get SQL statement result
			$result = $sql->get_result();

			if ($result->num_rows > 0) {
				$row = array();
				$row = $result->fetch_assoc();
				$sql->close();

				if ($row['result'] != 'Success') {
					$this->unlink_image($image);
				} else {
					if (isset($row['old_image'])) {
						if ($row['old_image'] != 'None') {
							$this->unlink_image($row['old_image']);
						}
					}
				}

				return json_encode(array(array("response"=>$row['result'])));
			} else {
				$sql->close();
				return json_encode(array(array("response"=>"Error")));
				die();
			}
		}

		/********** Add Location **********/
		public function add_location($accountID, $my_session_id, $name, $address, $latitude, $longitude, $branch, $phone, $email, $hours, $status, $loyalty, $image) {
			include("php/api/push/push.class.php");
			$push = new push_notification;
			$mysqli = self::$tmp_mysqli;
			$logs = self::$tmp_logs;
			$file_name = self::$tmp_file_name;
			$error000 = self::$tmp_error000;
			$error400 = self::$tmp_error400;
			$error1329 = self::$tmp_error1329;
			$qry = "CALL dashboard_add_location(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

			if (!isset($accountID) || (!$accountID)) {
				$logs->write_logs('Dashboard Add Location', $file_name, "Bad Request - Invalid/Empty [accountID: $accountID]");
				die($error400);
				return;
			}

			if (!isset($my_session_id) || (!$my_session_id)) {
				$logs->write_logs('Dashboard Add Location', $file_name, "Bad Request - Invalid/Empty [my_session_id: $my_session_id]");
				die($error400);
				return;
			}

			if (!isset($name) || (!$name)) {
				$logs->write_logs('Dashboard Add Location', $file_name, "Bad Request - Invalid/Empty [name: $name]");
				die($error400);
				return;
			}

			if (!isset($address) || (!$address)) {
				$logs->write_logs('Dashboard Add Location', $file_name, "Bad Request - Invalid/Empty [address: $address]");
				die($error400);
				return;
			}

			if (!isset($latitude) ) {
				$logs->write_logs('Dashboard Add Location', $file_name, "Bad Request - Invalid/Empty [latitude: $latitude]");
				die($error400);
				return;
			}

			if (!isset($longitude) ) {
				$logs->write_logs('Dashboard Add Location', $file_name, "Bad Request - Invalid/Empty [longitude: $longitude]");
				die($error400);
				return;
			}

			if (!isset($branch) ) {
				$logs->write_logs('Dashboard Add Location', $file_name, "Bad Request - Invalid/Empty [branch: $branch]");
				die($error400);
				return;
			}

			if (!isset($phone) ) {
				$logs->write_logs('Dashboard Add Location', $file_name, "Bad Request - Invalid/Empty [phone: $phone]");
				die($error400);
				return;
			}

			if (!isset($email) ) {
				$logs->write_logs('Dashboard Add Location', $file_name, "Bad Request - Invalid/Empty [email: $email]");
				die($error400);
				return;
			}


			if (!isset($status) || (!$status)) {
				$logs->write_logs('Dashboard Add Location', $file_name, "Bad Request - Invalid/Empty [status: $status]");
				die($error400);
				return;
			}

			if (!isset($loyalty) || (!$loyalty)) {
				$logs->write_logs('Dashboard Add Location', $file_name, "Bad Request - Invalid/Empty [loyalty: $loyalty]");
				die($error400);
				return;
			} 

			// Prepared statement, stage 1: prepare
			if (!($sql = $mysqli->prepare($qry))) {
				$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: dashboard_add_location] [Query: $qry]");
			    die($error000);
				return;
			}

			if (!$sql->bind_param('sssssssssssss', $accountID, $my_session_id, $name, $address, $latitude, $longitude, $branch, $phone, $email, $hours, $status, $loyalty, $image)) {
				$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: dashboard_add_location] [Param: $accountID, $my_session_id, $name, $address, $latitude, $longitude, $branch, $phone, $email, $hours, $status, $loyalty, $image]");
			    die($error000);
				return;
			}

			$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$name = filter_var($name, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$address = filter_var($address, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$latitude = filter_var($latitude, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$longitude = filter_var($longitude, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$branch = filter_var($branch, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$phone = filter_var($phone, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$email = filter_var($email, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$hours = filter_var($hours, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$status = filter_var($status, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$loyalty = filter_var($loyalty, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH); 

			if (!isset($image) || (!$image)) {
				$image = NULL;
			} else {
				$image = filter_var($image, FILTER_VALIDATE_URL, FILTER_FLAG_PATH_REQUIRED);
			}
			
 
			// Execute Prepared Statement
			if (!$sql->execute()) {
				$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: dashboard_add_location] [Query: $qry]");
			    die($error000);
				return;
			}

			// Get SQL statement result
			$result = $sql->get_result();

			if ($result->num_rows > 0) {
				$row = array();
				$row = $result->fetch_assoc();
				$sql->close(); 


				$logs->write_logs('Dashboard', $file_name, "\t [Procedure: dashboard_add_location] [Param: $accountID, $my_session_id, $name, $address, $latitude, $longitude, $branch, $phone, $email, $hours, $status, $loyalty, $image]");


				return json_encode(array(array("response"=>$row['result'])));
			} else {
				$sql->close();
				return json_encode(array(array("response"=>"Error")));
				die();
			}
		}

		/********** Update Location **********/
		public function update_location($accountID, $my_session_id, $locID, $name, $address, $latitude, $longitude, $branch, $phone, $email, $hours, $status, $loyalty, $image) {
			include("php/api/push/push.class.php");
			$push = new push_notification;
			$mysqli = self::$tmp_mysqli;
			$logs = self::$tmp_logs;
			$file_name = self::$tmp_file_name;
			$error000 = self::$tmp_error000;
			$error400 = self::$tmp_error400;
			$error1329 = self::$tmp_error1329;
			$qry = "CALL dashboard_update_location(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

			if (!isset($accountID) || (!$accountID)) {
				$logs->write_logs('Dashboard Add Location', $file_name, "Bad Request - Invalid/Empty [accountID: $accountID]");
				die($error400);
				return;
			}

			if (!isset($my_session_id) || (!$my_session_id)) {
				$logs->write_logs('Dashboard Add Location', $file_name, "Bad Request - Invalid/Empty [my_session_id: $my_session_id]");
				die($error400);
				return;
			}

			if (!isset($locID) || (!$locID)) {
				$logs->write_logs('Dashboard Add Location', $file_name, "Bad Request - Invalid/Empty [locID: $locID]");
				die($error400);
				return;
			}

			if (!isset($name) || (!$name)) {
				$logs->write_logs('Dashboard Add Location', $file_name, "Bad Request - Invalid/Empty [name: $name]");
				die($error400);
				return;
			}

			if (!isset($address) || (!$address)) {
				$logs->write_logs('Dashboard Add Location', $file_name, "Bad Request - Invalid/Empty [address: $address]");
				die($error400);
				return;
			}

			if (!isset($latitude) ) {
				$logs->write_logs('Dashboard Add Location', $file_name, "Bad Request - Invalid/Empty [latitude: $latitude]");
				die($error400);
				return;
			}

			if (!isset($longitude) ) {
				$logs->write_logs('Dashboard Add Location', $file_name, "Bad Request - Invalid/Empty [longitude: $longitude]");
				die($error400);
				return;
			}

			if (!isset($branch)  ) {
				$logs->write_logs('Dashboard Add Location', $file_name, "Bad Request - Invalid/Empty [branch: $branch]");
				die($error400);
				return;
			}

			if (!isset($phone)  ) {
				$logs->write_logs('Dashboard Add Location', $file_name, "Bad Request - Invalid/Empty [phone: $phone]");
				die($error400);
				return;
			}

			if (!isset($email) ) {
				$logs->write_logs('Dashboard Add Location', $file_name, "Bad Request - Invalid/Empty [email: $email]");
				die($error400);
				return;
			}

			if (!isset($hours) ) {
				$logs->write_logs('Dashboard Add Location', $file_name, "Bad Request - Invalid/Empty [hours: $hours]");
				die($error400);
				return;
			}


			if (!isset($status) || (!$status)) {
				$logs->write_logs('Dashboard Add Location', $file_name, "Bad Request - Invalid/Empty [status: $status]");
				die($error400);
				return;
			}

			if (!isset($loyalty) || (!$loyalty)) {
				$logs->write_logs('Dashboard Add Location', $file_name, "Bad Request - Invalid/Empty [loyalty: $loyalty]");
				die($error400);
				return;
			} 
			
			// Prepared statement, stage 1: prepare
			if (!($sql = $mysqli->prepare($qry))) {
				$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: dashboard_update_location] [Query: $qry]");
			    die($error000);
				return;
			}

			if (!$sql->bind_param('ssssssssssssss', $accountID, $my_session_id, $locID, $name, $address, $latitude, $longitude, $branch, $phone, $email, $hours, $status, $loyalty, $image )) {
				$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: dashboard_update_location] [Param: $accountID, $my_session_id, $locID, $name, $address, $latitude, $longitude, $branch, $phone, $email, $hours, $status, $loyalty, $image ]");
			    die($error000);
				return;
			}

			$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$locID = filter_var($locID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$name = filter_var($name, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$address = filter_var($address, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$latitude = filter_var($latitude, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$longitude = filter_var($longitude, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$branch = filter_var($branch, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$phone = filter_var($phone, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$email = filter_var($email, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$hours = filter_var($hours, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$status = filter_var($status, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$loyalty = filter_var($loyalty, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH); 


			if (!isset($image) || (!$image)) {
				$image = NULL;
			} else {
				$image = filter_var($image, FILTER_VALIDATE_URL, FILTER_FLAG_PATH_REQUIRED);
			}
			

			// Execute Prepared Statement
			if (!$sql->execute()) {
				$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: dashboard_update_location] [Query: $qry]");
			    die($error000);
				return;
			}

			// Get SQL statement result
			$result = $sql->get_result();

			if ($result->num_rows > 0) {
				$row = array();
				$row = $result->fetch_assoc();
				$sql->close(); 

				if ($row['result'] != 'Success') {
					$this->unlink_image($image);
				} else {
					if (isset($row['old_image'])) {
						if ($row['old_image'] != 'None') {
							$this->unlink_image($row['old_image']);
						}
					}
				}


				$logs->write_logs('Dashboard', $file_name, "\t [Procedure: dashboard_update_location] [Param: $accountID, $my_session_id, $locID, $name, $address, $latitude, $longitude, $branch, $phone, $email, $hours, $status, $loyalty, $image ]");

				return json_encode(array(array("response"=>$row['result'])));
			} else {
				$sql->close();
				return json_encode(array(array("response"=>"Error")));
				die();
			}
		}

		/********** Update Location **********/
		public function update_locPoints($accountID, $my_session_id, $locID,  $points) {
			include("php/api/push/push.class.php");
			$push = new push_notification;
			$mysqli = self::$tmp_mysqli;
			$logs = self::$tmp_logs;
			$file_name = self::$tmp_file_name;
			$error000 = self::$tmp_error000;
			$error400 = self::$tmp_error400;
			$error1329 = self::$tmp_error1329;
			$qry = "CALL dashboard_update_locPoints(?, ?, ?, ?)";

			if (!isset($accountID) || (!$accountID)) {
				$logs->write_logs('Dashboard Add Location', $file_name, "Bad Request - Invalid/Empty [accountID: $accountID]");
				die($error400);
				return;
			}

			if (!isset($my_session_id) || (!$my_session_id)) {
				$logs->write_logs('Dashboard Add Location', $file_name, "Bad Request - Invalid/Empty [my_session_id: $my_session_id]");
				die($error400);
				return;
			}

			if (!isset($locID) || (!$locID)) {
				$logs->write_logs('Dashboard Add Location', $file_name, "Bad Request - Invalid/Empty [locID: $locID]");
				die($error400);
				return;
			}

			if (!isset($points) ) {
				$logs->write_logs('Dashboard Add Location', $file_name, "Bad Request - Invalid/Empty [points: $points]");
				die($error400);
				return;
			}
			
			// Prepared statement, stage 1: prepare
			if (!($sql = $mysqli->prepare($qry))) {
				$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: dashboard_update_locPoints] [Query: $qry]");
			    die($error000);
				return;
			}

			if (!$sql->bind_param('ssss', $accountID, $my_session_id, $locID, $points)) {
				$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: dashboard_update_locPoints] [Param: $accountID, $my_session_id, $locID, $points]");
			    die($error000);
				return;
			}

			$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$locID = filter_var($locID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$points = filter_var($points, FILTER_VALIDATE_FLOAT);

			// Execute Prepared Statement
			if (!$sql->execute()) {
				$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: dashboard_update_locPoints] [Query: $qry]");
			    die($error000);
				return;
			}

			// Get SQL statement result
			$result = $sql->get_result();

			if ($result->num_rows > 0) {
				$row = array();
				$row = $result->fetch_assoc();
				$sql->close();

				return json_encode(array(array("response"=>$row['result'])));
			} else {
				$sql->close();
				return json_encode(array(array("response"=>"Error")));
				die();
			}
		}


		/********** Add Product **********/
		public function add_product($accountID, $my_session_id, $category, $name, $description, $price, $image, $status) {
			include("php/api/push/push.class.php");
			$push = new push_notification;
			$mysqli = self::$tmp_mysqli;
			$logs = self::$tmp_logs;
			$file_name = self::$tmp_file_name;
			$error000 = self::$tmp_error000;
			$error400 = self::$tmp_error400;
			$error1329 = self::$tmp_error1329;
			$qry = "CALL dashboard_add_product(?, ?, ?, ?, ?, ?, ?, ?)";

			if (!isset($accountID) || (!$accountID)) {
				$logs->write_logs('Dashboard Add Product', $file_name, "Bad Request - Invalid/Empty [accountID: $accountID]");
				die($error400);
				return;
			}

			if (!isset($my_session_id) || (!$my_session_id)) {
				$logs->write_logs('Dashboard Add Product', $file_name, "Bad Request - Invalid/Empty [my_session_id: $my_session_id]");
				die($error400);
				return;
			}

			if (!isset($category)  ) {
				$logs->write_logs('Dashboard Add Product', $file_name, "Bad Request - Invalid/Empty [category: $category]");
				die($error400);
				return;
			}

			if (!isset($name) ) {
				$logs->write_logs('Dashboard Add Product', $file_name, "Bad Request - Invalid/Empty [name: $name]");
				die($error400);
				return;
			}

			if (!isset($description) ) {
				$logs->write_logs('Dashboard Add Product', $file_name, "Bad Request - Invalid/Empty [description: $description]");
				die($error400);
				return;
			}

			if (!isset($price) ) {
				$logs->write_logs('Dashboard Add Product', $file_name, "Bad Request - Invalid/Empty  asdas [price: $price]");
				die($error400);
				return;
			}
 

			if (!isset($image) || (!$image)) {
				$logs->write_logs('Dashboard Add Product', $file_name, "Bad Request - Invalid/Empty [image: $image]");
				die($error400);
				return;
			}

			if (!isset($status) || (!$status)) {
				$logs->write_logs('Dashboard Add Product', $file_name, "Bad Request - Invalid/Empty [status: $status]");
				die($error400);
				return;
			}

			// Prepared statement, stage 1: prepare
			if (!($sql = $mysqli->prepare($qry))) {
				$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: dashboard_add_product] [Query: $qry]");
			    die($error000);
				return;
			}

			if (!$sql->bind_param('ssssssss', $accountID, $my_session_id, $category, $name, $description, $price, $image, $status)) {
				$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: dashboard_add_product] [Param: $accountID, $my_session_id, $category, $name, $description, $price, $image, $status]");
			    die($error000);
				return;
			}

			$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$category = filter_var($category, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$name = filter_var($name, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$description = filter_var($description, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$price = filter_var($price, FILTER_VALIDATE_FLOAT);
			$image = filter_var($image, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$status = filter_var($status, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

			// Execute Prepared Statement
			if (!$sql->execute()) {
				$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: dashboard_add_product] [Query: $qry]");
			    die($error000);
				return;
			}

			// Get SQL statement result
			$result = $sql->get_result();

			if ($result->num_rows > 0) {
				$row = array();
				$row = $result->fetch_assoc();
				$sql->close();

				if ($row['result'] != 'Success') {
					$this->unlink_image($image);
				}

				$logs->write_logs('Dashboard', $file_name, "\t [Procedure: dashboard_add_product] [Param: $accountID, $my_session_id, $category, $name, $description, $price, $image, $status]");  

				return json_encode(array(array("response"=>$row['result'])));
			} else {
				$sql->close();
				return json_encode(array(array("response"=>"Error")));
				die();
			}
		}


		 

		/********** Update Product **********/
		public function update_product($accountID, $my_session_id, $prodID, $category, $name, $description, $price, $image, $status) {
			include("php/api/push/push.class.php");
			$push = new push_notification;
			$mysqli = self::$tmp_mysqli;
			$logs = self::$tmp_logs;
			$file_name = self::$tmp_file_name;
			$error000 = self::$tmp_error000;
			$error400 = self::$tmp_error400;
			$error1329 = self::$tmp_error1329;
			$qry = "CALL dashboard_update_product(?, ?, ?, ?, ?, ?, ?, ?, ?)";

			if (!isset($accountID) || (!$accountID)) {
				$logs->write_logs('Dashboard Update Product', $file_name, "Bad Request - Invalid/Empty [accountID: $accountID]");
				die($error400);
				return;
			}

			if (!isset($my_session_id) || (!$my_session_id)) {
				$logs->write_logs('Dashboard Update Product', $file_name, "Bad Request - Invalid/Empty [my_session_id: $my_session_id]");
				die($error400);
				return;
			}

			if (!isset($prodID) || (!$prodID)) {
				$logs->write_logs('Dashboard Update Product', $file_name, "Bad Request - Invalid/Empty [prodID: $prodID]");
				die($error400);
				return;
			}

			if (!isset($category) ) {
				$logs->write_logs('Dashboard Update Product', $file_name, "Bad Request - Invalid/Empty [category: $category]");
				die($error400);
				return;
			}

			if (!isset($name) || (!$name)) {
				$logs->write_logs('Dashboard Update Product', $file_name, "Bad Request - Invalid/Empty [name: $name]");
				die($error400);
				return;
			}

			if (!isset($description) ) {
				$logs->write_logs('Dashboard Update Product', $file_name, "Bad Request - Invalid/Empty [description: $description]");
				die($error400);
				return;
			}

			if (!isset($price) ) {
				$logs->write_logs('Dashboard Update Product', $file_name, "Bad Request - Invalid/Empty [price: $price]");
				die($error400);
				return;
			}

			if (!isset($image) || (!$image)) {
				// $logs->write_logs('Dashboard Update Product', $file_name, "Bad Request - Invalid/Empty [image: $image]");
				// die($error400);
				// return;
				$image = NULL;
			}

			if (!isset($status) || (!$status)) {
				$logs->write_logs('Dashboard Update Product', $file_name, "Bad Request - Invalid/Empty [status: $status]");
				die($error400);
				return;
			}
			
			// Prepared statement, stage 1: prepare
			if (!($sql = $mysqli->prepare($qry))) {
				$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: dashboard_update_product] [Query: $qry]");
			    die($error000);
				return;
			}

			if (!$sql->bind_param('sssssssss', $accountID, $my_session_id, $prodID, $category, $name, $description, $price, $image, $status)) {
				$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: dashboard_update_product] [Param: $accountID, $my_session_id, $prodID, $category, $name, $description, $price, $image, $status]");
			    die($error000);
				return;
			}

			$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$prodID = filter_var($prodID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$category = filter_var($category, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$name = filter_var($name, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$description = filter_var($description, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$price = filter_var($price, FILTER_VALIDATE_FLOAT);
			// $image = filter_var($image, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);


			if (!isset($image) || (!$image)) {
				$image = NULL;
			} else {
				$image = filter_var($image, FILTER_VALIDATE_URL, FILTER_FLAG_PATH_REQUIRED);
			}
			
			$status = filter_var($status, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

			// Execute Prepared Statement
			if (!$sql->execute()) {
				$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: dashboard_update_product] [Query: $qry]");
			    die($error000);
				return;
			}

			// Get SQL statement result
			$result = $sql->get_result();

			if ($result->num_rows > 0) {
				$row = array();
				$row = $result->fetch_assoc();
				$sql->close();

				if ($row['result'] != 'Success') {
					$this->unlink_image($image);
				} else {
					if (isset($row['old_image'])) {
						if ($row['old_image'] != 'None') {
							$this->unlink_image($row['old_image']);
						}
					}
				}


				$logs->write_logs('Dashboard', $file_name, "\t [Procedure: dashboard_update_product] [Param: $accountID, $my_session_id, $prodID, $category, $name, $description, $price, $image, $status]");  

				return json_encode(array(array("response"=>$row['result'])));
			} else {
				$sql->close();
				return json_encode(array(array("response"=>"Error")));
				die();
			}
		}



		/********** Add Card Design **********/
		public function add_carddesign($accountID, $my_session_id,  $name,  $image, $status) {
			include("php/api/push/push.class.php");
			$push = new push_notification;
			$mysqli = self::$tmp_mysqli;
			$logs = self::$tmp_logs;
			$file_name = self::$tmp_file_name;
			$error000 = self::$tmp_error000;
			$error400 = self::$tmp_error400;
			$error1329 = self::$tmp_error1329;
			$qry = "CALL dashboard_add_carddesign(?, ?, ?, ?, ?)";

			if (!isset($accountID) || (!$accountID)) {
				$logs->write_logs('Dashboard Add carddesign', $file_name, "Bad Request - Invalid/Empty [accountID: $accountID]");
				die($error400);
				return;
			}

			if (!isset($my_session_id) || (!$my_session_id)) {
				$logs->write_logs('Dashboard Add carddesign', $file_name, "Bad Request - Invalid/Empty [my_session_id: $my_session_id]");
				die($error400);
				return;
			}

			if (!isset($name) ) {
				$logs->write_logs('Dashboard Add carddesign', $file_name, "Bad Request - Invalid/Empty [name: $name]");
				die($error400);
				return;
			}

			if (!isset($image) ) {
				$logs->write_logs('Dashboard Add carddesign', $file_name, "Bad Request - Invalid/Empty [image: $image]");
				die($error400);
				return;
			}

			if (!isset($status) || (!$status)) {
				$logs->write_logs('Dashboard Add carddesign', $file_name, "Bad Request - Invalid/Empty [status: $status]");
				die($error400);
				return;
			}

			// Prepared statement, stage 1: prepare
			if (!($sql = $mysqli->prepare($qry))) {
				$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: dashboard_add_carddesign] [Query: $qry]");
			    die($error000);
				return;
			}

			if (!$sql->bind_param('sssss', $accountID, $my_session_id, $name, $image, $status)) {
				$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: dashboard_add_carddesign] [Param: $accountID, $my_session_id, $name, $image, $status]");
			    die($error000);
				return;
			}

			$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH); 
			$name = filter_var($name, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH); 
			$image = filter_var($image, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$status = filter_var($status, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

			// Execute Prepared Statement
			if (!$sql->execute()) {
				$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: dashboard_add_carddesign] [Query: $qry]");
			    die($error000);
				return;
			}

			// Get SQL statement result
			$result = $sql->get_result();

			if ($result->num_rows > 0) {
				$row = array();
				$row = $result->fetch_assoc();
				$sql->close();

				if ($row['result'] != 'Success') {
					$this->unlink_image($image);
				}

				$logs->write_logs('Dashboard', $file_name, "\t [Procedure: dashboard_add_carddesign] [Param: $accountID, $my_session_id, $name, $image, $status]");  

				return json_encode(array(array("response"=>$row['result'])));
			} else {
				$sql->close();
				return json_encode(array(array("response"=>"Error")));
				die();
			}
		}


		 

		/********** Update carddesign **********/
		public function update_carddesign($accountID, $my_session_id, $cardID, $name, $image, $status) {
			include("php/api/push/push.class.php");
			$push = new push_notification;
			$mysqli = self::$tmp_mysqli;
			$logs = self::$tmp_logs;
			$file_name = self::$tmp_file_name;
			$error000 = self::$tmp_error000;
			$error400 = self::$tmp_error400;
			$error1329 = self::$tmp_error1329;
			$qry = "CALL dashboard_update_carddesign(?, ?, ?, ?, ?, ?)";

			if (!isset($accountID) || (!$accountID)) {
				$logs->write_logs('Dashboard Update carddesign', $file_name, "Bad Request - Invalid/Empty [accountID: $accountID]");
				die($error400);
				return;
			}

			if (!isset($my_session_id) || (!$my_session_id)) {
				$logs->write_logs('Dashboard Update carddesign', $file_name, "Bad Request - Invalid/Empty [my_session_id: $my_session_id]");
				die($error400);
				return;
			}

			if (!isset($cardID) || (!$cardID)) {
				$logs->write_logs('Dashboard Update carddesign', $file_name, "Bad Request - Invalid/Empty [cardID: $cardID]");
				die($error400);
				return;
			} 

			if (!isset($name) || (!$name)) {
				$logs->write_logs('Dashboard Update carddesign', $file_name, "Bad Request - Invalid/Empty [name: $name]");
				die($error400);
				return;
			} 

			if (!isset($image) || (!$image)) { 
				$image = NULL;
			}

			if (!isset($status) || (!$status)) {
				$logs->write_logs('Dashboard Update carddesign', $file_name, "Bad Request - Invalid/Empty [status: $status]");
				die($error400);
				return;
			}

			$imgsource = "http://10.23.203.9/project/starbucks/assets/images/carddesign/".$name;
			if (file_exists($imgsource)){
			   unlink($source);
			} 

			// echo "CALL dashboard_update_carddesign('$accountID', '$my_session_id', '$cardID', '$name', '$image', '$status')";
			// Prepared statement, stage 1: prepare
			if (!($sql = $mysqli->prepare($qry))) {
				$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: dashboard_update_carddesign] [Query: $qry]");
			    die($error000);
				return;
			}

			if (!$sql->bind_param('ssssss', $accountID, $my_session_id, $cardID, $name, $image, $status)) {
				$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: dashboard_update_carddesign] [Param: $accountID, $my_session_id, $cardID, $name, $image, $status]");
			    die($error000);
				return;
			}

			$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$cardID = filter_var($cardID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH); 
			$name = filter_var($name, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH); 


			if (!isset($image) || (!$image)) {
				$image = NULL;
			} else {
				$image = filter_var($image, FILTER_VALIDATE_URL, FILTER_FLAG_PATH_REQUIRED);
			}
			
			$status = filter_var($status, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

			// Execute Prepared Statement
			if (!$sql->execute()) {
				$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: dashboard_update_carddesign] [Query: $qry]");
			    die($error000);
				return;
			}

			// Get SQL statement result
			$result = $sql->get_result();

			if ($result->num_rows > 0) {
				$row = array();
				$row = $result->fetch_assoc();
				$sql->close(); 


				$logs->write_logs('Dashboard', $file_name, "\t [Procedure: dashboard_update_carddesign] [Param: $accountID, $my_session_id, $cardID, $name, $image, $status]");

				return json_encode(array(array("response"=>$row['result'])));
			} else {
				$sql->close();
				return json_encode(array(array("response"=>"Error")));
				die();
			}
		}



		/********** Add Level **********/
		public function add_level($accountID, $my_session_id, $name, $level, $min, $max, $status, $qoute) {
			include("php/api/push/push.class.php");
			$push = new push_notification;
			$mysqli = self::$tmp_mysqli;
			$logs = self::$tmp_logs;
			$file_name = self::$tmp_file_name;
			$error000 = self::$tmp_error000;
			$error400 = self::$tmp_error400;
			$error1329 = self::$tmp_error1329;
			$qry = "CALL dashboard_add_level(?, ?, ?, ?, ?, ?, ?, ?)";

			if (!isset($accountID) || (!$accountID)) {
				$logs->write_logs('Dashboard Add Level', $file_name, "Bad Request - Invalid/Empty [accountID: $accountID]");
				die($error400);
				return;
			}

			if (!isset($my_session_id) || (!$my_session_id)) {
				$logs->write_logs('Dashboard Add Level', $file_name, "Bad Request - Invalid/Empty [my_session_id: $my_session_id]");
				die($error400);
				return;
			}

			if (!isset($name) || ($name == NULL)) {
				$logs->write_logs('Dashboard Add Level', $file_name, "Bad Request - Invalid/Empty [name: $name]");
				die($error400);
				return;
			} 

			if (!isset($level) || ($level == NULL)) {
				$logs->write_logs('Dashboard Add Level', $file_name, "Bad Request - Invalid/Empty [level: $level]");
				die($error400);
				return;
			}

			if (!isset($min)  || ($min == NULL)) {
				$logs->write_logs('Dashboard Add Level', $file_name, "Bad Request - Invalid/Empty [min: $min]");
				die($error400);
				return;
			}

			if (!isset($max) || ($max == NULL) ) {
				$logs->write_logs('Dashboard Add Level', $file_name, "Bad Request - Invalid/Empty [max: $max]");
				die($error400);
				return;
			}

			if (!isset($status) || (!$status)) {
				$logs->write_logs('Dashboard Add Level', $file_name, "Bad Request - Invalid/Empty [status: $status]");
				die($error400);
				return;
			}

			if (!isset($qoute) ) {
				$logs->write_logs('Dashboard Add Level', $file_name, "Bad Request - Invalid/Empty [qoute: $qoute]");
				die($error400);
				return;
			} 

			// Prepared statement, stage 1: prepare
			if (!($sql = $mysqli->prepare($qry))) {
				$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: dashboard_add_level] [Query: $qry]");
			    die($error000);
				return;
			}

			if (!$sql->bind_param('ssssssss', $accountID, $my_session_id, $name, $level, $min, $max, $status, $qoute)) {
				$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: dashboard_add_level] [Param: $accountID, $my_session_id, $name, $level, $min, $max, $status, $qoute]");
			    die($error000);
				return;
			}

			$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$name = filter_var($name, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$level = filter_var($level, FILTER_SANITIZE_NUMBER_INT);
			$min = filter_var($min, FILTER_SANITIZE_NUMBER_INT);
			$max = filter_var($max, FILTER_SANITIZE_NUMBER_INT);
			$status = filter_var($status, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH); 
			$qoute = filter_var($qoute, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

			// Execute Prepared Statement
			if (!$sql->execute()) {
				$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: dashboard_add_level] [Query: $qry]");
			    die($error000);
				return;
			}

			// Get SQL statement result
			$result = $sql->get_result();

			if ($result->num_rows > 0) {
				$row = array();
				$row = $result->fetch_assoc();
				$sql->close();

				if ($row['result'] != 'Success') {
					$this->unlink_image($image);
				}


				$logs->write_logs('Dashboard', $file_name, "\t [Procedure: dashboard_add_level] [Param: $accountID, $my_session_id, $name, $level, $min, $max, $status, $qoute]");

				return json_encode(array(array("response"=>$row['result'])));
			} else {
				$sql->close();
				return json_encode(array(array("response"=>"Error")));
				die();
			}

		}


		/********** Update Level **********/
		public function update_level($accountID, $my_session_id, $levelID, $name, $level, $min, $max, $status, $qoute) {
			include("php/api/push/push.class.php");
			$push = new push_notification;
			$mysqli = self::$tmp_mysqli;
			$logs = self::$tmp_logs;
			$file_name = self::$tmp_file_name;
			$error000 = self::$tmp_error000;
			$error400 = self::$tmp_error400;
			$error1329 = self::$tmp_error1329;
			$qry = "CALL dashboard_update_level(?, ?, ?, ?, ?, ?, ?, ?, ?)";

			if (!isset($accountID) || (!$accountID)) {
				$logs->write_logs('Dashboard Update Location', $file_name, "Bad Request - Invalid/Empty [accountID: $accountID]");
				die($error400);
				return;
			}

			if (!isset($my_session_id) || (!$my_session_id)) {
				$logs->write_logs('Dashboard Update Location', $file_name, "Bad Request - Invalid/Empty [my_session_id: $my_session_id]");
				die($error400);
				return;
			}

			if (!isset($levelID) || (!$levelID)) {
				$logs->write_logs('Dashboard Update Location', $file_name, "Bad Request - Invalid/Empty [levelID: $levelID]");
				die($error400);
				return;
			}

			if (!isset($name) || ($name == NULL)) {
				$logs->write_logs('Dashboard Update Level', $file_name, "Bad Request - Invalid/Empty [name: $name]");
				die($error400);
				return;
			} 

			if (!isset($level) || ($level == NULL)) {
				$logs->write_logs('Dashboard Update Level', $file_name, "Bad Request - Invalid/Empty [level: $level]");
				die($error400);
				return;
			}

			if (!isset($min) || ($min == NULL)) {
				$logs->write_logs('Dashboard Update Level', $file_name, "Bad Request - Invalid/Empty [min: $min]");
				die($error400);
				return;
			}

			if (!isset($max) || ($max  == NULL)) {
				$logs->write_logs('Dashboard Update Level', $file_name, "Bad Request - Invalid/Empty [max: $max]");
				die($error400);
				return;
			}

			if (!isset($status) || (!$status)) {
				$logs->write_logs('Dashboard Update Level', $file_name, "Bad Request - Invalid/Empty [status: $status]");
				die($error400);
				return;
			}

			if (!isset($qoute) ) {
				$logs->write_logs('Dashboard Add Level', $file_name, "Bad Request - Invalid/Empty [qoute: $qoute]");
				die($error400);
				return;
			} 

			// Prepared statement, stage 1: prepare
			if (!($sql = $mysqli->prepare($qry))) {
				$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: dashboard_update_location] [Query: $qry]");
			    die($error000);
				return;
			}

			if (!$sql->bind_param('sssssssss', $accountID, $my_session_id, $levelID, $name, $level, $min, $max, $status, $qoute)) {
				$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: dashboard_add_level] [Param: $accountID, $my_session_id, $levelID, $name, $level, $min, $max, $status, $qoute]");
			    die($error000);
				return;
			}

			$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$levelID = filter_var($levelID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$name = filter_var($name, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$level = filter_var($level, FILTER_SANITIZE_NUMBER_INT);
			$min = filter_var($min, FILTER_SANITIZE_NUMBER_INT);
			$max = filter_var($max, FILTER_SANITIZE_NUMBER_INT);
			$status = filter_var($status, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH); 
			$qoute = filter_var($qoute, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

			// Execute Prepared Statement
			if (!$sql->execute()) {
				$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: dashboard_update_location] [Query: $qry]");
			    die($error000);
				return;
			}

			// Get SQL statement result
			$result = $sql->get_result();

			if ($result->num_rows > 0) {
				$row = array();
				$row = $result->fetch_assoc();
				$sql->close();

				if ($row['result'] != 'Success') {
					$this->unlink_image($image);
				} else {
					if (isset($row['old_image'])) {
						if ($row['old_image'] != 'None') {
							$this->unlink_image($row['old_image']);
						}
					}
				}

				return json_encode(array(array("response"=>$row['result'])));
			} else {
				$sql->close();
				return json_encode(array(array("response"=>"Error")));
				die();
			}
		}



		/********** Add Category **********/
		public function add_category($accountID, $my_session_id, $name, $image, $icon, $status) {
			include("php/api/push/push.class.php");
			$push = new push_notification;
			$mysqli = self::$tmp_mysqli;
			$logs = self::$tmp_logs;
			$file_name = self::$tmp_file_name;
			$error000 = self::$tmp_error000;
			$error400 = self::$tmp_error400;
			$error1329 = self::$tmp_error1329;
			$qry = "CALL dashboard_add_category(?, ?, ?, ?, ?, ?)";

			if (!isset($accountID) || (!$accountID)) {
				$logs->write_logs('Dashboard Add Product', $file_name, "Bad Request - Invalid/Empty [accountID: $accountID]");
				die($error400);
				return;
			}

			if (!isset($my_session_id) || (!$my_session_id)) {
				$logs->write_logs('Dashboard Add Product', $file_name, "Bad Request - Invalid/Empty [my_session_id: $my_session_id]");
				die($error400);
				return;
			}

			if (!isset($name) || (!$name)) {
				$logs->write_logs('Dashboard Add Product', $file_name, "Bad Request - Invalid/Empty [name: $name]");
				die($error400);
				return;
			}

			if (!isset($image) || (!$image)) {
				$logs->write_logs('Dashboard Add Product', $file_name, "Bad Request - Invalid/Empty [image: $image]");
				die($error400);
				return;
			}

			// if (!isset($icon) || (!$icon)) {
			// 	$logs->write_logs('Dashboard Add Product', $file_name, "Bad Request - Invalid/Empty [icon: $icon]");
			// 	die($error400);
			// 	return;
			// }

			if (!isset($status) || (!$status)) {
				$logs->write_logs('Dashboard Add Product', $file_name, "Bad Request - Invalid/Empty [status: $status]");
				die($error400);
				return;
			}

			// Prepared statement, stage 1: prepare
			if (!($sql = $mysqli->prepare($qry))) {
				$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: dashboard_add_category] [Query: $qry]");
			    die($error000);
				return;
			}

			if (!$sql->bind_param('ssssss', $accountID, $my_session_id, $name, $image, $icon, $status)) {
				$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: dashboard_add_category] [Param: $accountID, $my_session_id, $name, $image, $icon, $status]");
			    die($error000);
				return;
			}

			$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$name = filter_var($name, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$image = filter_var($image, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$icon = filter_var($icon, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$status = filter_var($status, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

			// Execute Prepared Statement
			if (!$sql->execute()) {
				$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: dashboard_add_category] [Query: $qry]");
			    die($error000);
				return;
			}

			// Get SQL statement result
			$result = $sql->get_result();

			if ($result->num_rows > 0) {
				$row = array();
				$row = $result->fetch_assoc();
				$sql->close();

				if ($row['result'] != 'Success') {
					$this->unlink_image($image);
				}


				$logs->write_logs('Dashboard', $file_name, "\t [Procedure: dashboard_add_category] [Param: $accountID, $my_session_id, $name, $image, $icon, $status");

				return json_encode(array(array("response"=>$row['result'])));
			} else {
				$sql->close();
				return json_encode(array(array("response"=>"Error")));
				die();
			}
		}


		/********** Update Category **********/
		public function update_category($accountID, $my_session_id, $categoryID, $name, $icon, $image, $status) {
			include("php/api/push/push.class.php");
			$push = new push_notification;
			$mysqli = self::$tmp_mysqli;
			$logs = self::$tmp_logs;
			$file_name = self::$tmp_file_name;
			$error000 = self::$tmp_error000;
			$error400 = self::$tmp_error400;
			$error1329 = self::$tmp_error1329;
			$qry = "CALL dashboard_update_category(?, ?, ?, ?, ?, ?, ?)";

			if (!isset($accountID) || (!$accountID)) {
				$logs->write_logs('Dashboard Update Category', $file_name, "Bad Request - Invalid/Empty [accountID: $accountID]");
				die($error400);
				return;
			}

			if (!isset($my_session_id) || (!$my_session_id)) {
				$logs->write_logs('Dashboard Update Category', $file_name, "Bad Request - Invalid/Empty [my_session_id: $my_session_id]");
				die($error400);
				return;
			}

			if (!isset($categoryID) || (!$categoryID)) {
				$logs->write_logs('Dashboard Update Category', $file_name, "Bad Request - Invalid/Empty [categoryID: $categoryID]");
				die($error400);
				return;
			}

			if (!isset($icon) ) {
				$logs->write_logs('Dashboard Update Category', $file_name, "Bad Request - Invalid/Empty [icon: $icon]");
				die($error400);
				return;
			}

			if (!isset($name) || (!$name)) {
				$logs->write_logs('Dashboard Update Category', $file_name, "Bad Request - Invalid/Empty [name: $name]");
				die($error400);
				return;
			}

			if (!isset($image) || (!$image)) {
				// $logs->write_logs('Dashboard Update Category', $file_name, "Bad Request - Invalid/Empty [image: $image]");
				// die($error400);
				// return;
				$image = NULL;
			}


			if (!isset($status) || (!$status)) {
				$logs->write_logs('Dashboard Update Category', $file_name, "Bad Request - Invalid/Empty [status: $status]");
				die($error400);
				return;
			}

			// Prepared statement, stage 1: prepare
			if (!($sql = $mysqli->prepare($qry))) {
				$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: dashboard_update_category] [Query: $qry]");
			    die($error000);
				return;
			}

			if (!$sql->bind_param('sssssss', $accountID, $my_session_id, $categoryID, $name, $icon, $image, $status)) {
				$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: dashboard_update_category] [Param: $accountID, $my_session_id, $categoryID, $name, $icon, $image, $status]");
			    die($error000);
				return;
			}

			$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$categoryID = filter_var($categoryID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$name = filter_var($name, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$icon = filter_var($icon, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

			if (!isset($image) || (!$image)) {
				$image = NULL;
			} else {
				$image = filter_var($image, FILTER_VALIDATE_URL, FILTER_FLAG_PATH_REQUIRED);
			}
			
			$status = filter_var($status, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);


			// Execute Prepared Statement
			if (!$sql->execute()) {
				$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: dashboard_update_category] [Query: $qry]");
			    die($error000);
				return;
			}

			// Get SQL statement result
			$result = $sql->get_result();

			if ($result->num_rows > 0) {
				$row = array();
				$row = $result->fetch_assoc();
				$sql->close();

				if ($row['result'] != 'Success') {
					$this->unlink_image($image);
				} else {
					if (isset($row['old_image'])) {
						if ($row['old_image'] != 'None') {
							$this->unlink_image($row['old_image']);
						}
					}
				}


				$logs->write_logs('Dashboard', $file_name, "\t [Procedure: dashboard_update_category] [Param: $accountID, $my_session_id, $categoryID, $name, $icon, $image, $status]");

				return json_encode(array(array("response"=>$row['result'])));
			} else {
				$sql->close();
				return json_encode(array(array("response"=>"Error")));
				die();
			}
		}



		/********** Add Category **********/
		public function add_subcategory($accountID, $my_session_id, $name, $categoryID, $image, $icon, $status) {
			include("php/api/push/push.class.php");
			$push = new push_notification;
			$mysqli = self::$tmp_mysqli;
			$logs = self::$tmp_logs;
			$file_name = self::$tmp_file_name;
			$error000 = self::$tmp_error000;
			$error400 = self::$tmp_error400;
			$error1329 = self::$tmp_error1329;
			$qry = "CALL dashboard_add_subcategory(?, ?, ?, ?, ?, ?, ?)";

			if (!isset($accountID) || (!$accountID)) {
				$logs->write_logs('Dashboard Add Product', $file_name, "Bad Request - Invalid/Empty [accountID: $accountID]");
				die($error400);
				return;
			}

			if (!isset($my_session_id) || (!$my_session_id)) {
				$logs->write_logs('Dashboard Add Product', $file_name, "Bad Request - Invalid/Empty [my_session_id: $my_session_id]");
				die($error400);
				return;
			}

			if (!isset($name) || (!$name)) {
				$logs->write_logs('Dashboard Add Product', $file_name, "Bad Request - Invalid/Empty [name: $name]");
				die($error400);
				return;
			}

			if (!isset($categoryID) || (!$categoryID)) {
				$logs->write_logs('Dashboard Add Product', $file_name, "Bad Request - Invalid/Empty [categoryID: $categoryID]");
				die($error400);
				return;
			}

			if (!isset($image) ) {
				$image = NULL;
			}

			// if (!isset($icon) || (!$icon)) {
			// 	$logs->write_logs('Dashboard Add Product', $file_name, "Bad Request - Invalid/Empty [icon: $icon]");
			// 	die($error400);
			// 	return;
			// }

			if (!isset($status) || (!$status)) {
				$logs->write_logs('Dashboard Add Product', $file_name, "Bad Request - Invalid/Empty [status: $status]");
				die($error400);
				return;
			}

			// Prepared statement, stage 1: prepare
			if (!($sql = $mysqli->prepare($qry))) {
				$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: dashboard_add_subcategory] [Query: $qry]");
			    die($error000);
				return;
			}

			if (!$sql->bind_param('sssssss', $accountID, $my_session_id, $name, $categoryID, $image, $icon, $status)) {
				$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: dashboard_add_subcategory] [Param: $accountID, $my_session_id, $name, $categoryID, $image, $icon, $status]");
			    die($error000);
				return;
			}

			$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$name = filter_var($name, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$categoryID = filter_var($categoryID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH); 
			$icon = filter_var($icon, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$status = filter_var($status, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

			if (!isset($image) || (!$image)) {
				$image = NULL;
			} else {
				$image = filter_var($image, FILTER_VALIDATE_URL, FILTER_FLAG_PATH_REQUIRED);
			}
			

			// Execute Prepared Statement
			if (!$sql->execute()) {
				$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: dashboard_add_subcategory] [Query: $qry]");
			    die($error000);
				return;
			}

			// Get SQL statement result
			$result = $sql->get_result();

			if ($result->num_rows > 0) {
				$row = array();
				$row = $result->fetch_assoc();
				$sql->close();

				if ($row['result'] != 'Success') {
					$this->unlink_image($image);
				}


				$logs->write_logs('Dashboard', $file_name, "\t [Procedure: dashboard_add_subcategory] [Param: $accountID, $my_session_id, $name, $categoryID, $image, $icon, $status]");

				return json_encode(array(array("response"=>$row['result'])));
			} else {
				$sql->close();
				return json_encode(array(array("response"=>"Error")));
				die();
			}
		}


		/********** Update Category **********/
		public function update_subcategory($accountID, $my_session_id, $subcategoryID, $categoryID, $name, $icon, $image, $status) {
			include("php/api/push/push.class.php");
			$push = new push_notification;
			$mysqli = self::$tmp_mysqli;
			$logs = self::$tmp_logs;
			$file_name = self::$tmp_file_name;
			$error000 = self::$tmp_error000;
			$error400 = self::$tmp_error400;
			$error1329 = self::$tmp_error1329;
			$qry = "CALL dashboard_update_subcategory(?, ?, ?, ?, ?, ?, ?, ?)";

			if (!isset($accountID) || (!$accountID)) {
				$logs->write_logs('Dashboard Update Category', $file_name, "Bad Request - Invalid/Empty [accountID: $accountID]");
				die($error400);
				return;
			}

			if (!isset($my_session_id) || (!$my_session_id)) {
				$logs->write_logs('Dashboard Update Category', $file_name, "Bad Request - Invalid/Empty [my_session_id: $my_session_id]");
				die($error400);
				return;
			}

			if (!isset($subcategoryID) || (!$subcategoryID)) {
				$logs->write_logs('Dashboard Update Category', $file_name, "Bad Request - Invalid/Empty [subcategoryID: $subcategoryID]");
				die($error400);
				return;
			}

			if (!isset($categoryID) || (!$categoryID)) {
				$logs->write_logs('Dashboard Update Category', $file_name, "Bad Request - Invalid/Empty [categoryID: $categoryID]");
				die($error400);
				return;
			}

			if (!isset($icon) ) {
				$logs->write_logs('Dashboard Update Category', $file_name, "Bad Request - Invalid/Empty [icon: $icon]");
				die($error400);
				return;
			}

			if (!isset($name) || (!$name)) {
				$logs->write_logs('Dashboard Update Category', $file_name, "Bad Request - Invalid/Empty [name: $name]");
				die($error400);
				return;
			}

			if (!isset($image) || (!$image)) { 
				$image = NULL;
			}


			if (!isset($status) || (!$status)) {
				$logs->write_logs('Dashboard Update Category', $file_name, "Bad Request - Invalid/Empty [status: $status]");
				die($error400);
				return;
			}

			// Prepared statement, stage 1: prepare
			if (!($sql = $mysqli->prepare($qry))) {
				$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: dashboard_update_subcategory] [Query: $qry]");
			    die($error000);
				return;
			}

			if (!$sql->bind_param('ssssssss', $accountID, $my_session_id, $subcategoryID, $categoryID, $name, $icon, $image, $status)) {
				$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: dashboard_update_subcategory] [Param: $accountID, $my_session_id, $subcategoryID, $categoryID, $name, $icon, $image, $status]");
			    die($error000);
				return;
			}

			$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$subcategoryID = filter_var($subcategoryID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$categoryID = filter_var($categoryID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$name = filter_var($name, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$icon = filter_var($icon, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

			if (!isset($image) || (!$image)) {
				$image = NULL;
			} else {
				$image = filter_var($image, FILTER_VALIDATE_URL, FILTER_FLAG_PATH_REQUIRED);
			}
			
			$status = filter_var($status, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);


			// Execute Prepared Statement
			if (!$sql->execute()) {
				$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: dashboard_update_subcategory] [Query: $qry]");
			    die($error000);
				return;
			}

			// Get SQL statement result
			$result = $sql->get_result();

			if ($result->num_rows > 0) {
				$row = array();
				$row = $result->fetch_assoc();
				$sql->close();

				if ($row['result'] != 'Success') {
					$this->unlink_image($image);
				} else {
					if (isset($row['old_image'])) {
						if ($row['old_image'] != 'None') {
							$this->unlink_image($row['old_image']);
						}
					}
				}

				$logs->write_logs('Dashboard', $file_name, "\t [Procedure: dashboard_update_subcategory] [Param: $accountID, $my_session_id, $subcategoryID, $categoryID, $name, $icon, $image, $status]");

				return json_encode(array(array("response"=>$row['result'])));
				// return json_encode(array(array("response"=>$row['statement'])));
			} else {
				$sql->close();
				return json_encode(array(array("response"=>"Error")));
				die();
			}
		}

		/********** View Record **********/
		public function view_record($accountID, $my_session_id, $table, $recordID) {
			include("php/api/push/push.class.php");
			$push = new push_notification;
			$mysqli = self::$tmp_mysqli;
			$logs = self::$tmp_logs;
			$file_name = self::$tmp_file_name;
			$error000 = self::$tmp_error000;
			$error400 = self::$tmp_error400;
			$error1329 = self::$tmp_error1329;
			$output = array();
			$qry = "CALL dashboard_view_record(?, ?, ?, ?)";

			if (!isset($accountID) || (!$accountID)) {
				$logs->write_logs('Dashboard Add Product', $file_name, "Bad Request - Invalid/Empty [accountID: $accountID]");
				die($error400);
				return;
			}

			if (!isset($my_session_id) || (!$my_session_id)) {
				$logs->write_logs('Dashboard Add Product', $file_name, "Bad Request - Invalid/Empty [my_session_id: $my_session_id]");
				die($error400);
				return;
			}

			if (!isset($table) || (!$table)) {
				$logs->write_logs('Dashboard Add Product', $file_name, "Bad Request - Invalid/Empty [table: $table]");
				die($error400);
				return;
			}

			if (!isset($recordID) || (!$recordID)) {
				$logs->write_logs('Dashboard Add Product', $file_name, "Bad Request - Invalid/Empty [recordID: $recordID]");
				die($error400);
				return;
			}

			// Prepared statement, stage 1: prepare
			if (!($sql = $mysqli->prepare($qry))) {
				$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: dashboard_view_record] [Query: $qry]");
			    die($error000);
				return;
			}

			if (!$sql->bind_param('ssss', $accountID, $my_session_id, $table, $recordID)) {
				$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: dashboard_view_record] [Param: $accountID, $my_session_id, $table, $recordID]");
			    die($error000);
				return;
			}

			$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$table = filter_var($table, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$recordID = filter_var($recordID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

			// Execute Prepared Statement
			if (!$sql->execute()) {
				$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: dashboard_view_record] [Query: $qry]");
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
					array_push($output, $row);
				}
				
				if (!isset($row['result'])) {
					return json_encode(array(array("response"=>"Success", "data"=>$output)));
				} else {
					return json_encode(array(array("response"=>$row['result'])));
				}
			} else {
				return json_encode(array(array("response"=>"Error")));
			}
		}

		/********** Update Profile Info **********/
		public function update_profile_info($accountID, $my_session_id, $company, $fname1, $mname1, $lname1, $fname2, $mname2, $lname2, $landline1, $landline2, $mobile1, $mobile2, $fax1, $fax2, $email, $address, $website, $about, $merchantCode) {
			include("php/api/push/push.class.php");
			$push = new push_notification;
			$mysqli = self::$tmp_mysqli;
			$logs = self::$tmp_logs;
			$file_name = self::$tmp_file_name;
			$error000 = self::$tmp_error000;
			$error400 = self::$tmp_error400;
			$error1329 = self::$tmp_error1329;
			$qry = "CALL dashboard_update_profile_info(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
 
			if (!isset($accountID) || (!$accountID)) {
				$logs->write_logs('Dashboard Update Profile Info', $file_name, "Bad Request - Invalid/Empty [accountID: $accountID]");
				die($error400);
				return;
			}

			if (!isset($my_session_id) || (!$my_session_id)) {
				$logs->write_logs('Dashboard Update Profile Info', $file_name, "Bad Request - Invalid/Empty [my_session_id: $my_session_id]");
				die($error400);
				return;
			}

			if (!isset($company) || (!$company)) {
				$logs->write_logs('Dashboard Update Profile Info', $file_name, "Bad Request - Invalid/Empty [company: $company]");
				die($error400."company");
				return;
			}

			if (!isset($fname1) ) {
				$logs->write_logs('Dashboard Update Profile Info', $file_name, "Bad Request - Invalid/Empty [fname1: $fname1]");
				die($error400."fname1");
				return;
			}

			if (!isset($mname1) ) { $mname1 = NULL; }

			if (!isset($lname1) ) {
				$logs->write_logs('Dashboard Update Profile Info', $file_name, "Bad Request - Invalid/Empty [lname1: $lname1]");
				die($error400."lname1");
				return;
			}

			if (!isset($fname2)  ) { $fname2 = NULL; }
			if (!isset($mname2)  ) { $mname2 = NULL; }
			if (!isset($lname2)  ) { $lname2 = NULL; }
			if (!isset($landline1)  ) { $landline1 = NULL; }
			if (!isset($landline2)  ) { $landline2 = NULL; }
			if (!isset($mobile1)  ) { $mobile1 = NULL; }
			if (!isset($mobile2)  ) { $mobile2 = NULL; }
			if (!isset($fax1)  ) { $fax1 = NULL; }
			if (!isset($fax2)  ) { $fax2 = NULL; }

			if (!isset($email) ) { 
				if (!$this->validate_email($email)) {
					$logs->write_logs('Dashboard Update Profile Info', $file_name, "Bad Request - Invalid/Empty [email: $email]");
					die($error400."company");
					return;
				}
			}

			if (!isset($address)  ) { $address = NULL; }
			if (!isset($website)  ) { $website = NULL; }

			if (!isset($about)  ) {
				$logs->write_logs('Dashboard Update Profile Info', $file_name, "Bad Request - Invalid/Empty [about: $about]");
				die($error400."company");
				return;
			}

            if (!isset($merchantCode)  ) {
				$logs->write_logs('Dashboard Update Profile Info', $file_name, "Bad Request - Invalid/Empty [merchantCode: $merchantCode]");
				die($error400."company");
				return;
			} 

			// Prepared statement, stage 1: prepare
			if (!($sql = $mysqli->prepare($qry))) {
				$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: dashboard_update_profile_info] [Query: $qry]");
			    die($error000);
				return;
			}
            
			if (!$sql->bind_param('ssssssssssssssssssss', $accountID, $my_session_id, $company, $fname1, $mname1, $lname1, $fname2, $mname2, $lname2, $landline1, $landline2, $mobile1, $mobile2, $fax1, $fax2, $email, $address, $website, $about, $merchantCode)) {
				$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: dashboard_update_profile_info] [Param: $accountID, $my_session_id, $company, $fname1, $mname1, $lname1, $fname2, $mname2, $lname2, $landline1, $landline2, $mobile1, $mobile2, $fax1, $fax2, $email, $address, $website, $about, $merchantCode]");
			    die($error000);
				return;
			}

			$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$company = filter_var($company, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$fname1 = filter_var($fname1, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$mname1 = filter_var($mname1, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$lname1 = filter_var($lname1, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$fname2 = filter_var($fname2, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$mname2 = filter_var($mname2, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$lname2 = filter_var($lname2, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$landline1 = filter_var($landline1, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$landline2 = filter_var($landline2, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$mobile1 = filter_var($mobile1, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$mobile2 = filter_var($mobile2, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$fax1 = filter_var($fax1, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$fax2 = filter_var($fax2, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$email = strtolower(filter_var($email, FILTER_SANITIZE_EMAIL));
			$address = filter_var($address, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$website = filter_var($website, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			// $about = filter_var($about, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
            $merchantCode = filter_var($merchantCode, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			// Execute Prepared Statement
			if (!$sql->execute()) {
				$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: dashboard_update_profile_info] [Query: $qry]");
			    die($error000);
				return;
			}

			// Get SQL statement result
			$result = $sql->get_result();

			if ($result->num_rows > 0) {
				$row = array();
				$row = $result->fetch_assoc();
				$sql->close();


				$logs->write_logs('Dashboard', $file_name, "\t [Procedure: dashboard_update_profile_info] [Param: $accountID, $my_session_id, $company, $fname1, $mname1, $lname1, $fname2, $mname2, $lname2, $landline1, $landline2, $mobile1, $mobile2, $fax1, $fax2, $email, $address, $website, $about, $merchantCode]");

				return json_encode(array(array("response"=>$row['result'])));
			} else {
				$sql->close();
				return json_encode(array(array("response"=>"Error")));
				die();
			}
		}

        
		/********** Update Profile Logo **********/
		public function update_profile_logo($accountID, $my_session_id, $profilePic) {
			include("php/api/push/push.class.php");
			$push = new push_notification;
			$mysqli = self::$tmp_mysqli;
			$logs = self::$tmp_logs;
			$file_name = self::$tmp_file_name;
			$error000 = self::$tmp_error000;
			$error400 = self::$tmp_error400;
			$error1329 = self::$tmp_error1329;
			$qry = "CALL dashboard_update_profile_logo(?, ?, ?)";

			if (!isset($accountID) || (!$accountID)) {
				$logs->write_logs('Dashboard Update Profile Logo', $file_name, "Bad Request - Invalid/Empty [accountID: $accountID]");
				die($error400);
				return;
			}

			if (!isset($my_session_id) || (!$my_session_id)) {
				$logs->write_logs('Dashboard Update Profile Logo', $file_name, "Bad Request - Invalid/Empty [my_session_id: $my_session_id]");
				die($error400);
				return;
			}

			if (!isset($profilePic) ) {
				$logs->write_logs('Dashboard Update Profile Logo', $file_name, "Bad Request - Invalid/Empty [profilePic: $profilePic]");
				die($error400);
				return;
			}

            
			// Prepared statement, stage 1: prepare
			if (!($sql = $mysqli->prepare($qry))) {
				$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: dashboard_update_profile_logo] [Query: $qry]");
			    die($error000);
				return;
			}

            
			if (!$sql->bind_param('sss', $accountID, $my_session_id, $profilePic)) {
				$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: dashboard_update_profile_logo] [Param: $accountID, $my_session_id, $profilePic]");
			    die($error000);
				return;
			}

			$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$profilePic = filter_var($profilePic, FILTER_VALIDATE_URL, FILTER_FLAG_PATH_REQUIRED);

			// Execute Prepared Statement
			if (!$sql->execute()) {
				$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: dashboard_update_profile_logo] [Query: $qry]");
			    die($error000);
				return;
			}

			// Get SQL statement result
			$result = $sql->get_result();

			if ($result->num_rows > 0) {
				$row = array();
				$row = $result->fetch_assoc();
				$sql->close();

				if ($row['result'] != 'Success') {
					$this->unlink_image($profilePic);
				} else {
					$this->unlink_image($row['old_image']);
				}


				$logs->write_logs('Dashboard', $file_name, "\t [Procedure: dashboard_update_profile_logo] [Param: $accountID, $my_session_id, $profilePic]");

				return json_encode(array(array("response"=>$row['result'])));
			} else {
				$sql->close();
				return json_encode(array(array("response"=>"Error")));
				die();
			}
		}

		/********** Update Profile Loyalty **********/
		public function update_profile_loyalty($accountID, $my_session_id, $merchantCode, $baseValue, $basePoint, $regPoint, $raffleValue, $raffleEntry, $raffleStatus, $nonCash_status, $nonCash_key, $baseValue_nonCash, $basePoint_nonCash) {
			include("php/api/push/push.class.php");
			$push = new push_notification;
			$mysqli = self::$tmp_mysqli;
			$logs = self::$tmp_logs;
			$file_name = self::$tmp_file_name;
			$error000 = self::$tmp_error000;
			$error400 = self::$tmp_error400;
			$error1329 = self::$tmp_error1329;
			$qry = "CALL dashboard_update_profile_loyalty(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

			if (!isset($accountID) || (!$accountID)) {
				$logs->write_logs('Dashboard Update Profile Loyalty', $file_name, "Bad Request - Invalid/Empty [accountID: $accountID]");
				die($error400);
				return;
			}

			if (!isset($my_session_id) || (!$my_session_id)) {
				$logs->write_logs('Dashboard Update Profile Loyalty', $file_name, "Bad Request - Invalid/Empty [my_session_id: $my_session_id]");
				die($error400);
				return;
			}

			if (!isset($merchantCode) || (!$merchantCode)) {
				$logs->write_logs('Dashboard Update Profile Loyalty', $file_name, "Bad Request - Invalid/Empty [merchantCode: $merchantCode]");
				die($error400);
				return;
			}

			if (!isset($baseValue) ) {
				$logs->write_logs('Dashboard Update Profile Loyalty', $file_name, "Bad Request - Invalid/Empty [baseValue: $baseValue]");
				die($error400);
				return;
			}

			if (!isset($basePoint) ) {
				$logs->write_logs('Dashboard Update Profile Loyalty', $file_name, "Bad Request - Invalid/Empty [basePoint: $basePoint]");
				die($error400);
				return;
			}

			if (!isset($regPoint) ) {
				$logs->write_logs('Dashboard Update Profile Loyalty', $file_name, "Bad Request - Invalid/Empty [regPoint: $regPoint]");
				die($error400);
				return;
			}

			if (!isset($raffleValue) ) {
				$logs->write_logs('Dashboard Update Profile Loyalty', $file_name, "Bad Request - Invalid/Empty [raffleValue: $raffleValue]");
				die($error400);
				return;
			}

			if (!isset($raffleEntry) ) {
				$logs->write_logs('Dashboard Update Profile Loyalty', $file_name, "Bad Request - Invalid/Empty [raffleEntry: $raffleEntry]");
				die($error400);
				return;
			}

			if (!isset($raffleStatus) || (!$raffleStatus)) {
				$logs->write_logs('Dashboard Update Profile Loyalty', $file_name, "Bad Request - Invalid/Empty [raffleStatus: $raffleStatus]");
				die($error400);
				return;
			}

			if (!isset($nonCash_status) || (!$nonCash_status)) {
				$logs->write_logs('Dashboard Update Profile Loyalty', $file_name, "Bad Request - Invalid/Empty [nonCash_status: $nonCash_status]");
				die($error400);
				return;
			}

			if (!isset($nonCash_key) ) {
				$logs->write_logs('Dashboard Update Profile Loyalty', $file_name, "Bad Request - Invalid/Empty [nonCash_key: $nonCash_key]");
				die($error400);
				return;
			}

			if (!isset($baseValue_nonCash) ) {
				$logs->write_logs('Dashboard Update Profile Loyalty', $file_name, "Bad Request - Invalid/Empty [baseValue_nonCash: $baseValue_nonCash]");
				die($error400);
				return;
			}

			if (!isset($basePoint_nonCash) ) {
				$logs->write_logs('Dashboard Update Profile Loyalty', $file_name, "Bad Request - Invalid/Empty [basePoint_nonCash: $basePoint_nonCash]");
				die($error400);
				return;
			}

			// Prepared statement, stage 1: prepare
			if (!($sql = $mysqli->prepare($qry))) {
				$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: dashboard_update_profile_loyalty] [Query: $qry]");
			    die($error000);
				return;
			}

			if (!$sql->bind_param('sssiiiiisssii', $accountID, $my_session_id, $merchantCode, $baseValue, $basePoint, $regPoint, $raffleValue, $raffleEntry, $raffleStatus, $nonCash_status, $nonCash_key, $baseValue_nonCash, $basePoint_nonCash)) {
				$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: dashboard_update_profile_loyalty] [Param: $accountID, $my_session_id, $merchantCode, $baseValue, $basePoint, $regPoint, $raffleValue, $raffleEntry, $raffleStatus, $nonCash_status, $nonCash_key, $baseValue_nonCash, $basePoint_nonCash]");
			    die($error000);
				return;
			}

			$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			// $merchantCode = filter_var($merchantCode, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$baseValue = filter_var($baseValue, FILTER_SANITIZE_NUMBER_INT);
			$basePoint = filter_var($basePoint, FILTER_SANITIZE_NUMBER_INT);
			$regPoint = filter_var($regPoint, FILTER_SANITIZE_NUMBER_INT);
			$raffleValue = filter_var($raffleValue, FILTER_SANITIZE_NUMBER_INT);
			$raffleEntry = filter_var($raffleEntry, FILTER_SANITIZE_NUMBER_INT);
			$raffleStatus = filter_var($raffleStatus, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$nonCash_status = filter_var($nonCash_status, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$nonCash_key = filter_var($nonCash_key, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$baseValue_nonCash = filter_var($baseValue_nonCash, FILTER_SANITIZE_NUMBER_INT);
			$basePoint_nonCash = filter_var($basePoint_nonCash, FILTER_SANITIZE_NUMBER_INT);

			// Execute Prepared Statement
			if (!$sql->execute()) {
				$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: dashboard_update_profile_loyalty] [Query: $qry]");
			    die($error000);
				return;
			}

			// Get SQL statement result
			$result = $sql->get_result();

			if ($result->num_rows > 0) {
				$row = array();
				$row = $result->fetch_assoc();
				$sql->close();


				$logs->write_logs('Dashboard', $file_name, "\t [Procedure: dashboard_update_profile_loyalty] [Param: $accountID, $my_session_id, $merchantCode, $baseValue, $basePoint, $regPoint, $raffleValue, $raffleEntry, $raffleStatus, $nonCash_status, $nonCash_key, $baseValue_nonCash, $basePoint_nonCash]");

				return json_encode(array(array("response"=>$row['result'])));
			} else {
				$sql->close();
				return json_encode(array(array("response"=>"Error")));
				die();
			}
		}

		public function add_sku($accountID, $my_session_id, $name, $skuCode, $price, $promoType, $points, $description, $status, $group, $loyaltyID) {
			include("php/api/push/push.class.php");
			$push = new push_notification;
			$mysqli = self::$tmp_mysqli;
			$logs = self::$tmp_logs;
			$file_name = self::$tmp_file_name;
			$error000 = self::$tmp_error000;
			$error400 = self::$tmp_error400;
			$error1329 = self::$tmp_error1329;
			$qry = "CALL dashboard_add_sku(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            
			if (!isset($accountID) || (!$accountID)) {
				$logs->write_logs('Dashboard Add SKU', $file_name, "Bad Request - Invalid/Empty [accountID: $accountID]");
				die($error400);
				return;
			}

			if (!isset($my_session_id) || (!$my_session_id)) {
				$logs->write_logs('Dashboard Add SKU', $file_name, "Bad Request - Invalid/Empty [my_session_id: $my_session_id]");
				die($error400);
				return;
			}

			if (!isset($name) || (!$name)) {
				$logs->write_logs('Dashboard Add SKU', $file_name, "Bad Request - Invalid/Empty [name: $name]");
				die($error400);
				return;
			}

			if (!isset($skuCode) || (!$skuCode)) {
				$logs->write_logs('Dashboard Add SKU', $file_name, "Bad Request - Invalid/Empty [skuCode: $skuCode]");
				die($error400);
				return;
			}

			if (!isset($price)  || ($price == NULL)) {
				$logs->write_logs('Dashboard Add SKU', $file_name, "Bad Request - Invalid/Empty [price: $price]");
				die($error400);
				return;
			}

			if (!isset($promoType) || (!$promoType)) {
				$logs->write_logs('Dashboard Add SKU', $file_name, "Bad Request - Invalid/Empty [promoType: $promoType]");
				die($error400);
				return;
			}

            // snap points
			if (!isset($points) ) {
				$logs->write_logs('Dashboard Add SKU', $file_name, "Bad Request - Invalid/Empty [points: $points]");
				die($error400);
				return;
			}

			if (!isset($description)  ) {
				$logs->write_logs('Dashboard Add SKU', $file_name, "Bad Request - Invalid/Empty [description: $description]");
				die($error400);
				return;
			}

			if (!isset($status) || (!$status)) {
				$logs->write_logs('Dashboard Add SKU', $file_name, "Bad Request - Invalid/Empty [status: $status]");
				die($error400);
				return;
			}

			if (!isset($group) ) {
				$logs->write_logs('Dashboard Add SKU', $file_name, "Bad Request - Invalid/Empty [group: $group]");
				die($error400);
				return;
			}

            
			if (!isset($loyaltyID)  ) {
				$logs->write_logs('Dashboard Add SKU', $file_name, "Bad Request - Invalid/Empty [loyaltyID: $loyaltyID]");
				die($error400);
				return;
			}
 
			// Prepared statement, stage 1: prepare
			if (!($sql = $mysqli->prepare($qry))) {
				$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: dashboard_add_sku] [Query: $qry]");
			    die($error000);
				return;
			}
            
            

			if (!$sql->bind_param('ssssisissss', $accountID, $my_session_id, $name, $skuCode, $price, $promoType, $points, $description, $status, $group, $loyaltyID)) {
				$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: dashboard_add_sku] [Param: $accountID, $my_session_id, $name, $skuCode, $price, $promoType, $points, $description, $status, $group, $loyaltyID]");
			    die($error000);
				return;
			}

			$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$name = filter_var($name, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$skuCode = filter_var($skuCode, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$price = filter_var($price, FILTER_SANITIZE_NUMBER_INT);
			$promoType = filter_var($promoType, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$points = filter_var($points, FILTER_SANITIZE_NUMBER_INT);
			$description = filter_var($description, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$status = filter_var($status, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$group = filter_var($group, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$loyaltyID = filter_var($loyaltyID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

			// Execute Prepared Statement
			if (!$sql->execute()) {
				$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: dashboard_add_sku] [Query: $qry]");
			    die($error000);
				return;
			}

			// Get SQL statement result
			$result = $sql->get_result();

			if ($result->num_rows > 0) {
				$row = array();
				$row = $result->fetch_assoc();
				$sql->close();
				return json_encode(array(array("response"=>$row['result'])));
			} else {
				$sql->close();
				return json_encode(array(array("response"=>"Error")));
				die();
			}
			
		}

		public function delete_sku($accountID, $my_session_id, $skuID) {

			include("php/api/push/push.class.php");
			$push = new push_notification;
			$mysqli = self::$tmp_mysqli;
			$logs = self::$tmp_logs;
			$file_name = self::$tmp_file_name;
			$error000 = self::$tmp_error000;
			$error400 = self::$tmp_error400;
			$error1329 = self::$tmp_error1329;
			$qry = "CALL dashboard_delete_sku(?, ?, ?)";

			if (!isset($accountID) || (!$accountID)) {
				$logs->write_logs('Dashboard Delete SKU', $file_name, "Bad Request - Invalid/Empty [accountID: $accountID]");
				die($error400);
				return;
			}

			if (!isset($my_session_id) || (!$my_session_id)) {
				$logs->write_logs('Dashboard Delete SKU', $file_name, "Bad Request - Invalid/Empty [my_session_id: $my_session_id]");
				die($error400);
				return;
			}

			if (!isset($skuID) || (!$skuID)) {
				$logs->write_logs('Dashboard Delete SKU', $file_name, "Bad Request - Invalid/Empty [skuID: $skuID]");
				die($error400);
				return;
			}

			// Prepared statement, stage 1: prepare
			if (!($sql = $mysqli->prepare($qry))) {
				$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: dashboard_delete_sku] [Query: $qry]");
			    die($error000);
				return;
			}

			if (!$sql->bind_param('sss', $accountID, $my_session_id, $skuID)) {
				$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: dashboard_delete_sku] [Param: $accountID, $my_session_id, $skuID]");
			    die($error000);
				return;
			}

			$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$skuID = filter_var($skuID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

			// Execute Prepared Statement
			if (!$sql->execute()) {
				$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: dashboard_delete_sku] [Query: $qry]");
			    die($error000);
				return;
			}

			// Get SQL statement result
			$result = $sql->get_result();

			if ($result->num_rows > 0) {
				$row = array();
				$row = $result->fetch_assoc();
				$sql->close();
				return json_encode(array(array("response"=>$row['result'])));
			} else {
				$sql->close();
				return json_encode(array(array("response"=>"Error")));
				die();
			}

		}


		/********** Update SKU **********/
		public function update_sku($accountID, $my_session_id, $skuID, $name, $skuCode, $price, $points, $description, 
                                   $status, $group, $loyaltyID, $promoType) {
			include("php/api/push/push.class.php");
			$push = new push_notification;
			$mysqli = self::$tmp_mysqli;
			$logs = self::$tmp_logs;
			$file_name = self::$tmp_file_name;
			$error000 = self::$tmp_error000;
			$error400 = self::$tmp_error400;
			$error1329 = self::$tmp_error1329;
			$qry = "CALL dashboard_update_sku(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

			if (!isset($accountID) || (!$accountID)) {
				$logs->write_logs('Dashboard UPDATE SKU', $file_name, "Bad Request - Invalid/Empty [accountID: $accountID]");
				die($error400);
				return;
			}

			if (!isset($my_session_id) || (!$my_session_id)) {
				$logs->write_logs('Dashboard UPDATE SKU', $file_name, "Bad Request - Invalid/Empty [my_session_id: $my_session_id]");
				die($error400);
				return;
			}

			if (!isset($skuID) || (!$skuID)) {
				$logs->write_logs('Dashboard UPDATE SKU', $file_name, "Bad Request - Invalid/Empty [skuID: $skuID]");
				die($error400);
				return;
			}


			if (!isset($name) || (!$name)) {
				$logs->write_logs('Dashboard UPDATE SKU', $file_name, "Bad Request - Invalid/Empty [name: $name]");
				die($error400);
				return;
			}

			if (!isset($skuCode) || (!$skuCode)) {
				$logs->write_logs('Dashboard UPDATE SKU', $file_name, "Bad Request - Invalid/Empty [skuCode: $skuCode]");
				die($error400);
				return;
			}

			if (!isset($price)  || ($price == NULL)) {
				$logs->write_logs('Dashboard UPDATE SKU', $file_name, "Bad Request - Invalid/Empty [price: $price]");
				die($error400);
				return;
			}

			if (!isset($points)    ) {
				$logs->write_logs('Dashboard UPDATE SKU', $file_name, "Bad Request - Invalid/Empty [points: $points]");
				die($error400);
				return;
			}

			if (!isset($description)  ) {
				$logs->write_logs('Dashboard UPDATE SKU', $file_name, "Bad Request - Invalid/Empty [description: $description]");
				die($error400);
				return;
			}

			if (!isset($status) || (!$status)) {
				$logs->write_logs('Dashboard UPDATE SKU', $file_name, "Bad Request - Invalid/Empty [status: $status]");
				die($error400);
				return;
			} 

			if (!isset($group) ) {
				$logs->write_logs('Dashboard Add SKU', $file_name, "Bad Request - Invalid/Empty [group: $group]");
				die($error400);
				return;
			} 
            
			if (!isset($loyaltyID)  ) {
				$logs->write_logs('Dashboard Add SKU', $file_name, "Bad Request - Invalid/Empty [loyaltyID: $loyaltyID]");
				die($error400);
				return;
			}

			if (!isset($promoType) || (!$promoType)) {
				$logs->write_logs('Dashboard Add SKU', $file_name, "Bad Request - Invalid/Empty [promoType: $promoType]");
				die($error400);
				return;
			}

            
			// Prepared statement, stage 1: prepare
			if (!($sql = $mysqli->prepare($qry))) {
				$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: dashboard_update_sku] [Query: $qry]");
			    die($error000);
				return;
			}

			if (!$sql->bind_param('sssssiisssss', $accountID, $my_session_id, $skuID, $name, $skuCode, $price, $points, 
                                  $description, $status, $group, $loyaltyID, $promoType)) {
				$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: dashboard_update_sku] [Param: $accountID, $my_session_id, $skuID, $name, $skuCode, $price, $points, 
                $description, $status, $group, $loyaltyID, $promoType]");
			    die($error000);
				return;
			}

			$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$skuID = filter_var($skuID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$name = filter_var($name, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$skuCode = filter_var($skuCode, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$price = filter_var($price, FILTER_SANITIZE_NUMBER_INT);
			$points = filter_var($points, FILTER_SANITIZE_NUMBER_INT);
			$description = filter_var($description, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$status = filter_var($status, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH); 
			$group = filter_var($group, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$loyaltyID = filter_var($loyaltyID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
            $promoType = filter_var($promoType, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

			// Execute Prepared Statement
			if (!$sql->execute()) {
				$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: dashboard_update_sku] [Query: $qry]");
			    die($error000);
				return;
			}

			// Get SQL statement result
			$result = $sql->get_result();

			if ($result->num_rows > 0) {
				$row = array();
				$row = $result->fetch_assoc();
				$sql->close();
				return json_encode(array(array("response"=>$row['result'])));
			} else {
				$sql->close();
				return json_encode(array(array("response"=>"Error")));
				die();
			}
		}


		/************* ADD TABLET *****************/
		public function add_tablet($accountID, $my_session_id, $locID, $status) {
			include("php/api/push/push.class.php");
			$push = new push_notification;
			$mysqli = self::$tmp_mysqli;
			$logs = self::$tmp_logs;
			$file_name = self::$tmp_file_name;
			$error000 = self::$tmp_error000;
			$error400 = self::$tmp_error400;
			$error1329 = self::$tmp_error1329;
			$qry = "CALL dashboard_add_tablet(?, ?, ?, ?)";

			if (!isset($accountID) || (!$accountID)) {
				$logs->write_logs('Dashboard ADD Tablet', $file_name, "Bad Request - Invalid/Empty [accountID: $accountID]");
				die($error400);
				return;
			}

			if (!isset($my_session_id) || (!$my_session_id)) {
				$logs->write_logs('Dashboard ADD Tablet', $file_name, "Bad Request - Invalid/Empty [my_session_id: $my_session_id]");
				die($error400);
				return;
			}

			if (!isset($locID) || (!$locID)) {
				$logs->write_logs('Dashboard ADD Tablet', $file_name, "Bad Request - Invalid/Empty [locID: $locID]");
				die($error400);
				return;
			}

			if (!isset($status) || (!$status)) {
				$logs->write_logs('Dashboard ADD Tablet', $file_name, "Bad Request - Invalid/Empty [status: $status]");
				die($error400);
				return;
			}

			// Prepared statement, stage 1: prepare
			if (!($sql = $mysqli->prepare($qry))) {
				$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: dashboard_add_tablet] [Query: $qry]");
			    die($error000);
				return;
			}

			if (!$sql->bind_param('ssss', $accountID, $my_session_id, $locID, $status)) {
				$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: dashboard_add_tablet] [Param: $accountID, $my_session_id, $locID, $status]");
			    die($error000);
				return;
			}

			$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$locID = filter_var($locID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$status = filter_var($status, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

			// Execute Prepared Statement
			if (!$sql->execute()) {
				$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: dashboard_add_tablet] [Query: $qry]");
			    die($error000);
				return;
			}

			// Get SQL statement result
			$result = $sql->get_result();

			if ($result->num_rows > 0) {
				$row = array();
				$row = $result->fetch_assoc();
				$sql->close();
				return json_encode(array(array("response"=>$row['result'])));
			} else {
				$sql->close();
				return json_encode(array(array("response"=>"Error")));
				die();
			}
			
		}

		public function delete_tablet($accountID, $my_session_id, $deviceCode) {

			include("php/api/push/push.class.php");
			$push = new push_notification;
			$mysqli = self::$tmp_mysqli;
			$logs = self::$tmp_logs;
			$file_name = self::$tmp_file_name;
			$error000 = self::$tmp_error000;
			$error400 = self::$tmp_error400;
			$error1329 = self::$tmp_error1329;
			$qry = "CALL dashboard_delete_tablet(?, ?, ?)";

			if (!isset($accountID) || (!$accountID)) {
				$logs->write_logs('Dashboard Delete Tablet', $file_name, "Bad Request - Invalid/Empty [accountID: $accountID]");
				die($error400);
				return;
			}

			if (!isset($my_session_id) || (!$my_session_id)) {
				$logs->write_logs('Dashboard Delete Tablet', $file_name, "Bad Request - Invalid/Empty [my_session_id: $my_session_id]");
				die($error400);
				return;
			}

			if (!isset($deviceCode) || (!$deviceCode)) {
				$logs->write_logs('Dashboard Delete Tablet', $file_name, "Bad Request - Invalid/Empty [deviceCode: $deviceCode]");
				die($error400);
				return;
			}

			// Prepared statement, stage 1: prepare
			if (!($sql = $mysqli->prepare($qry))) {
				$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: delete_tablet] [Query: $qry]");
			    die($error000);
				return;
			}

			if (!$sql->bind_param('sss', $accountID, $my_session_id, $deviceCode)) {
				$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: delete_tablet] [Param: $accountID, $my_session_id, $deviceCode]");
			    die($error000);
				return;
			}

			$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$deviceCode = filter_var($deviceCode, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

			// Execute Prepared Statement
			if (!$sql->execute()) {
				$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: delete_tablet] [Query: $qry]");
			    die($error000);
				return;
			}

			// Get SQL statement result
			$result = $sql->get_result();

			if ($result->num_rows > 0) {
				$row = array();
				$row = $result->fetch_assoc();
				$sql->close();
				return json_encode(array(array("response"=>$row['result'])));
			} else {
				$sql->close();
				return json_encode(array(array("response"=>"Error")));
				die();
			}

		}

		public function update_tablet($accountID, $my_session_id, $deviceCode, $locID, $status, $deploy) {
			include("php/api/push/push.class.php");
			$push = new push_notification;
			$mysqli = self::$tmp_mysqli;
			$logs = self::$tmp_logs;
			$file_name = self::$tmp_file_name;
			$error000 = self::$tmp_error000;
			$error400 = self::$tmp_error400;
			$error1329 = self::$tmp_error1329;
			$qry = "CALL dashboard_update_tablet(?, ?, ?, ?, ?, ?)";

			if (!isset($accountID) || (!$accountID)) {
				$logs->write_logs('Dashboard Update Loyalty', $file_name, "Bad Request - Invalid/Empty [accountID: $accountID]");
				die($error400);
				return;
			}

			if (!isset($my_session_id) || (!$my_session_id)) {
				$logs->write_logs('Dashboard Update Loyalty', $file_name, "Bad Request - Invalid/Empty [my_session_id: $my_session_id]");
				die($error400);
				return;
			}

			if (!isset($deviceCode) || (!$deviceCode)) {
				$logs->write_logs('Dashboard Update Loyalty', $file_name, "Bad Request - Invalid/Empty [deviceCode: $deviceCode]");
				die($error400);
				return;
			}

			if (!isset($locID) || (!$locID)) {
				$logs->write_logs('Dashboard Update Loyalty', $file_name, "Bad Request - Invalid/Empty [locID: $locID]");
				die($error400);
				return;
			}

			if (!isset($status) || (!$status)) {
				$logs->write_logs('Dashboard Update Loyalty', $file_name, "Bad Request - Invalid/Empty [status: $status]");
				die($error400);
				return;
			}

			if (!isset($deploy) || (!$deploy)) {
				$logs->write_logs('Dashboard Update Loyalty', $file_name, "Bad Request - Invalid/Empty [deploy: $deploy]");
				die($error400);
				return;
			}

			// Prepared statement, stage 1: prepare
			if (!($sql = $mysqli->prepare($qry))) {
				$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: dashboard_update_tablet] [Query: $qry]");
			    die($error000);
				return;
			}

			if (!$sql->bind_param('ssssss', $accountID, $my_session_id, $deviceCode, $locID, $status, $deploy)) {
				$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: dashboard_update_tablet] [Param: $accountID, $my_session_id, $deviceCode, $locID, $status, $deploy]");
			    die($error000);
				return;
			}

			$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$deviceCode = filter_var($deviceCode, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$locID = filter_var($locID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$deploy = filter_var($deploy, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);


			// Execute Prepared Statement
			if (!$sql->execute()) {
				$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: dashboard_update_tablet] [Query: $qry]");
			    die($error000);
				return;
			}

			// Get SQL statement result
			$result = $sql->get_result();

			if ($result->num_rows > 0) {
				$row = array();
				$row = $result->fetch_assoc();
				$sql->close();
				return json_encode(array(array("response"=>$row['result'])));
			} else {
				$sql->close();
				return json_encode(array(array("response"=>"Error")));
				die();
			}
		}


		public function card_series($accountID, $my_session_id) {
			include("php/api/push/push.class.php");
			$push = new push_notification;
			$mysqli = self::$tmp_mysqli;
			$logs = self::$tmp_logs;
			$file_name = self::$tmp_file_name;
			$error000 = self::$tmp_error000;
			$error400 = self::$tmp_error400;
			$error1329 = self::$tmp_error1329;
			$qry = "CALL generate_cardseries(?, ?)";

			if (!isset($accountID) || (!$accountID)) {
				$logs->write_logs('Dashboard Add Press', $file_name, "Bad Request - Invalid/Empty [accountID: $accountID]");
				die($error400);
				return;
			}

			if (!isset($my_session_id) || (!$my_session_id)) {
				$logs->write_logs('Dashboard Add Press', $file_name, "Bad Request - Invalid/Empty [my_session_id: $my_session_id]");
				die($error400);
				return;
			}

			$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

			// Execute Prepared Statement
			if (!$sql->execute()) {
				$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: generate_cardseries] [Query: $qry]");
			    die($error000);
				return;
			}

			// Get SQL statement result
			$result = $sql->get_result();

			if ($result->num_rows > 0) {
				$row = array();
				$row = $result->fetch_assoc();
				$sql->close();

				if ($row['result'] != 'Success') {
					$this->unlink_image($image);
				}

				return json_encode(array(array("response"=>$row['result'])));
			} else {
				$sql->close();
				$this->unlink_image($image);
				return json_encode(array(array("response"=>"Error")));
				die();
			}

		}


		/********** Get User Download Yearly **********/
		public function get_userDownload($accountID, $my_session_id) {
			include("php/api/push/push.class.php");
			$push = new push_notification;
			$mysqli = self::$tmp_mysqli;
			$logs = self::$tmp_logs;
			$file_name = self::$tmp_file_name;
			$error000 = self::$tmp_error000;
			$error400 = self::$tmp_error400;
			$error1329 = self::$tmp_error1329;
			$qry = "CALL reports_get_userDownload(?, ?)";
			$output = array();

			if (!isset($accountID) || (!$accountID)) {
				$logs->write_logs('Dashboard Delete Loyalty', $file_name, "Bad Request - Invalid/Empty [accountID: $accountID]");
				die($error400);
				return;
			}

			if (!isset($my_session_id) || (!$my_session_id)) {
				$logs->write_logs('Dashboard Delete Loyalty', $file_name, "Bad Request - Invalid/Empty [my_session_id: $my_session_id]");
				die($error400);
				return;
			} 

			// Prepared statement, stage 1: prepare
			if (!($sql = $mysqli->prepare($qry))) {
				$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: reports_get_userDownload] [Query: $qry]");
			    die($error000);
				return;
			}

			if (!$sql->bind_param('ss', $accountID, $my_session_id)) {
				$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: reports_get_userDownload] [Param: $accountID, $my_session_id]");
			    die($error000);
				return;
			}

			$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH); 

			// Execute Prepared Statement
			if (!$sql->execute()) {
				$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: reports_get_userDownload] [Query: $qry]");
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
					array_push($output, $row);
				}
				$logs->write_logs('Dashboard Downloads Fetch', $file_name, "get_userDownload");

				if (!isset($row['result'])) {
					return json_encode(array(array("response"=>"Success", "data"=>$output)));
				} else {
					return json_encode(array(array("response"=>$row['result'])));
				}
			} else {
				return json_encode(array(array("response"=>"Empty")));
			}	

		}

		/********** Get User Download Yearly **********/
		public function get_userdownload_yearly($accountID, $my_session_id) {
			include("php/api/push/push.class.php");
			$push = new push_notification;
			$mysqli = self::$tmp_mysqli;
			$logs = self::$tmp_logs;
			$file_name = self::$tmp_file_name;
			$error000 = self::$tmp_error000;
			$error400 = self::$tmp_error400;
			$error1329 = self::$tmp_error1329;
			$qry = "CALL reports_get_userdownload_yearly(?, ?)";
			$output = array();

			if (!isset($accountID) || (!$accountID)) {
				$logs->write_logs('Dashboard User Download Yearly', $file_name, "Bad Request - Invalid/Empty [accountID: $accountID]");
				die($error400);
				return;
			}

			if (!isset($my_session_id) || (!$my_session_id)) {
				$logs->write_logs('Dashboard User Download Yearly', $file_name, "Bad Request - Invalid/Empty [my_session_id: $my_session_id]");
				die($error400);
				return;
			} 

			// echo "CALL reports_get_userdownload_yearly('$accountID', '$my_session_id')";
			// Prepared statement, stage 1: prepare
			if (!($sql = $mysqli->prepare($qry))) {
				$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: reports_get_userdownload_yearly] [Query: $qry]");
			    die($error000);
				return;
			}

			if (!$sql->bind_param('ss', $accountID, $my_session_id)) {
				$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: reports_get_userdownload_yearly] [Param: $accountID, $my_session_id]");
			    die($error000);
				return;
			}

			$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH); 

			// Execute Prepared Statement
			if (!$sql->execute()) {
				$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: reports_get_userdownload_yearly] [Query: $qry]");
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
					array_push($output, $row);
				}
				$logs->write_logs('Dashboard Downloads Fetch', $file_name, "get_userdownload_yearly");

				if (!isset($row['result'])) {
					return json_encode(array(array("response"=>"Success", "data"=>$output)));
				} else {
					return json_encode(array(array("response"=>$row['result'])));
				}
			} else {
				return json_encode(array(array("response"=>"Empty")));
			}	


		}

		
		/********** Get User Download Yearly **********/
		public function get_userdownload_monthly($accountID, $my_session_id) {
			include("php/api/push/push.class.php");
			$push = new push_notification;
			$mysqli = self::$tmp_mysqli;
			$logs = self::$tmp_logs;
			$file_name = self::$tmp_file_name;
			$error000 = self::$tmp_error000;
			$error400 = self::$tmp_error400;
			$error1329 = self::$tmp_error1329;
			$qry = "CALL reports_get_userdownload_monthly(?, ?)";
			$output = array();

			if (!isset($accountID) || (!$accountID)) {
				$logs->write_logs('Dashboard Delete Loyalty', $file_name, "Bad Request - Invalid/Empty [accountID: $accountID]");
				die($error400);
				return;
			}

			if (!isset($my_session_id) || (!$my_session_id)) {
				$logs->write_logs('Dashboard Delete Loyalty', $file_name, "Bad Request - Invalid/Empty [my_session_id: $my_session_id]");
				die($error400);
				return;
			} 

			// Prepared statement, stage 1: prepare
			if (!($sql = $mysqli->prepare($qry))) {
				$logs->write_logs('MySQLi Prepare', $file_name, "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: reports_get_userdownload_monthly] [Query: $qry]");
			    die($error000);
				return;
			}

			if (!$sql->bind_param('ss', $accountID, $my_session_id)) {
				$logs->write_logs('MySQLi Bind Param', $file_name, "Bind Param failed: (" . $mysqli->errno . ") " . $mysqli->error . "\t [Procedure: reports_get_userdownload_monthly] [Param: $accountID, $my_session_id]");
			    die($error000);
				return;
			}

			$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH); 

			// Execute Prepared Statement
			if (!$sql->execute()) {
				$logs->write_logs('MySQLi Execute', $file_name, "Execute failed: (" . $sql->errno . ") " . $sql->error . "\t [Procedure: reports_get_userdownload_monthly] [Query: $qry]");
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
					array_push($output, $row);
				}
				$logs->write_logs('Dashboard Downloads Fetch', $file_name, "get_userdownload_monthly");

				if (!isset($row['result'])) {
					return json_encode(array(array("response"=>"Success", "data"=>$output)));
				} else {
					return json_encode(array(array("response"=>$row['result'])));
				}
			} else {
				return json_encode(array(array("response"=>"Empty")));
			}	

		}


		/********** Unlink Image **********/
		public function unlink_image($source) {	

			$source = str_replace(PATH, "", $source);

			if (file_exists($source)){
			    unlink($source);
			}

			return;

			// $source = str_replace("http://familymart.appsolutely.ph/", "", $source);
			// $noslash = str_replace("/", "\\", $source);
			// $delpath = getcwd(). "\\". $noslash;
 
			// if (file_exists($delpath)){ 
			//     unlink($delpath);
			// }
			// else
			// 	echo "\n\nimage file does not exist ";
			
			// return;
		}

		/********** Validate E-Mail Address **********/
		public function validate_email($email) {
			$isValid = true;

	        // First, we check that there's one @ symbol, and that the lengths are right
	        if (!preg_match("/^[^@]{1,64}@[^@]{1,255}$/", $email)) {
	            // Email invalid because wrong number of characters in one section, or wrong number of @ symbols.
	            $isValid = false;
	        }

	        // Split it into sections to make life easier
	        $email_array = explode("@", $email);
	        if (count($email_array) < 2) {
	        	$isValid = false;
	        	return $isValid;
	        }

	        $local_array = explode(".", $email_array[0]);

	        for ($i = 0; $i < sizeof($local_array); $i++) {
	            if (!preg_match("/^(([A-Za-z0-9!#$%&'*+\/=?^_`{|}~-][A-Za-z0-9!#$%&'*+\/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$/", $local_array[$i])) {
	                $isValid = false;
	            }
	        }

	        if (!preg_match("/^\[?[0-9\.]+\]?$/", $email_array[1])) { // Check if domain is IP. If not, it should be valid domain name
	            $domain_array = explode(".", $email_array[1]);
	            if (sizeof($domain_array) < 2) {
	                $isValid = false; // Not enough parts to domain
	            }
	            for ($i = 0; $i < sizeof($domain_array); $i++) {
	                if (!preg_match("/^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|([A-Za-z0-9]+))$/", $domain_array[$i])) {
	                    $isValid = false;
	                }
	            }
	        }

		   	return $isValid;
		}

	}

	/********** Invalid Access Checker **********/
	if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {
		include_once('../../logs/logs.class.php');
		$logs = NEW logs();
		// $file_name = substr(strtolower(basename($_SERVER['PHP_SELF'])),0,strlen(basename($_SERVER['PHP_SELF'])));
		$logs->write_logs('Invalid Access', 'core.class.php', 'Illegal access attempt.');
		die('Access denied'); 
	}

?>