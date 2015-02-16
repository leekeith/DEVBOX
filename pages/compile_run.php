<!DOCTYPE html>
<?php 	
	putenv('PATH=/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin');
	if(isset($_POST['kill']))
 	{
 		kill_main();
 		$output=file_get_contents('./output.log',true);
 		if(empty($output))
 			$output="main.c: No Errors";
 		$main_c=file_get_contents('./main.c',true);
 		$prog_out=file_get_contents('./main.out',true);
 	}else if(isset($_POST['running']))
	{
		shell_exec('rm ./main');
		file_put_contents('./main.c',$_POST['code']);
		$output=shell_exec('arm-cortexa9_neon_native-linux-gnueabihf-gcc main.c -Wall -std=c99 -o main -I/usr/include -L/lib -lfbDraw 2>&1');
		file_put_contents('./output.log',$output);
		shell_exec('./main > main.out');
		$prog_out=file_get_contents('./main.out');	
		$main_c=file_get_contents('./main.c',true);
	}else if(isset($_POST['codetext']))
	{
		$main_c=$_POST['codetext'];
	}else
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
<html><head>
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
<style type="text/css"></style></head><body style="width:99%;height:99%">

	

<big style="color: rgb(246, 0, 0);"><big><big>DEV<br>
BOX</big></big></big><br>

<br>

Enter your code:<br>


<form method="post" action="" name="code_entry" id="code_entry" accept-charset="utf-8"><textarea rows="15" name="code" id="code"><?php echo $main_c;?></textarea><br>
	<button name="submit">Compile &amp; Run</button>
	<input type="hidden" name="running" value="true"></input>
</form>
<form id="kill" method="post" action="">
	<button name="kill">Terminate Program</button>
</form>
<textarea cols="50" rows="5" name="comp_out" readonly><?php echo $output;?></textarea><br>
<textarea cols="50" rows="5" name="prog_ex" readonly><?php echo $prog_out;?></textarea><br>
<!--<br>
<?php echo getenv('PATH');?>
<br>-->
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
</body></html>
