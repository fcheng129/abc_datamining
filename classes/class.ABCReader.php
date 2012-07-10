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
		if($_filename){
			$this->filename= $_filename;
			$this->path= $_path;
			// $this->readFile();
			// if(file_exists($this->path. $this->filename)) $this->readFile();
		}else{
			$this->zipfile= DATA_FILENAME_PREFIX. date("Ymd"). ".zip";
			$this->downloadFolder= "download". DIRECTORY_SEPARATOR;
			$this->dataFolder= "data". DIRECTORY_SEPARATOR;
			// $zipfile= "download". DIRECTORY_SEPARATOR. DATA_FILENAME_PREFIX. date("Ymd"). ".zip";
			$zipPath= $this->downloadFolder. $this->zipfile;
			echo "zipfile: ". $zipPath. "<br />";

			$this->downloadFile("http://www.abc.ca.gov/datport/ABC_Data_Export.zip", $zipPath);
			
			$zip = new ZipArchive;
			if ($zip->open($zipPath)) {
    			$zip->extractTo($this->dataFolder. $this->zipfile);
    			$zip->close();
    			echo 'Extract file successfully<br />';
				$this->filename= "m_tape437.LST";
				$this->path= $this->dataFolder. $this->zipfile. DIRECTORY_SEPARATOR;
				// $this->readFile();
			} else {
    			echo 'Failed to read the zip file<br />';
			}
		}
		$this->db= new DataInfo();
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
		$this->db->dbInitList();
		$p= $this->path. $this->filename;
		echo "file path: $p<br />";
		if(file_exists($p)) $this->fileHandler = fopen($p, 'r');
		$i= 0;
		// while (!feof($this->fileHandler) && $i< 10){
		while (!feof($this->fileHandler)){
			$i++;
			// $theData = fgets($this->fileHandler, 1024);
			// echo $theData. "<br />";
			$line= fgets($this->fileHandler, 1024);
			// echo $line. "<br />";
			$theData = new ABCRecord($line);
			// $theData->display();
			$this->db->writeDataList($theData);
		}
		echo "Total $i records ..... Read Completed.<br /><br />";
		if($this->fileHandler) fclose($this->fileHandler);
	}

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
	
	public function outputSanbarndardinoResult($_dataTableName){
		$this->db->outputSanbarndardinoResultXls($_dataTableName, "sanbarndardino.csv");
	}
	
	public function outputVenturaResult($_dataTableName){
		$this->db->outputVenturaResultXls($_dataTableName, "ventura.csv");
	}
}
?>