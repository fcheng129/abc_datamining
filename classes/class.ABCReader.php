<?php
require_once("include/config.php");
require_once("class.ABCRecord.php");
require_once("models/DataInfo.php");

class ABCReader{
	
	private $filename;
	private $path;
	private $downloadFolder;
	private $dataFolder;
	private $zipfile;
	
	private $fileHandler;
	private $db;
	
	function __construct($_filename= NULL, $_path= ""){
		$this->zipfile= DATA_FILENAME_PREFIX. date("Ymd"). ".zip";
		$this->downloadFolder= "download". DIRECTORY_SEPARATOR;
		$this->dataFolder= "data". DIRECTORY_SEPARATOR;
		$this->filename= "m_tape437.LST";
		$this->path= $this->dataFolder. $this->zipfile. DIRECTORY_SEPARATOR;
		
		if($_filename){
			$this->filename= $_filename;
			$this->path= $_path;
			// $this->readFile();
			// if(file_exists($this->path. $this->filename)) $this->readFile();
		}
		$this->db= new DataInfo();
	}

	public function downloading(){
		$originZipfile= "http://www.abc.ca.gov/datport/ABC_Data_Export.zip";

		// $zipfile= "download". DIRECTORY_SEPARATOR. DATA_FILENAME_PREFIX. date("Ymd"). ".zip";
		$zipPath= $this->downloadFolder. $this->zipfile;
		echo "Downloading File<br />";
		echo "&nbsp;&nbsp;&nbsp;Origin Zip File: ". $originZipfile. "<br />";
		echo "&nbsp;&nbsp;&nbsp;Taget Zip File: ". $zipPath. "<br />";


		$this->path= $this->dataFolder. $this->zipfile. DIRECTORY_SEPARATOR;
		// try{
		$this->downloadFile($originZipfile, $zipPath);
		// }catch(Exception $e){
			// echo "Failed to download file! (Error ". $e->getMessage(). ")";
			// die();
		// }
		return true;
	}

	public function extracting(){
		echo "Extracting File<br />";
		$zipPath= $this->downloadFolder. $this->zipfile;
		$zip = new ZipArchive;
		if ($zip->open($zipPath)) {
    		$zip->extractTo($this->dataFolder. $this->zipfile);
    		$zip->close();
    		echo '&nbsp;&nbsp;&nbsp;Extract file successfully<br />'; flush();
			// $this->filename= "m_tape437.LST";
			$this->path= $this->dataFolder. $this->zipfile. DIRECTORY_SEPARATOR;
			// $this->readFile();
		} else {
    		echo '&nbsp;&nbsp;&nbsp;Failed to read the zip file<br />'; flush();
		}
	}

	public function readFile(){
		$p= $this->paht. $this->filename;
		if(file_exists($p)) $this->fileHandler = fopen($p, 'r');
	}

	private function downloadFile ($url, $path) {
		$newfname = $path;
		$file = fopen ($url, "rb");
		if($file){
			$newf = fopen ($newfname, "wb");
			if ($newf)
				while(!feof($file)) {
					fwrite($newf, fread($file, 1024 * 8 ), 1024 * 8 );
				}
		}
		if($file) fclose($file);
		if($newf) fclose($newf);
	}

	public function processListFile(){
		echo "Processing Data<br />";
		$this->db->dbInitList();
		$p= $this->path. $this->filename;
		// $p= $this->filename;
		echo "&nbsp;&nbsp;&nbsp;file path: $p<br />";
		if(file_exists($p)) $this->fileHandler = fopen($p, 'r');
		$i= 0;
		// while (!feof($this->fileHandler) && $i< 100){
		while (!feof($this->fileHandler)){
			// $i++;
			// $theData = fgets($this->fileHandler, 1024);
			// echo $theData. "<br />";
			$line= fgets($this->fileHandler, 1024);
			// echo $line. "<br />";
			if($line){
				$i++;
				$theData = new ABCRecord($line);
				// $theData->display();
				// echo "Writing...<br />";
				$this->db->writeDataList($theData);
			}
		}
		echo "&nbsp;&nbsp;&nbsp;Total $i records ..... Read Completed.<br />";
		if($this->fileHandler) fclose($this->fileHandler);
	}
	
