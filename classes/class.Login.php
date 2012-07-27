<?
require_once('models/UserInfo.php');
// require_once('class.msg.php');
require_once('class.LoginSession.php');

class Login{  
	// private static $testStarting= "-------------------------- Testing Starting";
	// private static $testEnding= "-------------------------- Testing   Ending";
	private $loginSession;
	private static $db;
	const NO_ID= -1;
	
	public function __construct(){
		self::initDBConnection();
		// $db->Connect();
		$this->loginSession= LoginSession::getInstance();
	}
	
	public static function initDBConnection(){
		if(!self::$db) self::$db= new UserInfo;		
	}
	
	public function validation($_username, $_password, $_redirectLink= NULL){
		$id= self::NO_ID;
		$username= isset($_username)? trim($_username): "";
		$password= isset($_password)? trim($_password): "";
		if(strlen($username)> 0 && strlen($password)> 0){
			// echo "no null<br />";
			$id= self::$db->getID($username, $password);
			// echo "no null<br />";
			if($id!= self::NO_ID){
				// echo "get id: $id<br />";
				$this->loginSession->createTimeSession(time());
				$this->loginSession->createIDSession($id);
				if($_redirectLink) header('Location: '. $_redirectLink);
			}
		}
		return $id;
	}
	
	public static function validateSession($_maxElapsedTime, $_redirectLink){
		// global $loginSession;
		$loginSession= LoginSession::getInstance();
		$elapsed= time()- $loginSession->getTimeSession();
		// msg::display("elapsed", $elapsed);
		if($elapsed> $_maxElapsedTime) {
			// msg::oc("expired");
			// msg::oc('Location: '. $_redirectLink);
			header('Location: '. $_redirectLink);
			die();
		}
	}
	
	// public static function getUserID(){
		// $loginSession= LoginSession::getInstance();
		// return $loginSession->getIDSession();
	// }
	
	public static function logoutSession(){
		$loginSession= LoginSession::getInstance();
		$loginSession->deleteLoginSession();
	}
// 	
	// public function updatePassword($_username, $_password, $_newPassword){
		// return self::$db->changePassword(self::$db->getID($_username, $_password), $_newPassword);
	// }
// 
	// public function addUser($_username, $_password){
		// return self::$db->createUser($_username, $_password);
	// }
// 
	// public function deleteUser($_username, $_password){
		// global $db;
		// if($this->validation($_username, $_password)){
			// $sql= "DELETE FROM `Users` ". 
			// "WHERE `Username`='". $_username. "' and `password`= PASSWORD('". $_password. "');";
			// msg::display("sql", $sql);
			// return $db->ExecuteSQL($sql);
		// }else return false;
	// }
// 	
	// public function findUsername($_email){
		// global $db;
		// $sql= "SELECT * FROM Users WHERE `Email`='". $_email. "'";
		// // msg::display("sql", $sql);
		// $rs= $db->runSQL($sql);
		// // msg::oc($db->rowCount($rs));
		// if($db->rowCount($rs)== 1) {
			// $rsRow= $db->fetchRow($rs);
			// // msg::oc($rsRow[username]);
			// return $rsRow["Username"];
		// }
		// else return "";	
	// }
// 	
	// //check user existed by matching email + username
	// public function checkUserExisted($_email, $_username){
		// global $db;
		// $_email= trim($_email);
		// $_username= trim($_username);
		// $sql= "SELECT * FROM Users WHERE `Email`='". $_email. "' and ".
			// "`Username`='". $_username. "';";
		// //msg::display("sql", $sql);
		// $rs= $db->runSQL($sql);
		// //msg::display("row count", $db->rowCount($rs));
		// if($db->rowCount($rs)== 1) return true;
		// else return false;	
	// }
// 
	// public function generateVCode($_username, $_email, $_vCode){
		// global $db;
		// $_username= trim($_username);
		// $_email= trim($_email);
		// $_vCode= trim($_vCode);
		// if($this->checkUserExisted($_email, $_username)){
			// $sql= "UPDATE Users SET `validationCode`='". $_vCode. "' ".			
			// "WHERE `Email`='". $_email. "' and `Username`='". $_username. "';";
			// //msg::display("sql", $sql);
			// return $db->ExecuteSQL($sql);
		// }else return false;
	// }
// 	
	// public function resetPassword($_username, $_email, $_password, $_vCode){
		// global $db;
		// $_username= trim($_username);
		// $_email= trim($_email);
		// $_vCode= trim($_vCode);
		// $_password= trim($_password);
// 		
		// $sql= "SELECT * FROM Users WHERE `Email`='". $_email. "' and ".
			// "`Username`='". $_username. "' and `validationCode`='". $_vCode. "';";
		// // msg::display("sql", $sql);
		// $rs= $db->runSQL($sql);
		// // msg::display("row count", $db->rowCount($rs));
		// if($db->rowCount($rs)== 1){
			// $sql= "UPDATE Users SET `password`=PASSWORD('". $_password. "'), `validationCode`=null ".			
			// "WHERE `Email`='". $_email. "' and ". "`Username`='". $_username. 
			// "' and `validationCode`='". $_vCode. "';";
			// // msg::display("sql", $sql);
			// return $db->ExecuteSQL($sql);
		// }else return false;
	// }
}
?>