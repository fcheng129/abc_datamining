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
	private $licTableName= "license_type";
	
/*
 * value of $columns: [column_name]=> array(start_index, number of chars)
 */
	private static $colums=array(
		"license_type"=> array( "startAt"=>1, "size"=>2, "output"=>true),
		"file_number"=> array( "startAt"=>3, "size"=>8, "output"=>false),
		"license_application"=> array( "startAt"=>11, "size"=>3, "output"=>false),
		"type_status"=> array( "startAt"=>14, "size"=>8, "output"=>true),
		"org_issue_dates"=> array( "startAt"=>22, "size"=>11, "output"=>true),
		"exp_issue_dates"=> array( "startAt"=>33, "size"=>11, "output"=>true),
		"fee_codes"=> array( "startAt"=>44, "size"=>8, "output"=>false),
		"duplicate_counts"=> array( "startAt"=>52, "size"=>3, "output"=>false),
		"master_indicator"=> array( "startAt"=>55, "size"=>1, "output"=>false),
		"term_in_of_months"=> array( "startAt"=>56, "size"=>2, "output"=>false),
		"geo_code"=> array( "startAt"=>58, "size"=>4, "output"=>false),
		"district_office_code"=> array( "startAt"=>62, "size"=>2, "output"=>true),
		"primary_name"=> array( "startAt"=>64, "size"=>50, "output"=>true),
		"premise_street_address1"=> array( "startAt"=>114, "size"=>50, "output"=>true),
		"premise_street_address2"=> array( "startAt"=>164, "size"=>50, "output"=>false),
		"premise_city"=> array( "startAt"=>214, "size"=>25, "output"=>true),
		"premist_state"=> array( "startAt"=>239, "size"=>2, "output"=>true),
		"premist_zip"=> array( "startAt"=>241, "size"=>10, "output"=>true),
		"dba_name"=> array( "startAt"=>251, "size"=>300, "output"=>true),
		"mail_street_address1"=> array( "startAt"=>301, "size"=>50, "output"=>true),
		"mail_street_address2"=> array( "startAt"=>351, "size"=>50, "output"=>false),
		"mail_city"=> array( "startAt"=>401, "size"=>25, "output"=>true),
		"mail_state"=> array( "startAt"=>426, "size"=>2, "output"=>true),
		"mail_zip"=> array( "startAt"=>428, "size"=>10, "output"=>true)
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
			$colName.= "`$key` varchar(". $values["size"]. ") NOT NULL,";
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

	public function findZipcodeActiveList($_dataTableName, $_filename, $_zipcodeTableName){
		return $this->findZipcodeList($_dataTableName, $_filename, $_zipcodeTableName, true);
	}
	
	public function findZipcodeAllList($_dataTableName, $_filename, $_zipcodeTableName){
		return $this->findZipcodeList($_dataTableName, $_filename, $_zipcodeTableName, false);
	}
	
	private function findZipcodeList($_dataTableName, $_filename, $_zipcodeTableName, $_isActiveList= false){
		$dataTableAbbr= "d";
		$zipTableAbbr= "z";
		$licTableAbbr= "l";
		$col_seperator= ",";
		$col_enclosure= "\"";
		$colName= "";
		$zipCodeColumns= array("city_name"=> 0);
		$licColumns= array("license_desc"=> 0);
		$selColName= "";
		// $seperator= ", ";
		foreach($licColumns as $key => $value){
			// $selColName.= "$licTableAbbr.`$key`". $col_seperator;
			$colName.= "$key". $col_seperator;
		}
		foreach(self::$colums as $key => $values){
			// echo "$key<br />";
			if($values["output"]){
				$selColName.= "$dataTableAbbr.`$key`". $col_seperator;
				$colName.= "$key". $col_seperator;
				// echo "$colName<br />";
			}
		}
		foreach($zipCodeColumns as $key => $value){
			$selColName.= "$zipTableAbbr.`$key`". $col_seperator;
			$colName.= "$key". $col_seperator;
		}
		$selColName= substr($selColName, 0, strlen($selColName)- strlen($col_seperator));
		$colName= substr($colName, 0, strlen($colName)- strlen($col_seperator));
		// echo $colName. "<br />";
		// $this->ExecuteSQL("SET SQL_BIG_SELECTS=1;");
		// $sql=
			// "SELECT ". $selColName. 
			// " FROM `". $_dataTableName. DATA_TABLE_LIST_POSTFIX. "` AS $dataTableAbbr, $_zipcodeTableName AS $zipTableAbbr". 
				// // ", ". $this->licTableName. " AS $licTableAbbr".
			// " WHERE $dataTableAbbr.`premist_zip` = z.`zipcode`". 
			// //" AND $licTableAbbr.`license_type` = $dataTableAbbr.`license_type`".
			// ($_isActiveList?" AND $dataTableAbbr.`type_status`= \"active\"": "").
			// " ORDER BY $dataTableAbbr.`type_status`, $dataTableAbbr.`premist_zip`, $dataTableAbbr.`license_type`";
		$sql=
			"SELECT $licTableAbbr.`license_desc`, a.*".
			" FROM".
				" (SELECT ". $selColName. 
				// " FROM `". $_dataTableName. DATA_TABLE_LIST_POSTFIX. "` AS $dataTableAbbr LEFT JOIN $_zipcodeTableName AS $zipTableAbbr".
				" FROM `". $_dataTableName. DATA_TABLE_LIST_POSTFIX. "` AS $dataTableAbbr, $_zipcodeTableName AS $zipTableAbbr".
					// ", ". $this->licTableName. " AS $licTableAbbr".
				" WHERE LEFT($dataTableAbbr.`premist_zip`, 5) = z.`zipcode`". 
				//" AND $licTableAbbr.`license_type` = $dataTableAbbr.`license_type`".
				($_isActiveList?" AND $dataTableAbbr.`type_status`= \"active\"": "").
				// ($_isActiveList?" WHERE $dataTableAbbr.`type_status`= \"active\"": "").
				" ORDER BY $dataTableAbbr.`type_status`, $dataTableAbbr.`premist_zip`, $dataTableAbbr.`license_type`) AS a LEFT JOIN".
				" `license_type` as $licTableAbbr ON $licTableAbbr.`license_code`=a.`license_type`";
		// echo $sql. "<br />";
		// die();
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
			foreach($licColumns as $key => $value){
				$output.= "=". $col_enclosure. $rowRs[$key]. $col_enclosure. $col_seperator;
			}			
			foreach(self::$colums as $key => $values){
				// echo $j. "<br />";
				// echo $rowRs[$j]. "&nbsp;&nbsp;&nbsp;";
				if($values["output"]){
					$output.= "=". $col_enclosure. $rowRs[$key]. $col_enclosure. $col_seperator;
				}
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