<?
// require_once('class.msg.php');
require_once('class.Session.php');

class LoginSession extends Session{
	private static $instance;
	
	private function LoginSession() {
		parent::Session();
	}
	
	public static function getInstance(){
		if (!self::$instance){
            self::$instance = new LoginSession();
        }
        return self::$instance; 
	}
	
	public function createTimeSession($_value){
		parent::createSession("login", $_value);
	}
	
	public function getTimeSession(){
		return parent::getSession("login");
	}
		
	public function createIDSession($_id){
		parent::createSession("id", $_id);
	}
	
	public function getIDSession(){
		return parent::getSession("id");
	}
	
	public function deleteLoginSession(){
		parent::deleteSession("id");
		parent::deleteSession("login");
	}
}
?>