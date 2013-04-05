<?php

class DBModel {


	protected static $db_host = ""; //
	protected static $db_name = ""; //   Fill this with 
	protected static $db_user = ""; //   your DB data
	protected static $db_pass = ""; //

	private static $select = "SELECT `data_ins`, `nome`, `classe`, `t1`.`order_id`, 
									 `importo`, `email`, `session_id`, `data_esito` 
							  FROM `richieste_comodato` AS `t1` 
							  LEFT OUTER JOIN `esito_richiesta` AS `t2`  
							  			   ON `t1`.`order_id` =  `t2`.`order_id` 
							  WHERE `t1`.`order_id` = :order_id";
	private static $insert = "INSERT INTO `richieste_comodato`
							  (`nome`, `classe`, `order_id`, `importo`, `email`, `session_id`) 
							  VALUES (:nome, :classe, :order_id, :tot, :email, :sess);";		  
	private static $update = "INSERT INTO `esito_richiesta` 
							  (`order_id`, `esito`) 
							  VALUES (:order_id, :outcome) ";

	private $con;
	
	function __construct()
	{
		$mysql_dsn = "mysql:host=".self::$db_host.";dbname=".self::$db_name;
		try
		{
			$this->con = new PDO($mysql_dsn, self::$db_user, self::$db_pass);
		} catch (PDOException $e) { throw $e; }
	}
	
	function __destruct()
	{
		$this->con = null;
	}
	
	function getPaymentData($order_id)
	{
		$stmt = $this->con->prepare(self::$select);
		$stmt->bindParam(":order_id", $order_id, PDO::PARAM_STR);
		
		try { $stmt->execute(); } 
		catch (PDOException $e) { throw $e; }
		
		return $stmt->fetch(PDO::FETCH_ASSOC);
	}
	
	function insertNewPayment($nome, $classe, $order_id, $importo, $email, $session)
	{
		$stmt = $this->con->prepare(self::$insert);
		$stmt->bindParam(":nome", $nome, PDO::PARAM_STR);
		$stmt->bindParam(":classe", $classe, PDO::PARAM_STR);
		$stmt->bindParam(":order_id", $order_id, PDO::PARAM_STR);
		$stmt->bindParam(":tot", $importo, PDO::PARAM_INT);
		$stmt->bindParam(":email", $email, PDO::PARAM_STR);
		$stmt->bindParam(":sess", $session, PDO::PARAM_STR);

		try 
		{ return $stmt->execute(); }
		catch (PDOException $e)
		{ throw $e; }
	}
	
	function updatePayment($order_id, $outcome)
	{
		$stmt = $this->con->prepare(self::$update);
		$stmt->bindParam(":outcome", $outcome, PDO::PARAM_BOOL);
		$stmt->bindParam(":order_id", $order_id, PDO::PARAM_STR);
		
		try { return $stmt->execute(); }
		catch (PDOException $e) { throw $e; }
	}	
}

?>
