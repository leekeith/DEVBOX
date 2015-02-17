	<?php
		if(!isset($_POST['posted']))
		{
			$wiz_stage=0;
			$codetext="";
		}else if(isset($_POST['submit'])){
			$wiz_stage=$_POST['wiz_stage']+1;
			$codetext=$_POST['codetext'];	
			if(isset($_POST['template']))
			{
				if($_POST['template']!="blank")
				{
					switch($_POST['template'])
					{
					case "helloworld":
						$comments=file_get_contents('./db_HelloWorld.comments',true);
						break;
					default:
						break;
					}
				}
			}
			if($wiz_stage==2)
				$codetext=$codetext."\n".$_POST['outlinetext'];
			#if($wiz_stage>2)
			#	header("Location: ./compile_run.php");
		}else if(isset($_POST['back']) && $_POST['wiz_stage']>0){
			$wiz_stage=0;
			$codetext="";
		}
		if($wiz_stage==1)
			$filehead=$codetext;
		else if($wiz_stage>1)
			$filehead=$_POST['filehead'];	
?>
<html>
	<head>
	<script src="codemirror-4.2/lib/codemirror.js"></script>
	<link rel="stylesheet" href="codemirror-4.2/lib/codemirror.css">
	<link rel="stylesheet" href="codemirror-4.2/theme/eclipse.css">
	<script src="codemirror-4.2/mode/clike/clike.js"></script>
	
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
	<meta content="text/html; charset=ISO-8859-1" http-equiv="content-type" >
	<style type="text/css">
  		
  		input, select {
  			float:right;
  		}
  		label {
  			display: inline-block; 
  			width: 175px; 
  			text-align:right;
  		}
  			
  </style>
	<title>DEV BOX Setup Wizard</title>
	</head>
	<body>
		
		<big style="color: rgb(246, 0, 0);"><big><big>DEV<br>
		BOX</big></big></big><br><br>
		<div id="form" class="left">
			<?php if($wiz_stage==0){ ?>
				<form method="post" name="setup1">
					<div id="fields" >
					<br>
						<label for="username">Your name:</label> <input type="text" id="username" name="username" onchange="updateCode()"><br><br>
						<label for="language">Program Language:</label>
						<select style="width:50px" id="language" name="language" onchange="updateCode()">
							<option selected value="C">C</option>
						</select><br><br>
						<label for="template">Template:</label>
						<select id="template" name="template" size="5" style="vertical-align:top" onclick="setProgStuff()">
							<option selected value="blank">Blank Project</option>
							<option value="helloworld">Hello World</option>
							<option value="blink">Blink LEDs</option>
							<option value="vgatest">VGA color Test</option>
							<option value="7seg">7-Segment Test</option>	
						</select><br><br><br><br><br><br>
						<label for="progname">Program name:</label> <input type="text" id="progname" name="progname" onchange="updateCode()"><br><br>
						<label for="progdesc">Program Description:</label><textarea style="resize:none;float:right;width:228px" rows="4" id="progdesc" name="progdesc" onchange="updateCode(window.event)"></textarea><br><br><br><br><br><br>
					</div>
			<div id="buttons" style="float:right">
				<button name="submit">NEXT&gt&gt</button>
			<?php }else if($wiz_stage==1){ ?>
				<form method="post" name="setup2">
					<div id="fields">
					<br><label for="outline">Outline your program (one step per line):</label><textarea style="resize:none;float:right;width:400px;" rows="19" id="outline" name="outline" onkeypress="updateOutline(event)"><?php echo $comments; ?></textarea><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
				</div>
				<div id="buttons" style="float:right">
					<button name="submit" onclick="updateOutline(event)">NEXT&gt&gt</button>
			<?php } else { ?>
				<br>Your program outline has been prepared.  To begin modifying your program, click 'NEXT'.  To start again, click 'RESET'.<br><br>
				<form method="post" name="setup3" action="compile_run.php" style="float:right">
				<div id="buttons">
					<button name="submit">NEXT&gt&gt</button>
			<?php } ?>
			 	<input type="hidden" name="posted" value="true"></input>
				<input type="hidden" name="codetext" id="codetext" value="<?php echo $codetext; ?>"></input>
				<input type="hidden" name="wiz_stage" id="wiz_stage" value="<?php echo $wiz_stage; ?>"></input>
				<input type="hidden" name="outlinetext" id="outlinetext"></input>
				<input type="hidden" name="filehead" id="filehead" value="<?php if(isset($filehead))echo $filehead; ?></input>
	</form>
		<form method="post" name="resetform" style="float:right">
		<button name="back">RESET</button>
		</div>
	</form>
		</div>
		<div id="codebox" class="right">
			Your code will look like:<br>
			<textarea readonly style="resize:none; height:400px; float:center" id="code" name="code"><?php echo $codetext; ?></textarea>
		</div>
	
	</body>
	<script type="text/javascript">
	
		var today=new Date();
		var temptext;
		var text_area=document.getElementById("code");
		var editor=CodeMirror.fromTextArea(text_area,{
			theme: "eclipse",
			lineNumbers: true,
			mode: "text/x-csrc",
			indentUnit: 3,
			tabSize: 3,
			indentWithTabs: true,
			lineWrapping: true,
			readOnly: true,
			cursorBlinkRate: -1,
		});
			var codetext;
			function setProgStuff()
			{
				var template=document.getElementById("template").value;
				var lang=document.getElementById("language").value;
				var pname=document.getElementById("progname");
				var pdesc=document.getElementById("progdesc");
				if(lang="C")
				{
					switch(template)
					{
					case "helloworld":
						pname.value="Hello World";
						pdesc.value="Prints 'Hello World!' to stdout.";
						break;
					case "blink":
						pname.value="Blink LEDs";
						pdesc.value="Causes the 10 red LEDs on the device to blink.";
						break;
					case "vgatest":
						pname.value="VGA Color Test";
						pdesc.value="Displays a color test pattern on the VGA device."
						break;
					case "7seg":
						pname.value="7-Segment Test"
						pdesc.value="Displays some hexadecimal numbers on the 7-segment display."
						break;
					default:
						pname.value="";
						pdesc.value="";
						break;
					}
				}
				updateCode();	
			}
		  	function updateCode()
		  	{
		  	 	var uname=document.getElementById("username").value;
		  	 	var pname=document.getElementById("progname").value;
		  	 	var pdesc=document.getElementById("progdesc").value;
		  	 	var lang=document.getElementById("language").value;
		  	 	var template=document.getElementById("template").value;
		  	 		  	 	switch(lang)
		  	 	{
			  	 	default:
			  	 		codetext="/*\n================================================================\nmain.c\n   Written by: "+uname+"\n   date: "+today.toDateString()+"\n   Program: "+pname+"\n   ---------------------------\n   "+pdesc+"\n================================================================\n*/";
			  	 		break;
		  	 	}
				editor.setValue(codetext);
				document.getElementById("codetext").value=codetext;
		  	}
		  	function updateOutline(event)
		  	{
		  		event = event || window.event;
		  		if(event.keyCode==13 || (event.target.name=="submit" && event.type=="click"))
		  		{
			  		codetext=document.getElementById("codetext").value;
		  			temptext=document.getElementById("outline").value;
		  			lines=temptext.split("\n");
		  			temptext="";
		  			for(var i=0;i<lines.length;i++)
		  			{
		  				temptext+="//  "+lines[i]+"\n\n\n";
		  			}
		  			editor.setValue(codetext+"\n"+temptext);
		  			document.getElementById("outlinetext").value=temptext;
		  		}
		  	}
		  	function reset()
		  	{
		  		document.getElementById("username").value="";
		  		document.getElementById("progname").value="";
		  		document.getElementById("progdesc").value="";
		  		document.getElementById("language").selectedIndex="0";
		  		document.getElementById("template").selectedIndex="0";
		  		document.getElementById("codetext").value="";
		  	}
		  	
		  	function finalize()
		  	{
		  		document.getElementById("codetext").value=editor.getValue();
		  	}
	</script>
</html>
