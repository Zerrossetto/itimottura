<?php


class Helper {

	static function display_template($file, $errors = array())
	{
		global $title, $res;
		$arr = parse_ini_file("lib/itimottura.ini.php");
		include "lib/header.tpl.html";
		include "lib/".$file.".tpl.html";
		include "lib/footer.tpl.html";
	}
	
	static function pageFromPrefix($prefix)
	{
		$dict = array(	"COM_"=> "comodato.php",
						"VIA_"=> "viaggi.php",
						"SPO_"=> "sponsor.php",
						"LOC_"=> "uso-locali.php");
		return $dict[$prefix];
	}

	static function implodeDictionary ($glue, $pieces, $add_quotes = false)
	{
		$tmp = array();
	
		foreach ($pieces as $k => $v){
			$kquote = "";
			$vquote = "";
			if ($add_quotes)
			{
				if (gettype($k) == "string") $kquote = "\"";
				if (gettype($v) == "string") $vquote = "\"";
			}
			array_push($tmp, $kquote.$k.$kquote.$glue.$vquote.$v.$vquote);
		}
		return $tmp;
	}

	static function check_email_address($email) {
		// First, we check that there's one @ symbol, and that the lengths are right
		if (!preg_match("/^[^@]{1,64}@[^@]{1,255}$/", $email)) {
			// Email invalid because wrong number of characters in one section, or wrong number of @ symbols.
			return false;
		}
		// Split it into sections to make life easier
		$email_array = explode("@", $email);
		$local_array = explode(".", $email_array[0]);
		for ($i = 0; $i < sizeof($local_array); $i++) {
			if (!preg_match("/^(([A-Za-z0-9!#$%&'*+\/=?^_`{|}~-][A-Za-z0-9!#$%&'*+\/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$/", $local_array[$i])) {
				return false;
			}
		}
		if (!preg_match("/^\[?[0-9\.]+\]?$/", $email_array[1])) { // Check if domain is IP. If not, it should be valid domain name
			$domain_array = explode(".", $email_array[1]);
			if (sizeof($domain_array) < 2) {
				return false; // Not enough parts to domain
			}
			for ($i = 0; $i < sizeof($domain_array); $i++) {
				if (!preg_match("/^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|([A-Za-z0-9]+))$/", $domain_array[$i])) {
					return false;
				}
			}
		}

		return true;
	}
	
} 

?>
