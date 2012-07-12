<?
require_once('classes/class.Login.php');
// require_once('classes/class.msg.php');

if(isset($_GET["action"])){
	if(trim($_GET["action"])== "logout"){
		Login::logoutSession();
	}
}

if(!isset($_POST["log"])){
	$log= 0;
	$_POST["log"]= "0";	
}else{
	$log= intval($_POST["log"])+ 1;
	$_POST["log"]= strval($log);	
}

$l= new Login();
// $id= $l->validation($_POST['username'], $_POST['password']);
$isLogin= $l->validation($_POST["username"], $_POST["password"], 'http://'. $_SERVER['SERVER_NAME']. '/index.php')!= Login::NO_ID;
// $isLogin= $l->validation($_POST["username"], $_POST["password"])!= Login::NO_ID;
// echo "id: $id&nbsp;&nbsp;&nbsp;". ($isLogin? "good": "bad"). "<br />";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">

<head>
<meta http-equiv="Content-Language" content="zh-tw" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Adding Order Info</title>
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
</head>

<body onload="document.getElementById('username').focus();">

<div id="container">
	<form method="post" action="login.php" name="loginForm">
		<table width="381" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<th height="36" width="80" scope="row">&nbsp;</th>
				<td width="165">
				<div class="inputTableRow">
					<strong>Login Page</strong>
					<input type="hidden" name="log" value="0" /></div>
				</td>
				<td width="136" height="36">&nbsp;</td>
			</tr>
			<tr>
				<th height="35" scope="row">Username</th>
				<td>
				<div class="inputTableRow">
					<input name="username" id="username" type="text" style="width: 150px; height: 22px" tabindex="1" />
				</div>
				</td>
				<td height="35">
				<div class="forgetLink"><a href="<?echo 'http://'. $_SERVER['SERVER_NAME']. "/forgetUsername.php"; ?>" tabindex="4">Forget Username?</a></div>
				</td>
			</tr>
			<tr>
				<th scope="row" style="height: 35px">Password</th>
				<td style="height: 35px">
				<div class="inputTableRow">
					<input name="password" type="password" style="width: 150px; height: 22px" tabindex="2" />
				</div>
				</td>
				<td style="height: 35px">
				<div class="forgetLink"><a href="forgetPassword.php" tabindex="5">Forget Password?</a></div>
				</td>
			</tr>
			<tr>
				<th height="35" scope="row">&nbsp;</th>
				<td>
				<div class="inputTableRow">
					<input name="Submit1" type="submit" value="submit" tabindex="3" /> </div>
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
<? if($log> 0 && !$isLogin) echo "Incorrected Username or Password."; ?>
					</div>
				</td>
			</tr>
		</table>
		<div id="errorMsg">
		</div>
	</form>
</div>

</body>

</html>
