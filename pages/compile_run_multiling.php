<!DOCTYPE html>

<?php
	ini_set('include_path','phplibs/'); 
	require "create_v_harness.php";
	
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
	
	
	
	
	if(isset($_POST['language']))
		$lang=$_POST['language'];
	else $lang="c";
	
	if(isset($_POST['port_list']))
	{
		$port_list=$_POST['port_list'];
		$port_array=explode(',',$port_list);
		foreach($port_array as $x)
			trim($x);
	}
	else $port_list='';
	
	if(isset($_POST['codetext']))
	{
		$main_code=$_POST['codetext'];
		if($lang=='verilog')
		{
			$save_code='module main('.$port_list.');'.PHP_EOL.$main_code;
			create_v_harness(explode(PHP_EOL,$_POST['rules']));
		}
		else $save_code=$main_code;
		file_put_contents($main_file[$lang],$save_code);
		
	}
		
	/*
	if(isset($_POST['work']))
	{
		kill_prog($lang);
		if(isset($_POST['candr']))
		{
			shell_exec('rm ./main');

			
			$build_out.=shell_exec('make '.$lang.' 2>&1');                                           
			$prog_out.=shell_exec('./main 2>&1');
			
		}
		else if(isset($_POST['veri_trace']))
		{
			shell_exec('rm ./main');
			
			$build_out.=shell_exec('make '.$lang.'_trace 2>&1');
		}
		else if(isset($_POST['veri_run']))
		{
			shell_exec('rm -r obj_dir/');
			
			create_v_harness(explode(PHP_EOL,$_POST['rules']));
			$build_out.=shell_exec('make '.$lang.'_run 2>&1');
			$prog_out.=shell_exec('./obj_dir/Vharness');
		}
	}
	*/
	if(isset($_POST['go']) || !isset($_POST))
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
	if(isset($_POST['action']))
		$action=$_POST['action'];
		
	if(isset($_POST['rules']))
		$rules=$_POST['rules'];
	else
		$rules="";
	$rule_array=explode(PHP_EOL, $rules);
	if(isset($_POST['ok']) && $action != "null")
	{
		$new_rule=$action;
		switch($action)
		{
			case "attach":
				$new_rule.=' '.$_POST['io'].' '.$_POST['signal'];
				break;
			case "clock":
				$new_rule.=' set to '.$_POST['clk'].' cycles';
				if($_POST['clk']=='count')
					$new_rule.=': '.$_POST['clk_num'];
				break;
			default:
				break;
		}
		$rules.=$new_rule.PHP_EOL;
		$rule_array[]=$new_rule;
		$action="null";
	}
?>

<html><head>
	<script src="socket.io.js"></script>
	<script src="codemirror-4.2/lib/codemirror.js"></script>
	<link rel="stylesheet" href="codemirror-4.2/lib/codemirror.css">
	<link rel="stylesheet" href="codemirror-4.2/theme/eclipse.css">
	<?php echo('<script src="'.$cm_script[$lang].'"></script>'); ?>
	<meta content="text/html; charset=ISO-8859-1" http-equiv="content-type">
	
	<style type="text/css">
		.CodeMirror {border: 1px solid black}
  </style>
  <title>DEVBOX Editor</title>
  <script type="text/javascript">
  	var socket=io(document.domain+':81');
	socket.connect();
	
	
	socket.on('compile', function(data){
		document.getElementById('compile_out').innerHTML+=data;
	});
	socket.on('run', function(data){
		document.getElementById('program_out').innerHTML+=data;
	});
  </script>
 </head>
	
<body style="height:100%; width:98%">
	<font size="22"><a href="index.html"><img style="width: 209px; vertical-align: middle;" alt="DEVBOX" title="DEVBOX Logo"
	src="DevBoxLogoTest418x146.png"></a> <?php echo(strtoupper($lang)); ?> Editor</font>
<br>
<form id="code_entry" method="post" action="" name="code_entry">
<?php if($lang=="verilog")
		{
			echo('<div style="width:74.5%; float:left;">');
			echo('List your ports below:');

		
		echo('<br><label>module main(</label><input type="text" id="port_list" name="port_list" value="'.$port_list.'"></input>);');

		}

		else echo('<div style="width:99%; float:left;">');
	 	echo('<H3>Enter your '.$lang.' code:</H3>'); 
