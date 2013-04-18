<?php
function __autoload($class_name) { include "lib/". $class_name . ".php"; } 

$title = "Modulo di pagamento comodati d&#39;uso";

if (@$_POST["do"] != "validate")
{
	Helper::display_template("richiesta-comodato");
	die();
}

$errors = array();
$settings = array();

try {
	$db = new DBModel();
} catch (PDOException $e) {
	$errors["database"] = "Connessione al database fallita:<br />". $e->getMessage();
}

// validate form errors before submitting
if ($_POST["alunno"] == "")
{
	$errors["alunno"] = "Il nome dell&#39;alunno deve essere inserito";
}
if ($_POST["totale"] != 0)
{
	$settings["totaleOrdine"] = str_replace(".", "", $_POST["totale"]);
} else {
	$errors["totale"] = "Selezionare una sezione e una classe";
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
	$settings["numeroOrdine"] = "COM_". time() ."_". str_pad(rand(0,999), 3, STR_PAD_LEFT);
	$settings["causalePagamento"] = "Al. ". $settings["numeroOrdine"] ." ".
									"Cl. ". $_POST["classe"].$_POST["sezione"] ." ".
									"quota comodato";
	
	try {
		$db->insertNewPayment($_POST["alunno"],
							  $_POST["classe"] . $_POST["sezione"],
							  $settings["numeroOrdine"],
							  $settings["totaleOrdine"],
							  $_POST["emailCompratore"],
							  session_id());
	} catch (PDOException $e) { 
		$errors["database"] = "Errore durante la scrittura dei dati:<br />". $e->getMessage();
		Helper::display_template("richiesta-comodato");
		$db = null;
		die();
	}
	//redirecting to UCB
	$mp = new MACPoster(array_merge($_POST, $settings));
	$mp->open_connection ();
} else { Helper::display_template("richiesta-comodato", $errors); }

?>