<?
require_once('classes/class.MySQL.php');
require_once('classes/class.msg.php');
require_once('classes/class.Email.php');

class PasswordEmail extends Email{ 
	public function __construct() {
		parent::__construct(); 
		$this->setSender("me@fcheng129.com");
		// msg::display("Sender", $this->getSender());
		$this->setSenderName("Customer Service");
		// msg::display("SenderName", $this->getSenderName());
	}
	public function send($_username, $_validationCode){
		// msg::display("Sender", $this->getSender());
		$nl= "<br />"; //html new line
		$subject= 'Password Reset Information - fcheng129 Tracking System';
		//define the message to be sent. Each line should be separated with \n
		$message= "Dear ". $this->getRecipents(). $nl. $nl.
			"Thank you for contacting fcheng129.com.$nl".
			"Your username is $_username and the validation code is ". $_validationCode. $nl.  
			"Please using <a href=\"http://abc.socalbev.com/resetPassword.php?vCode=". 
			$_validationCode. "\">this link</a>.". $nl. 
			"Or entering password process in http://abc.socalbev.com/resetPassword.php ".
			"along with your email adress, username, and validation code.";
		$message.= $this->getEmailFooter();
		//define the headers we want passed. Note that they are separated with \r\n
		$senderName= "";
		if(strlen($this->getSenderName())> 0) $senderName= $this->getSenderName();
		$headers= "From: ". $senderName. " <". $this->getSender(). ">\r\n";
		$headers.= "Reply-To: ". $senderName. " <". $this->getSender(). ">\r\n"; 
		$headers.= "Content-type: text/html\r\n"; 
		// //send the email
		// msg::display("headers", $headers);
		// msg::display("recipents", $this->getRecipents());
		$mail_sent= mail($this->getRecipents(), $subject, $message, $headers);
		return $mail_sent;
	}
}
?>