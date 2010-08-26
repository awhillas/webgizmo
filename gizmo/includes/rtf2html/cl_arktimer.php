<?
class ArkTimer {
	var $start;

	function ArkTimer() {
		$this->start = $this->GetMicrotime();
	}

	function GetMicrotime() {
		list($usec, $sec) = explode(" ",microtime()); 
		return ((float)$usec + (float)$sec); 
	}

	function GetTime() {
		return $this->GetMicrotime() - $this->start;
	}

	function EchoTime() {
		echo "<hr size=1>Did in ";
		echo round($this->GetMicrotime() - $this->start,6);
		echo " seconds";
	}
}

?>