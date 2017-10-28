<?php
class MysqliQueries
{
	private $con;

	public function __construct($connection)
	{
		$this->con = $connection;
	}

	private function query($sql)
	{
		$con = $this->con;

		$query = mysqli_query($con, $sql);
		return $query;	
	}

	public function runquery($sql)
	{
		if(!empty($sql))
		{
			$query = $this->query($sql);
			return $query;
		}
	}

	public function rows($query)
	{
		return $query->num_rows;
	}

	public function verb($string, $jsonData = "")
	{
		$verbRequest = explode("/", $string);
		$args = end($verbRequest);
		$con = $this->con;

		if(strtoupper($verbRequest[0]) == "GET")
		{
			if(isset($verbRequest[2]) && preg_match("/[^0-9]/", $args) == false)
			{
				$id = intval($args);
				$column = "";

				$table = $this->query("select * from {$verbRequest[1]}");

				if($table->num_rows > 0)
				{
					$i=0;
					while($tb = $table->fetch_field())
					{
						$i++;
						if($i == 1)
						{
							$column = $tb->name;
							break;
						}
					}
				}

				// Perform query now
				if($column != "")
				{
					$run = $this->query("select * from {$verbRequest[1]} where {$column} = $id");
					if($run->num_rows > 0 && $run->num_rows == 1)
					{
						return $run->fetch_object();
					}
					elseif($run->num_rows > 1)
					{
						return $run;
					}
					else
					{
						return false;
					}

					$run->close();
				}

			}
			else
			{
				// personalized request
				$args = str_replace("-w", " where ", $args);
				$args = str_replace("-o", "order by ", $args);
				$args = str_replace("-l", "limit", $args);

				$run = $this->query("select * from {$verbRequest[1]} $args");

				if($run->num_rows > 0 && $run->num_rows == 1)
				{
					return $run->fetch_object();
				}
				elseif($run->num_rows > 1)
				{
					return $run;
				}
				else
				{
					return false;
				}

				$run->close();
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

				if($check->num_rows == 0)
				{
					$insert = $this->query("insert into {$verbRequest[1]} $keys $values");
					return $con->affected_rows;	
				}
				else
				{
					return false;
				}
				

				$check->close();
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

				$table = $this->query("select * from {$verbRequest[1]}");

				if($table->num_rows > 0)
				{
					$i=0;
					while($tb = $table->fetch_field())
					{
						$i++;
						if($i == 1)
						{
							$column = $tb->name;
							break;
						}
					}
				}

				// Perform query now
				if($column != "")
				{
					$run = $this->query("delete from {$verbRequest[1]} where {$column} = $id");
					
					if($con->affected_rows == 1)
					{
						return true;
					}
					else
					{
						return false;
					}

					$run->close();
				}

				$table->close();
				
			}
			else
			{
				// personalized request
				$args = str_replace("-w", " where ", $args);
				$args = str_replace("-o", "order by ", $args);
				$args = str_replace("-l", "limit", $args);

				$run = $this->query("delete from {$verbRequest[1]} $args");

				if($con->affected_rows > 0)
				{
					return true;
				}
				else
				{
					return false;
				}

				$run->close();
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

					$table = $this->query("select * from {$verbRequest[1]}");

					if($table->num_rows > 0)
					{
						$i=0;
						while($tb = $table->fetch_field())
						{
							$i++;
							if($i == 1)
							{
								$column = $tb->name;
								break;
							}
						}
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

				if($con->affected_rows > 0)
				{
					$update->close();
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
			return $query->fetch_object();
		}
		else
		{
			return $query->fetch_array();
		}
		
	}

	// Get a column title
	public function field_name($table)
	{
		$column = "";

		$check = $this->query("select * from $table ");

		if($check->num_rows > 0)
		{
			$i=0;
			while($tb = $check->fetch_field())
			{
				$i++;
				if($i == 1)
				{
					$column = $tb->name;
					break;
				}
			}
		}

		return $column;
	}
}