<?php
class ABCRecord{
	
	private static $count= 0;
	private $id;
	private $contents;
		
	public function __construct($_contents){
		$this->id= self::$count++;
		$target= array("\"", "'");
		$replace= "";
		$this->contents= explode("\t", str_replace($target, $replace, $_contents));
		$this->processData();
	}
	
	public function processData(){
	//license
		$this->contents["license"]= substr($this->contents[0], 0, 10);
		$this->contents["status"]= substr($this->contents[0], 13- strlen($this->contents[0]));
		// $this->contents[0]= Array("license"=> $license, "status"=> $status);
	//zipcode
		$this->contents["state"]= substr($this->contents[5], 0, 2);
		$zipcode= explode("-", substr($this->contents[5], 2, strlen($this->contents[5])- 2));
		$this->contents["zipcode"]= $zipcode[0];
		$this->contents["zipcode2"]= $zipcode[1];
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
					$result.= "$indent$indent$key1: $value1<br />";
			}else
				$result.= ": $value<br />";
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