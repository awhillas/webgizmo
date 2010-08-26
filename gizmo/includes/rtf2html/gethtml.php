<?

?>
<HTML><HEAD><TITLE>RTF to HTML Test Project</TITLE>
</HEAD>
<BODY>
<?
include "functions_parce.php";

// --- ADD f ----------------------------------------------------------------------------------
	function read_file() {
		GLOBAL $HTTP_POST_FILES;
		for ($i=0;$i<sizeof($HTTP_POST_FILES["rtffile"]["name"]);$i++) {
			$f_name = $HTTP_POST_FILES["rtffile"]["name"][$i];
			if (strlen($f_name) > 2) {
				$GLOBALS["rtf_name"] = $f_name;
				$cont = join("",file($HTTP_POST_FILES["rtffile"]["tmp_name"][$i]));
				@unlink($HTTP_POST_FILES["rtffile"]["tmp_name"][$i]);
				return $cont;
			}
		}
		return false;
	} // end of function
// ------------------------------------------------------------------------------------------------



$cont = read_file();

if ($rtf_name == "Gen-result.rtf") {
	echo "<br><br><center><b>My RTF Generator is too smart for this small script<br><br>the file won't be converted correctly due to headers, footers and images.</b></center><br>";
	exit();
}

if ($cont) {
	if (preg_match("/^".preg_quote("{\\rtf")."/msi",$cont)) {

		$sample = preg_replace("/ \\\/msi","\\",$cont);
		$sample = preg_replace("/[\n\r]/msi","",$cont);
		$sample = "{".substr($sample,strpos($sample,"\\pard"));

		$timer = new ArkTimer;

		$c_fin = mkl($sample);
		$c_fin = $c_fin[0];

		$htm = new RtfHtml;
		$htm->parce_levels($c_fin);

		echo "<h3>File:&nbsp;&nbsp;".$rtf_name."</h3><hr size=1>";
		echo $htm->final_html;
		$timer->EchoTime(); 
		echo "<hr size=1>";
//-------------------------------------------------------------------------------------------------
		$gen_stat = "../../write_files/rtf2html.stat";
		$check = date("d.m.Y");

		if (file_exists($gen_stat)) {
			$cont_st = file($gen_stat);
			for ($i=0;$i<sizeof($cont_st);$i++) {
				$cont_st[$i] = preg_replace("/\n/","",$cont_st[$i]);
				if (preg_match("/".$check."/",$cont_st[$i])) {
					$num = round(preg_replace("/".$check." = /","",$cont_st[$i]));
					$num++;
					$cont_st[$i] = $check." = ".$num;
					$flg = 1;
				}
			}
			if (!$flg) {
				$cont_st[] = $check." = 1";
			}
		}
		else {
			$cont_st[] = $check." = 1";
		}
			$fp1 = fopen($gen_stat, "w");
			fwrite($fp1, join("\n",$cont_st));
			@fclose($fp1);

//-------------------------------------------------------------------------------------------------
	
	}
	else {
		echo "<br><br><center><b>This file is not an RTF</b></center><br>";
	}


}
else {
	echo "<br><br><center><b>You should specify a file to convert</b></center><br>";
}



?>
</BODY></HTML>