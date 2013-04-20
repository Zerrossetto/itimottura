<?php

/**
 * Data models used for handling different tables
 * INSERT statements based on the user type
 */

class Agent {}

class Pupil extends Agent {

	public $name;
	public $year;
	public $class;
	public $email;

	public function __construct($n, $y, $c, $e)
	{
		$this->name = $n;
		$this->year = $y;
		$this->class = $c;
		$this->email = $e;
	}

}

class Company extends Agent {
	
	public $name;
	public $email;
	
	public function __construct($n, $e)
	{
		$this->name = $n;
		$this->email = $e;
	}
}

/**
 * Here's where the actual DB Model starts
 */

class DBModel {


	protected static $db_host = "";	//
	protected static $db_name = "";	//   Fill this with 
	protected static $db_user = "";			//   your DB data
	protected static $db_pass = "";		//

	private static $select = "SELECT `t1`.`data_ins`, `t2`.`nome`, `t1`.`order_id`, 
									 `t1`.`importo`, `t2`.`email`, `t1`.`session_id`, 
									 `t3`.`data_esito`, `t4`.`body` AS `causale`   
							  FROM `richieste_pagamento` AS `t1` 
							  INNER JOIN (
							  			SELECT `id`, 
							  					CONCAT( `nome` , \", classe \", `classe` ) AS `nome` ,
							  					`email` 
										FROM `alunno`
										UNION 
										SELECT `id` , `nome` , `email` 
										FROM `azienda`
										 ) AS `t2`
										   ON  `t1`.`order_id` = `t2`.`id`
							  LEFT OUTER JOIN `esito_richiesta` AS `t3`  
							  			   ON `t1`.`order_id` =  `t3`.`order_id`
							  LEFT OUTER JOIN `causale` AS `t4`  
							  			   ON `t1`.`order_id` = `t4`.`id` 
							  WHERE `t1`.`order_id` = :order_id";	  
	private static $update = "INSERT INTO `esito_richiesta` 
							  (`order_id`, `esito`) 
							  VALUES (:order_id, :outcome) ";

	private static $ins_pupil = "INSERT INTO `alunno` VALUES (:order_id, :nome, :classe, :email);";
	private static $ins_company = "INSERT INTO `azienda` VALUES (:order_id, :nome, :email);"; 
	private static $ins_order = "INSERT INTO `richieste_pagamento` (`order_id`, `importo`, `session_id`) VALUES (:order_id , :tot, :sess);";
	private static $ins_reason = "INSERT INTO `causale` VALUES (:order_id, :causale);"; 

	private $con;
	
	public function __construct()
	{
		$mysql_dsn = "mysql:host=".self::$db_host.";dbname=".self::$db_name;
		try
		{
			$this->con = new PDO($mysql_dsn, self::$db_user, self::$db_pass);
		} catch (PDOException $e) { throw $e; }
	}
	
	public function __destruct()
	{
		$this->con = null;
	}
	
	public function getPaymentData($order_id)
	{
		$stmt = $this->con->prepare(self::$select);
		$stmt->bindParam(":order_id", $order_id, PDO::PARAM_STR);
		
		$this->con->setAttribute (PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
		try { $stmt->execute(); } 
		catch (PDOException $e) { throw $e; }
		
		return $stmt->fetch(PDO::FETCH_ASSOC);
	}
	
	public function insertNewPayment(Agent $o, $orderid, $amount, $sessid, $reason = NULL)
	{
		$orderStmt = $this->prepareStatementForOrder($orderid, $amount, $sessid);
		$agentStmt = call_user_func_array(array(&$this, "prepareStatementFor".get_class($o)), array($o, $orderid));
		if ($reason != NULL) { $reasonStmt = $this->prepareStatementForReason($orderid, $reason); }
		
		//$this->con->setAttribute (PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
		try 
		{
			$this->con->beginTransaction();
			$orderStmt->execute();
			$agentStmt->execute();
			if ($reason != NULL) { $reasonStmt->execute(); }
			return $this->con->commit();
		}
		catch (PDOException $e)
		{
			// if something wents wrong rollbacks data changes 
			$this->con->rollBack();
			throw $e;
		}
	}
	
	private function prepareStatementForOrder($orderid, $amount, $sessid)
	{
		$stmt = $this->con->prepare(self::$ins_order);
		$stmt->bindParam(":order_id", $orderid, PDO::PARAM_STR);
		$stmt->bindParam(":tot", $amount, PDO::PARAM_INT);
		$stmt->bindParam(":sess", $sessid, PDO::PARAM_STR);
		return $stmt;
	}
	
	private function prepareStatementForReason($orderid, $reason)
	{
		$stmt = $this->con->prepare(self::$ins_reason);
		$stmt->bindParam(":order_id", $orderid, PDO::PARAM_STR);
		$stmt->bindParam(":causale", $reason, PDO::PARAM_STR);
		return $stmt;
	}
	
	private function prepareStatementForPupil(Pupil $u, $orderid)
	{
		$stmt = $this->con->prepare(self::$ins_pupil);
		$stmt->bindParam(":order_id", $orderid, PDO::PARAM_STR);
		$stmt->bindParam(":nome", utf8_encode($u->name), PDO::PARAM_STR);
		$stmt->bindParam(":classe", sprintf("%s%s", $u->year ,$u->class), PDO::PARAM_STR);
		$stmt->bindParam(":email", $u->email, PDO::PARAM_STR);
		return $stmt;
	}
	
	private function prepareStatementForCompany(Company $c, $orderid)
	{
		$stmt = $this->con->prepare(self::$ins_company);
		$stmt->bindParam(":order_id", $orderid, PDO::PARAM_STR);
		$stmt->bindParam(":nome", utf8_encode($c->name), PDO::PARAM_STR);
		$stmt->bindParam(":email", $c->email, PDO::PARAM_STR);
		return $stmt;
	}
	
	public function updatePayment($order_id, $outcome)
	{
		$stmt = $this->con->prepare(self::$update);
		$stmt->bindParam(":outcome", $outcome, PDO::PARAM_BOOL);
		$stmt->bindParam(":order_id", $order_id, PDO::PARAM_STR);
		
		try { return $stmt->execute(); }
		catch (PDOException $e) { throw $e; }
	}	
}

?>
