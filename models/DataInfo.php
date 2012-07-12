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
	
/*
 * value of $columns: [column_name]=> array(start_index, number of chars)
 */
	private static $colums=array(
		"license_type"=> array(1, 2),
		"file_number"=> array(3, 8),
		"license_application"=> array(11, 3),
		"type_status"=> array(14, 8),
		"org_issue_dates"=> array(22, 11),
		"exp_issue_dates"=> array(33, 11),
		"fee_codes"=> array(44, 8),
		"duplicate_counts"=> array(52, 3),
		"master_indicator"=> array(55, 1),
		"term_in_of_months"=> array(56, 2),
		"geo_code"=> array(58, 4),
		"district_office_code"=> array(62, 2),
		"primary_name"=> array(64, 50),
		"premise_street_address1"=> array(114, 50),
		"premise_street_address2"=> array(164, 50),
		"premise_city"=> array(214, 25),
		"premist_state"=> array(239, 2),
		"premist_zip"=> array(241, 10),
		"dba_name"=> array(251, 300),
		"mail_street_address1"=> array(301, 50),
		"mail_street_address2"=> array(351, 50),
		"mail_city"=> array(401, 25),
		"mail_state"=> array(426, 2),
		"mail_zip"=> array(428, 10)
	);
	
	public function __construct($_tableName){
		parent::__construct($this->servername, $this->dbname, $this->dbusername, $this->dbpassword);
		if($_tableName)
			$this->tableName= DATA_TABLE_PREFIX. $_tableName;
		else
			$this->tableName= DATA_TABLE_PREFIX. date("Ymd");
	}
	
	public static function getColumns(){
		return self::$colums;
	}

	public function dbInitList(){
		// echo "Drop<br />";
		$sql= "DROP TABLE IF EXISTS `". $this->tableName. DATA_TABLE_LIST_POSTFIX. "`;";
		$this->runSQL($sql);
		// echo "Create<br />";
		$colName= "";
		foreach(self::$colums as $key => $values){
			$colName.= "`$key` varchar($values[1]) NOT NULL,";
		}
		$sql= 
			"CREATE TABLE IF NOT EXISTS `". $this->tableName. DATA_TABLE_LIST_POSTFIX. "` (".
  				"`id` int(11) NOT NULL AUTO_INCREMENT,".
				$colName.
  				"PRIMARY KEY (`id`)".
			") ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";
		// echo $sql. "<br />";
		$this->runSQL($sql);
	}

	public function writeDataList($_rec){
		// echo "write data.<br />";
		// $contents= $_rec->getContents();
		$colName= "";
		$colValue= "";
		$seperator= ", ";
		foreach($_rec->getContents() as $key => $value){
			$colName.= "`". $key. "`". $seperator;
			$colValue.= "\"". $value. "\"". $seperator;
		}
		$colName= substr($colName, 0, strlen($colName)- strlen($seperator));
		$colValue= substr($colValue, 0, strlen($colValue)- strlen($seperator));
		$sql= 
			"INSERT INTO `". $this->tableName. DATA_TABLE_LIST_POSTFIX. "` (". $colName. ")".
			" VALUES (". $colValue. ");";
		// echo $sql. "<br />";
		$this->runSQL($sql);
	}
	
	public function findZipcodeList($_dataTableName, $_filename, $_zipcodeTableName){
		$dataTableAbbr= "d";
		$zipTableAbbr= "z";
		$col_seperator= ",";
		$col_enclosure= "\"";
		$colName= "";
		$zipCodeColumns= array("city_name"=> 0);
		$selColName= "";
		// $seperator= ", ";
		foreach(self::$colums as $key => $values){
			$selColName.= "$dataTableAbbr.`$key`". $col_seperator;
			$colName.= "$key". $col_seperator;
		}
		foreach($zipCodeColumns as $key => $value){
			$selColName.= "$zipTableAbbr.`$key`". $col_seperator;
			$colName.= "$key". $col_seperator;
		}
		$selColName= substr($selColName, 0, strlen($selColName)- strlen($col_seperator));
		$colName= substr($colName, 0, strlen($colName)- strlen($col_seperator));
		// echo $colName. "<br />";
		$sql=
			"SELECT ". $selColName. 
			" FROM `". $_dataTableName. DATA_TABLE_LIST_POSTFIX. "` AS $dataTableAbbr, $_zipcodeTableName AS $zipTableAbbr".
			" WHERE $dataTableAbbr.`premist_zip` = z.`zipcode`".
			" ORDER BY $dataTableAbbr.`premist_zip`";
		// echo $sql. "<br />";
		//$i= 0;
		$fp = fopen($_filename, 'w');
		fwrite($fp, $colName. "\r\n");
		$rs= $this->runSQL($sql);
		$num_rows = $this->rowCount($rs);
		// printf("total row: %d<br />", $num_rows);
		
		while ($rowRs= $this->fetchRow($rs)) {
			// print_r($rowRs);
			// echo "<br />";
			//$i++;
			// echo (sizeof($rowRs)/ 2);
			$output= "";
			//j starts at 1 because the id col is not required.
			// for($j= 1; $j< (sizeof($rowRs)/ 2); $j++){
			foreach(self::$colums as $key => $values){
				// echo $j. "<br />";
				// echo $rowRs[$j]. "&nbsp;&nbsp;&nbsp;";
				$output.= "=". $col_enclosure. $rowRs[$key]. $col_enclosure. $col_seperator;
				//fwrite($fp, $rowRs[$j]. ",");
			}
			foreach($zipCodeColumns as $key => $value){
				$output.= "=". $col_enclosure. $rowRs[$key]. $col_enclosure. $col_seperator;
			}
			// echo "<br />";
			fwrite($fp, substr($output, 0, strlen($output)- strlen($col_seperator)). "\r\n");
		}
		if($fp) fclose($fp);
		return $num_rows;
	}
	
	// public function outputLosangelesResultList($_dataTableName, $_filename){
		// return $this->findZipcodeList($_dataTableName, $_filename, "losangeles");
	// }
