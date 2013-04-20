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
$utility = Helper::utilityFromPrefix(substr($_POST["numeroOrdine"], 0, 4));

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
		$to = "";
		$to_name = "";
		$from = "";
		$from_name = "";
		$mailer = new Mailer($to, $to_name, $from, $from_name);
		$mailer->send_mail($_GET["numeroOrdine"], $res["nome"], $res["classe"], 
				   $res["importo"], $res["data_ins"], $res["data_esito"]);
		setcookie("phpsession", $res["session_id"], time() - (session_cache_expire()*60));
		session_id($res["session_id"]);
		@session_destroy();
	}
	$utility["title"] = sprintf($utility["titleOK"], $_POST["numeroOrdine"]);
	$utility = array_merge($utility, $_POST, $res);
	Helper::display_template("ok");
}
if ($_POST["esito"] == "KO")
{ 
	$utility["title"] = sprintf($utility["titleKO"], $_POST["numeroOrdine"]);
	$utility = array_merge($utility, $_POST, $res);
	if ($utility["isPupil"]) { $utility["classe"] = substr($utility["nome"], -2); }
	Helper::display_template("ko");
}

?>

