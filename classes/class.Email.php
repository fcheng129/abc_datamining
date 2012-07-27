<?
require_once('classes/class.MySQL.php');
require_once('classes/class.msg.php');

class Email{  
	// private static $testStarting= "-------------------------- Testing Starting";
	// private static $testEnding= "-------------------------- Testing   Ending";
	// private static $db;
	private $sender;
	private $senderName;
	private $recipients= array();	
	private $ccRecipients= array();
	private $bccRecipients= array();
	private $subject;
	private $contents;
	private $htmlNewLine;
	private $isHTMLType;
	private $emailFooter;
	
	public function __construct() {
		$nl= "<br />";
		$this->emailFooter= $message.= $nl. $nl. "Sincerely,". $nl. $nl. "Summit Travelware Customer Service.";
	}
	public function getEmailFooter(){
		return $this->emailFooter;
	}
	public function setSender($_sender){
		$this->sender= trim($_sender);
	}
	
	public function getSender(){
		return $this->sender;
	}
	
	public function setSenderName($_senderName){
		$_senderName= trim($_senderName);
		$this->senderName= $_senderName;
	}
	
	public function getSenderName(){
		return $this->senderName;
	}
	
	public function addRecipent($_recipient){
		//global $recipients;
		$_recipien= trim($_recipien);
		if(array_search($_recipient, $this->recipients)== 0){
			$this->recipients[]= $_recipient; 
		}
	}
	
	public function getRecipents(){
		return $this->getAllRecipentsList($this->recipients);
	}
	
	public function addccRecipent($_recipient){
		$_recipien= trim($_recipien);
		if(array_search($_recipient, $this->ccRecipients)== 0){
			$this->ccRecipients[]= $_recipient; 
		}
	}
	
	public function getccRecipents(){
		return $this->getAllRecipentsList($this->ccRecipients);
	}
	
	public function addbccRecipent($_recipient){
		$_recipien= trim($_recipien);
		if(array_search($_recipient, $this->bccRecipients)== 0){
			$this->bccRecipients[]= $_recipient; 
		}
	}
	
	public function getbccRecipents(){
		return $this->getAllRecipentsList($this->bccRecipients);
	}

	private function getAllRecipentsList($_targetArray){
		$result= "";
		foreach($_targetArray as $key => $value){
			//msg::oc($key);
			//msg::oc($value);
			$result.= $value. ";";
		}
		$result= substr($result, 0, -1);
		return $result;
	}
		
	public function send(){
		//$to= $email;
		//$defaultMsg= "<Blank>";
		//$from= "Tt_Power_Sweepstakes@thermaltake.com";
		$subject= 'Thermaltake Power Sweepstakes Registration Confirmation';
		//define the message to be sent. Each line should be separated with \n
		$message= "Hello $first $last<br /><br />".
			"Thank you. Your registration is now completed. ".
			"Please note your registration will still need to be verified ".
			"by an agent to validate the proof of purchase. If there is an error, ".
			"an agent will be contacting you via email. ".
			"There is nothing you need to act on right now. ". 
			"Please save a copy of this receipt for your record.<br /><br />";
		$message.= "Confirmation #: ". $confirmation. "<br />";
		$message.= "Email: $email<br />";
		$message.= "Phone: $phone<br />";
		$message.= "Address: $addr1 $addr2<br />";
		$message.= "City: $city<br />";
		$message.= "State/Province: $state<br />";
		$message.= "Zipcode: $zip<br />";
		$message.= "Country: $country<br />";
		$message.= "Retailer: $retailer<br />";
		$message.= "Invoice/Receipt #: $invoice<br /><br />";
		$message.= "Please remember to check back on the following dates to see if you are the winner.<br /><br />".
			"<table><tr><td width='155' nowrap='nowrap'><p align='center'></p></td>".
			"<td width='243' nowrap='nowrap'><p align='center'>Entry   Deadline</p></td>".
			"<td width='155' nowrap='nowrap'><p align='center'>Winner   Announcement</p></td>".
			"</tr><tr><td width='155' nowrap='nowrap'><p>July   Sweepstakes</p></td>".
			"<td width='243' nowrap='nowrap'><p align='center'>7/31/2010 11:59PM   Pacific Time</p></td>".
			"<td width='155' nowrap='nowrap'><p align='center'>8/5/2010</p></td></tr><tr>".
			"<td width='155' nowrap='nowrap'><p>August   Sweepstakes</p></td>".
			"<td width='243' nowrap='nowrap'><p align='center'>8/31/2010 11:59PM   Pacific Time</p></td>".
			"<td width='155' nowrap='nowrap'><p align='center'>9/6/2010</p></td></tr><tr>".
			"<td width='155' nowrap='nowrap'><p>September   Sweepstakes</p></td>".
			"<td width='243' nowrap='nowrap'><p align='center'>9/30/2010 11:59PM   Pacific Time</p></td>".
			"<td width='155' nowrap='nowrap'><p align='center'>10/6/2010</p></td></tr></table><br />".
			"(Remember, if you submitted a valid registration in July, ". 
			"but was not a winner for the July Sweepstakes, ". 
			"you are still eligible for the August Sweepstakes. ". 
			"The eligibility only ends if you were announced as a winner in any of ".
			"the Sweepstakes or when all of the winners for the Sweepstakes were announced.) ".
			"You can find commonly asked questions and answers here: ". 
			"<a href='http://www.powersweepstakes.com/faq.html' target='_blank'>FAQ</a><br /><br />".
			"Good luck!<br /><br />Thermaltake Team";
		//define the headers we want passed. Note that they are separated with \r\n
		$headers= "From: ". $this->sender. "\r\n";
		$headers.= "Reply-To: ". $this->sender. "\r\n"; 
		$headers .= "Content-type: text/html\r\n"; 
		//$headers .= "Return-Path: freekid@gmail.com\r\n";
		//$headers .= "CC: sombodyelse@noplace.com\r\n";
		//$headers .= "BCC: hidden@special.com\r\n";
		//send the email
		$mail_sent= mail($this->getRecipentsList($this->recipients), $subject, $message, $headers);
		return $mail_sent;
		//echo "<font size='2'>";
		//echo $mail_sent ? "Mail sent" : "Mail failed";
		//echo "</font>";
	}
}
?>