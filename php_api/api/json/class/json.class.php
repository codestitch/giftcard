<?php

	/**
		Creator 	: Jim Karlo Jamero
		Company		: Appsolutely Inc.
		File Name 	: json.class.php
		Description : This PHP class handles the fetching of data and conversion to json object.	
	**/

	class json {

		private static $tmp_mysqli;
		private static $tmp_logs;
		private static $tmp_file_name;
		private static $tmp_error000;
		private static $tmp_error400;
		private static $tmp_error1329;
		
		function __construct() {
			include_once('php/api/settings/config.php');
			self::$tmp_mysqli = $mysqli;
			self::$tmp_logs = $logs;
			self::$tmp_file_name = 'json.class.php';
			self::$tmp_error000 = $error000;
			self::$tmp_error400 = $error400;
			self::$tmp_error1329 = $error1329;
		}

		public function fetch($table) {
			$mysqli = self::$tmp_mysqli;
			$logs = self::$tmp_logs;
			$file_name = self::$tmp_file_name;
			$error000 = self::$tmp_error000;
			$error400 = self::$tmp_error400;
			$error1329 = self::$tmp_error1329;

			if (!isset($table) || (!$table)) {
				die($error400);
				return;
			}

			$table = strtolower(filter_var($table, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH));


			if ($table == 'loctable') {
				$qry = "CALL fetch_json('loctable')";
			} elseif ($table == 'loyaltytable') {
				$qry = "CALL fetch_json('loyaltytable')";
			} elseif ($table == 'producttable') {
				$qry = "CALL fetch_json('producttable')";
			} elseif ($table == 'settings') {
				$qry = "CALL fetch_json('settings')";
			} elseif ($table == 'skutable') {
				$qry = "CALL fetch_json('skutable')";
			} elseif ($table == 'faqtable') {
				$qry = "CALL fetch_json('faqtable')";
			} elseif ($table == 'promotable') {
				$qry = "CALL fetch_json('promotable')";
			} elseif ($table == 'carddesigntable') {
				$qry = "CALL fetch_json('carddesigntable')";
			} else {
				$logs->write_logs('JSON Fetch', $file_name, "Bad Request - [Message: Unable to complete process.] [Table: $table]");
		 		die($error400);
				return;
		 	}

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

			$result = $sql->get_result();
			
			$output = array();

			if ($result->num_rows > 0) {
				while ($row = $result->fetch_assoc()) {
					foreach ($row as $array_key => $array_value) {
						if (($table == 'carddesigntable') && ($array_key == 'image')) {
							$row[$array_key] = PATH."assets/images/carddesign/".html_entity_decode($array_value, ENT_QUOTES, 'UTF-8')."?".$this->randomizer(3);
						} else {
							$row[$array_key] = html_entity_decode($array_value, ENT_QUOTES, 'UTF-8');
						}					    	
				    }
					array_push($output, $row);
				}
				$logs->write_logs('JSON Fetch', $file_name, "Table: [$table]\t Status: [Success]");
			}

			return json_encode(array(array("response"=>"Success", "data"=>$output)));
		}

		/********** Randomizer **********/
		public function randomizer($len, $norepeat = true) {
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

	}

	/********** Invalid Access Checker **********/
	if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {
		include_once('../../logs/logs.class.php');
		$logs = NEW logs();
		$file_name = substr(strtolower(basename($_SERVER['PHP_SELF'])),0,strlen(basename($_SERVER['PHP_SELF'])));
		$logs->write_logs('Invalid Access', 'json.class.php', 'Illegal access attempt.');
		die('Access denied'); 
	}

?>