<?
class msg{  
	// private static $testStarting= "-------------------------- Testing Starting";
	// private static $testEnding= "-------------------------- Testing   Ending";
	private static $tabStart= "<span style=\"margin-left:10px;\">";
	private static $tabEnd= "</span>";
	private static $tab2Start= "<span style=\"margin-left:20px;\">";
	private static $tab2End= "</span>";
	public function __construct() {
	
	}
	
	public static function testStart ($title){
		echo $title. "-------------------------- Testing Starting". "<br/>";
	}
	
	public static function testEnd($title){
		echo $title. "-------------------------- Testing   Ending". "<br/>";
	}
	
	//Output Title
	public static function ot($_contents){
		echo "<span style=\"font-weight:bold;\">**".$_contents. "</span><br />";		
	}
	
	//Output Contents
	public static function oc($_contents){
		echo "<span style=\"margin-left:10px;\">".$_contents. "</span><br />";		
	}
	
	//Normal Output
	public static function out($_contents){
		echo $_contents. "<br />";		
	}
	

	public static function display($title, $value){
		echo "<span style=\"margin-left:10px;\">". $title. ": ". $value. "</span><br />";		
	}
	
	public static function displayArray($title, $value){
		echo "<span style=\"margin-left:10px;\">". $title. ":</span><br />";
		echo "<span style=\"margin-left:20px;\">";
		print_r($value);
		echo "</span><br />";		
	}
	
	public static function msgNecessaryField(){
		echo "*necessary Field";
	}

}
?>