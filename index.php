<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" 
	"http://www.w3.org/TR/html4/strict.dtd">
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>Ajax Loader</title>
	<script type="text/javascript" src="js/jquery-1.7.2.js"></script>
	<script type="text/javascript" src="js/jquery.history.js"></script>

	<script type="text/javascript">
	
	$(document).ready(function () {


	    $.history.init(pageload);	
	    
		$('a[href=' + window.location.hash + ']').addClass('selected');
		
		$('a[rel=ajax]').click(function () {
			var hash = this.href;
			hash = hash.replace(/^.*#/, '');
	 		$.history.load(hash);	
	 		
	 		$('a[rel=ajax]').removeClass('selected');
	 		$(this).addClass('selected');
	 		$('#body').hide();
	 		$('#wrapper').hide();
	 		$('.loading').show();
	 		
			getPage();
	
			return false;
		});	
	});
	
	function pageload(hash) {
		if (hash) getPage();    
	}
		
	function getPage() {
		var data = 'page=' + encodeURIComponent(document.location.hash);
		$.ajax({
			url: "process.php",	
			type: "GET",		
			data: data,		
			cache: false,
			success: function (html) {	
				$('.loading').hide();				
				$('#content').html(html);
				$('#body').fadeIn('slow');		
		
			}		
		});
	}

	</script>
	
	<style>
		body {
			margin:20px 0 0 0; 
			padding:0; 
/*			text-align:center;*/
			font-family: arial;
			font-size:12px;
/*			background:#282828;*/
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
				display:none;
				margin: 10px 0 0 10px;
			}
			
			.loading {
				background: url(images/loading_icon.gif) no-repeat 0 0;
				margin: 10px 0 0 10px;
				height:66px;
				display:none;
			}
			
			
	</style>
	
</head>
<body>

<div id="wrapper"><a href="#" rel="ajax">Process Data</a></div>
<div class="loading"></div>
	<div id="body">
		<div class="header"></div>
		<div class="body">
			<div id="content">
			<!-- Ajax Content -->
			</div>
		</div>
		<div class="footer"></div>
	</div>
</div>

</div>
</body>
</html>