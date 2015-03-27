<!DOCTYPE html>

<?php
	ini_set('include_path','/www/pages/phplibs/'); 
	function kill_prog($lang)
	{
		shell_exec('pkill main');
	}
	putenv('PATH=/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin');
	
	$handle=fopen('langtable.csv', 'r');
	$langtable=fgetcsv($handle);
	while(!feof($handle))
	{
		
		$cm_script[$langtable[0]]=$langtable[1];
		$cm_mode[$langtable[0]]=$langtable[2];
		$main_file[$langtable[0]]=$langtable[3];
		
		$langtable=fgetcsv($handle);
	}
	
	$build_out='---Error Messages---'.PHP_EOL;
	$prog_out='--Program Output---'.PHP_EOL;
	
	
	if(isset($_POST['language']))
		$lang=$_POST['language'];
	else $lang="c";
	
	if(isset($_POST['work']))
	{
		kill_prog($lang);
		if(isset($_POST['candr']))
		{
			shell_exec('rm ./main');
			file_put_contents($main_file[$lang],$_POST['codetext']);
			
				$build_out.=shell_exec('make '.$lang.' 2>&1');                                           
				$prog_out.=shell_exec('./main 2>&1');
			
		}
		$main_code=file_get_contents($main_file[$lang], "true");
	}
	else if(isset($_POST['codetext']))
		$main_code=$_POST['codetext'];
	else
	{
	 $f=@fopen($main_file[$lang],"r+");
	 if($f!==false)
	 {
	 	ftruncate($f,0);
	 	fclose($f);
	 }
	 $main_code='//'.$main_file[$lang].PHP_EOL;
	 if($lang=="arduino")
	 	$main_code.='void setup()'.PHP_EOL.'{'.PHP_EOL.'}'.PHP_EOL.PHP_EOL.'void loop()'.PHP_EOL.'{'.PHP_EOL.'}'.PHP_EOL;
	 $build_out="";
	 $prog_out="";
	}
?>

<html><head>
	<script src="codemirror-4.2/lib/codemirror.js"></script>
	<link rel="stylesheet" href="codemirror-4.2/lib/codemirror.css">
	<link rel="stylesheet" href="codemirror-4.2/theme/eclipse.css">
	<?php echo('<script src="'.$cm_script[$lang].'"></script>'); ?>
	<meta content="text/html; charset=ISO-8859-1" http-equiv="content-type">
	
	<style type="text/css">
		.CodeMirror {border: 1px solid black}
  </style>
  <title>DEVBOX Editor</title>
  
 </head>
	
<body style="height:100%">
	<font size="22"><img style="width: 209px; vertical-align: middle;" alt="DEVBOX" title="DEVBOX Logo"
	src="DevBoxLogoTest418x146.png"> <?php echo(strtoupper($lang)); ?></font>
<br>
</body>

<div style="width:100%; float:left;">
	<?php echo('Enter your '.$lang.' code:'); ?>
	<br>
	<form id="code_entry" method="post" action="" name="code_entry">
		<textarea rows="15" id="code" name="codetext"><?php echo($main_code); ?></textarea>
		<button name="candr">Compile &amp; Run</button>
		<button name="kill">Stop Program</button>
		<input type="hidden" id="work" name="work" value="work"></input>
		<?php
			echo('<input type="hidden" id="language" name="language" value="'.$lang.'"></input>'.PHP_EOL);
			echo('<input type="hidden" id="mode" name="mode" value="'.$cm_mode[$lang].'"></input>'.PHP_EOL);
		?>
	</form>
	<textarea style="width: 60%;" rows="5" name="compile_out" readonly><?php echo($build_out); ?></textarea><br>
	<textarea style="width: 60%;" rows="5" name="program_out" readonly><?php echo($prog_out); ?></textarea><br>
	
</div>

<script type="text/javascript">
	var cm_mode=document.getElementById("mode");
	var text=document.getElementById("code");
	var editor=CodeMirror.fromTextArea(text,{
		theme:	"eclipse",
		lineNumbers: "true",
		mode:	cm_mode.value,
		indentUnit: 3,
		tabSize: 3,
		indentWithTabs: true,
		});
</script>
</body>
</html>
