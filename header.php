<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<title><?php echo $_Label; ?></title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<link rel="stylesheet" type="text/css" href="FileEditor.css">
		<script type="text/javascript" src="ajas_tabbing.js"></script>
		<script type="text/javascript"><!--
			var h=24,v=120;//horizontal and vertical margins for textarea
			function setSize(){
				if(!document.getElementById('fileContents'))return;
				document.getElementById('fileContents').style.width=(document.body.clientWidth-h)+'px';
				if(document.documentElement){
					document.getElementById('fileContents').style.height=Math.max(200,document.documentElement.clientHeight-v)+'px';
				}else{
					document.getElementById('fileContents').style.height=Math.max(200,document.body.clientHeight-v)+'px';
				}
			}
			window.onresize=setSize;
		//--></script>
	</head>
	<body onload="setSize();">
		<div class="main rounded">
			<h1 class="left"><?php echo $_Label; ?></h1>
<?php if($_Pass!=''){echo '<p class="right"><a class="logout" href="'.$_SERVER['SCRIPT_NAME'].'?logout=1">Logout</a></p>';} ?>
			<div class="clear rounded body">
<?php 
	if ($sErr!=''){
		echo '<h3>Errors:</h3><p>'.$sErr.'</p>';
	}
	if ($sMsg!=''){
		echo '<h3>Message:</h3><p>'.$sMsg.'</p>';
	}
?>
