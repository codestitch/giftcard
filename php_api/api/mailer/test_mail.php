<?php

	/********** E-Mailer **********/
	require_once('PHPMailerAutoload.php');
	require_once("class.smtp.php");
	require_once("class.phpmailer.php");
	include_once('config.php');

	$receiver = "jimkarlojamero@gmail.com";
	$recipient_name = "Jim Karlo P. Jamero";
	$subject = "Test Mail";
	$message = "The time is: ".date('h:i A');

	class phpmailerAppException extends phpmailerException {}

	$sender = MAILER_SENDER;
	$fromName = MAILER_FROM_NAME;		
	$return = false;
	$mail = new PHPMailer(true);
	$mail->CharSet = 'utf-8';
	$smtp = MAILER_SMTP;
	$port = MAILER_PORT;
	$un = MAILER_ACCOUNT_USERNAME;
	$pw = MAILER_ACCOUNT_PASSWORD;
	$bouncemail = MAILER_BOUNCE;

	try {
		$to = $receiver;

		if(!PHPMailer::validateAddress($to)) {
		  throw new phpmailerAppException("Email address " . $to . " is invalid -- aborting!");
		}

		$mail->isSMTP();
		//$mail-SMTPDebug 0 = Disabled || 1 = Client Messages || 2 = Client and server messages
		$mail->SMTPDebug  = 2;
		$mail->Host       = $smtp;
		$mail->Port       = $port;
		$mail->SMTPSecure = "ssl"; //"none";
		$mail->SMTPAuth   = true;
		$mail->Username   = $un;
		$mail->Password   = $pw;
		$mail->addReplyTo($sender, $fromName);
		$mail->From       = $sender;
		$mail->FromName   = $fromName;
		$mail->addAddress($receiver, $recipient_name);
		//$mail->adconnxnC("support@appsolutely.ph");
		$mail->Subject  = $subject;
		$body = $message;
		
		$mail->WordWrap = 80;
		$mail->msgHTML($body, dirname(__FILE__), true); //Create message bodies and embed images
		//$mail->addAttachment('images/phpmailer_mini.gif','phpmailer_mini.gif');  // optional name
		//$mail->addAttachment('images/phpmailer.png', 'phpmailer.png');  // optional name

		$mail->addCustomHeader("MIME-Version", "1.0");
		$mail->addCustomHeader("Organization" , $recipient_name); 
		$mail->addCustomHeader("Content-Transfer-encoding" , "8bit");
		$mail->addCustomHeader("Message-ID" , "<".md5(uniqid(time()))."@{$_SERVER['SERVER_NAME']}>");
		$mail->addCustomHeader("X-MSmail-Priority" , "High");
		$mail->addCustomHeader("X-Mailer" , "PHPMailer 5.1 (phpmailer.sourceforge.net)");
		$mail->addCustomHeader("X-MimeOLE" , "5.1 (phpmailer.sourceforge.net)");
		$mail->addCustomHeader("X-Sender" , $mail->Sender);
		$mail->addCustomHeader("X-AntiAbuse" , "This is a solicited email for - ".$recipient_name." mailing list.");
		$mail->addCustomHeader("X-AntiAbuse" , "Servername - {$_SERVER['SERVER_NAME']}");
		$mail->addCustomHeader("X-AntiAbuse" , $mail->Sender);
							 
		try {
		  // $mail->IsQmail();
		  $mail->send();
		  $results_messages[] = "Success";
		}
		catch (phpmailerException $e) {
		  throw new phpmailerAppException('Error: (Message Failed to Email: ' . $to . ') '.$e->getMessage());
		}
	}
	catch (phpmailerAppException $e) {
	  $results_messages[] = $e->errorMessage();
	}

	var_dump($results_messages);

	// if (count($results_messages) > 0) {
	// 	foreach ($results_messages as $result) {
	// 		if ($result != "Success") {
	// 			return "Failed";
	// 		} elseif ($result == "Success") {
	// 			return "Success";
	// 		}
	// 	}
	// }


?>