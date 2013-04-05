<?php 

function __autoload($class_name) { include "lib/". $class_name . ".php"; } 

$mp = new MACPoster();
if (!$mp->verify_response($_POST))
{
	header("HTTP/1.0 403 Forbidden");
	include("lib/403.tpl.html");
	die();
}

$error = array();

if ($_POST["esito"] == "OK") { $esito = TRUE; }
else { $esito = FALSE; } 

try 
{
	$db = new DBModel();
	$upd_ok = $db->updatePayment($_POST["numeroOrdine"], $esito);
	$res = $db->getPaymentData($_POST["numeroOrdine"]);
} catch (PDOException $e) {
	echo "Database error --> ". $e->getMessage();
	$db = null;
	die($err);
}

if ($_POST["esito"] == "OK")
{
	if ($upd_ok) //do this only if row has been added
	{
		$mailer = new Mailer();
		$mailer->send_mail($_POST["numeroOrdine"], $res["nome"], $res["classe"], 
						   $res["importo"], $res["data_ins"], $res["data_esito"]);
		setcookie("phpsession", $res["session_id"], time() - (session_cache_expire()*60));
		session_id($res["session_id"]);
		@session_destroy();
	}
	include("lib/ok.tpl.html");
}
if ($_POST["esito"] == "KO")
{ include("lib/ko.tpl.html"); }

?>

