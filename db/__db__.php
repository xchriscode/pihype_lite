<?php

include_once("db/adapter.php");

// DatabaseHandler
class DatabaseHandler extends Adapter
{
	public $db_object;
	public $connect_with;
	public $connect_var;

	public function connect()
	{
		$con = explode(";",$this->connect_var);

		$this->EH = $this->EH;

		if(isset($con[3]))
		{
			$this->db_vars["host"] = trim(str_replace("-h", "", $con[0]));
			$this->db_vars["user"] = trim(str_replace("-u", "", $con[1]));
			$this->db_vars["pass"] = trim(str_replace("-p", "", $con[2]));
			$this->db_vars["name"] = trim(str_replace("-n", "", $con[3]));

			return $this->new_connection();	
		}
		
	}


	private function new_connection()
	{
		if(method_exists($this, $this->connect_with))
		{
			$a = func_get_args();
			$con = $this->{$this->connect_with}();
			return $con;
		}
		else
		{
			$this->EH->log_error("Invalid Database Server, no $this->connect_with method found in adapter.php");
		}
	}

	public function is($sql)
	{
		echo $this->instance;
		// if(is_object($this->db_object))
		// {
		// 	$db = $this->db_object;

		// 	$query = mysqli_query($db, $sql);
		// 	if($db->errno == 0)
		// 	{
		// 		return $query;
		// 	}
		// 	else
		// 	{
		// 		echo $db->error;
		// 		return false;
		// 	}
		// }
	}

}