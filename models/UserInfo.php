<?php
require_once("classes/class.MySQL.php");
require_once('classes/class.ConstVar.php');
// require_once("classes/class.ABCRecord.php");
require_once("include/config.php");

class UserInfo extends MySQL{
	private $servername = "localhost";
	private $dbname = "socalbev_abc";
	private $dbusername= "socalbev_abc";
	private $dbpassword= "%@Ot9iw{&)?ELFUy?d";
	private $tableName;
	
	public function __construct(){
		parent::__construct($this->servername, $this->dbname, $this->dbusername, $this->dbpassword);
		$this->tableName= "users";
	}
	
	/*
	 * return id (int)
	 * -1: no result, otherwise $id
	 */
	public function getID($_username, $_password){
		$id= ConstVar::DB_USER_ID_NOT_FOUND;
		$sql= "SELECT `user_id` FROM `". $this->tableName."` WHERE `Username`='". $_username. "' and ".
			"`password`=PASSWORD('". $_password. "');";
		// printf("sql: %s<br />", $sql);
		$rs= $this->runSQL($sql);
		if($this->rowCount($rs)== 1){
			$rsRow= $this->fetchRow($rs);
			$id= $rsRow["user_id"];
			// printf("user_id: %s<br />", $id);
		}
		return $id;
	}
	
	public function getUsername($_email){
		$username= "";
		$_email= trim($_email);
		$sql= "SELECT * FROM `". $this->tableName."` WHERE `email`='". $_email. "';";
		// printf("sql: %s<br />", $sql);
		// msg::oc($db->rowCount($rs));
		$rs= $this->runSQL($sql);
		if($this->rowCount($rs)== 1) {
			$rsRow= $this->fetchRow($rs);
			// msg::oc($rsRow[username]);
			$username= $rsRow["username"];
			// printf("username: %s<br />", $username);
		}
		return $username;
	}
	
	public function getIDFromVCode($_username, $_email, $_vCode){
		// $isValid= false;
		$id= ConstVar::DB_USER_ID_NOT_FOUND;
		$_username= trim($_username);
		$_email= trim($_email);
		$_vCode= trim($_vCode);
		// if($this->checkUserExisted($_email, $_username)){
		$sql= "SELECT * FROM `". $this->tableName."`".			
			"WHERE `email`='". $_email. "' and `username`='". $_username. "' and `validation_code`='". $_vCode. "';";
		// printf("sql: %s<br />", $sql);
		// msg::display("sql", $sql);
		$rs= $this->runSQL($sql);
		if($this->rowCount($rs)== 1) {
			$rsRow= $this->fetchRow($rs);
			$id= $rsRow["user_id"];
			// msg::oc($rsRow[username]);
			// $username= $rsRow["username"];
			// printf("username: %s<br />", $username);
		// $isValid= ();
		}
		return $id;
	}
	
	public function updateVCode($_username, $_email, $_vCode){
		// global $db;
		$_username= trim($_username);
		$_email= trim($_email);
		$_vCode= trim($_vCode);
		// if($this->checkUserExisted($_email, $_username)){
			$sql= "UPDATE `". $this->tableName."` SET `validation_code`='". $_vCode. "' ".			
			"WHERE `email`='". $_email. "' and `username`='". $_username. "';";
			// printf("sql: %s<br />", $sql);
			// msg::display("sql", $sql);
			return $this->ExecuteSQL($sql);
		// }else return false;
	}
	public function changePassword($_id, $_newPassword){
		$result= false;
		if(!is_null($_id) && !is_null($_newPassword)){
			$sql= "UPDATE ". $this->tableName. 
				" SET `password`=PASSWORD('". $_newPassword. "')".
				" WHERE `user_id`='". $_id. "'";
			// msg::display("sql", $sql);
			$result= $this->ExecuteSQL($sql);
		}
		return $result;
	}
// 	
	// public function createUser($_username, $_password){
		// //need to check username existed and email existed
		// $sql= "INSERT INTO `Users` (`ID` ,`Username` ,`password`)". 
		// "VALUES (NULL , '". $_username. "', PASSWORD('". $_password. "'));";
		// msg::display("sql", $sql);
		// return $db->ExecuteSQL($sql);		
	// }
}
?>