	public function outputResultList($_dataTableName){
		$tableName= array("los_angeles", "san_barndardino", "ventura", "san_luis_obispo", "kern", "santa_barbara", "orange", "riverside", "san_diego", "imperial");
		// $tableName= array("los_angeles");
		$fileType= ".csv";
		echo "Output Files: <br />";
		foreach($tableName as $value){
			$rowNum= $this->db->findZipcodeAllList(DATA_TABLE_PREFIX. $_dataTableName, $value. $fileType, DATA_ZIPCODE_TABLE_POSTFIX. $value);
			echo "&nbsp;&nbsp;&nbsp;<a href=\"$value$fileType\">$value$fileType</a>&nbsp;&nbsp;($rowNum Record". ($rowNum> 1? "s": "").")<br />";
			$rowNum= $this->db->findZipcodeActiveList(DATA_TABLE_PREFIX. $_dataTableName, $value. "_active_list". $fileType, DATA_ZIPCODE_TABLE_POSTFIX. $value);
			echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"$value". "_active_list". "$fileType\">$value". "_active_list". "$fileType</a>&nbsp;&nbsp;($rowNum Active Record". ($rowNum> 1? "s": "").")<br />";
			echo "<br />";
		}
	}
	// private function outputResultList($_dataTableName, $_tableName){
		// $fileType= ".csv";
		// $rowNum= $this->db->findZipcodeList($_dataTableName, $_tableName. $fileType, $_tableName);
		// echo "<a href=\"$_tableName$fileType\">$_tableName$fileType</a>&nbsp;&nbsp;($rowNum Record(s))<br />";
	// }
// 	
	// public function outputLosangelesResultList($_dataTableName){
		// // echo "outputLosangelesResultList<br />";
		// $this->outputResultList($_dataTableName, "losangeles");
	// }
// 	
	// public function outputSanbarndardinoResultList($_dataTableName){
		// // $filename= "sanbarndardino.csv";
		// // $this->db->outputSanbarndardinoResultList($_dataTableName, $filename);
		// // echo "<a href=\"$filename\">$filename</a>&nbsp;&nbsp; ($rowNum Record(s)<br />";
		// $this->outputResultList($_dataTableName, "sanbarndardino");
	// }
// 	
	// public function outputVenturaResultList($_dataTableName){
		// // $filename= "ventura.csv";
		// // $rowNum=$this->db->outputVenturaResultList($_dataTableName, $filename);
		// // echo "<a href=\"$filename\">$filename</a>&nbsp;&nbsp; ($rowNum Record(s)<br />";
		// $this->outputResultList($_dataTableName, "ventura");
	// }
	public function processXls(){
		// $theData = fgetcsv($fh, 2048, ",");
		// echo "Date_". Date("Ym");
		//$this->dbInit("Data_". date("Ymd"));
		$this->db->dbInitXls();
		$p= $this->path. $this->filename;
		echo "file path: $p<br />";
		if(file_exists($p)) $this->fileHandler = fopen($p, 'r');
		$i= 0;
		// while (!feof($this->fileHandler) && $i< 30){
		while (!feof($this->fileHandler)){
			$i++;
			// echo $i. "<br />";
			$theData = stream_get_line($this->fileHandler, 1024, "\r");
			// $theData2= explode("\t", $theData);
			// echo $theData. "<br />";
			// print_r($theData2);
			// echo "<br /><br />";
			$theData2= new ABCRecord($theData);
			// $theData2->display();
			$this->db->writeDataXls($theData2);
		}
		echo "Total $i records ..... Read Completed.<br /><br />";
		if($this->fileHandler) fclose($this->fileHandler);
	}
	
	public function outputLosangelesResultXls($_dataTableName){
		$this->db->outputLosangelesResultXls($_dataTableName, "los_angeles.csv");
	}
	
	public function outputSanbarndardinoResultXls($_dataTableName){
		$this->db->outputSanbarndardinoResultXls($_dataTableName, "sanbarndardino.csv");
	}
	
	public function outputVenturaResultXls($_dataTableName){
		$this->db->outputVenturaResultXls($_dataTableName, "ventura.csv");
	}
}
?>