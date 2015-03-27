<!DOCTYPE html>

<?php 
	putenv('PATH=/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin');
	
	if(isset($_POST['kill']))
	{
		kill_main();
		//$output=shell_exec('gcc main.c -Wall -std=c99 -o main 2>&1');
		//$output=file_get_contents('./output.log');
		$output=file_get_contents('./output.log',true);
		if(empty($output))
			$output="main.c: No Errors";
		$main_c=file_get_contents('./main.c',true);
		$prog_out=file_get_contents('./main.out',true);
	}else if(isset($_POST['running']))
	{
		shell_exec('rm ./main');
		file_put_contents('./main.c',$_POST['code']);
		$output=shell_exec('gcc main.c -Wall -std=c99 -o main 2>&1');
		file_put_contents('./output.log',$output);
		if(empty($output))
			$output="main.c: No Errors";
		shell_exec('./main >main.out');	
		$main_c=file_get_contents('./main.c',true);
		$prog_out=file_get_contents('./main.out');
	}else if(isset($_POST['codetext']))
	{
		$main_c=$_POST['codetext'];
	}
	else 
	{
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
  <script src="codemirror-4.2/lib/codemirror.js"></script>
  <link rel="stylesheet" href="codemirror-4.2/lib/codemirror.css">
  <link rel="stylesheet" href="codemirror-4.2/theme/eclipse.css">
  <script src="codemirror-4.2/mode/clike/clike.js"></script>
  <meta content="text/html; charset=ISO-8859-1" http-equiv="content-type" >
  <style type="text/css">
	.CodeMirror {border: 1px solid black}
  </style>
  <title>DEVBOX Edit</title>


</head>

<style type="text/css"></style></head><body style="height:100%"><!-- onLoad="$main_c=editor.getValue();"-->

	

<img style="width: 209px" alt="DEVBOX" title="DEVBOX Logo"
	src="DevBoxLogoTest418x146.png">
<br>

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
<input type="hidden" name="running" value="true"></input>
</form>
<form id="kill" method="post" action="">
<button name="kill">Terminate Program</button></form>
<textarea cols="100" rows="5" name="comp_out" readonly><?php echo $output;?></textarea><br>
<textarea cols="100" rows="5" name="prog_ex" readonly><?php echo $prog_out;?></textarea><br>
<br>
<!--<?php echo getenv('PATH'); ?><br>
<?php echo $main_c." :main_c<br>"; 
      echo $_POST['code']." :Post"; ?>-->
<br>
</div>

<script type="text/javascript">
	var text_area=document.getElementById("code");
	var editor=CodeMirror.fromTextArea(text_area,{
		theme: "eclipse",
		lineNumbers: true,
		mode: "text/x-csrc",
		indentUnit: 3,
		tabSize: 3,
		indentWithTabs: true,
	});
	
</script>
</body>
</html>

