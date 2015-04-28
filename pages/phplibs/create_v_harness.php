<?php
	function create_v_harness($sig_array)
	{
			$assigned['SW']=0;
			$assigned['KEY']=0;
			$assigned['LEDR']=0;
			$clock['set']=0;
			foreach ($sig_array as $elem)
			{
				$elem=preg_split('/\s+/', $elem);
				if($elem[0]=='attach')
				{
					//echo ($elem[0].' '.$elem[2].' '.$elem[1].' '.$elem[3].'<BR>'); 
					$attach[]=array($elem[1], $elem[2]);
					$assigned[$elem[1]]=1;
				}
				elseif($elem[0]=='clock')
				{	
					$clock['set']=1;
					if($elem[3]=='count')
						$clock['count']=$elem[5];
					else
						$clock['count']=0;
				}
			}
		$harness='//verilator lint_off UNUSED'.PHP_EOL;
		$harness.='module harness(CLK, CLK_EN, SW, KEY, LEDR, CLK_COUNT);'.PHP_EOL;
		$harness.='	input wire CLK;'.PHP_EOL.'	input wire[9:0] SW;'.PHP_EOL.'	input wire[3:0] KEY;'.PHP_EOL;
		$harness.='	output wire CLK_EN;'.PHP_EOL.'	output wire[9:0] LEDR;'.PHP_EOL;
		$harness.='	assign CLK_EN='.$clock['set'].';'.PHP_EOL;
		if($clock['set']==0)
    {
			$harness.='	wire CLK_SINK;'.PHP_EOL.'	assign CLK_SINK=CLK;'.PHP_EOL;
      $harness.="  output wire[31:0] CLK_COUNT=32'd0;".PHP_EOL;
		}
    else
			$harness.="	output wire[31:0] CLK_COUNT=32'd".$clock['count'].';'.PHP_EOL;
		if($assigned['SW']==0)
			$harness.='	wire[9:0]SW_SINK;'.PHP_EOL.'	assign SW_SINK=SW;'.PHP_EOL;
		if($assigned['KEY']==0)
			$harness.='	wire[3:0]KEY_SINK;'.PHP_EOL.'	assign KEY_SINK=KEY;'.PHP_EOL;
		if($assigned['LEDR']==0)
			$harness.="	assign LEDR=10'b0;".PHP_EOL;
		$harness.='	main u0('.PHP_EOL;
		$i=0;
		$len=count($attach);
		foreach ($attach as $sig)
		{
			$harness.='		.'.$sig[1].'('.$sig[0].')';
			if($i!=$len-1)
				$harness.=','.PHP_EOL;
			else $harness.=');'.PHP_EOL;
			$i++;
			
		}
		$harness.='endmodule'.PHP_EOL;
		$harness.='// verilator lint_on UNUSED'.PHP_EOL;

		$vf=fopen('harness.v','w');
		fwrite($vf,$harness);
		fclose($vf);
		
	}
	
?>
