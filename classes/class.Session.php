<?
// require_once('class.msg.php');

class Session{
	public function Session() {
		session_start();
	}
	
	public function createSession($_name, $_value){
		$_SESSION[$_name]= $_value;
	}
	
	public function deleteSession($_name){
		unset($_SESSION[$_name]);
	}
	
	public function getSession($_name){
		return $_SESSION[$_name];
	}
	
	public function destoryAllSession(){
		session_destroy();
	}
}
?>