<!DOCTYPE html>
<?php 
	putenv('PATH=/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin');
	
	if(isset($_POST['kill']))
	{
		$cnr=0;
		kill_main();
		//$output=shell_exec('gcc main.c -Wall -std=c99 -o main 2>&1');
		//$output=file_get_contents('./output.log');
		$output=file_get_contents('./output.log',true);
		if(empty($output))
			$output="main.c: No Errors";
		$main_c=file_get_contents('./main.c',true);
		//$prog_out=file_get_contents('./main.out',true);
	}else if(isset($_POST['running']))
	{
		shell_exec('rm ./main');
		file_put_contents('./main.c',$_POST['code']);
		$main_c=$_POST['code'];
		
		$cnr=1;
		
	
	}else if(isset($_POST['codetext']))
	{
		$main_c=$_POST['codetext'];
		$cnr=0;
	}
	else 
	{
		$cnr=0;
		$f=@fopen('./main.c', "r+");
		if($f!==false)
		{
			ftruncate($f,0);
			fclose($f);
		}
		$main_c="//main.c";
		$output="";
		$prog_out="";
		
	}
	
	
	function kill_main()
	{
		shell_exec('pkill main');
	}

?>
<html>
<head>
	<script src="socket.io-1.1.0.js"></script>
  <script src="codemirror-4.2/lib/codemirror.js"></script>
  <link rel="stylesheet" href="codemirror-4.2/lib/codemirror.css">
  <link rel="stylesheet" href="codemirror-4.2/theme/eclipse.css">
  <script src="codemirror-4.2/mode/clike/clike.js"></script>
  <meta content="text/html; charset=ISO-8859-1" http-equiv="content-type" >
  <style type="text/css">
	.CodeMirror {border: 1px solid black}
  </style>
  <title>DEV BOX Edit</title>
</head>

<style type="text/css"></style></head>
<body style="height:100%"><!-- onLoad="$main_c=editor.getValue();"-->

	

<big style="color: rgb(246, 0, 0);"><big><big>DEV<br>
BOX</big></big></big><br>

<br>
<!--
<div style="width:39%;height:100%;float:right;vertical-align:top">
<textarea id="stuff" style="height:100%;width:99%">Some text</textarea>
</div>
-->

<div style="width:100%;float:left;">
Enter your code:<br>
<form id="code_entry" method="post" action="" name="code_entry" accept-charset="utf-8" >
<textarea rows="15" id="code" name="code"><?php echo $main_c; ?></textarea>
<br>
<button name="submit">Compile &amp; Run</button>
<input type="hidden" id="running" name="running" value="true"></input>
</form>
<form id="kill" method="post" action="">
<button name="kill">Terminate Program</button></form>
<textarea cols="100" rows="5" id="comp_out" readonly></textarea><br>
<textarea cols="100" rows="5" id="prog_ex" readonly></textarea><br>

<br>
</div>

<script type="text/javascript">
	var text_area=document.getElementById("code");
	var comp_out=document.getElementById("comp_out");
	var prog_ex=document.getElementById("prog_ex");
	var running=document.getElementById("running");
	var editor=CodeMirror.fromTextArea(text_area,{
		theme: "eclipse",
		lineNumbers: true,
		mode: "text/x-csrc",
		indentUnit: 3,
		tabSize: 3,
		indentWithTabs: true,
	});
	comp_out.value='Errors:\n';
	prog_ex.value='';
	
	function CompileRun()
	{
		var socket=io.connect('seuss.ca:4000');
		socket.emit('C',{data: 'C'});
		socket.on('compile',function(msg)
		{
			console.log("compile");
			comp_out.value+=msg.data+'\n';
		});
		socket.on('run',function(msg)
		{
			console.log("Run");
			prog_ex.value+=msg.data+'\n';
		});
		socket.on('end',function(msg)
		{
			console.log("End");
			//socket.disconnect();
		});
		
	}
		
</script>
</body>
</html>
<?php 
	if($cnr==1)
	{	
		echo "<script>console.log(running.vaule);CompileRun()</script>";
		unset($_POST['running']);
		$cnr=1;
	}
?>
