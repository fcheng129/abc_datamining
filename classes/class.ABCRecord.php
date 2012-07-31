<?php
require_once("models/DataInfo.php");

class ABCRecord{
	
	private static $count= 0;
	private $id;
	private $contents;

	public function __construct($_data){
		$this->id= self::$count++;
		$this->contents= array();
		// $this->contents= array(
			// "org"=> $_data
		// );
		$target= array("\"", "'");
		$replace= "";
		$_data= str_replace($target, $replace, $_data);
		$this->processData($_data);
	}
	
	public function processData($_data){
		foreach(DataInfo::getColumns() as $key => $values){
			$this->contents[$key]= strtolower(trim(substr($_data, $values["startAt"]- 1, $values["size"])));
			// $this->contents[$key]= strtolower(trim(substr($this->contents["org"], $values[0]- 1, $values[1])));
		}
	}
	
	public function getContents(){
		return $this->contents;
	}
	
	public function toString(){
		$result= "ID: $this->id{ <br />";
		$i= 0;
		$indent= "&nbsp;&nbsp;&nbsp";
		foreach($this->contents as $key => $value){
			$i++;
			$result.= "$indent$key";
			if(is_array($value)){
				$result.= "<br />";
				foreach($value as $key1 => $value1)
					$result.= "$indent$indent$key1: [$value1]<br />";
			}else
				$result.= ": [$value]<br />";
		}
		$result.= "}<br />";
		return $result;
	}
	
	public function display(){
		// echo "display";
		echo $this->toString();
	}
}
?>