// 	
	// public function outputSanbarndardinoResultList($_dataTableName, $_filename){
		// return $this->findZipcodeList($_dataTableName, $_filename, "sanbarndardino");
	// }
// 	
	// public function outputVenturaResultList($_dataTableName, $_filename){
		// return $this->findZipcodeList($_dataTableName, $_filename, "ventura");
	// }

	public function dbInitXls(){
		$sql= "DROP TABLE IF EXISTS `". $this->tableName. DATA_TABLE_XLS_POSTFIX. "`;";
		$this->runSQL($sql);
		$sql= 
			"CREATE TABLE IF NOT EXISTS `". $this->tableName. DATA_TABLE_XLS_POSTFIX. "` (".
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
	
	public function writeDataXls($_data){
		$contents= $_data->getContents();
		$sql= 
			"INSERT INTO `". $this->tableName. DATA_TABLE_XLS_POSTFIX. "` (".
  				"`licence`,`licence_status`,`dates`,`name`, `address`,`city`,`state`,`zipcode`,`zipcode1`)".
  			// " VALUES ($contents[0]['license'],'$contents[0]['status']')";
			" VALUES (\"". $contents["license"]. "\",\"". $contents["status"]. "\",\"". $contents[1]. 
				"\",\"". $contents[2]. "\",\"". $contents[3]. "\",\"". $contents[4]. "\",\"". $contents["state"]. 
				"\",\"". $contents["zipcode"]. "\",\"". $contents["zipcode2"]. "\");";
		// echo $sql. "<br />";
		$this->runSQL($sql);
	}

	public function findZipcodeXls($_dataTableName, $_filename, $_zipcodeTableName){
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
	
	public function outputLosangelesResultXls($_dataTableName, $_filename){
		$this->findZipcode($_dataTableName, $_filename, "losangeles");
	}
	
	public function outputSanbarndardinoResultXls($_dataTableName, $_filename){
		$this->findZipcode($_dataTableName, $_filename, "sanbarndardino");
	}
	
	public function outputVenturaResultXls($_dataTableName, $_filename){
		$this->findZipcode($_dataTableName, $_filename, "ventura");
	}
}
?>