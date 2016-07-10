<?php

	require_once('php/api/points/class/points.class.php');

	$class = new points();

	switch ($function) {

		case 'check_user_qr':
			$deviceID = "";
			$locID = "";
			$qrCode = "";

			if (isset($_POST['deviceID']) && $_POST['deviceID'] != NULL) {
				$deviceID = $cipher->decrypt($_POST['deviceID']);
				$deviceID = filter_var($deviceID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(deviceID: '.$_POST['deviceID'].')');
				echo $error400;
				die();
			}

			if (isset($_POST['locID']) && $_POST['locID'] != NULL) {
				$locID = $cipher->decrypt($_POST['locID']);
				$locID = filter_var($locID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(locID: '.$_POST['locID'].')');
				echo $error400;
				die();
			}

			if (isset($_POST['qrCode']) && $_POST['qrCode'] != NULL) {
				$qrCode = $cipher->decrypt($_POST['qrCode']);
				$qrCode = filter_var($qrCode, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
				$codeType = substr($qrCode, 0, 3);

				if (($codeType != 'APP') && ($codeType != 'CRD')) {
					api_error_logger('(qrCode: '.$_POST['qrCode'].')');
					echo $error400;
					die();
				}
			} else {
				api_error_logger('(qrCode: '.$_POST['qrCode'].')');
				echo $error400;
				die();
			}

			echo $class->check_user_qr($deviceID, $locID, $qrCode);
			break;

		case 'earn_raffle':
			$qrApp = "";
			$locID = "";
			$deviceID = "";

			if (isset($_POST['qrApp']) && $_POST['qrApp'] != NULL) {
				$qrApp = $cipher->decrypt($_POST['qrApp']);
				$qrApp = filter_var($qrApp, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
				$codeType = substr($qrApp, 0, 3);

				if ($codeType != 'APP') {
					api_error_logger('(qrApp: '.$_POST['qrApp'].')');
					echo $error400;
					die();
				}
			} else {
				api_error_logger('(qrApp: '.$_POST['qrApp'].')');
				echo $error400;
				die();
			}

			if (isset($_POST['locID']) && $_POST['locID'] != NULL) {
				$locID = $cipher->decrypt($_POST['locID']);
				$locID = filter_var($locID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(locID: '.$_POST['locID'].')');
				echo $error400;
				die();
			}

			if (isset($_POST['deviceID']) && $_POST['deviceID'] != NULL) {
				$deviceID = $cipher->decrypt($_POST['deviceID']);
				$deviceID = filter_var($deviceID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(deviceID: '.$_POST['deviceID'].')');
				echo $error400;
				die();
			}

			echo $class->earn_raffle($qrApp, $locID, $deviceID);
			break;

		case 'offline_earn_points':
			$deviceID = "";
			$locID = "";
			$qrCode = "";
			$transactionID = "";
			$transactionType = "";
			$earn = "";
			$sku = "";
			$raffleValue = "";
			$raffleEntry = "";
			$ticket = "";
			$version = "";
			$branchCode = "";
			$terminalID = "";

			if (isset($_POST['deviceID']) && $_POST['deviceID'] != NULL) {
				$deviceID = $cipher->decrypt($_POST['deviceID']);
				$deviceID = filter_var($deviceID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(deviceID: '.$_POST['deviceID'].')');
				echo $error400;
				die();
			}

			if (isset($_POST['locID']) && $_POST['locID'] != NULL) {
				$locID = $cipher->decrypt($_POST['locID']);
				$locID = filter_var($locID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(locID: '.$_POST['locID'].')');
				echo $error400;
				die();
			}

			if (isset($_POST['qrCode']) && $_POST['qrCode'] != NULL) {
				$qrCode = $cipher->decrypt($_POST['qrCode']);
				$qrCode = filter_var($qrCode, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(qrCode: '.$_POST['qrCode'].')');
				echo $error400;
				die();
			}

			if (isset($_POST['transactionID']) && $_POST['transactionID'] != NULL) {
				$transactionID = $cipher->decrypt($_POST['transactionID']);
				$transactionID = filter_var($transactionID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(transactionID: '.$_POST['transactionID'].')');
				echo $error400;
				die();
			}

			if (isset($_POST['transactionType']) && $_POST['transactionType'] != NULL) {
				$transactionType = $cipher->decrypt($_POST['transactionType']);
				$transactionType = filter_var($transactionType, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(transactionType: '.$_POST['transactionType'].')');
				echo $error400;
				die();
			}

			if (isset($_POST['earn']) && $_POST['earn'] != NULL) {
				$earn = $cipher->decrypt($_POST['earn']);
			} else {
				api_error_logger('(earn: '.$_POST['earn'].')');
				echo $error400;
				die();
			}

			if (isset($_POST['sku']) && $_POST['sku'] != NULL) {
				$sku = $cipher->decrypt($_POST['sku']);
			}

			if (isset($_POST['raffleValue']) && $_POST['raffleValue'] != NULL) {
				$raffleValue = $cipher->decrypt($_POST['raffleValue']);
				$raffleValue = filter_var($raffleValue, FILTER_SANITIZE_NUMBER_INT);
			} else {
				api_error_logger('(raffleValue: '.$_POST['raffleValue'].')');
				echo $error400;
				die();
			}

			if (isset($_POST['raffleEntry']) && $_POST['raffleEntry'] != NULL) {
				$raffleEntry = $cipher->decrypt($_POST['raffleEntry']);
				$raffleEntry = filter_var($raffleEntry, FILTER_SANITIZE_NUMBER_INT);
			} else {
				api_error_logger('(raffleEntry: '.$_POST['raffleEntry'].')');
				echo $error400;
				die();
			}

			if (isset($_POST['ticket']) && $_POST['ticket'] != NULL) {
				$ticket = $cipher->decrypt($_POST['ticket']);
				$ticket = filter_var($ticket, FILTER_SANITIZE_NUMBER_INT);
			} else {
				api_error_logger('(ticket: '.$_POST['ticket'].')');
				echo $error400;
				die();
			}

			if (isset($_POST['version']) && $_POST['version'] != NULL) {
				$version = $cipher->decrypt($_POST['version']);
				$version = filter_var($version, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(version: '.$_POST['version'].')');
				echo $error400;
				die();
			}

			if (isset($_POST['branchCode']) && $_POST['branchCode'] != NULL) {
				$branchCode = $cipher->decrypt($_POST['branchCode']);
				$branchCode = filter_var($branchCode, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(branchCode: '.$_POST['branchCode'].')');
				echo $error400;
				die();
			}

			if (isset($_POST['terminalID']) && $_POST['terminalID'] != NULL) {
				$terminalID = $cipher->decrypt($_POST['terminalID']);
				$terminalID = filter_var($terminalID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(terminalID: '.$_POST['terminalID'].')');
				echo $error400;
				die();
			}

			echo $class->offline_earn_points($deviceID, $locID, $qrCode, $transactionID, $transactionType, $earn, $sku, $raffleValue, $raffleEntry, $ticket, $version, $branchCode, $terminalID);
			die();
			break;

		case 'redeem_voucher':
			$deviceID = "";
			$locID = "";
			$voucherID = "";
			$memberID = "";
			$transactionType = "";
			$version = "";

			if (isset($_POST['deviceID']) && $_POST['deviceID'] != NULL) {
				$deviceID = $cipher->decrypt($_POST['deviceID']);
				$deviceID = filter_var($deviceID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(deviceID: '.$_POST['deviceID'].')');
				echo $error400;
				die();
			}

			if (isset($_POST['locID']) && $_POST['locID'] != NULL) {
				$locID = $cipher->decrypt($_POST['locID']);
				$locID = filter_var($locID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(locID: '.$_POST['locID'].')');
				echo $error400;
				die();
			}

			if (isset($_POST['voucherID']) && $_POST['voucherID'] != NULL) {
				$voucherID = $cipher->decrypt($_POST['voucherID']);
				$voucherID = filter_var($voucherID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(voucherID: '.$_POST['voucherID'].')');
				echo $error400;
				die();
			}

			if (isset($_POST['memberID']) && $_POST['memberID'] != NULL) {
				$memberID = $cipher->decrypt($_POST['memberID']);
				$memberID = filter_var($memberID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(memberID: '.$_POST['memberID'].')');
				echo $error400;
				die();
			}

			if (isset($_POST['transactionType']) && $_POST['transactionType'] != NULL) {
				$transactionType = $cipher->decrypt($_POST['transactionType']);
				$transactionType = filter_var($transactionType, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

				if (($transactionType != 'APP') && ($transactionType != 'CRD')) {
					api_error_logger('(transactionType: '.$_POST['transactionType'].')');
					echo $error400;
					die();
				}
			} else {
				api_error_logger('(transactionType: '.$_POST['transactionType'].')');
				echo $error400;
				die();
			}

			if (isset($_POST['version']) && $_POST['version'] != NULL) {
				$version = $cipher->decrypt($_POST['version']);
				$version = filter_var($version, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(version: '.$_POST['version'].')');
				echo $error400;
				die();
			}

			echo $class->redeem_voucher($deviceID, $locID, $voucherID, $memberID, $transactionType, $version);
			break;

		case 'redeem_points':
			$deviceID = "";
			$locID = "";
			$loyaltyID = "";
			$memberID = "";
			$points = "";
			$transactionType = "";
			$version = "";

			if (isset($_POST['deviceID']) && $_POST['deviceID'] != NULL) {
				$deviceID = $cipher->decrypt($_POST['deviceID']);
				$deviceID = filter_var($deviceID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(deviceID: '.$_POST['deviceID'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['locID']) && $_POST['locID'] != NULL) {
				$locID = $cipher->decrypt($_POST['locID']);
				$locID = filter_var($locID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(locID: '.$_POST['locID'].')');
				echo $error400;
				die();
			}

			if (isset($_POST['loyaltyID']) && $_POST['loyaltyID'] != NULL) {
				$loyaltyID = $cipher->decrypt($_POST['loyaltyID']);
				$loyaltyID = filter_var($loyaltyID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(loyaltyID: '.$_POST['loyaltyID'].')');
				echo $error400;
				die();
			}

			if (isset($_POST['memberID']) && $_POST['memberID'] != NULL) {
				$memberID = $cipher->decrypt($_POST['memberID']);
				$memberID = filter_var($memberID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(memberID: '.$_POST['memberID'].')');
				echo $error400;
				die();
			}

			if (isset($_POST['points']) && $_POST['points'] != NULL) {
				$points = $cipher->decrypt($_POST['points']);
				$points = filter_var($points, FILTER_SANITIZE_NUMBER_INT);

				if ((int)$points < 1) {
					api_error_logger('(points: '.$_POST['points'].')');
					echo $error400;
					die();
				}
			} else {
				api_error_logger('(points: '.$_POST['points'].')');
				echo $error400;
				die();
			}

			if (isset($_POST['transactionType']) && $_POST['transactionType'] != NULL) {
				$transactionType = $cipher->decrypt($_POST['transactionType']);
				$transactionType = filter_var($transactionType, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

				if (($transactionType != 'APP') && ($transactionType != 'CRD')) {
					api_error_logger('(transactionType: '.$_POST['transactionType'].')');
					echo $error400;
					die();
				}
			} else {
				api_error_logger('(transactionType: '.$_POST['transactionType'].')');
				echo $error400;
				die();
			}

			if (isset($_POST['version']) && $_POST['version'] != NULL) {
				$version = $cipher->decrypt($_POST['version']);
				$version = filter_var($version, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(version: '.$_POST['version'].')');
				echo $error400;
				die();
			}

			echo $class->redeem_points($deviceID, $locID, $loyaltyID, $memberID, $points, $transactionType, $version);
			break;
		
		default:
			echo $error400;
			die();
			break;
	}

	/********** Invalid Access Checker **********/
	if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {
		include_once('../../logs/logs.class.php');
		$logs = NEW logs();
		$file_name = substr(strtolower(basename($_SERVER['PHP_SELF'])),0,strlen(basename($_SERVER['PHP_SELF'])));
		$logs->write_logs('Invalid Access', 'points.function.php', 'Illegal access attempt.');
		die('Access denied'); 
	}

?>