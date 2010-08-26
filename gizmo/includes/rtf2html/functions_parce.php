<?

// ------------------------------------------------------------------------------------------------
class RtfHtml {
	
	var $final_html;
	
// ------------------------------------------------------------------------------------------------
	function RtfHtml() {
		
	} // end of function
// ------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
	function rtf_to_html($text) {
		$text = $this->change_sym($text);
		if (
			preg_match("/^\\\*\\\/msi",$text)
		
		) {
			return "";
		}
		if (
				$text == "." ||
				$text == "(" ||
				$text == ")"
			) {
			return "";
		}
		return $text;
	} // end of function
// ------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
	function parce_levels($arr) {
		$ar_size = sizeof($arr);
		for ($i=0;$i<$ar_size;$i++) {
			if (is_array($arr[$i])) {
				if (!$flg) {
					if ( // 1
						!preg_match("/^\\\*\\\shpinst/msi",$arr[$i][0])
					) {
						$this->parce_levels($arr[$i]);
					}
				}
			}
			else {
				$this->final_html .= $this->rtf_to_html($arr[$i]);
				if (preg_match("/fonttbl/msi",$arr[$i])) {$flg = true;}
				else {$flg = false;}
				
			}
		}
	} // end of function
// ------------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------------
	function change_sym($text) {
		$params = preg_split("/ /msi",$text);
		$parms = $params[0]."\\";$params[0] = "";
		$alt_text = join(" ",$params);

		$text = $alt_text;

		$text = preg_replace("/^ /msi","",$text);
		$text = preg_replace("/$ /msi","",$text);
		unset($alt_text,$params);
		//echo "parms = ".$parms."<br>";
		if (preg_match("/\\\footer/msi",$parms)) {return "";}

		//if (preg_match("/\\\trowd/msi",$parms) && !preg_match("/\\\lastrow/msi",$parms)) {return "<table>";}

		if (preg_match("/\\\fs([0-9]+)\\\/msi",$parms,$fsize)) {$text = "<font style=\"font-size: ".round($fsize[1]/2)."pt;\">".$text."</font>";}
		if (preg_match("/\\\b\\\/msi",$parms)) {$text = "<b>".$text."</b>";}
		if (preg_match("/\\\i\\\/msi",$parms)) {$text = "<i>".$text."</i>";}
		if (preg_match("/\\\ul\\\/msi",$parms)) {$text = "<u>".$text."</u>";}

		$text = preg_replace("/".preg_quote("\\"."b ")."/msi","<b> ",$text);
		$text = preg_replace("/".preg_quote("\\"."ul ")."/msi","<u> ",$text);
		$text = preg_replace("/".preg_quote("\\"."i ")."/msi","<i> ",$text);
		$text = preg_replace("/".preg_quote("\\"."b0")."/msi","</b>",$text);
		$text = preg_replace("/".preg_quote("\\"."ulnone")."/msi","</u>",$text);
		$text = preg_replace("/".preg_quote("\\"."i0")."/msi","</i>",$text);

		
		$text = preg_replace("/".preg_quote("\\"."'93")."/msi","\"",$text);
		$text = preg_replace("/".preg_quote("\\"."'94")."/msi","\"",$text);
		$text = preg_replace("/".preg_quote("\\"."rquote ")."/msi","'",$text);
		$text = preg_replace("/".preg_quote("\\"."lquote ")."/msi","'",$text);
		$text = preg_replace("/".preg_quote("\\"."endash ")."/msi"," -",$text);
		$text = preg_replace("/".preg_quote("\\"."par")."/msi","<br>",$text);
/*
		
		if (preg_match("/\\\cell/msi",$text)) {
			$trow = preg_split("/\\\cell/msi",$text);
			if (preg_match("/^x[0-9]+/msi",$trow[0])) return "";
			for ($i=0;$i<sizeof($trow);$i++) {
				$tr_text .= $trow[$i]."</td>";
				$tr_text .= ($i<sizeof($trow)-1) ? "<td>" : "";
			}
			$text = "<td>".$tr_text; unset($tr_text);
		}
*/
		
		$text = preg_replace("/\\\[^ ]*/msi","",$text);

		return $text;
	} // end of function
// ------------------------------------------------------------------------------------------------

} // END OF CLASS
// ------------------------------------------------------------------------------------------------






// ------------------------------------------------------------------------------------------------
	function mkl($sample) {
		//--------------
		$findl = preg_match_all("/\{/msi",$sample,$left);
		$findr = preg_match_all("/\}/msi",$sample,$right);
		$num_left = sizeof($left[0]);
		$num_right = sizeof($right[0]);
		if (!$findl && !$findr) {
			$c_fin[] = $sample;
			return $c_fin;
		}
		$smpl = $sample;
		$num_l = $nold = $lcount = 0;
		for ($i=0;$i<$num_left;$i++) {
			$nold += $num_l;
			$num_l = strpos($smpl,"{");
			//$first_l = substr($smpl,0,$num_l);
			$smpl = substr(strstr($smpl,"{"),1);
			$ar_left[] = $nold + $num_l+$lcount;
			$lcount++;
		}

		$smpl = $sample;
		$nold = 0;
		$num_r = $rcount = 0;
		for ($i=0;$i<$num_right;$i++) {
			$nold += $num_r;
			$num_r = strpos($smpl,"}");
			//$first_l = substr($smpl,0,$num_l);
			$smpl = substr(strstr($smpl,"}"),1);
			$ar_right[] = $nold + $num_r+$rcount;
			$rcount++;
		}

		for ($i=0;$i<sizeof($ar_left);$i++) {
				$final[$ar_right[$i]] = "}";
				$final[$ar_left[$i]] = "{";
		}
		ksort($final);

		//--------------
		
		reset ($final);
		$lflg = 0;
		$rflg = 0;
		$tmpbl = '';
		while (list ($key, $val) = each ($final)) {
			if ($val == "{") {
				if ($lflg == 0 && !$rflg) { $tmpbl .= $key; $rflg=1;}
				else {$lflg++;}
			}
			else {
				if ($lflg == 0) { $tmpbl .= ",".$key; $f_final[] = $tmpbl; unset($tmpbl); $rflg=0; }
				$lflg = ($lflg == 0) ? 0 : $lflg-1;
			}
		}
		$fcn = 0;
		$lcn = 0;
		$jki = false;
		for ($i=0;$i<sizeof($f_final);$i++) {
			$arr = preg_split("/,/msi",$f_final[$i]);

			$first = preg_replace("/[\{\}]/msi","",substr($sample,$fcn,$arr[0]+1));
			$ffirst = substr($sample,$fcn,$arr[0]+1);
			$fcn += $arr[0]+1;
			if ($first != "" && $i==0) {$c_fin[] = $first;$jki=1;}

			
			$middle = substr($sample,$arr[0]+1,$arr[1]-$arr[0]-1);
			$c_fin[] = mkl($middle);

			$flast = substr($sample,$arr[1]);
			$ht = strpos($flast,"{");
			if ($ht) {$flast1 = substr($flast,0,$ht);}
			else {$flast1 = $flast;}

			$last = preg_replace("/[\{\}]/msi","",$flast1);
			if ($last != "") $c_fin[] = $last;
		}
		return $c_fin;
	} // end of function
// ------------------------------------------------------------------------------------------------



?>