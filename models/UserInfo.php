<?php
require_once("classes/class.MySQL.php");
require_once("classes/class.ABCRecord.php");
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
		$id= -1;
		$sql= "SELECT `user_id` FROM `". $this->tableName."` WHERE `Username`='". $_username. "' and ".
			"`password`=PASSWORD('". $_password. "')";
		// printf("sql: %s<br />", $sql);
		$rs= $this->runSQL($sql);
		if($this->rowCount($rs)== 1){
			$rsRow= $this->fetchRow($rs);
			$id= $rsRow["user_id"];
			// printf("user_id: %s<br />", $id);
		}
		return $id;
	}
	
	// public function changePassword($_id, $_newPassword){
		// $result= false;
		// if(!is_null($_id) && !is_null($_newPassword)){
			// $sql= "UPDATE ". $this->tableName. 
				// " SET `password`=PASSWORD('". $_newPassword. "')".
				// "WHERE `ID`='". $_id. "'";
			// //msg::display("sql", $sql);
			// $result= $db->ExecuteSQL($sql);
		// }
		// return $result;
	// }
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