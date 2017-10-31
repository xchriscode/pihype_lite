<?php

pihype("start-app");

class Adapter extends Main
{
	public $db_vars;
	public $instance = null;
	public $EH = null;

	// MYSQLI Connection
	function mysqli()
	{
		extract($this->db_vars);

		$mysqli = new mysqli($host, $user, $pass, $name);

		if(mysqli_connect_error())
		{
			$this->EH->log_error(mysqli_connect_error());
		}
		else
		{
			import("db/mysqli_queries");
			$obj = new MysqliQueries($mysqli);
			return $obj;
		}	
	}

	// PDO Connection
	function pdo()
	{
		extract($this->db_vars);
		import("db/pdo_queries");
		$obj = new PdoQueries("mysql:dbhost=$host;dbname=$name", $user, $pass);
		return $obj;
	}
}