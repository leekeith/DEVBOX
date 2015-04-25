<!DOCTYPE html>
<?php
	ini_set('include_path','phplibs/');
	include "TutoriML.php";
	if(isset($_POST['lang']))
	{
		$lang=$_POST['lang'];
		$dir="tutorials/".$lang;
		$files=array_slice(scandir($dir),2);
	}
		$page=$_POST['page'];
		$tml=$_POST['tutorial'];
	if(isset($tml))
	{
		if(isset($_POST['code']))
			$base=$_POST['code'];
		else $base=$_POST['code_x'];
	
		$title=getTitle($dir, $tml);
		$n_pages=getPages($dir, $tml);
		if(isset($_POST['submit']))
		{
			$code=getCode($dir, $tml, $page);
			$base=$base."\n".$code[0][1];
		}
	
		if(isset($_POST['next']) || isset($_POST['prev']))
			$getPage="";
		if(isset($_POST['next']) && $page<$n_pages)
		{
			$page=$page+1;
			$code=getCode($dir, $tml, $page);
			$base=$base."\n".$code[0][1];
		}

		if(isset($_POST['prev']) && $page>1)
			$page=$page-1;
	
		$desc=getDesc($dir, $tml, $page);
	
	}
	
	
	

	if(!isset($n_pages))
		$n_pages=1;	
		
	
		
	
		
		
		
	
	
	function insertVars($page, $tml, $base, $lang)
	{
		echo('<input type="hidden" id="_lang" name="lang" value="'.$lang.'"></input>'.PHP_EOL);
		echo('<input type="hidden" name="code_x" value="'.$base.'"></input>'.PHP_EOL);
		echo('<input type="hidden" name="selected" value="true"></input>'.PHP_EOL);
		echo('<input type="hidden" id="tut" name="tutorial" value="'.$tml.'"></input>'.PHP_EOL);
		echo('<input type="hidden" name="page" value="'.$page.'"></input>'.PHP_EOL);		
	}
?>
<html>
	<head>
	<script src="codemirror-4.2/lib/codemirror.js"></script>
  <link rel="stylesheet" href="codemirror-4.2/lib/codemirror.css">
  <link rel="stylesheet" href="codemirror-4.2/theme/eclipse.css">
  <script src="codemirror-4.2/mode/clike/clike.js"></script>
  <script src="codemirror-4.2/mode/verilog/verilog.js"></script>
  
  <meta content="text/html; charset=ISO-8859-1" http-equiv="content-type" >
  
  <style type="text/css">
		.CodeMirror {border: 1px solid black}
		div.left
		{
			width:49.5%;
			height:100%;
			float:left;

		}
		div.right
		{
			width:50%;
			height:100%;
			float:right;

		}
  </style>
	
	<title>DEVBOX Tutorials</title>
	</head>
	<body>
	<font size="22"><a href="index.html"><img style="width: 209px; vertical-align: middle;" alt="DEVBOX" title="DEVBOX Logo"
	src="DevBoxLogoTest418x146.png"></a> TUTORIALS</font>
<br>

	<div>
	<form id="setlang" method="post" action="">
		<label for="lang">Select Language: </label>
		<select id="lang" name="lang" size=1 onchange="this.form.submit()">
			<option disabled selected>-language-</option>
			<option id="c" value="c">C</option>
			<option id="arduino" value="arduino">Arduino</option>
			<option id="verilog" value="verilog">Verilog</option>
		</select>
	</form>
		
	<?php
		if(isset($lang))
		{ ?>
			<form id="choose" method="post" action="" name="choose">
			<label for="tutorial">Select Tutorial: </label>
			<?php echo('<input type="hidden" id="_lang" name="lang" value="'.$lang.'"></input>');
			 
			if(!isset($_POST['selected']))
				echo('<select id="tutorial" name="tutorial" size="'.count($files).'">');
			else
				echo('<select id="tutorial" name="tutorial" size="1">');
			foreach($files as $tmlx)
			{
				$file=unfile($tmlx);
				echo('<option id="'.$tmlx.'" value="'.$tmlx.'">'.$file.'</option>');
			}
	?>
			</select>
			<input type="hidden" name="selected" id="selected" value="true"></input>
			<input type="hidden" name="page" id="page" value=1></input>
			
	
	<button name="submit">GO</button>
	
	<?php } ?>
	</form>
	</div>
	<br>
	
	
	<?php if(isset($tml))
	{
		?>
		<h1><?php echo($title);?></h1>
		<div id="description" class="left">
			<?php echo($desc); ?>
			<br><br><form id="turnpage" name="turnpage" action="" method="post">
				<div style="float: center; text-align: center;">
				<?php 
					echo($page.'/'.$n_pages);
				?> </div>
				<button name="prev" style="float: left;"> &lt;&lt; Prev</button>			
				<button name="next" style="float: right;"> Next &gt;&gt;</button>
				<?php insertVars($page, $tml,$base, $lang); ?>
			</form>
		</div>
		<div id="thecode" class="right">
			<form id="code_entry" method="post" action="" name="code_entry" accept-charset="utf-8" >
				<textarea rows="15" id="code" name="code"><?php echo($base); ?></textarea>
				<br>
				<button name="candr">Compile &amp; Run</button>
				<input type="hidden" name="running" value="true">
				<?php insertVars($page, $tml, $base, $lang); ?>
			</form>
			<form id="kill" method="post" action="">
				<button name="kill">Terminate Program</button>
				<input type="hidden" name="running" value="false">
				<?php insertVars($page, $tml, $base, $lang); ?>
			</form>
			<textarea cols="60" rows="5" name="comp_out" readonly></textarea><br>
			<textarea cols="60" rows="5" name="prog_ex" readonly></textarea><br>
			<br>
			
		</div>
 		<script type="text/javascript">
 			var lang=document.getElementById("_lang");
 			var hilite="text/x-csrc"
 			switch(lang.value)
 			{
 			case "verilog":
 				hilite="text/x-verilog"
 				break;
 			case "arduino":
 			case "c":
 			default:
 				break;
 			}
 			 
			var text_area=document.getElementById("code");
			var editor=CodeMirror.fromTextArea(text_area,{theme: "eclipse",lineNumbers: true, mode: hilite,	indentUnit: 3,tabSize: 3,indentWithTabs: true});
			
			
			var selected=document.getElementById("tut");
			var optn=document.getElementById(selected.value);
			optn.setAttribute('selected','selected');
			selected=document.getElementById("_lang");
			optn=document.getElementById(selected.value);
			optn.setAttribute('selected','selected');
		</script>
	<?php } ?>
	<script type="text/javascript">
		var selected=document.getElementById("_lang");
		var optn=document.getElementById(selected.value);
		optn.setAttribute('selected','selected');
	</script>
	</body>
</html>