?>
	
		<?php echo('<textarea rows="15" id="code" name="codetext">'.$main_code.'</textarea>');?>
		<?php if($lang!="verilog")
		{
		?>
			<button name="candr" type="button" onclick="cr(document.getElementById('language').value)">Compile &amp; Run</button>
			<button name="kill"type="button" onclick="kp()">Stop Program</button>
		<?php
		}
		else
		{
		?>
			<button name="veri_trace" enabled="false">Run Trace</button>
			<button name="veri_run" type="button" onclick="cr_verilog()">Run Simulation</button>
			<button name="kill" type="button" onclick="kp()">Stop Program</button>
		<?php
		}
		echo('<input type="hidden" id="work" name="work" value="work"></input>'.PHP_EOL);
		echo('<input type="hidden" id="language" name="language" value="'.$lang.'"></input>'.PHP_EOL);
		echo('<input type="hidden" id="mode" name="mode" value="'.$cm_mode[$lang].'"></input>'.PHP_EOL);
		echo('<input type="hidden" id="rules" name="rules" value="'.$rules.'"></input>'.PHP_EOL);
			
		?>
		
			
	</form>
	<br>
	<label>Compiler Output</label><br><textarea style="width: 60%;" rows="5" id="compile_out" name="compile_out" readonly><?php echo($build_out); ?></textarea><br>
	<label>Program Output</label><br><textarea style="width: 60%;" rows="5" id="program_out" name="program_out" readonly><?php echo($prog_out); ?></textarea><br>
	<label>Enter your program input here</label><br><input id="input" type="text" cols="40"></input>
			<button type="button" onclick="scanf(escape(document.getElementById('input').value));">Submit</button>
	
</div>
<?php	if($lang=="verilog")
	{
//Verilog harness generation
?>

	<div style="width:25%; float:right;">
	<H3>Simulation</H3>
	
	<form id="harness" method="post" action="" name="harness">
		<select name="action" id="action" onchange="submitForms()">
			<option id="null" value="null"></option>
			<option id="attach" value="attach">Attach</option>
			<option id="clock"value="clock">Clock</option>
	
		</select>
	<?php if(isset($_POST['action']))
	{
		echo ' ';
		if($action=="attach")
		{ 
	?>
			<select name="io" id="io">
				<option value="SW">Switches</option>
				<option value="KEY">Keys</option>
				<option value="LEDR">Red LEDs</option>
				<option value="CLK">Clock</option>
			</select> 
			to 
		<?php
			echo('<select name="signal" id="signal" size=1>');
				foreach($port_array as $x)
				
					echo('<option value="'.$x.'">'.$x.'</option>');
			echo('</select>');
		}
		else if($action=="clock")
		{
		?>
				Set Clock cycle count to<br>
				<input type="radio" name="clk" value="count" checked>This many</input>
				<input type="text" name="clk_num" style="width:40px;"></input><br>
				<input type="radio" name="clk" value="infinite">Infinite</input>
				
				
		<?php
		}
		?>
		<br>
		<button name="ok" id="ok">OK</button>

	<?php
	}
	
	echo('<br><select size="'.count($rule_array).'" name="rule_list">');
	foreach($rule_array as $elem)
		if($elem!="" && $elem!=NULL)
			echo('<option>'.$elem.'</option>');
	echo('</select>');
	
	
	echo('<input type="hidden" id="language" name="language" value="'.$lang.'"></input>'.PHP_EOL);
	echo('<input type="hidden" id="mode" name="mode" value="'.$cm_mode[$lang].'"></input>'.PHP_EOL);
	echo('<input type="hidden" id="act" name="act" value="'.$action.'"></input>'.PHP_EOL);
	echo('<input type="hidden" id="rules" name="rules" value="'.$rules.'"></input>'.PHP_EOL);
	echo('<input type="hidden" id="_code" name="codetext" value="'.$main_code.'"></input>'.PHP_EOL);
	echo('<input type="hidden" id="_port_list" name="port_list" value="'.$port_list.'"></input>'.PHP_EOL);
	
	?>
	
	</form>
	</div>
<script type="text/javascript">
	var action=document.getElementById("act");
	if(action.value!=null)
	{
		var sel=document.getElementById(action.value);
		sel.setAttribute("selected","selected");
	}
</script>	
<?php
	}
?>

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
			pollInterval: 0,
		});
	function submitForms()
	{
		document.getElementById("code_entry").submit();
		document.getElementById("harness").submit();
	}
	function cr(lang)
	{
	//	if(lang=='verilog_run')	
	//		{socket.emit('candr',{lang: lang, code: {'module main('+document.getElementById('port_list').value+');'+editor.doc.getValue()}, page: 99});}
	//	else
			{socket.emit('candr',{lang: lang, code: editor.doc.getValue(), page: 99});}
		document.getElementById('compile_out').innerHTML="";
		document.getElementById('program_out').innerHTML="";
	}
	function cr_verilog()
	{
		var code = 'module main('+document.getElementById('port_list').value+');'+editor.doc.getValue();
		socket.emit('candr',{lang: 'verilog', code: code, page: 99});
		document.getElementById('compile_out').innerHTML="";
		document.getElementById('program_out').innerHTML="";
	}
	function kp()
	{
		socket.emit('kill');
	}
	
	function scanf(data)
	{
		socket.emit('input', data);
		document.getElementById('input').value='';
	}
	
</script>

</body>
</html>
