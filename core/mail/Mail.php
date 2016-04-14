<?php

namespace mail;

class Mail {
	public $subject = "";
	public $message = "";
	public $to = "";
	public $from = "";
	
	private static $defaultFrom = "info@salumedia.com";
	
	public function send() {
		require_once(__DIR__ ."/../vendor/phpmailer/class.phpmailer.php");
		require_once(__DIR__ ."/../vendor/phpmailer/class.smtp.php");
		
		if($this->subject
		&& $this->message
		&& $this->to) {
			if($this->from)
				$from = $this->from;
			else
				$from = self::$defaultFrom;
				
			
			$message = $this->message;
			
			
			if(USESMTP) {
				$mail = new \PHPMailer();
					
				$mail->IsSMTP();
				$mail->SMTPAuth = true;                  // enable SMTP authentication
				if(SMTPSECURE)
					$mail->SMTPSecure = SMTPSECURE;                 // sets the prefix to the servier
				$mail->Host = SMTPSERVER;      // sets GMAIL as the SMTP server
				$mail->Port = SMTPPORT;                   // set the SMTP port
					
				$mail->Username = SMTPUSER;  // GMAIL username
				$mail->Password = SMTPPASSWORD;            // GMAIL password
					
				$mail->From = $from;
				$mail->FromName = "Ag1le";
				$mail->Subject = $this->subject;
				$mail->AltBody = $this->message; //Text Body
				$mail->WordWrap = 50; // set word wrap
				$mail->CharSet = "utf-8";
				$mail->IsHTML(true); // send as HTML
					
				$mail->MsgHTML($message);
				
				if(gettype($this->to) == "string") {
					$mail->AddAddress($this->to);
				} else if(gettype($this->to) == "array") {
					foreach($this->to as $to) {
						$mail->AddBCC($to);
					}
				}
				
				return $mail->Send();
			} else {
				
				$headers = "From: ".$from."\r\n".
						"Reply-To: ".$from."\r\n".
						"Content-type: text/html; charset=UTF-8\r\n";
				
				$to = "";
				if(gettype($this->to) == "string") {
					$to = $this->to;
					
				} else if(gettype($this->to) == "array") {
					$tos = implode(",", $this->to);
					$headers .= "Bcc: ". $tos ."\r\n";
					$to = "";
				}
				
				
				return mail($to, $this->subject, $message, $headers);
			}
		}
		
		return false;
	}
}