<?php
function __autoload($class_name) { include "lib/". $class_name . ".php"; } 

$utility["title"] = "Pagamento quota di sponsorizzazione";
$utility["bodyonload"] = " onload=\"canTransmit(document.forms[0])\"";

if (@$_POST["do"] != "validate")
{
	Helper::display_template("richiesta-sponsor");
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
if ($_POST["azienda"] == "")
{
	$errors["azienda"] = "&Egrave; necessario inserire la propria ragione sociale";
}
if ($_POST["totale"] == "" || !is_numeric($_POST["totale"]) || $_POST["totale"] == 0)
{
	$errors["totale"] = "L&apos;importo deve essere un valore valido diverso da zero";
}
if ($_POST["causale"] == "")
{
	$errors["causale"] = "Causale mancante";
}
if (!Helper::check_email_address(trim($_POST["emailCompratore"])))
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
	$settings["numeroOrdine"] = "SPO_". time() . str_pad(rand(0,999), 3, "0", STR_PAD_LEFT);
	$tmp = explode(".", $_POST["totale"]);
	$settings["totaleOrdine"] = $tmp[0] . substr(str_pad($tmp[1], 2, "0", STR_PAD_RIGHT), 0, 2);
	unset($tmp);
	$settings["causalePagamento"] = "Sponsorizzazione az. ". $_POST["azienda"].
									" per ". $_POST["causale"];
	
	$c = new Company($_POST["azienda"], $_POST["emailCompratore"]);
	try {
		$db->insertNewPayment($c, $settings["numeroOrdine"], $settings["totaleOrdine"], session_id(), $_POST["causale"]);
	} catch (PDOException $e) { 
		$errors["database"] = "Errore durante la scrittura dei dati:<br />". $e->getMessage();
		Helper::display_template("richiesta-sponsor");
		$db = null;
		die();
	}
	//redirecting to UCB
	$mp = new MACPoster(array_merge($_POST, $settings));
	$mp->open_connection ();
} else { Helper::display_template("richiesta-sponsor", $errors); }

?>