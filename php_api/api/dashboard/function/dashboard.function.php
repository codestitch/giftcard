<?php

	require_once('php_api/api/dashboard/class/dashboard.class.php');

	$class = new dashboard();

	switch ($function) {

		case 'session_checker':
			$accountID = "";
			$my_session_id = "";
			
			if (isset($_POST['accountID']) && $_POST['accountID'] != NULL) {
				$accountID = $cipher->decrypt($_POST['accountID']);
				$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(accountID: '.$_POST['accountID'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['my_session_id']) && $_POST['my_session_id'] != NULL) {
				$my_session_id = $cipher->decrypt($_POST['my_session_id']);
				$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(my_session_id: '.$_POST['my_session_id'].')');
				echo $error400;
				die();
			}

			echo $class->session_checker($accountID, $my_session_id);
			die();
			break;

		case 'login':
			$username = "";
			$password = "";
			
			if (isset($_POST['username']) && $_POST['username'] != NULL) {
				$username = $cipher->decrypt($_POST['username']);
				$username = filter_var($username, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(username: '.$_POST['username'].')');
				echo $error400;
				die();
			}

			if (isset($_POST['password']) && $_POST['password'] != NULL) {
				$password = $cipher->decrypt($_POST['password']);
				$password = filter_var($password, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(password: '.$_POST['password'].')');
				echo $error400;
				die();
			}

			echo $class->login($username, $password);
			die();
			break;

		case 'password':
			$accountID = "";
			$my_session_id = "";
			$old_password = "";
			$new_password = "";
			
			if (isset($_POST['accountID']) && $_POST['accountID'] != NULL) {
				$accountID = $cipher->decrypt($_POST['accountID']);
				$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(accountID: '.$_POST['accountID'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['my_session_id']) && $_POST['my_session_id'] != NULL) {
				$my_session_id = $cipher->decrypt($_POST['my_session_id']);
				$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(my_session_id: '.$_POST['my_session_id'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['old_password']) && $_POST['old_password'] != NULL) {
				$old_password = $cipher->decrypt($_POST['old_password']);
				$old_password = filter_var($old_password, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

				if (preg_match('/^[a-zA-Z0-9]+$/', $old_password) <= 0) {
					api_error_logger('(old_password: '.$_POST['old_password'].')');
					echo $error400;
					die();
				}
			} else {
				api_error_logger('(old_password: '.$_POST['old_password'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['new_password']) && $_POST['new_password'] != NULL) {
				$new_password = $cipher->decrypt($_POST['new_password']);
				$new_password = filter_var($new_password, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
				
				if (preg_match('/^[a-zA-Z0-9]+$/', $new_password) <= 0) {
					api_error_logger('(new_password: '.$_POST['new_password'].')');
					echo $error400;
					die();
				}
			} else {
				api_error_logger('(new_password: '.$_POST['new_password'].')');
				echo $error400;
				die();
			}

			echo $class->password($accountID, $my_session_id, $old_password, $new_password);
			die();
			break;

		case 'json':
			$accountID = "";
			$my_session_id = "";
			$table = "";
			
			if (isset($_POST['accountID']) && $_POST['accountID'] != NULL) {
				$accountID = $cipher->decrypt($_POST['accountID']);
				$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(accountID: '.$_POST['accountID'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['my_session_id']) && $_POST['my_session_id'] != NULL) {
				$my_session_id = $cipher->decrypt($_POST['my_session_id']);
				$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(my_session_id: '.$_POST['my_session_id'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['table']) && $_POST['table'] != NULL) {
				$table = $cipher->decrypt($_POST['table']);
				$table = filter_var($table, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(table: '.$_POST['table'].')');
				echo $error400;
				die();
			}

			echo $class->json($accountID, $my_session_id, $table);
			die();
			break;

		case 'send_push':
			$accountID = "";
			$my_session_id = "";
			$message = "";
			$type = "";
			
			if (isset($_POST['accountID']) && $_POST['accountID'] != NULL) {
				$accountID = $cipher->decrypt($_POST['accountID']);
				$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(accountID: '.$_POST['accountID'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['my_session_id']) && $_POST['my_session_id'] != NULL) {
				$my_session_id = $cipher->decrypt($_POST['my_session_id']);
				$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(my_session_id: '.$_POST['my_session_id'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['message']) && $_POST['message'] != NULL) {
				$message = $cipher->decrypt($_POST['message']);
				$message = filter_var($message, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(message: '.$_POST['message'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['type']) && $_POST['type'] != NULL) {
				$type = $cipher->decrypt($_POST['type']);
				$type = filter_var($type, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(type: '.$_POST['type'].')');
				echo $error400;
				die();
			}

			echo $class->send_push($accountID, $my_session_id, $message, $type);
			die();
			break;

		 

		case 'add_promo':
			$accountID = "";
			$my_session_id = "";
			$name = "";
			$description = "";
			$image = "";
			$status = "";
			
			if (isset($_POST['accountID']) && $_POST['accountID'] != NULL) {
				$accountID = $cipher->decrypt($_POST['accountID']);
				$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(accountID: '.$_POST['accountID'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['my_session_id']) && $_POST['my_session_id'] != NULL) {
				$my_session_id = $cipher->decrypt($_POST['my_session_id']);
				$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(my_session_id: '.$_POST['my_session_id'].')');
				echo $error400;
				die();
			}

			if (isset($_POST['name']) && $_POST['name'] != NULL) {
				$name = $cipher->decrypt($_POST['name']);
				$name = filter_var($name, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(name: '.$_POST['name'].')');
				echo $error400;
				die();
			}

			if (isset($_POST['description']) ) {
				$description = $cipher->decrypt($_POST['description']);
				$description = filter_var($description, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(description: '.$_POST['description'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['image']) && $_POST['image'] != NULL) {
				$image = $cipher->decrypt($_POST['image']);
				$image = filter_var($image, FILTER_VALIDATE_URL, FILTER_FLAG_PATH_REQUIRED);
			} else {
				api_error_logger('(image: '.$_POST['image'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['status']) && $_POST['status'] != NULL) {
				$status = $cipher->decrypt($_POST['status']);
				$status = filter_var($status, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

				if (($status != 'active') && ($status != 'inactive')) {
					api_error_logger('(status: '.$_POST['status'].')');
					echo $error400;
					die();
				}
			} else {
				api_error_logger('(status: '.$_POST['status'].')');
				echo $error400;
				die();
			}
			
			echo $class->add_promo($accountID, $my_session_id, $name, $description, $image, $status);
			die();
			break;

		case 'delete_promo':
			$accountID = "";
			$my_session_id = "";
			$promoID = "";
			
			if (isset($_POST['accountID']) && $_POST['accountID'] != NULL) {
				$accountID = $cipher->decrypt($_POST['accountID']);
				$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(accountID: '.$_POST['accountID'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['my_session_id']) && $_POST['my_session_id'] != NULL) {
				$my_session_id = $cipher->decrypt($_POST['my_session_id']);
				$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(my_session_id: '.$_POST['my_session_id'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['promoID']) && $_POST['promoID'] != NULL) {
				$promoID = $cipher->decrypt($_POST['promoID']);
				$promoID = filter_var($promoID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(promoID: '.$_POST['promoID'].')');
				echo $error400;
				die();
			}
			
			echo $class->delete_promo($accountID, $my_session_id, $promoID);
			die();
			break;

		case 'update_promo':
			$accountID = "";
			$my_session_id = "";
			$name = "";
			$description = "";
			$promoID = "";
			$image = "";
			$status = "";
			
			if (isset($_POST['accountID']) && $_POST['accountID'] != NULL) {
				$accountID = $cipher->decrypt($_POST['accountID']);
				$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(accountID: '.$_POST['accountID'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['my_session_id']) && $_POST['my_session_id'] != NULL) {
				$my_session_id = $cipher->decrypt($_POST['my_session_id']);
				$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(my_session_id: '.$_POST['my_session_id'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['name']) && $_POST['name'] != NULL) {
				$name = $cipher->decrypt($_POST['name']);
				$name = filter_var($name, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(name: '.$_POST['name'].')');
				echo $error400;
				die();
			}

			if (isset($_POST['description']) ) {
				$description = $cipher->decrypt($_POST['description']);
				$description = filter_var($description, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(description: '.$_POST['description'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['promoID']) && $_POST['promoID'] != NULL) {
				$promoID = $cipher->decrypt($_POST['promoID']);
				$promoID = filter_var($promoID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(promoID: '.$_POST['promoID'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['image']) && $_POST['image'] != NULL) {
				$image = $cipher->decrypt($_POST['image']);
				$image = filter_var($image, FILTER_VALIDATE_URL, FILTER_FLAG_PATH_REQUIRED);
			} else {
				// api_error_logger('(image: '.$_POST['image'].')');
				// echo $error400;
				// die();
				$image = NULL;
			}
			
			if (isset($_POST['status']) && $_POST['status'] != NULL) {
				$status = $cipher->decrypt($_POST['status']);
				$status = filter_var($status, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

				if (($status != 'active') && ($status != 'inactive')) {
					api_error_logger('(status: '.$_POST['status'].')');
					echo $error400;
					die();
				}
			} else {
				api_error_logger('(status: '.$_POST['status'].')');
				echo $error400;
				die();
			}
			
			
			echo $class->update_promo($accountID, $my_session_id, $promoID, $name, $description, $image, $status);
			die();
			break;


		case 'add_voucher':
			$accountID = "";
			$my_session_id = ""; 
			$name = "";
			$description = "";
			$limit = "";
			$type = "";
			$status = "";
			$startdate = "";
			$enddate = "";
			$image = ""; 
             
			if (isset($_POST['accountID']) && $_POST['accountID'] != NULL) {
				$accountID = $cipher->decrypt($_POST['accountID']);
				$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(accountID: '.$_POST['accountID'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['my_session_id']) && $_POST['my_session_id'] != NULL) {
				$my_session_id = $cipher->decrypt($_POST['my_session_id']);
				$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(my_session_id: '.$_POST['my_session_id'].')');
				echo $error400;
				die();
			} 
			
			if (isset($_POST['name']) ) {
				$name = $cipher->decrypt($_POST['name']);
				$name = filter_var($name, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(name: '.$_POST['name'].')');
				echo $error400;
				die();
			} 
			
			if (isset($_POST['description']) ) {
				$description = $cipher->decrypt($_POST['description']);
				$description = filter_var($description, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(description: '.$_POST['description'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['limit']) ) {
				$limit = $cipher->decrypt($_POST['limit']);
				$limit = filter_var($limit, FILTER_SANITIZE_NUMBER_INT);
			} else {
				api_error_logger('(limit: '.$_POST['limit'].')');
				echo $error400;
				die();
			} 
			
			if (isset($_POST['type']) ) {
				$type = $cipher->decrypt($_POST['type']);
				$type = filter_var($type, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(type: '.$_POST['type'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['status']) && $_POST['status'] != NULL) {
				$status = $cipher->decrypt($_POST['status']);
				$status = filter_var($status, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(status: '.$_POST['status'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['startdate']) ) {
				$startdate = $cipher->decrypt($_POST['startdate']);
				$startdate = filter_var($startdate, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(startdate: '.$_POST['startdate'].')');
				echo $error400;
				die();
			} 
			
			if (isset($_POST['enddate']) ) {
				$enddate = $cipher->decrypt($_POST['enddate']);
				$enddate = filter_var($enddate, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(enddate: '.$_POST['enddate'].')');
				echo $error400;
				die();
			}  
			
			if (isset($_POST['image']) ) {
				$image = $cipher->decrypt($_POST['image']);
				$image = filter_var($image, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(image: '.$_POST['image'].')');
				echo $error400;
				die();
			}
            
			echo $class->add_voucher($accountID, $my_session_id, $name, $description, $limit, $type, $status, $startdate, $enddate, $image);
			die();
			break; 

		case 'update_voucher':
			$accountID = "";
			$my_session_id = "";
			$voucherID = ""; 
			$name = "";
			$description = "";
			$limit = "";
			$type = "";
			$status = "";
			$startdate = "";
			$enddate = "";
			$image = "";  
             
			if (isset($_POST['accountID']) && $_POST['accountID'] != NULL) {
				$accountID = $cipher->decrypt($_POST['accountID']);
				$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(accountID: '.$_POST['accountID'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['my_session_id']) && $_POST['my_session_id'] != NULL) {
				$my_session_id = $cipher->decrypt($_POST['my_session_id']);
				$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(my_session_id: '.$_POST['my_session_id'].')');
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

			if (isset($_POST['name']) ) {
				$name = $cipher->decrypt($_POST['name']);
				$name = filter_var($name, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(name: '.$_POST['name'].')');
				echo $error400;
				die();
			} 
			
			if (isset($_POST['description']) ) {
				$description = $cipher->decrypt($_POST['description']);
				$description = filter_var($description, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(description: '.$_POST['description'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['limit']) ) {
				$limit = $cipher->decrypt($_POST['limit']);
				$limit = filter_var($limit, FILTER_SANITIZE_NUMBER_INT);
			} else {
				api_error_logger('(limit: '.$_POST['limit'].')');
				echo $error400;
				die();
			} 
			
			if (isset($_POST['type']) ) {
				$type = $cipher->decrypt($_POST['type']);
				$type = filter_var($type, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(type: '.$_POST['type'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['status']) && $_POST['status'] != NULL) {
				$status = $cipher->decrypt($_POST['status']);
				$status = filter_var($status, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(status: '.$_POST['status'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['startdate']) ) {
				$startdate = $cipher->decrypt($_POST['startdate']);
				$startdate = filter_var($startdate, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(startdate: '.$_POST['startdate'].')');
				echo $error400;
				die();
			} 
			
			if (isset($_POST['enddate']) ) {
				$enddate = $cipher->decrypt($_POST['enddate']);
				$enddate = filter_var($enddate, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(enddate: '.$_POST['enddate'].')');
				echo $error400;
				die();
			}  
			
			if (isset($_POST['image']) ) {
				$image = $cipher->decrypt($_POST['image']);
				$image = filter_var($image, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(image: '.$_POST['image'].')');
				echo $error400;
				die();
			}
            
			echo $class->update_voucher($accountID, $my_session_id, $voucherID, $name, $description, $limit, $type, $status, $startdate, $enddate, $image);
			die();
			break;

		case 'add_loyalty':
			$accountID = "";
			$my_session_id = "";
			$name = "";
			$points = "";
			$promo = "";
			$terms = "";
			$description = "";
			$status = ""; 
            
			if (isset($_POST['accountID']) && $_POST['accountID'] != NULL) {
				$accountID = $cipher->decrypt($_POST['accountID']);
				$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(accountID: '.$_POST['accountID'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['my_session_id']) && $_POST['my_session_id'] != NULL) {
				$my_session_id = $cipher->decrypt($_POST['my_session_id']);
				$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(my_session_id: '.$_POST['my_session_id'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['name']) && $_POST['name'] != NULL) {
				$name = $cipher->decrypt($_POST['name']);
				$name = filter_var($name, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(name: '.$_POST['name'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['points']) && $_POST['points'] != NULL) {
				$points = $cipher->decrypt($_POST['points']);
				$points = filter_var($points, FILTER_SANITIZE_NUMBER_INT);
			} else {
				api_error_logger('(points: '.$_POST['points'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['promo']) && $_POST['promo'] != NULL) {
				$promo = $cipher->decrypt($_POST['promo']);
				$promo = filter_var($promo, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(promo: '.$_POST['promo'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['terms']) ) {
				$terms = $cipher->decrypt($_POST['terms']);
				$terms = filter_var($terms, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(terms: '.$_POST['terms'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['description']) ) {
				$description = $cipher->decrypt($_POST['description']);
				$description = filter_var($description, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(description: '.$_POST['description'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['status']) && $_POST['status'] != NULL) {
				$status = $cipher->decrypt($_POST['status']);
				$status = filter_var($status, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(status: '.$_POST['status'].')');
				echo $error400;
				die();
			} 
            
			echo $class->add_loyalty($accountID, $my_session_id, $name, $points, $promo, $terms, $description, $status);
			die();
			break;

		case 'delete_loyalty':
			$accountID = "";
			$my_session_id = "";
			$loyaltyID = "";
			
			if (isset($_POST['accountID']) && $_POST['accountID'] != NULL) {
				$accountID = $cipher->decrypt($_POST['accountID']);
				$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(accountID: '.$_POST['accountID'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['my_session_id']) && $_POST['my_session_id'] != NULL) {
				$my_session_id = $cipher->decrypt($_POST['my_session_id']);
				$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(my_session_id: '.$_POST['my_session_id'].')');
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
			
			echo $class->delete_loyalty($accountID, $my_session_id, $loyaltyID);
			die();
			break;

		case 'update_loyalty':
			$accountID = "";
			$my_session_id = "";
			$loyaltyID = "";
			$name = "";
			$points = "";
			$promo = "";
			$terms = "";
			$description = "";
			$status = ""; 

			
			if (isset($_POST['accountID']) && $_POST['accountID'] != NULL) {
				$accountID = $cipher->decrypt($_POST['accountID']);
				$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(accountID: '.$_POST['accountID'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['my_session_id']) && $_POST['my_session_id'] != NULL) {
				$my_session_id = $cipher->decrypt($_POST['my_session_id']);
				$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(my_session_id: '.$_POST['my_session_id'].')');
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
			
			if (isset($_POST['name']) && $_POST['name'] != NULL) {
				$name = $cipher->decrypt($_POST['name']);
				$name = filter_var($name, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(name: '.$_POST['name'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['points']) && $_POST['points'] != NULL) {
				$points = $cipher->decrypt($_POST['points']);
				$points = filter_var($points, FILTER_SANITIZE_NUMBER_INT);
			} else {
				api_error_logger('(points: '.$_POST['points'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['promo']) && $_POST['promo'] != NULL) {
				$promo = $cipher->decrypt($_POST['promo']);
				$promo = filter_var($promo, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(promo: '.$_POST['promo'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['terms']) ) {
				$terms = $cipher->decrypt($_POST['terms']);
				$terms = filter_var($terms, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(terms: '.$_POST['terms'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['description'])  ) {
				$description = $cipher->decrypt($_POST['description']);
				$description = filter_var($description, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(description: '.$_POST['description'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['status']) && $_POST['status'] != NULL) {
				$status = $cipher->decrypt($_POST['status']);
				$status = filter_var($status, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(status: '.$_POST['status'].')');
				echo $error400;
				die();
			} 
            
			echo $class->update_loyalty($accountID, $my_session_id, $loyaltyID, $name, $points, $promo, $terms, $description, $status);
			die();
			break;

		 

		case 'add_post':
			$accountID = "";
			$my_session_id = "";
			$title = "";
			$description = "";
			$image = "";
			$status = "";
			
			if (isset($_POST['accountID']) && $_POST['accountID'] != NULL) {
				$accountID = $cipher->decrypt($_POST['accountID']);
				$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(accountID: '.$_POST['accountID'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['my_session_id']) && $_POST['my_session_id'] != NULL) {
				$my_session_id = $cipher->decrypt($_POST['my_session_id']);
				$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(my_session_id: '.$_POST['my_session_id'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['title']) && $_POST['title'] != NULL) {
				$title = $cipher->decrypt($_POST['title']);
				$title = filter_var($title, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(title: '.$_POST['title'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['description'])  ) {
				$description = $cipher->decrypt($_POST['description']);
				$description = filter_var($description, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(description: '.$_POST['description'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['image']) && $_POST['image'] != NULL) {
				$image = $cipher->decrypt($_POST['image']);
				$image = filter_var($image, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(image: '.$_POST['image'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['status']) && $_POST['status'] != NULL) {
				$status = $cipher->decrypt($_POST['status']);
				$status = filter_var($status, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(status: '.$_POST['status'].')');
				echo $error400;
				die();
			}
			
			echo $class->add_post($accountID, $my_session_id, $title, $description, $image, $status);
			die();
			break;

		case 'delete_post':
			$accountID = "";
			$my_session_id = "";
			$postID = "";
			
			if (isset($_POST['accountID']) && $_POST['accountID'] != NULL) {
				$accountID = $cipher->decrypt($_POST['accountID']);
				$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(accountID: '.$_POST['accountID'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['my_session_id']) && $_POST['my_session_id'] != NULL) {
				$my_session_id = $cipher->decrypt($_POST['my_session_id']);
				$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(my_session_id: '.$_POST['my_session_id'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['postID']) && $_POST['postID'] != NULL) {
				$postID = $cipher->decrypt($_POST['postID']);
				$postID = filter_var($postID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(postID: '.$_POST['postID'].')');
				echo $error400;
				die();
			}
			
			echo $class->delete_post($accountID, $my_session_id, $postID);
			die();
			break;

		case 'update_post':
			$accountID = "";
			$my_session_id = "";
			$postID = "";
			$title = "";
			$description = "";
			$image = "";
			$status = "";
			
			if (isset($_POST['accountID']) && $_POST['accountID'] != NULL) {
				$accountID = $cipher->decrypt($_POST['accountID']);
				$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(accountID: '.$_POST['accountID'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['my_session_id']) && $_POST['my_session_id'] != NULL) {
				$my_session_id = $cipher->decrypt($_POST['my_session_id']);
				$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(my_session_id: '.$_POST['my_session_id'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['postID']) && $_POST['postID'] != NULL) {
				$postID = $cipher->decrypt($_POST['postID']);
				$postID = filter_var($postID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(postID: '.$_POST['postID'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['title']) && $_POST['title'] != NULL) {
				$title = $cipher->decrypt($_POST['title']);
				$title = filter_var($title, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(title: '.$_POST['title'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['description']) ) {
				$description = $cipher->decrypt($_POST['description']);
				$description = filter_var($description, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(description: '.$_POST['description'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['image']) && $_POST['image'] != NULL) {
				$image = $cipher->decrypt($_POST['image']);
				$image = filter_var($image, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				// api_error_logger('(image: '.$_POST['image'].')');
				// echo $error400;
				// die();
				$image = NULL;
			}
			
			if (isset($_POST['status']) && $_POST['status'] != NULL) {
				$status = $cipher->decrypt($_POST['status']);
				$status = filter_var($status, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(status: '.$_POST['status'].')');
				echo $error400;
				die();
			}
			
			echo $class->update_post($accountID, $my_session_id, $postID, $title, $description, $image, $status);
			die();
			break;

		case 'add_location':
			$accountID = "";
			$my_session_id = "";
			$name = "";
			$address = "";
			$latitude = "";
			$longitude = "";
			$branch = "";
			$phone = "";
			$email = "";
			$hours = "";
			$status = "";
			$loyalty = "";
			$image = ""; 
			
			if (isset($_POST['accountID']) && $_POST['accountID'] != NULL) {
				$accountID = $cipher->decrypt($_POST['accountID']);
				$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(accountID: '.$_POST['accountID'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['my_session_id']) && $_POST['my_session_id'] != NULL) {
				$my_session_id = $cipher->decrypt($_POST['my_session_id']);
				$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(my_session_id: '.$_POST['my_session_id'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['name']) && $_POST['name'] != NULL) {
				$name = $cipher->decrypt($_POST['name']);
				$name = filter_var($name, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(name: '.$_POST['name'].')');
				echo $error400;
				die();
			}
			
			
			if (isset($_POST['address']) && $_POST['address'] != NULL) {
				$address = $cipher->decrypt($_POST['address']);
				$address = filter_var($address, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(address: '.$_POST['address'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['latitude']) && $_POST['latitude'] != NULL) {
				$latitude = $cipher->decrypt($_POST['latitude']);
				$latitude = filter_var($latitude, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(latitude: '.$_POST['latitude'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['longitude']) && $_POST['longitude'] != NULL) {
				$longitude = $cipher->decrypt($_POST['longitude']);
				$longitude = filter_var($longitude, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(longitude: '.$_POST['longitude'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['branch']) ) {
				$branch = $cipher->decrypt($_POST['branch']);
				$branch = filter_var($branch, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(branch: '.$_POST['branch'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['phone']) ) {
				$phone = $cipher->decrypt($_POST['phone']);
				$phone = filter_var($phone, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(phone: '.$_POST['phone'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['email'])  ) {
				$email = $cipher->decrypt($_POST['email']);
				$email = filter_var($email, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(email: '.$_POST['email'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['hours'])  ) {
				$hours = $cipher->decrypt($_POST['hours']);
				$hours = filter_var($hours, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(hours: '.$_POST['hours'].')');
				echo $error400;
				die();
			}
			
			
			if (isset($_POST['status']) && $_POST['status'] != NULL) {
				$status = $cipher->decrypt($_POST['status']);
				$status = filter_var($status, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(status: '.$_POST['status'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['loyalty']) && $_POST['loyalty'] != NULL) {
				$loyalty = $cipher->decrypt($_POST['loyalty']);
				$loyalty = filter_var($loyalty, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(loyalty: '.$_POST['loyalty'].')');
				echo $error400;
				die();
			} 

			if (isset($_POST['image']) ) {
				$image = $cipher->decrypt($_POST['image']);
				$image = filter_var($image, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				$image = NULL;
			} 

			echo $class->add_location($accountID, $my_session_id, $name, $address, $latitude, $longitude, $branch, $phone, $email, $hours, $status, $loyalty, $image);
			die();
			break;

		case 'update_location':
			$accountID = "";
			$my_session_id = "";
			$locID = "";
			$name = "";
			$address = "";
			$latitude = "";
			$longitude = "";
			$branch = "";
			$phone = "";
			$email = ""; 
			$hours = "";
			$status = "";
			$loyalty = "";
			$image = ""; 
			
			if (isset($_POST['accountID']) && $_POST['accountID'] != NULL) {
				$accountID = $cipher->decrypt($_POST['accountID']);
				$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(accountID: '.$_POST['accountID'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['my_session_id']) && $_POST['my_session_id'] != NULL) {
				$my_session_id = $cipher->decrypt($_POST['my_session_id']);
				$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(my_session_id: '.$_POST['my_session_id'].')');
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

			
			if (isset($_POST['name']) && $_POST['name'] != NULL) {
				$name = $cipher->decrypt($_POST['name']);
				$name = filter_var($name, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(name: '.$_POST['name'].')');
				echo $error400;
				die();
			}
			
			
			if (isset($_POST['address']) && $_POST['address'] != NULL) {
				$address = $cipher->decrypt($_POST['address']);
				$address = filter_var($address, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(address: '.$_POST['address'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['latitude']) && $_POST['latitude'] != NULL) {
				$latitude = $cipher->decrypt($_POST['latitude']);
				$latitude = filter_var($latitude, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(latitude: '.$_POST['latitude'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['longitude']) && $_POST['longitude'] != NULL) {
				$longitude = $cipher->decrypt($_POST['longitude']);
				$longitude = filter_var($longitude, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(longitude: '.$_POST['longitude'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['branch']) ) {
				$branch = $cipher->decrypt($_POST['branch']);
				$branch = filter_var($branch, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(branch: '.$_POST['branch'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['phone']) ) {
				$phone = $cipher->decrypt($_POST['phone']);
				$phone = filter_var($phone, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(phone: '.$_POST['phone'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['email']) ) {
				$email = $cipher->decrypt($_POST['email']);
				$email = filter_var($email, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(email: '.$_POST['email'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['hours']) ) {
				$hours = $cipher->decrypt($_POST['hours']);
				$hours = filter_var($hours, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(hours: '.$_POST['hours'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['status']) && $_POST['status'] != NULL) {
				$status = $cipher->decrypt($_POST['status']);
				$status = filter_var($status, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(status: '.$_POST['status'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['loyalty']) && $_POST['loyalty'] != NULL) {
				$loyalty = $cipher->decrypt($_POST['loyalty']);
				$loyalty = filter_var($loyalty, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(loyalty: '.$_POST['loyalty'].')');
				echo $error400;
				die();
			}

			if (isset($_POST['image']) ) {
				$image = $cipher->decrypt($_POST['image']);
				$image = filter_var($image, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				$image = NULL;
			} 

			
			echo $class->update_location($accountID, $my_session_id, $locID, $name, $address, $latitude, $longitude, $branch, $phone, $email, $hours, $status, $loyalty, $image);
			die();
			break;

		case 'update_locPoints':
			$accountID = "";
			$my_session_id = "";
			$locID = "";
			$points = "";
			
			if (isset($_POST['accountID']) && $_POST['accountID'] != NULL) {
				$accountID = $cipher->decrypt($_POST['accountID']);
				$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(accountID: '.$_POST['accountID'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['my_session_id']) && $_POST['my_session_id'] != NULL) {
				$my_session_id = $cipher->decrypt($_POST['my_session_id']);
				$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(my_session_id: '.$_POST['my_session_id'].')');
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


			if (isset($_POST['points']) && $_POST['points'] != NULL) {
				$points = $cipher->decrypt($_POST['points']);
				$points = filter_var($points, FILTER_SANITIZE_NUMBER_INT);
			} else {
				api_error_logger('(points: '.$_POST['points'].')');
				echo $error400;
				die();
			}

			
			echo $class->update_locPoints($accountID, $my_session_id, $locID, $points);
			die();
			break;

		case 'add_product':
			$accountID = "";
			$my_session_id = "";
			$category = "";
			$name = "";
			$description = "";
			$price = "";
			$image = "";
			$status = "";
			
			if (isset($_POST['accountID']) && $_POST['accountID'] != NULL) {
				$accountID = $cipher->decrypt($_POST['accountID']);
				$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(accountID: '.$_POST['accountID'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['my_session_id']) && $_POST['my_session_id'] != NULL) {
				$my_session_id = $cipher->decrypt($_POST['my_session_id']);
				$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(my_session_id: '.$_POST['my_session_id'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['category']) ) {
				$category = $cipher->decrypt($_POST['category']);
				$category = filter_var($category, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(category: '.$_POST['category'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['name']) && $_POST['name'] != NULL) {
				$name = $cipher->decrypt($_POST['name']);
				$name = filter_var($name, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(name: '.$_POST['name'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['description'])  ) {
				$description = $cipher->decrypt($_POST['description']);
				$description = filter_var($description, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(description: '.$_POST['description'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['price']) ) {
				$price = $cipher->decrypt($_POST['price']);
				$price = filter_var($price, FILTER_VALIDATE_FLOAT);
			} else {
				api_error_logger('(price: '.$_POST['price'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['image']) ) {
				$image = $cipher->decrypt($_POST['image']);
				$image = filter_var($image, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(image: '.$_POST['image'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['status']) && $_POST['status'] != NULL) {
				$status = $cipher->decrypt($_POST['status']);
				$status = filter_var($status, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(status: '.$_POST['status'].')');
				echo $error400;
				die();
			}

			// echo $price." in function";
			
			echo $class->add_product($accountID, $my_session_id, $category, $name, $description, $price, $image, $status);
			die();
			break;

		 
		case 'update_product':
			$accountID = "";
			$my_session_id = "";
			$prodID = "";
			$category = "";
			$name = "";
			$description = "";
			$price = "";
			$image = "";
			$status = "";
			
			if (isset($_POST['accountID']) && $_POST['accountID'] != NULL) {
				$accountID = $cipher->decrypt($_POST['accountID']);
				$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(accountID: '.$_POST['accountID'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['my_session_id']) && $_POST['my_session_id'] != NULL) {
				$my_session_id = $cipher->decrypt($_POST['my_session_id']);
				$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(my_session_id: '.$_POST['my_session_id'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['prodID']) && $_POST['prodID'] != NULL) {
				$prodID = $cipher->decrypt($_POST['prodID']);
				$prodID = filter_var($prodID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(prodID: '.$_POST['prodID'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['category']) ) {
				$category = $cipher->decrypt($_POST['category']);
				$category = filter_var($category, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(category: '.$_POST['category'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['name']) && $_POST['name'] != NULL) {
				$name = $cipher->decrypt($_POST['name']);
				$name = filter_var($name, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(name: '.$_POST['name'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['description']) ) {
				$description = $cipher->decrypt($_POST['description']);
				$description = filter_var($description, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(description: '.$_POST['description'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['price']) ) {
				$price = $cipher->decrypt($_POST['price']);
				$price = filter_var($price, FILTER_VALIDATE_FLOAT);
			} else {
				api_error_logger('(price: '.$_POST['price'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['image']) && $_POST['image'] != NULL) {
				$image = $cipher->decrypt($_POST['image']);
				$image = filter_var($image, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				// api_error_logger('(image: '.$_POST['image'].')');
				// echo $error400;
				// die();
				$image = NULL;
			}
			
			if (isset($_POST['status']) && $_POST['status'] != NULL) {
				$status = $cipher->decrypt($_POST['status']);
				$status = filter_var($status, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(status: '.$_POST['status'].')');
				echo $error400;
				die();
			}
			
			echo $class->update_product($accountID, $my_session_id, $prodID, $category, $name, $description, $price, $image, $status);
			die();
			break;


		case 'add_carddesign': 
			$name = ""; 
			$image = "";
			$status = "";
			
			if (isset($_POST['accountID']) && $_POST['accountID'] != NULL) {
				$accountID = $cipher->decrypt($_POST['accountID']);
				$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(accountID: '.$_POST['accountID'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['my_session_id']) && $_POST['my_session_id'] != NULL) {
				$my_session_id = $cipher->decrypt($_POST['my_session_id']);
				$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(my_session_id: '.$_POST['my_session_id'].')');
				echo $error400;
				die();
			} 

			if (isset($_POST['name']) && $_POST['name'] != NULL) {
				$name = $cipher->decrypt($_POST['name']);
				$name = filter_var($name, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(name: '.$_POST['name'].')');
				echo $error400;
				die();
			}

			if (isset($_POST['image']) ) {
				$image = $cipher->decrypt($_POST['image']);
				$image = filter_var($image, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(image: '.$_POST['image'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['status']) && $_POST['status'] != NULL) {
				$status = $cipher->decrypt($_POST['status']);
				$status = filter_var($status, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(status: '.$_POST['status'].')');
				echo $error400;
				die();
			} 
			
			echo $class->add_carddesign($accountID, $my_session_id, $name, $image, $status);
			die();
			break;

		 
		case 'update_carddesign': 
			$accountID = "";
			$my_session_id = "";
			$cardID = ""; 
			$name = ""; 
			$image = "";
			$status = "";
			
			if (isset($_POST['accountID']) && $_POST['accountID'] != NULL) {
				$accountID = $cipher->decrypt($_POST['accountID']);
				$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(accountID: '.$_POST['accountID'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['my_session_id']) && $_POST['my_session_id'] != NULL) {
				$my_session_id = $cipher->decrypt($_POST['my_session_id']);
				$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(my_session_id: '.$_POST['my_session_id'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['cardID']) && $_POST['cardID'] != NULL) {
				$cardID = $cipher->decrypt($_POST['cardID']);
				$cardID = filter_var($cardID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(cardID: '.$_POST['cardID'].')');
				echo $error400;
				die();
			} 
			
			if (isset($_POST['name']) && $_POST['name'] != NULL) {
				$name = $cipher->decrypt($_POST['name']);
				$name = filter_var($name, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(name: '.$_POST['name'].')');
				echo $error400;
				die();
			} 
			
			if (isset($_POST['image']) && $_POST['image'] != NULL) {
				$image = $cipher->decrypt($_POST['image']);
				$image = filter_var($image, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else { 
				$image = NULL;
			}
			
			if (isset($_POST['status']) && $_POST['status'] != NULL) {
				$status = $cipher->decrypt($_POST['status']);
				$status = filter_var($status, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(status: '.$_POST['status'].')');
				echo $error400;
				die();
			}
			
			echo $class->update_carddesign($accountID, $my_session_id, $cardID,  $name, $image, $status);
			die();
			break;


		case 'add_level':
			$accountID = "";
			$my_session_id = "";
			$name = ""; 
			$level = "";
			$min = "";
			$max = "";
			$status = ""; 
			$qoute = "";
			
			if (isset($_POST['accountID']) && $_POST['accountID'] != NULL) {
				$accountID = $cipher->decrypt($_POST['accountID']);
				$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(accountID: '.$_POST['accountID'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['my_session_id']) && $_POST['my_session_id'] != NULL) {
				$my_session_id = $cipher->decrypt($_POST['my_session_id']);
				$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(my_session_id: '.$_POST['my_session_id'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['name']) && $_POST['name'] != NULL) {
				$name = $cipher->decrypt($_POST['name']);
				$name = filter_var($name, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(name: '.$_POST['name'].')');
				echo $error400;
				die();
			} 
			
			if (isset($_POST['level']) && $_POST['level'] != NULL) {
				$level = $cipher->decrypt($_POST['level']);
				$level = filter_var($level, FILTER_SANITIZE_NUMBER_INT);
			} else {
				api_error_logger('(level: '.$_POST['level'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['min']) && $_POST['min'] != NULL ) {
				$min = $cipher->decrypt($_POST['min']);
				$min = filter_var($min, FILTER_SANITIZE_NUMBER_INT);
			} else {
				api_error_logger('(min: '.$_POST['min'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['max']) && $_POST['max'] != NULL ) {
				$max = $cipher->decrypt($_POST['max']);
				$max = filter_var($max, FILTER_SANITIZE_NUMBER_INT);
			} else {
				api_error_logger('(max: '.$_POST['max'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['status']) && $_POST['status'] != NULL) {
				$status = $cipher->decrypt($_POST['status']);
				$status = filter_var($status, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(status: '.$_POST['status'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['qoute']) ) {
				$qoute = $cipher->decrypt($_POST['qoute']);
				$qoute = filter_var($qoute, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(qoute: '.$_POST['qoute'].')');
				echo $error400;
				die();
			} 

			echo $class->add_level($accountID, $my_session_id, $name, $level, $min, $max, $status, $qoute);
			die();
			break;

		case 'update_level':
			$accountID = "";
			$my_session_id = "";
			$levelID = "";
			$name = ""; 
			$level = "";
			$min = "";
			$max = "";
			$status = ""; 
			$qoute = ""; 
			
			if (isset($_POST['accountID']) && $_POST['accountID'] != NULL) {
				$accountID = $cipher->decrypt($_POST['accountID']);
				$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(accountID: '.$_POST['accountID'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['my_session_id']) && $_POST['my_session_id'] != NULL) {
				$my_session_id = $cipher->decrypt($_POST['my_session_id']);
				$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(my_session_id: '.$_POST['my_session_id'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['levelID']) && $_POST['levelID'] != NULL) {
				$levelID = $cipher->decrypt($_POST['levelID']);
				$levelID = filter_var($levelID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(levelID: '.$_POST['levelID'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['name']) && $_POST['name'] != NULL) {
				$name = $cipher->decrypt($_POST['name']);
				$name = filter_var($name, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(name: '.$_POST['name'].')');
				echo $error400;
				die();
			} 
			
			if (isset($_POST['level']) && $_POST['level'] != NULL) {
				$level = $cipher->decrypt($_POST['level']);
				$level = filter_var($level, FILTER_SANITIZE_NUMBER_INT);
			} else {
				api_error_logger('(level: '.$_POST['level'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['min']) && $_POST['min'] != NULL ) {
				$min = $cipher->decrypt($_POST['min']);
				$min = filter_var($min, FILTER_SANITIZE_NUMBER_INT);
			} else {
				api_error_logger('(min: '.$_POST['min'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['max']) && $_POST['max'] != NULL ) {
				$max = $cipher->decrypt($_POST['max']);
				$max = filter_var($max, FILTER_SANITIZE_NUMBER_INT);
			} else {
				api_error_logger('(max: '.$_POST['max'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['status']) && $_POST['status'] != NULL) {
				$status = $cipher->decrypt($_POST['status']);
				$status = filter_var($status, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(status: '.$_POST['status'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['qoute']) ) {
				$qoute = $cipher->decrypt($_POST['qoute']);
				$qoute = filter_var($qoute, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(qoute: '.$_POST['qoute'].')');
				echo $error400;
				die();
			} 
			
			echo $class->update_level($accountID, $my_session_id, $levelID, $name, $level, $min, $max, $status, $qoute);
			die();
			break;


		case 'add_category':
			$accountID = "";
			$my_session_id = "";
			$name = "";
			$icon = "";
			$image = "";
			$status = "";
			
			if (isset($_POST['accountID']) && $_POST['accountID'] != NULL) {
				$accountID = $cipher->decrypt($_POST['accountID']);
				$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(accountID: '.$_POST['accountID'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['my_session_id']) && $_POST['my_session_id'] != NULL) {
				$my_session_id = $cipher->decrypt($_POST['my_session_id']);
				$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(my_session_id: '.$_POST['my_session_id'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['name']) && $_POST['name'] != NULL) {
				$name = $cipher->decrypt($_POST['name']);
				$name = filter_var($name, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(name: '.$_POST['name'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['image']) && $_POST['image'] != NULL) {
				$image = $cipher->decrypt($_POST['image']);
				$image = filter_var($image, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(image: '.$_POST['image'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['icon'])) {
				$icon = $cipher->decrypt($_POST['icon']);
				$icon = filter_var($icon, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(icon: '.$_POST['icon'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['status']) && $_POST['status'] != NULL) {
				$status = $cipher->decrypt($_POST['status']);
				$status = filter_var($status, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(status: '.$_POST['status'].')');
				echo $error400;
				die();
			}
			
			echo $class->add_category($accountID, $my_session_id, $name, $image, $icon, $status);
			die();
			break;

		case 'update_category':
			$accountID = "";
			$my_session_id = "";
			$categoryID = "";
			$name = "";
			$icon = "";
			$image = "";
			$status = "";
			
			if (isset($_POST['accountID']) && $_POST['accountID'] != NULL) {
				$accountID = $cipher->decrypt($_POST['accountID']);
				$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(accountID: '.$_POST['accountID'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['my_session_id']) && $_POST['my_session_id'] != NULL) {
				$my_session_id = $cipher->decrypt($_POST['my_session_id']);
				$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(my_session_id: '.$_POST['my_session_id'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['categoryID']) && $_POST['categoryID'] != NULL) {
				$categoryID = $cipher->decrypt($_POST['categoryID']);
				$categoryID = filter_var($categoryID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(categoryID: '.$_POST['categoryID'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['name']) && $_POST['name'] != NULL) {
				$name = $cipher->decrypt($_POST['name']);
				$name = filter_var($name, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(name: '.$_POST['name'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['icon'])  ) {
				$icon = $cipher->decrypt($_POST['icon']);
				$icon = filter_var($icon, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(icon: '.$_POST['icon'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['image']) && $_POST['image'] != NULL) {
				$image = $cipher->decrypt($_POST['image']);
				$image = filter_var($image, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				// api_error_logger('(image: '.$_POST['image'].')');
				// echo $error400;
				// die();
				$image = NULL;
			}
			
			if (isset($_POST['status']) && $_POST['status'] != NULL) {
				$status = $cipher->decrypt($_POST['status']);
				$status = filter_var($status, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(status: '.$_POST['status'].')');
				echo $error400;
				die();
			}
			
			echo $class->update_category($accountID, $my_session_id, $categoryID, $name, $icon, $image, $status);
			die();
			break;



		case 'add_subcategory':
			$accountID = "";
			$my_session_id = "";
			$name = "";
			$categoryID = "";
			$icon = "";
			$image = "";
			$status = "";
			
			if (isset($_POST['accountID']) && $_POST['accountID'] != NULL) {
				$accountID = $cipher->decrypt($_POST['accountID']);
				$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(accountID: '.$_POST['accountID'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['my_session_id']) && $_POST['my_session_id'] != NULL) {
				$my_session_id = $cipher->decrypt($_POST['my_session_id']);
				$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(my_session_id: '.$_POST['my_session_id'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['name']) && $_POST['name'] != NULL) {
				$name = $cipher->decrypt($_POST['name']);
				$name = filter_var($name, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(name: '.$_POST['name'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['categoryID']) && $_POST['categoryID'] != NULL) {
				$categoryID = $cipher->decrypt($_POST['categoryID']);
				$categoryID = filter_var($categoryID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(categoryID: '.$_POST['categoryID'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['image']) ) {
				$image = $cipher->decrypt($_POST['image']);
				$image = filter_var($image, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				$image = NULL;
			}
			
			if (isset($_POST['icon'])) {
				$icon = $cipher->decrypt($_POST['icon']);
				$icon = filter_var($icon, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(icon: '.$_POST['icon'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['status']) && $_POST['status'] != NULL) {
				$status = $cipher->decrypt($_POST['status']);
				$status = filter_var($status, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(status: '.$_POST['status'].')');
				echo $error400;
				die();
			}
			
			echo $class->add_subcategory($accountID, $my_session_id, $name, $categoryID, $image, $icon, $status);
			die();
			break;

		case 'update_subcategory':
			$accountID = "";
			$my_session_id = "";
			$subcategoryID = "";
			$categoryID = "";
			$name = "";
			$icon = "";
			$image = "";
			$status = "";
			
			if (isset($_POST['accountID']) && $_POST['accountID'] != NULL) {
				$accountID = $cipher->decrypt($_POST['accountID']);
				$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(accountID: '.$_POST['accountID'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['my_session_id']) && $_POST['my_session_id'] != NULL) {
				$my_session_id = $cipher->decrypt($_POST['my_session_id']);
				$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(my_session_id: '.$_POST['my_session_id'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['subcategoryID']) && $_POST['subcategoryID'] != NULL) {
				$subcategoryID = $cipher->decrypt($_POST['subcategoryID']);
				$subcategoryID = filter_var($subcategoryID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(subcategoryID: '.$_POST['subcategoryID'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['categoryID']) && $_POST['categoryID'] != NULL) {
				$categoryID = $cipher->decrypt($_POST['categoryID']);
				$categoryID = filter_var($categoryID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(categoryID: '.$_POST['categoryID'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['name']) && $_POST['name'] != NULL) {
				$name = $cipher->decrypt($_POST['name']);
				$name = filter_var($name, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(name: '.$_POST['name'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['icon'])  ) {
				$icon = $cipher->decrypt($_POST['icon']);
				$icon = filter_var($icon, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(icon: '.$_POST['icon'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['image']) && $_POST['image'] != NULL) {
				$image = $cipher->decrypt($_POST['image']);
				$image = filter_var($image, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				// api_error_logger('(image: '.$_POST['image'].')');
				// echo $error400;
				// die();
				$image = NULL;
			}
			
			if (isset($_POST['status']) && $_POST['status'] != NULL) {
				$status = $cipher->decrypt($_POST['status']);
				$status = filter_var($status, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(status: '.$_POST['status'].')');
				echo $error400;
				die();
			}
			
			echo $class->update_subcategory($accountID, $my_session_id, $subcategoryID, $categoryID, $name, $icon, $image, $status);
			die();
			break;

		case 'view_record':
			$accountID = "";
			$my_session_id = "";
			$table = "";
			$recordID = "";
			
			if (isset($_POST['accountID']) && $_POST['accountID'] != NULL) {
				$accountID = $cipher->decrypt($_POST['accountID']);
				$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(accountID: '.$_POST['accountID'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['my_session_id']) && $_POST['my_session_id'] != NULL) {
				$my_session_id = $cipher->decrypt($_POST['my_session_id']);
				$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(my_session_id: '.$_POST['my_session_id'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['table']) && $_POST['table'] != NULL) {
				$table = $cipher->decrypt($_POST['table']);
				$table = filter_var($table, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(table: '.$_POST['table'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['recordID']) && $_POST['recordID'] != NULL) {
				$recordID = $cipher->decrypt($_POST['recordID']);
				$recordID = filter_var($recordID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(recordID: '.$_POST['recordID'].')');
				echo $error400;
				die();
			}
			
			echo $class->view_record($accountID, $my_session_id, $table, $recordID);
			die();
			break;

		case 'update_profile_info':
			$accountID = "";
			$my_session_id = "";
			$company = "";
			$fname1 = "";
			$mname1 = "";
			$lname1 = "";
			$fname2 = "";
			$mname2 = "";
			$lname2 = "";
			$landline1 = "";
			$landline2 = "";
			$mobile1 = "";
			$mobile2 = "";
			$fax1 = "";
			$fax2 = "";
			$email = "";
			$address = "";
			$website = "";
			$about = "";
            $merchantCode = "";
			
			if (isset($_POST['accountID']) && $_POST['accountID'] != NULL) {
				$accountID = $cipher->decrypt($_POST['accountID']);
				$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(accountID: '.$_POST['accountID'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['my_session_id']) && $_POST['my_session_id'] != NULL) {
				$my_session_id = $cipher->decrypt($_POST['my_session_id']);
				$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(my_session_id: '.$_POST['my_session_id'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['company']) && $_POST['company'] != NULL) {
				$company = $cipher->decrypt($_POST['company']);
				$company = filter_var($company, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(company: '.$_POST['company'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['fname1'])  ) {
				$fname1 = $cipher->decrypt($_POST['fname1']);
				$fname1 = filter_var($fname1, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(fname1: '.$_POST['fname1'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['mname1'])  ) {
				$mname1 = $cipher->decrypt($_POST['mname1']);
				$mname1 = filter_var($mname1, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				// api_error_logger('(mname1: '.$_POST['mname1'].')');
				// echo $error400;
				// die();
				$mname1 = NULL;
			}
			
			if (isset($_POST['lname1'])  ) {
				$lname1 = $cipher->decrypt($_POST['lname1']);
				$lname1 = filter_var($lname1, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(lname1: '.$_POST['lname1'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['fname2'])  ) {
				$fname2 = $cipher->decrypt($_POST['fname2']);
				$fname2 = filter_var($fname2, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				// api_error_logger('(fname2: '.$_POST['fname2'].')');
				// echo $error400;
				// die();
				$fname2 = NULL;
			}
			
			if (isset($_POST['mname2']) ) {
				$mname2 = $cipher->decrypt($_POST['mname2']);
				$mname2 = filter_var($mname2, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				// api_error_logger('(mname2: '.$_POST['mname2'].')');
				// echo $error400;
				// die();
				$mname2 = NULL;
			}
			
			if (isset($_POST['lname2'])  ) {
				$lname2 = $cipher->decrypt($_POST['lname2']);
				$lname2 = filter_var($lname2, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				// api_error_logger('(lname2: '.$_POST['lname2'].')');
				// echo $error400;
				// die();
				$lname2 = NULL;
			}
			
			if (isset($_POST['landline1'])  ) {
				$landline1 = $cipher->decrypt($_POST['landline1']);
				$landline1 = filter_var($landline1, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				// api_error_logger('(landline1: '.$_POST['landline1'].')');
				// echo $error400;
				// die();
				$landline1 = NULL;
			}
			
			if (isset($_POST['landline2'])  ) {
				$landline2 = $cipher->decrypt($_POST['landline2']);
				$landline2 = filter_var($landline2, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				// api_error_logger('(landline2: '.$_POST['landline2'].')');
				// echo $error400;
				// die();
				$landline2 = NULL;
			}
			
			if (isset($_POST['mobile1'])  ) {
				$mobile1 = $cipher->decrypt($_POST['mobile1']);
				$mobile1 = filter_var($mobile1, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				// api_error_logger('(mobile1: '.$_POST['mobile1'].')');
				// echo $error400;
				// die();
				$mobile1 = NULL;
			}
			
			if (isset($_POST['mobile2'])  ) {
				$mobile2 = $cipher->decrypt($_POST['mobile2']);
				$mobile2 = filter_var($mobile2, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				// api_error_logger('(mobile2: '.$_POST['mobile2'].')');
				// echo $error400;
				// die();
				$mobile2 = NULL;
			}
			
			if (isset($_POST['fax1'])  ) {
				$fax1 = $cipher->decrypt($_POST['fax1']);
				$fax1 = filter_var($fax1, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				// api_error_logger('(fax1: '.$_POST['fax1'].')');
				// echo $error400;
				// die();
				$fax1 = NULL;
			}
			
			if (isset($_POST['fax2'])  ) {
				$fax2 = $cipher->decrypt($_POST['fax2']);
				$fax2 = filter_var($fax2, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				// api_error_logger('(fax2: '.$_POST['fax2'].')');
				// echo $error400;
				// die();
				$fax2 = NULL;
			}

			if (isset($_POST['email'])  ) {
				$email = $cipher->decrypt($_POST['email']);
				$email = strtolower(filter_var($email, FILTER_SANITIZE_EMAIL));
			} else {
				api_error_logger('(email: '.$_POST['email'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['address'])  ) {
				$address = $cipher->decrypt($_POST['address']);
				$address = filter_var($address, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				// api_error_logger('(address: '.$_POST['address'].')');
				// echo $error400;
				// die();
				$address = NULL;
			}
			
			if (isset($_POST['website'])  ) {
				$website = $cipher->decrypt($_POST['website']);
				$website = filter_var($website, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				// api_error_logger('(website: '.$_POST['website'].')');
				// echo $error400;
				// die();
				$website = NULL;
			}
			
			if (isset($_POST['about']) && $_POST['about'] != NULL) {
				$about = $cipher->decrypt($_POST['about']);
				// $about = filter_var($about, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(about: '.$_POST['about'].')');
				echo $error400;
				die();
			}
            
            
            if (isset($_POST['merchantCode']) && $_POST['merchantCode'] != NULL) {
				$merchantCode = $cipher->decrypt($_POST['merchantCode']);
				$merchantCode = filter_var($merchantCode, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				// api_error_logger('(merchantCode: '.$_POST['merchantCode'].')');
				// echo $error400;
				// die();
				$merchantCode = NULL;
			}
			
			echo $class->update_profile_info($accountID, $my_session_id, $company, $fname1, $mname1, $lname1, $fname2, $mname2, $lname2, $landline1, $landline2, $mobile1, $mobile2, $fax1, $fax2, $email, $address, $website, $about, $merchantCode);
			die();
			break;

		case 'update_profile_logo':
			$accountID = "";
			$my_session_id = "";
			$profilePic = "";
			
			if (isset($_POST['accountID']) && $_POST['accountID'] != NULL) {
				$accountID = $cipher->decrypt($_POST['accountID']);
				$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(accountID: '.$_POST['accountID'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['my_session_id']) && $_POST['my_session_id'] != NULL) {
				$my_session_id = $cipher->decrypt($_POST['my_session_id']);
				$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(my_session_id: '.$_POST['my_session_id'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['profilePic']) ) {
				$profilePic = $cipher->decrypt($_POST['profilePic']);
				$profilePic = filter_var($profilePic, FILTER_VALIDATE_URL, FILTER_FLAG_PATH_REQUIRED);
			} else {
				api_error_logger('(profilePic: '.$_POST['profilePic'].')');
				echo $error400;
				die();
			}
			
			echo $class->update_profile_logo($accountID, $my_session_id, $profilePic);
			die();
			break;

		case 'update_profile_loyalty':
			$accountID = "";
			$my_session_id = "";
			$merchantCode = "";
			$baseValue = "";
			$basePoint = "";
			$regPoint = "";
			$raffleValue = "";
			$raffleEntry = "";
			$raffleStatus = "";
			$nonCash_status = "";
			$nonCash_key = "";
			$baseValue_nonCash = "";
			$basePoint_nonCash = "";
			
			if (isset($_POST['accountID']) && ($_POST['accountID'] != NULL)) {
				$accountID = $cipher->decrypt($_POST['accountID']);
				$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(accountID: '.$_POST['accountID'].')');
				echo $error400 . "igit 1";
				die();
			}
			
			if (isset($_POST['my_session_id']) && ($_POST['my_session_id'] != NULL)) {
				$my_session_id = $cipher->decrypt($_POST['my_session_id']);
				$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(my_session_id: '.$_POST['my_session_id'].')');
				echo $error400 . "igit 2";
				die();
			}
			
			if (isset($_POST['merchantCode']) && ($_POST['merchantCode'] != NULL)) {
				$merchantCode = $cipher->decrypt($_POST['merchantCode']);
				// $merchantCode = filter_var($merchantCode, FILTER_VALIDATE_URL, FILTER_FLAG_PATH_REQUIRED);
			} else {
				api_error_logger('(merchantCode: '.$_POST['merchantCode'].')');
				echo $error400 . "igit 3";
				die();
			}
			
			if (isset($_POST['baseValue']) && ($_POST['baseValue'] != NULL)) {
				$baseValue = $cipher->decrypt($_POST['baseValue']);
				$baseValue = filter_var($baseValue, FILTER_SANITIZE_NUMBER_INT);
			} else {
				api_error_logger('(baseValue: '.$_POST['baseValue'].')');
				echo $error400 . "igit 4";
				die();
			}
			
			if (isset($_POST['basePoint']) && ($_POST['basePoint'] != NULL)) {
				$basePoint = $cipher->decrypt($_POST['basePoint']);
				$basePoint = filter_var($basePoint, FILTER_SANITIZE_NUMBER_INT);
			} else {
				api_error_logger('(basePoint: '.$_POST['basePoint'].')');
				echo $error400 . "igit 5";
				die();
			}
			
			if (isset($_POST['regPoint']) && ($_POST['regPoint'] != NULL)) {
				$regPoint = $cipher->decrypt($_POST['regPoint']);
				$regPoint = filter_var($regPoint, FILTER_SANITIZE_NUMBER_INT);
			} else {
				api_error_logger('(regPoint: '.$_POST['regPoint'].')');
				echo $error400 . "igit 6";
				die();
			}
			
			if (isset($_POST['raffleValue']) && ($_POST['raffleValue'] != NULL)) {
				$raffleValue = $cipher->decrypt($_POST['raffleValue']);
				$raffleValue = filter_var($raffleValue, FILTER_SANITIZE_NUMBER_INT);
			} else {
				api_error_logger('(raffleValue: '.$_POST['raffleValue'].')');
				echo $error400 . "igit 11";
				die();
			}
			
			if (isset($_POST['raffleEntry']) && ($_POST['raffleEntry'] != NULL)) {
				$raffleEntry = $cipher->decrypt($_POST['raffleEntry']);
				$raffleEntry = filter_var($raffleEntry, FILTER_SANITIZE_NUMBER_INT);
			} else {
				api_error_logger('(raffleEntry: '.$_POST['raffleEntry'].')');
				echo $error400 . "igit 12";
				die();
			}
			
			if (isset($_POST['raffleStatus']) && ($_POST['raffleStatus'] != NULL)) {
				$raffleStatus = $cipher->decrypt($_POST['raffleStatus']);
				$raffleStatus = filter_var($raffleStatus, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(raffleStatus: '.$_POST['raffleStatus'].')');
				echo $error400 . "igit 13";
				die();
			}
			
			if (isset($_POST['nonCash_status']) && ($_POST['nonCash_status'] != NULL)) {
				$nonCash_status = $cipher->decrypt($_POST['nonCash_status']);
				$nonCash_status = filter_var($nonCash_status, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(nonCash_status: '.$_POST['nonCash_status'].')');
				echo $error400 . "igit 14";
				die();
			}
			
			if (isset($_POST['nonCash_key']) && ($_POST['nonCash_key'] != NULL)) {
				$nonCash_key = $cipher->decrypt($_POST['nonCash_key']);
				$nonCash_key = filter_var($nonCash_key, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(nonCash_key: '.$_POST['nonCash_key'].')');
				echo $error400 . "igit 15";
				die();
			}
			
			if (isset($_POST['baseValue_nonCash']) && ($_POST['baseValue_nonCash'] != NULL)) {
				$baseValue_nonCash = $cipher->decrypt($_POST['baseValue_nonCash']);
				$baseValue_nonCash = filter_var($baseValue_nonCash, FILTER_SANITIZE_NUMBER_INT);
			} else {
				api_error_logger('(baseValue_nonCash: '.$_POST['baseValue_nonCash'].')');
				echo $error400 . "igit 16";
				die();
			}
			
			if (isset($_POST['basePoint_nonCash']) && ($_POST['basePoint_nonCash'] != NULL)) {
				$basePoint_nonCash = $cipher->decrypt($_POST['basePoint_nonCash']);
				$basePoint_nonCash = filter_var($basePoint_nonCash, FILTER_SANITIZE_NUMBER_INT);
			} else {
				api_error_logger('(basePoint_nonCash: '.$_POST['basePoint_nonCash'].')');
				echo $error400 . "igit 21s";
				die();
			}
			
			echo $class->update_profile_loyalty($accountID, $my_session_id, $merchantCode, $baseValue, $basePoint, $regPoint, $raffleValue, $raffleEntry, $raffleStatus, $nonCash_status, $nonCash_key, $baseValue_nonCash, $basePoint_nonCash);
			die();
			break;

		

		case 'add_faq':
			$accountID = "";
			$my_session_id = ""; 
			$question = "";
			$answer = "";
			$status = ""; 
			
			if (isset($_POST['accountID']) && $_POST['accountID'] != NULL) {
				$accountID = $cipher->decrypt($_POST['accountID']);
				$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(accountID: '.$_POST['accountID'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['my_session_id']) && $_POST['my_session_id'] != NULL) {
				$my_session_id = $cipher->decrypt($_POST['my_session_id']);
				$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(my_session_id: '.$_POST['my_session_id'].')');
				echo $error400;
				die();
			} 

			if (isset($_POST['question']) ) {
				$question = $cipher->decrypt($_POST['question']);
				$question = filter_var($question, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(question: '.$_POST['question'].')');
				echo $error400;
				die();
			} 
			
			if (isset($_POST['answer']) ) {
				$answer = $cipher->decrypt($_POST['answer']);
				$answer = filter_var($answer, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(answer: '.$_POST['answer'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['status']) && $_POST['status'] != NULL) {
				$status = $cipher->decrypt($_POST['status']);
				$status = filter_var($status, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(status: '.$_POST['status'].')');
				echo $error400;
				die();
			} 

			echo $class->add_faq($accountID, $my_session_id, $question, $answer, $status );
			die();
			break; 

		case 'update_faq':
			$accountID = "";
			$my_session_id = "";
			$faqID = "";   
			$question = "";
			$answer = "";
			$status = ""; 
			
			if (isset($_POST['accountID']) && $_POST['accountID'] != NULL) {
				$accountID = $cipher->decrypt($_POST['accountID']);
				$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(accountID: '.$_POST['accountID'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['my_session_id']) && $_POST['my_session_id'] != NULL) {
				$my_session_id = $cipher->decrypt($_POST['my_session_id']);
				$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(my_session_id: '.$_POST['my_session_id'].')');
				echo $error400;
				die();
			} 
			
			if (isset($_POST['faqID']) && $_POST['faqID'] != NULL) {
				$faqID = $cipher->decrypt($_POST['faqID']);
				$faqID = filter_var($faqID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(faqID: '.$_POST['faqID'].')');
				echo $error400;
				die();
			} 
 
			if (isset($_POST['question']) ) {
				$question = $cipher->decrypt($_POST['question']);
				$question = filter_var($question, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(question: '.$_POST['question'].')');
				echo $error400;
				die();
			} 
			
			if (isset($_POST['answer']) ) {
				$answer = $cipher->decrypt($_POST['answer']);
				$answer = filter_var($answer, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(answer: '.$_POST['answer'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['status']) && $_POST['status'] != NULL) {
				$status = $cipher->decrypt($_POST['status']);
				$status = filter_var($status, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(status: '.$_POST['status'].')');
				echo $error400;
				die();
			} 
			
			
			echo $class->update_faq($accountID, $my_session_id, $faqID, $question, $answer, $status);
			die();
			break;

		case 'add_sku':

			$accountID = "";
			$my_session_id = "";
			$name = "";
			$skuCode = "";
			$price = "";
			$promoType = "";
			$points = "";
			$description = "";
			$status = "";
			$group = "";
			$loyaltyID = "";

			if (isset($_POST['accountID']) && $_POST['accountID'] != NULL) {
				$accountID = $cipher->decrypt($_POST['accountID']);
				$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(accountID: '.$_POST['accountID'].')');
				echo $error400;
				die();
			}

			if (isset($_POST['my_session_id']) && $_POST['my_session_id'] != NULL) {
				$my_session_id = $cipher->decrypt($_POST['my_session_id']);
				$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(my_session_id: '.$_POST['my_session_id'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['name']) && $_POST['name'] != NULL) {
				$name = $cipher->decrypt($_POST['name']);
				$name = filter_var($name, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(name: '.$_POST['name'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['skuCode']) && $_POST['skuCode'] != NULL) {
				$skuCode = $cipher->decrypt($_POST['skuCode']);
				$skuCode = filter_var($skuCode, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(skuCode: '.$_POST['skuCode'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['price']) && $_POST['price'] != NULL) {
				$price = $cipher->decrypt($_POST['price']);
				$price = filter_var($price, FILTER_SANITIZE_NUMBER_INT);
			} else {
				api_error_logger('(price: '.$_POST['price'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['promoType']) && $_POST['promoType'] != NULL) {
				$promoType = $cipher->decrypt($_POST['promoType']);
				$promoType = filter_var($promoType, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(promoType: '.$_POST['promoType'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['points']) && $_POST['points'] != NULL) {
				$points = $cipher->decrypt($_POST['points']);
				$points = filter_var($points, FILTER_SANITIZE_NUMBER_INT);
			} else {
				api_error_logger('(points: '.$_POST['points'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['description'])  ) {
				$description = $cipher->decrypt($_POST['description']);
				$description = filter_var($description, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(description: '.$_POST['description'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['status']) && $_POST['status'] != NULL) {
				$status = $cipher->decrypt($_POST['status']);
				$status = filter_var($status, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(status: '.$_POST['status'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['group']) ) {
				$group = $cipher->decrypt($_POST['group']);
				$group = filter_var($group, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(group: '.$_POST['group'].')');
				echo $error400;
				die();
			}
            
			
			if (isset($_POST['loyaltyID']) ) {
				$loyaltyID = $cipher->decrypt($_POST['loyaltyID']);
				$loyaltyID = filter_var($loyaltyID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(loyaltyID: '.$_POST['loyaltyID'].')');
				echo $error400;
				die();
			}
 
			echo $class->add_sku($accountID, $my_session_id, $name, $skuCode, $price, $promoType, $points, $description, $status, $group, $loyaltyID);
			die();
			break;

		case 'delete_sku' :

			$accountID = "";
			$my_session_id = "";
			$skuID = "";
			
			if (isset($_POST['accountID']) && $_POST['accountID'] != NULL) {
				$accountID = $cipher->decrypt($_POST['accountID']);
				$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(accountID: '.$_POST['accountID'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['my_session_id']) && $_POST['my_session_id'] != NULL) {
				$my_session_id = $cipher->decrypt($_POST['my_session_id']);
				$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(my_session_id: '.$_POST['my_session_id'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['skuID']) && $_POST['skuID'] != NULL) {
				$skuID = $cipher->decrypt($_POST['skuID']);
				$skuID = filter_var($skuID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(skuID: '.$_POST['skuID'].')');
				echo $error400;
				die();
			}
			
			echo $class->delete_sku($accountID, $my_session_id, $skuID);
			die();
			break;


		case 'update_sku':
			$accountID = "";
			$my_session_id = "";
			$skuID = "";
			$skuCode = "";
			$name = "";
			$price = "";
			$points = "";
			$description = "";
			$status = "";
			$group = "";
			$loyaltyID = "";
            $promoType = "";
			
			if (isset($_POST['accountID']) && $_POST['accountID'] != NULL) {
				$accountID = $cipher->decrypt($_POST['accountID']);
				$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(accountID: '.$_POST['accountID'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['my_session_id']) && $_POST['my_session_id'] != NULL) {
				$my_session_id = $cipher->decrypt($_POST['my_session_id']);
				$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(my_session_id: '.$_POST['my_session_id'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['skuID']) && $_POST['skuID'] != NULL) {
				$skuID = $cipher->decrypt($_POST['skuID']);
				$skuID = filter_var($skuID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(skuID: '.$_POST['skuID'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['name']) && $_POST['name'] != NULL) {
				$name = $cipher->decrypt($_POST['name']);
				$name = filter_var($name, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(name: '.$_POST['name'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['skuCode']) && $_POST['skuCode'] != NULL) {
				$skuCode = $cipher->decrypt($_POST['skuCode']);
				$skuCode = filter_var($skuCode, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(skuCode: '.$_POST['skuCode'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['price']) && $_POST['price'] != NULL) {
				$price = $cipher->decrypt($_POST['price']);
				$price = filter_var($price, FILTER_SANITIZE_NUMBER_INT);
			} else {
				api_error_logger('(price: '.$_POST['price'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['points']) ) {
				$points = $cipher->decrypt($_POST['points']);
				$points = filter_var($points, FILTER_SANITIZE_NUMBER_INT);
			} else {
				api_error_logger('(points: '.$_POST['points'].')');
				echo $error400;
				die();
			}

			if (isset($_POST['description']) ) {
				$description = $cipher->decrypt($_POST['description']);
				$description = filter_var($description, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(description: '.$_POST['description'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['status']) && $_POST['status'] != NULL) {
				$status = $cipher->decrypt($_POST['status']);
				$status = filter_var($status, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(status: '.$_POST['status'].')');
				echo $error400;
				die();
			}

			
			if (isset($_POST['group']) ) {
				$group = $cipher->decrypt($_POST['group']);
				$group = filter_var($group, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(group: '.$_POST['group'].')');
				echo $error400;
				die();
			}
            
			
			if (isset($_POST['loyaltyID']) ) {
				$loyaltyID = $cipher->decrypt($_POST['loyaltyID']);
				$loyaltyID = filter_var($loyaltyID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(loyaltyID: '.$_POST['loyaltyID'].')');
				echo $error400;
				die();
			}
			
			
			if (isset($_POST['promoType']) && $_POST['promoType'] != NULL) {
				$promoType = $cipher->decrypt($_POST['promoType']);
				$promoType = filter_var($promoType, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(promoType: '.$_POST['promoType'].')');
				echo $error400;
				die();
			}

            
			echo $class->update_sku($accountID, $my_session_id, $skuID, $name, $skuCode, $price, $points, 
                                    $description, $status, $group, $loyaltyID, $promoType);
			die();
			break;

		// ************ ADD TABLET **************
		case 'add_tablet':

			$accountID = "";
			$my_session_id = "";
			$locID = "";
			$status = "";

			if (isset($_POST['accountID']) && $_POST['accountID'] != NULL) {
				$accountID = $cipher->decrypt($_POST['accountID']);
				$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(accountID: '.$_POST['accountID'].')');
				echo $error400;
				die();
			}

			if (isset($_POST['my_session_id']) && $_POST['my_session_id'] != NULL) {
				$my_session_id = $cipher->decrypt($_POST['my_session_id']);
				$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(my_session_id: '.$_POST['my_session_id'].')');
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
			
			if (isset($_POST['status']) && $_POST['status'] != NULL) {
				$status = $cipher->decrypt($_POST['status']);
				$status = filter_var($status, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(status: '.$_POST['status'].')');
				echo $error400;
				die();
			}

			echo $class->add_tablet($accountID, $my_session_id, $locID, $status);
			die();
			break;

		case 'delete_tablet' :

			$accountID = "";
			$my_session_id = "";
			$deviceCode = "";
			
			if (isset($_POST['accountID']) && $_POST['accountID'] != NULL) {
				$accountID = $cipher->decrypt($_POST['accountID']);
				$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(accountID: '.$_POST['accountID'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['my_session_id']) && $_POST['my_session_id'] != NULL) {
				$my_session_id = $cipher->decrypt($_POST['my_session_id']);
				$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(my_session_id: '.$_POST['my_session_id'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['deviceCode']) && $_POST['deviceCode'] != NULL) {
				$deviceCode = $cipher->decrypt($_POST['deviceCode']);
				$deviceCode = filter_var($deviceCode, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(deviceCode: '.$_POST['deviceCode'].')');
				echo $error400;
				die();
			}
			
			echo $class->delete_tablet($accountID, $my_session_id, $deviceCode);
			die();
			break;

		case 'update_tablet':
			$accountID = "";
			$my_session_id = "";
			$deviceCode = "";
			$locID = "";
			$status = "";
            $deploy = "";
			
			if (isset($_POST['accountID']) && $_POST['accountID'] != NULL) {
				$accountID = $cipher->decrypt($_POST['accountID']);
				$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(accountID: '.$_POST['accountID'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['my_session_id']) && $_POST['my_session_id'] != NULL) {
				$my_session_id = $cipher->decrypt($_POST['my_session_id']);
				$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(my_session_id: '.$_POST['my_session_id'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['deviceCode']) && $_POST['deviceCode'] != NULL) {
				$deviceCode = $cipher->decrypt($_POST['deviceCode']);
				$deviceCode = filter_var($deviceCode, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(deviceCode: '.$_POST['deviceCode'].')');
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
			
			if (isset($_POST['status']) && $_POST['status'] != NULL) {
				$status = $cipher->decrypt($_POST['status']);
				$status = filter_var($status, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(status: '.$_POST['status'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['deploy']) && $_POST['deploy'] != NULL) {
				$deploy = $cipher->decrypt($_POST['deploy']);
				$deploy = filter_var($deploy, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(deploy: '.$_POST['deploy'].')');
				echo $error400;
				die();
			}
			
			echo $class->update_tablet($accountID, $my_session_id, $deviceCode, $locID, $status, $deploy);
			die();
			break;


		case 'card_series':
			$accountID = "";
			$my_session_id = "";
			
			if (isset($_POST['accountID']) && $_POST['accountID'] != NULL) {
				$accountID = $cipher->decrypt($_POST['accountID']);
				$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(accountID: '.$_POST['accountID'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['my_session_id']) && $_POST['my_session_id'] != NULL) {
				$my_session_id = $cipher->decrypt($_POST['my_session_id']);
				$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(my_session_id: '.$_POST['my_session_id'].')');
				echo $error400;
				die();
			}
			
			echo $class->card_series($accountID, $my_session_id);
			die();
			break;

		case 'get_userDownload':
			$accountID = "";
			$my_session_id = ""; 
			
			if (isset($_POST['accountID']) && $_POST['accountID'] != NULL) {
				$accountID = $cipher->decrypt($_POST['accountID']);
				$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(accountID: '.$_POST['accountID'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['my_session_id']) && $_POST['my_session_id'] != NULL) {
				$my_session_id = $cipher->decrypt($_POST['my_session_id']);
				$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(my_session_id: '.$_POST['my_session_id'].')');
				echo $error400;
				die();
			} 
			
			echo $class->get_userDownload($accountID, $my_session_id);
			die();
			break;

		case 'get_userdownload_yearly':
			$accountID = "";
			$my_session_id = ""; 
			
			if (isset($_POST['accountID']) && $_POST['accountID'] != NULL) {
				$accountID = $cipher->decrypt($_POST['accountID']);
				$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(accountID: '.$_POST['accountID'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['my_session_id']) && $_POST['my_session_id'] != NULL) {
				$my_session_id = $cipher->decrypt($_POST['my_session_id']);
				$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(my_session_id: '.$_POST['my_session_id'].')');
				echo $error400;
				die();
			} 
			
			echo $class->get_userdownload_yearly($accountID, $my_session_id);
			die();
			break;

		case 'get_userdownload_monthly':
			$accountID = "";
			$my_session_id = ""; 
			
			if (isset($_POST['accountID']) && $_POST['accountID'] != NULL) {
				$accountID = $cipher->decrypt($_POST['accountID']);
				$accountID = filter_var($accountID, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(accountID: '.$_POST['accountID'].')');
				echo $error400;
				die();
			}
			
			if (isset($_POST['my_session_id']) && $_POST['my_session_id'] != NULL) {
				$my_session_id = $cipher->decrypt($_POST['my_session_id']);
				$my_session_id = filter_var($my_session_id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			} else {
				api_error_logger('(my_session_id: '.$_POST['my_session_id'].')');
				echo $error400;
				die();
			} 
			
			echo $class->get_userdownload_monthly($accountID, $my_session_id);
			die();
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
		$logs->write_logs('Invalid Access', $file_name, 'Illegal access attempt.');
		die('Access denied'); 
	}

?>