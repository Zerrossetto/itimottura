<?php

class MACPoster {

	function __construct($data = array()) 
	{
		$this->params = parse_ini_file("properties.ini.php", TRUE);
		$arr = parse_ini_file("config.ini.php", TRUE);
		
		foreach ($arr as $k=>&$v)
		{
			$this->params[$k] = $v;
		}
		if (!empty($data))
		{
			$arr = array_merge($this->params["insert"]["optional"], array("numeroOrdine","totaleOrdine"));
			foreach ($arr as &$v)
			{
				if (isset($data[$v]) && $data[$v] != "") 
				{
					$this->params["factory"][$v] = trim($data[$v]);
				}
			}
		}
		$absurl = "http://".$_SERVER["HTTP_HOST"] . dirname($_SERVER["SCRIPT_NAME"]) . "/";
		$this->params["factory"]["urlOk"] = $absurl . $this->params["factory"]["urlOk"];
		$this->params["factory"]["urlKo"] = $absurl . $this->params["factory"]["urlKo"];
	}
	
	public function debug () {
		print_r($this->params["factory"]);
	}

	public function open_connection ($ret = FALSE) {

		$toencode = "";
		foreach ($this->params["insert"]["mandatory"] as &$v)
			$toencode .= $v ."=". trim($this->params["factory"][$v]) ."&";
		$toencode .= $this->params["secret_string"][$this->params["factory"]["numeroCommerciante"].$this->params["factory"]["stabilimento"]];
		
		$mac = self::generate_mac($toencode);

		switch ($ret){
			case "get_url":
				return $this->build_connection_string($mac);
				break;
			case "get_array_assoc":
				$ar = array();
				foreach ($this->params["insert"]["mandatory"] as &$v)
					$ar["$v"] = $this->params["factory"][$v];
				$ar["password"] = "Password";
				$ar["mac"] = $mac;
				foreach ($this->params["insert"]["optional"] as &$v) {
					if (isset($this->params["factory"][$v])) $ar["$v"] = $this->params["factory"][$v];
				}
				return $ar;
				break;
			default:
				header("location: ".$this->build_connection_string($mac));
				break;
		}
	}

	public function verify_response (&$data) {

		if (!isset($data["esito"])) return FALSE;
		$status = strtolower($data["esito"]);
		$tmp = "";
		foreach ($this->params["response"][$status] as &$v)
			$tmp .= $v. "=". $data[$v] ."&";
		$tmp .= $this->params["secret_string"][$this->params["factory"]["numeroCommerciante"].$this->params["factory"]["stabilimento"]];
		return $data["mac"] == self::generate_mac($tmp);
	}

	public function verify_listener ($queryString) {

		if ($queryString == "") return FALSE;
		$queryString = urldecode($queryString);
		$tmp = substr($queryString,0,strpos($queryString,"mac=",0) - 1);
		$merchant = substr($queryString,strpos($queryString,"numeroCommerciante=",0) + 19, 7);
		$facility = substr($queryString,strpos($queryString,"stabilimento=",0) + 13, 5);
		$mac = substr($queryString,strpos($queryString,"mac=",0) + 4, 24);
		if (!isset($this->params["secret_string"][$merchant.$facility]))return FALSE;
		$tmp .= "&". $this->params["secret_string"][$merchant.$facility];
		return $mac == self::generate_mac($tmp);
	}
	
	private function build_connection_string ($mac){
		$conn = $this->params["globals"]["ins_url"] ."?";
		foreach ($this->params["insert"]["mandatory"] as &$v) {
			if ($v != "password") 
				$conn .= $v ."=". urlencode(trim($this->params["factory"][$v])) ."&";
			else
				$conn .= "password=Password&";
		}
		$conn .= "mac=". urlencode($mac);
		foreach ($this->params["insert"]["optional"] as &$v) {
			if (isset($this->params["factory"][$v]))
			{ 
				$conn .= "&". $v ."=". urlencode($this->params["factory"][$v]);
			}
		}
		return $conn; 
	}

	private static function generate_mac ($str) {
		$MAC = md5($str);
		$MACtemp = "";
		for($i=0;$i<strlen($MAC);$i=$i+2)
			$MACtemp .= chr(hexdec(substr($MAC,$i,2)));
		return base64_encode($MACtemp);	
	}

	
} 

?>
