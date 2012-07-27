<?
require_once('classes/class.Login.php');
require_once('classes/class.msg.php');
require_once('classes/class.PasswordEmail.php');

if(!isset($_POST["log"])){
	$log= 0;
	$_POST["log"]= "0";	
}else{
	$log= intval($_POST["log"])+ 1;
	$_POST["log"]= strval($log);	
}
if(isset($_GET["vCode"])){
	$vCode= $_GET["vCode"];
}else{
	$vCode= $_POST["vCode"];
}
$isBlankField= false;
$isPasswordMismatch= false;

if($_POST["password"] != $_POST["password2"]) $isPasswordMismatch= true;

if(isset($vCode) && isset($_POST["email"]) && isSet($_POST["username"]) && !$isPasswordMismatch){
	$l= new Login();
	//msg::ot("trying login ". $_POST["username"]. " w password ". $_POST["password"]);
	$isUpdated= $l->resetPassword($_POST["username"], $_POST["email"], $_POST["password"], $vCode);
	if($isUpdated){
		$vCode= "";
		$_POST["email"]= "";
		$_POST["username"]= "";
	}
	//if(isUpdated) header('Location: http://'. $_SERVER['SERVER_NAME']. '/login.php');
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">

<head>
<meta http-equiv="Content-Language" content="zh-tw" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Reset Password</title>
<style type="text/css">
<!--
#apDiv1 {
	position:absolute;
	left:303px;
	top:78px;
	width:502px;
	height:260px;
	z-index:1;
}
#container
{
	margin-left: auto;
	margin-right: auto;
	width: 800px;
}
.inputTableRow{
	margin-left: 5px;
}
#errorMsg{
	width:300px;
	height:35px;
	margin-left: 20px;
	font-size:12px;
	color: #ff0000;
	font-family: Verdana, Geneva, sans-serif;
	font-weight:bold;
}
.fieldErrorMsg{
	margin-left: 5px;
	font-size:10px;
	color: #ff0000;
	font-family: Verdana, Geneva, sans-serif;
	font-weight:bold;
}
.forgetLink{
	height: 36px;
	display:table-cell; 
	vertical-align:bottom;
}
.forgetLink a{
	text-decoration: none;
	margin-left: 5px;
	color: #ff0000;
}
-->
</style>
<script src="js/countdownRedirect.js" type="text/javascript"></script>
</head>

<body <? if($isUpdated) echo "onload='countdownRedirect(\"http://". $_SERVER['SERVER_NAME']. "/login.php\")'"; ?>>

<div id="container">
	<form method="post" action="resetPassword.php">
		<table width="381" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<th height="36" width="80" scope="row">&nbsp;</th>
				<td width="165">
				<div class="inputTableRow">
					<strong>Reset Password</strong>
					<input type="hidden" name="log" value="0" /></div>
				</td>
				<td width="136" height="36">&nbsp;</td>
			</tr>
			<tr>
			  <th height="35" scope="row">Validation Code</th>
			  <td><div class="inputTableRow">
			    <input name="vCode" type="text" value="<? echo $vCode;?>" style="width: 150px; height: 22px" id="vCode" />
			    </div></td>
			  <td height="35">
			    <div class="fieldErrorMsg">
<?
if($log> 0 && !$isUpdated){
	if(strlen(trim($vCode))== 0){
		msg::msgNecessaryField();
	}
}
?>
			    </div>
			  </td>
		  </tr>
			<tr>
			  <th height="35" scope="row">Email</th>
			  <td><div class="inputTableRow">
			    <input name="email" type="text" value="<? echo $_POST["email"]; ?>" style="width: 150px; height: 22px" />
			    </div></td>
			  <td height="35">
			    <div class="fieldErrorMsg">
<?
if($log> 0 && !$isUpdated){
	if(strlen(trim($_POST["email"]))== 0){
		msg::msgNecessaryField();
		$isBlankField= true;
	}
}
?>
			    </div>
			  </td>
		  </tr>
			<tr>
			  <th height="35" scope="row">Username</th>
			  <td><div class="inputTableRow">
			    <input name="username" type="text" value="<? echo $_POST["username"]; ?>" style="width: 150px; height: 22px" />
			    </div></td>
			  <td height="35">
			    <div class="fieldErrorMsg">
<?
if($log> 0 && !$isUpdated){
	if(strlen(trim($_POST["username"]))== 0){
		msg::msgNecessaryField();
		$isBlankField= true;
	}
}
?>
			    </div>
			  </td>
		  </tr>
			<tr>
			  <th height="35" scope="row">Password</th>
			  <td><div class="inputTableRow">
			    <input name="password" type="password" style="width: 150px; height: 22px" id="password" />
			    </div></td>
			  <td height="35">
			    <div class="fieldErrorMsg">
<?
if($log> 0 && !$isUpdated){
	if(strlen(trim($_POST["password"]))== 0){
		msg::msgNecessaryField();
		$isBlankField= true;
	}else if($isPasswordMismatch) msg::out("Password Mismatch.");
}
?>
			    </div>
			  </td>
		  </tr>
			<tr>
			  <th height="35" scope="row">Confirm<br />Password</th>
			  <td><div class="inputTableRow">
			    <input name="password2" type="password" style="width: 150px; height: 22px" id="password2" />
			    </div></td>
			  <td height="35">
			    <div class="fieldErrorMsg">
<?
if($log> 0 && !$isUpdated){
	if(strlen(trim($_POST["password2"]))== 0){
		msg::msgNecessaryField();
		$isBlankField= true;
	}else if($isPasswordMismatch) msg::out("Please re-enter.");
}
?>
			    </div>
			  </td>
		  </tr>
			<tr>
				<th height="35" scope="row">&nbsp;</th>
				<td>
				<div class="inputTableRow">
					<input name="Submit1" type="submit" value="submit" /> </div>
				</td>
				<td>
				<div class="fieldErrorMsg">
				</div>
				</td>
			</tr>
			<tr>
				<th height="35" scope="row">&nbsp;</th>
				<td colspan="2">
					<div class="fieldErrorMsg">
<?
$countDown= "Redirect to Login Page in <span class=\"counter\" id=\"COUNTDOWN_REDIRECT\">5</span> Sec. ";
$countDown.= "If not direct to login page, please use <a href=\"http://". $_SERVER['SERVER_NAME']. "/login.php\">this link</a>.";
// msg::display("isBlankField", $isBlankField);
// msg::display("isPasswordMismatch", $isPasswordMismatch);
if($log> 0 && !$isBlankField && !$isPasswordMismatch){
	if($isUpdated){
		msg::out("Password is reset successfully. Please use new password to login in. ". $countDown);
	}
	else msg::out("Information Provided is not matched. Please try again.");
} 
?>
					</div>
				</td>
			</tr>
		</table>
		<div id="errorMsg">
<??>
		</div>
	</form>
</div>

</body>

</html>
