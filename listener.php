<?php 
 
function __autoload($class_name) { include "lib/" .$class_name . ".php"; }		

$mp = new MACPoster();
if (!$mp->verify_listener($_SERVER["QUERY_STRING"]))
{
	header("HTTP/1.0 403 Forbidden");
	include("lib/403.tpl.html");
	die();
}

if (in_array($_GET["statoattuale"], array("IC", "KO", "AB"))) {
	
	$autorizzato = ($_GET["statoattuale"] == "IC");
	try 
	{
		$db = new DBModel();
		$upd_ok = $db->updatePayment($_GET["numeroOrdine"], $autorizzato);
		if ($autorizzato)
		{ $res = $db->getPaymentData($_GET["numeroOrdine"]); }
	} catch (PDOException $e) {
		$err = "Listener comodato: Database error --> ". $e->getMessage();
		error_log($err);
		$db = null;
		die($err);
	}

	if ($autorizzato && !empty($res) && $upd_ok) 
	{
		// check if order ID exists and the remote call is 
		// the first in the order status update process
		$to = "";
		$to_name = "";
		$from = "";
		$from_name = "";
		$mailer = new Mailer($to, $to_name, $from, $from_name);
		$mailer->send_mail($_GET["numeroOrdine"], $res["nome"], $res["classe"], 
						   $res["importo"], $res["data_ins"], $res["data_esito"]);
		session_id($res["session_id"]);
		@session_destroy();
	}
	$db = null;
}
?>

