<?php
header("Content-Type: application/json");
class PutRest
{
	public function __construct($table, $db)
	{
		$data = json_decode(file_get_contents("php://input"));
		
		if(count($data) > 0)
		{

			$single_data = false;
			$multi_data = false; 


			foreach($data as $k => $v)
			{
				if(is_object($v))
				{
					$multi_data = true;
					$single_data = false;
				}
				else
				{
					$multi_data = false;
					$single_data = true;
				}
			}

			$numerics = ["int","tinyint","smallint","bigint","mediumint","float","float","decimal","double","boolean","bit","real","serial"];
	
			if($single_data == true)
			{
				$key_value = "(";
				foreach($data as $k => $v)
				{
					$key_value .= $k.",";
				}

				$key_value = rtrim($key_value, ", ");
				$key_value .= ") values (";

				// GETData type
				$datatype = [];
				$invalid = [];


				$check_sql = "SELECT * FROM $table WHERE ";

				foreach($data as $k => $v)
				{
					$dt = $db->runquery("SELECT DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '$table' AND COLUMN_NAME = '$k'");
					if($db->rows($dt) > 0)
					{
						// valid column
						$res = $db->result($dt);
						$datatype[$k] = $res->DATA_TYPE;
					}
					else
					{
						$invalid[] = $k;
					}
				}

				

				// Set values
				if(count($invalid) <= 0)
				{
					// No Error occured	
					foreach($data as $k => $v)
					{
						// filter out if record exists
						if(in_array($datatype[$k], $numerics))
						{
							$key_value .= $v.", ";
							$check_sql .= $k."=$v AND ";
						}
						else
						{
							$key_value .= "'$v', ";
							$check_sql .= $k."='$v' AND ";
						}
					}

					$key_value = rtrim($key_value, ", ");
					$key_value .= ")";
					$check_sql = rtrim($check_sql, "AND ");

					// so insert now
					$run_check = $db->runquery($check_sql);

					if($db->rows($run_check) == 0)
					{
						$insert = $db->runquery("INSERT INTO $table $key_value");
						if($insert != false)
						{
							echo json_encode(["status" => "success", "data" => "Record inserted successfully."]);
						}
						else
						{
							echo json_encode(["status" => "error", "data" => "Query Failed. "]);
						}	
					}
					else
					{
						echo json_encode(["status" => "error", "data" => "Query Failed Data exists, cannot duplicate record."]);
					}
					
				}
				else
				{
					$error_count = count($invalid);
					$invalid = implode(",", $invalid);

					echo json_encode(["status"=>"error","data"=>"$error_count error occured, invalid column name '$invalid'"]);
				}
			}	
			else
			{

				$success = false;
				$failure_message = "";
				$rows_inserted = 0;
				$total_rows = 0;

				// perform operation in a foreach loop
				foreach($data as $key => $obj)
				{
					$key_value = "(";
					$check_sql = "SELECT * FROM $table WHERE ";

					foreach($obj as $k => $v)
					{
						$total_rows++;

						$key_value .= $k.",";

						
						$key_value = rtrim($key_value, ", ");
						$key_value .= ") values (";

						// GETData type
						$datatype = [];
						$invalid = [];

						
						$dt = $db->runquery("SELECT DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '$table' AND COLUMN_NAME = '$k'");
						if($db->rows($dt) > 0)
						{
							// valid column
							$res = $db->result($dt);
							$datatype[$k] = $res->DATA_TYPE;
						}
						else
						{
							$invalid[] = $k;
						}

						// Set values
						if(count($invalid) <= 0)
						{
							// No Error occured	
							// filter out if record exists
							if(in_array($datatype[$k], $numerics))
							{
								$key_value .= $v.", ";
								$check_sql .= "$k=$v AND ";
							}
							else
							{
								$key_value .= "'$v', ";
								$check_sql .= "$k = '$v' AND ";
							}

							$key_value = rtrim($key_value, ", ");
							$key_value .= ")";
							$check_sql = rtrim($check_sql, "AND ");

							// so insert now
							$run_check = $db->runquery($check_sql);

							if($db->rows($run_check) == 0)
							{
								$insert = $db->runquery("INSERT INTO $table $key_value");
								if($insert != false)
								{
									$success = true;
									$rows_inserted++;
									$total_rows --;
								}
								else
								{
									$success = false;
									$failure_message = "Query Failed. ";
								}
							}
							else
							{

								$failure_message = "Query Failed. $rows_inserted rows inserted, $total_rows rows remains, cannot duplicate record.";	
							}
							
						}
						else
						{
							$error_count = count($invalid);
							$invalid = implode(",", $invalid);

							echo json_encode(["status"=>"error","data"=>"$error_count error occured, invalid column name '$invalid'"]);
							break;
						}
					}		
				}

				if($success == true)
				{
					echo json_encode(["status" => "success", "data" => "Record inserted successfully."]);
				}
				else
				{
					echo json_encode(["status" => "error", "data" => "$failure_message"]);
				}
			}	
			
		}
		else
		{
			echo json_encode(["status" => "error", "data" => "No data sent."]);
		}	
	}
}