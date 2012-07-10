<?php
require_once("classes/class.MySQL.php");
require_once("classes/class.ABCRecord.php");
require_once("include/config.php");

class DataInfo extends MySQL{
	private $servername = "localhost";
	private $dbname = "socalbev_abc";
	private $dbusername= "socalbev_abc";
	private $dbpassword= "%@Ot9iw{&)?ELFUy?d";
	private $tableName;
	
	public function __construct(){
		parent::__construct($this->servername, $this->dbname, $this->dbusername, $this->dbpassword);
		$this->tableName= DATA_TABLE_PREFIX. date("Ymd");
	}
	
	public function dbInit(){
		$sql= "DROP TABLE IF EXISTS `$this->tableName`;";
		$this->runSQL($sql);
		$sql= 
			"CREATE TABLE IF NOT EXISTS `$this->tableName` (".
  				"`id` int(11) NOT NULL AUTO_INCREMENT,".
  				"`licence` varchar(15) NOT NULL,".
  				"`licence_status` varchar(10) NOT NULL,".
  				"`dates` varchar(30) NOT NULL,".
  				"`name` varchar(30) NOT NULL,".
  				"`address` varchar(30) NOT NULL,".
  				"`city` varchar(20) NOT NULL,".
  				"`state` varchar(10) NOT NULL,".
  				"`zipcode` varchar(5) NOT NULL,".
  				"`zipcode1` varchar(5) NOT NULL,".
  				"PRIMARY KEY (`id`)".
			") ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";
		$this->runSQL($sql);
	}
	
	public function writeData($_data){
		$contents= $_data->getContents();
		$sql= 
			"INSERT INTO `$this->tableName` (".
  				"`licence`,`licence_status`,`dates`,`name`, `address`,`city`,`state`,`zipcode`,`zipcode1`)".
  			// " VALUES ($contents[0]['license'],'$contents[0]['status']')";
			" VALUES (\"". $contents["license"]. "\",\"". $contents["status"]. "\",\"". $contents[1]. 
				"\",\"". $contents[2]. "\",\"". $contents[3]. "\",\"". $contents[4]. "\",\"". $contents["state"]. 
				"\",\"". $contents["zipcode"]. "\",\"". $contents["zipcode2"]. "\");";
		// echo $sql. "<br />";
		$this->runSQL($sql);
	}

	public function findZipcode($_dataTableName, $_filename, $_zipcodeTableName){
		$sql=
			"SELECT d.*".
			" FROM `$_dataTableName` AS d, $_zipcodeTableName AS z".
			" WHERE d.`zipcode` = z.`zipcode`".
			" ORDER BY d.`zipcode`";
			// " ORDER BY d.`zipcode`".
			// " LIMIT 0, 30;";
		// echo $sql. "<br />";

		$rs= $this->runSQL($sql);
		$num_rows = $this->rowCount($rs);
		$i= 0;
		$fp = fopen($_filename, 'w');
		$col_seperator= ",";
		$col_enclosure= "\"";
		
		while ($rowRs= $this->fetchRow($rs)) {
			$i++;
			// echo (sizeof($rowRs)/ 2);
			$output= "";
			//j starts at 1 because the id col is not required.
			for($j= 1; $j< (sizeof($rowRs)/ 2); $j++){
				// echo $j. "<br />";
				// echo $rowRs[$j]. "&nbsp;&nbsp;&nbsp;";
				$output.= $col_enclosure. $rowRs[$j]. $col_enclosure. $col_seperator;
				//fwrite($fp, $rowRs[$j]. ",");
				
			}
			// echo "<br />";
			fwrite($fp, substr($output, 0, strlen($output)- strlen($col_seperator)). "\r\n");
		}
		fclose($fp);
		echo "<a href=\"$_filename\">$_filename</a><br /><br />";
	}
	
	public function outputLosangelesResult($_dataTableName, $_filename){
		$this->findZipcode($_dataTableName, $_filename, "losangeles");
	}
	
	public function outputSanbarndardinoResult($_dataTableName, $_filename){
		$this->findZipcode($_dataTableName, $_filename, "sanbarndardino");
	}
	
	public function outputVenturaResult($_dataTableName, $_filename){
		$this->findZipcode($_dataTableName, $_filename, "ventura");
	}
}
?>