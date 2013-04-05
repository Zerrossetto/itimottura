<?php

class Mailer {

	private $header = "To: %s <%s>\nFrom: %s <%s>\n
					   X-Mailer: PHP Mailer daemon\nMIME-Version: 1.0\n
					   Content-Type: text/html; charset=\"UTF-8\"\n
					   Content-Transfer-Encoding: 7bit\n\n";
	private $subject = "Ordine %s approvato";
	private $body;
  
	function __construct($to, $to_name, $from, $from_name)
	{
		$this->header = sprintf($this->header, $to, $to_name, $from, $from_name);
		$this->body = file_get_contents("lib/mail.tpl.html");
	}

	public function send_mail($order_id, $nome, $classe, $importo, $data_ins, $data_ap)
	{
		$this->subject = sprintf($this->subject, $order_id);
		$this->body = sprintf($this->body, $order_id, $nome, $classe,
							  $importo/100, $order_id, $data_ins, $data_ap);
							  
		//echo $this->body;
		if (!mail($this->to, $this->subject, $this->body, $this->header)) 
		{
			$err = "Errore invio mail ID ordine ".$order_id;
			error_log($err);
			die($err);
		}

	}
}

?>
