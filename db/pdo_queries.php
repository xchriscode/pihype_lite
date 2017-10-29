<?php
class PdoQueries extends PDO
{
	private $con;
	
	public function __construct($dns, $user, $pass)
	{
		$this->con = parent::__construct($dns, $user, $pass);
	}

	private function do_query($sql)
	{
		$query = $this->prepare($sql);
		$query->setFetchMode(PDO::FETCH_OBJ);
		return $query;	
	}

	public function runquery($sql)
	{
		if(!empty($sql))
		{
			$query = $this->do_query($sql);
			return $query;
		}
	}

	public function rows($query)
	{
		return $query->rowCount();
	}

	public function verb($string, $jsonData = "")
	{
		$verbRequest = explode("/", $string);
		$args = end($verbRequest);
		$con = $this->con;

		if(strtoupper($verbRequest[0]) == "GET")
		{
			if(preg_match("/[^0-9]/", $args) == false)
			{
				$id = intval($args);
				$column = "";

				$table = $this->do_query("select * from $verbRequest[1]");
				$table->execute();

				$num = $table->fetchAll(PDO::FETCH_ASSOC);

				if($table->rowCount() > 0)
				{
					$keys = array_keys($num[0]);
					$column = $keys[0];
				}

				// Perform query now
				if($column != "")
				{
					$run = $this->query("select * from {$verbRequest[1]} where {$column} = $id");
					$run->setFetchMode(PDO::FETCH_OBJ);

					if($run->rowCount() > 0 && $run->rowCount() == 1)
					{
						return $run->fetch();
					}
					elseif($run->rowCount() > 1)
					{
						return $run;
					}
					else
					{
						return false;
					}
				}
				
			}
			else
			{
				// personalized request
				$args = str_replace("-w", " where ", $args);
				$args = str_replace("-o", "order by ", $args);
				$args = str_replace("-l", "limit", $args);

				$run = $this->query("select * from {$verbRequest[1]} $args");

				if($run !== false)
				{
					if($run->rowCount() > 0 && $run->rowCount() == 1)
					{
						return $run->fetch();
					}
					elseif($run->rowCount() > 1)
					{
						return $run;
					}
					else
					{
						return false;
					}	
				}
				
			}
		}
		elseif(strtoupper($verbRequest[0]) == "PUT")
		{
			// insert
			if($jsonData !== "")
			{
				$jsonData = preg_replace("/[}\{]/", "", $jsonData);
				$json = explode(",", $jsonData);

				$keys = "(";
				$values = "(";

				$where = "where ";

				foreach($json as $key => $val)
				{
					$val = explode(":", $val);
					$keys .= trim($val[0]).",";
					$values .= $val[1].",";
					$where .= $val[0]."={$val[1]} and ";
				}

				$keys = rtrim($keys, ", ");
				$keys .= ") values ";

				$values = rtrim($values, ", ");
				$values .= ")";

				$where = rtrim($where, "and ");

				//check to avoid duplication
				$check = $this->query("select * from {$verbRequest[1]} $where");

				if($check->rowCount() == 0)
				{
					$insert = $this->query("insert into {$verbRequest[1]} $keys $values");
					return $insert->rowCount();	
				}
				else
				{
					return false;
				}
			}
			else
			{
				return false;
			}
		}
		elseif(strtolower($verbRequest[0]) == "delete")
		{
			if(preg_match("/[^0-9]/", $args) == false)
			{
				$id = intval($args);
				$column = "";

				$table = $this->do_query("select * from $verbRequest[1]");
				$table->execute();

				$num = $table->fetchAll(PDO::FETCH_ASSOC);

				if($table->rowCount() > 0)
				{
					$keys = array_keys($num[0]);
					$column = $keys[0];
				}


				// Perform query now
				if($column != "")
				{
					$run = $this->query("delete from {$verbRequest[1]} where {$column} = $id");
					
					if($con->rowCount() == 1)
					{
						return true;
					}
					else
					{
						return false;
					}
				}
				
			}
			else
			{
				// personalized request
				$args = str_replace("-w", " where ", $args);
				$args = str_replace("-o", "order by ", $args);
				$args = str_replace("-l", "limit", $args);

				$run = $this->query("delete from {$verbRequest[1]} $args");

				if($con->rowCount() > 0)
				{
					return true;
				}
				else
				{
					return false;
				}
			}
		}
		elseif(strtoupper($verbRequest[0]) == "POST")
		{
			// insert
			if($jsonData !== "")
			{
				$jsonData = preg_replace("/[}\{]/", "", $jsonData);
				$json = explode(",", $jsonData);

				$set = "set ";

				foreach($json as $key => $val)
				{
					$val = explode(":", $val);
					$set .= $val[0]."={$val[1]}, ";
				}

				$set = rtrim($set, ", ");
				$other = "";

				if(preg_match("/[^0-9]/", $args) == false)
				{
					$id = intval($args);
					$column = "";

					$table = $this->do_query("select * from $verbRequest[1]");
					$table->execute();

					$num = $table->fetchAll(PDO::FETCH_ASSOC);

					if($table->rowCount() > 0)
					{
						$keys = array_keys($num[0]);
						$column = $keys[0];
					}

					$other = "$column = $id";	
				}
				else
				{
					$args = str_replace("-w", " where ", $args);
					$args = str_replace("-o", "order by ", $args);
					$args = str_replace("-l", "limit", $args);

					$other = $args;
				}
				
				//check to avoid duplication
				$update = $this->query("update {$verbRequest[1]} $set where $other");

				if($con->rowCount() > 0)
				{
					return true;	
				}
				else
				{
					return false;
				}
				
			}
			else
			{
				return false;
			}
		}
	}

	public function result($query, $type="")
	{
		if($type == "")
		{
			return $query->fetch(PDO::FETCH_OBJ);
		}
		else
		{
			return $query->fetch(PDO::FETCH_ASSOC);
		}
		
	}

	// Get a column title
	public function field_name($table)
	{

		$column = "";

		$table = $this->do_query("select * from $table");
		$table->execute();

		$num = $table->fetchAll(PDO::FETCH_ASSOC);

		if($table->rowCount() > 0)
		{
			$keys = array_keys($num[0]);
			$column = $keys[0];
		}

		return $column;
	}
}