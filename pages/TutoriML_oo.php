<?php
	//TutoriML.php
	//Keith Lee 2015
	//Functions for including .tml data in a webpage	
	use \Michelf\Markdown;
	
	
	function unfile($tml)
	{
		$ret=str_replace('_', ' ', $tml);
		$ret=str_replace('.tml', '', $ret);
		return $ret;
	}
	
	
	function getTagValue($handle, $tag)
	{
		$tag='`'.$tag;
		$ret="";
		while(!feof($handle))
		{
			$line=fgets($handle);
			$test=explode(' ',$line);
			if($test[0]==$tag)
			{
				$test=array_slice($test,1);
				$ret=implode(' ', $test);
				return $ret;
			}
		}
	}
	
	function getSlideDesc($handle, $page)
	{
		
	}
	function getTitle($dir, $tml)
	{
		$file=$dir.'/'.$tml;
		$handle=fopen($file, "r");
		title=getTagValue($handle,'title');
		fclose($handle);
		return $title;
	}
	
	function getPages($dir, $tml)
	{
		$file=$dir.'/'.$tml;
		$n_pages=0;
		$handle=fopen($file, "r");
		$n_pages=getTagValue($handle, 'n_pages');
		fclose($handle);
		return $n_pages;
	}
	
	function getDesc($dir, $tml, $page)
	{
		$file=$dir.'/'.$tml;
		$desc="";
		$handle=fopen($file, "r");
		$desc=getSlideDesc($handle, $page);
		fclose($handle);
		return $desc;
	}
	
	function getCode($dir, $tml, $page)
	{
		$code=array();
		$code[0]="set";
		$code[1]="int main(){\n\treturn 0;\n}";
		return $code;
	}
	
	
?>
