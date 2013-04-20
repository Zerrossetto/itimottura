<?php
function __autoload($class_name) { include "lib/". $class_name . ".php"; } 

$utility["title"] = "Pagamento viaggi di istruzione";
$tmp = parse_ini_file("lib/itimottura.ini.php", TRUE);
$utility["viaggi"] = $tmp["lista_viaggi"];
$utility["bodyonload"] = " onload=\"updateViaggio(document.forms[0])\"";

if (@$_POST["do"] != "validate")
{
	Helper::display_template("richiesta-viaggio");
	die();
}

$errors = array();
$settings = array();

try {
	$db = new DBModel();
} catch (PDOException $e) {
	$errors["database"] = "Connessione al database fallita:<br />". $e->getMessage();
}

function sanitize_array(&$item, $key)
{
	$item = trim($item);
}
array_walk($_POST, "sanitize_array");

// validate form errors before submitting
if ($_POST["alunno"] == "")
{
	$errors["alunno"] = "Il nome dell&#39;alunno deve essere inserito";
}
if ($_POST["viaggio"] == "")
{
	$errors["viaggio"] = "Devi selezionare un viaggio di istruzione valido";
}
if (!Helper::check_email_address($_POST["emailCompratore"]))
{
	$errors["email"] = "L&#39;email deve contenere un indirizzo valido";
}

if (count($errors) == 0)
{
	if (!isset($_SESSION)) { 
		session_start(); 
		$_SESSION["timeout"] = time() + (session_cache_expire()*60);	
	} else { session_id($_COOKIE["phpsession"]); }
	
	setcookie("phpsession", session_id(), $_SESSION["timeout"]);
	
	//if there are no errors, then prepare commitment
	$settings["numeroOrdine"] = "VIA_". time() . str_pad(rand(0,999), 3, "0", STR_PAD_LEFT);
	$settings["totaleOrdine"] = str_replace(".", "", $_POST["totale"]);
	$settings["causalePagamento"] = "Al. ". $_POST["alunno"].
									" per ". $_POST["currentViaggio"].
									" viaggio di istruzione";
	
	$p = new Pupil($_POST["alunno"], substr($_POST["currentViaggio"], 0, 1), substr($_POST["currentViaggio"], 1, 1), $_POST["emailCompratore"]);
	try
	{
		$db->insertNewPayment($p, $settings["numeroOrdine"], $settings["totaleOrdine"], session_id(), "Viaggio ".$_POST["currentViaggio"]);
	} catch (PDOException $e) { 
		$errors["database"] = "Errore durante la scrittura dei dati:<br />". $e->getMessage();
		Helper::display_template("richiesta-viaggio");
		$db = null;
		die();
	}
	//redirecting to UCB
	$mp = new MACPoster(array_merge($_POST, $settings));
	$mp->open_connection ();
} else { Helper::display_template("richiesta-viaggio", $errors); }

?>