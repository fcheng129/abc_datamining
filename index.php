<?
require_once('classes/class.Login.php');
Login::validateSession(30* 60, 'http://'. $_SERVER['SERVER_NAME']. '/login.php');

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" 
	"http://www.w3.org/TR/html4/strict.dtd">
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>ABC Data Processing</title>
	<script type="text/javascript" src="js/jquery-1.7.2.js"></script>

<script type="text/javascript">
	var count= 0;
	var currentID= 0;
	var maxCount= 4;
	var pages=new Array("download.php", "extract.php", "process.php", "outputfile.php");
	$(document).ready(function () {
		$('#processData').click(function () {
			for(i= 0; i< count; i++){
				$('#contentUpdate'+ (i)).hide();
			}
			currentID= 0;
	 		$(this).attr("disabled", "disabled");
			getPage();
			return false;
		});
	});
		
	function getPage() {
		addElement();
		$('#contentUpdate'+ (currentID)).hide();
	 	$('.loading').show();
		var data = 'page=' + encodeURIComponent(document.location.hash);
		$.ajax({
			url: pages[currentID],	
			type: "GET",		
			data: data,		
			cache: false,
			success: function (html) {
				$('#wrapper').show();
				$('.loading').hide();
				$('#contentUpdate'+ (currentID)).html(html);
				$('#contentUpdate'+ (currentID)).fadeIn('slow');
				currentID++;
				if(currentID< maxCount){
					getPage();
				}
				if((currentID+ 1)== maxCount) $('#processData').removeAttr("disabled");
			}		
		});
	}

	function addElement() {
		if(count< maxCount){
			var newdiv = document.createElement('div');
			var divIdName = 'contentUpdate'+ count;
			newdiv.setAttribute('id',divIdName);
			$('#content').append(newdiv);
			$('#'+ divIdName).addClass('contentBlock');
			$('#'+ divIdName).html('new div');
			count++;
		}		
	}
</script>
<style>
	body {
		margin:20px 0 0 0; 
		padding:0; 
/*		text-align:center;*/
		font-family: arial;
		font-size:12px;
/*		background:#282828;*/
	}
		
	#wrapper {
		width:600px; 
		margin:10px 0 0 10px;
	}
	
	#header {
		background:url(images/header.gif) no-repeat center center; 
		width:670px;
		height:58px;
	}
	
	#menu {
		list-style:none; 
		padding:0; 
		margin:23px 55px 0 0;
		float:right;
	}

	#menu li {
		float:left; 
		padding:0 15px 0 0;
	}

	#menu li a {
		color:#666; 
		font-weight: 700; 
		text-decoration:none;
	}

	#menu li a.selected {
		color:#333; 
		font-weight: 700; 
		text-decoration:underline;
	}
		
	#body {
		clear:both; 
/*		display:none;*/
		margin: 10px 0 0 20px;
	}
	
	.loading {
		background: url(images/loading_icon.gif) no-repeat 0 0;
		margin: 10px 0 0 20px;
		height:66px;
		display:none;
	}

	.contentBlock{
		margin: 5px 0;
	}
</style>
	
</head>
<body>
<div id="wrapper"><input type="submit" id="processData" value="Process Data"></div>

<div id="body">
	<div class="body">
		<div id="content">
		<!-- Ajax Content -->
		</div>
	</div>
</div>
<div class="loading"></div>
</body>
</html>