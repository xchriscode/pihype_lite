<?php
class PostRest
{
	public function __construct($table, $id, $db)
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
				$key_value = "";
				
				// No Error occured	
				foreach($data as $k => $v)
				{
					// Get data type
					if(in_array($datatype[$k], $numerics))
					{
						$key_value .= $k."=$v, ";
					}
					else
					{
						$key_value .= "$k='$v', ";
					}
				}

				$key_value = rtrim($key_value, ", ");

				// so update now

				$column = $db->field_name($table);

				$update = $db->runquery("UPDATE $table SET $key_value WHERE $column = $id");

				if($update != false)
				{
					echo json_encode(["status" => "success", "data" => "Record update successfully."]);
				}
				else
				{
					echo json_encode(["status" => "error", "data" => "Update Query Failed. "]);
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
					$key_value = "";

					foreach($obj as $k => $v)
					{
						$total_rows++;

						
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

						// No Error occured	
						// filter out if record exists
						if(in_array($datatype[$k], $numerics))
						{
							$key_value .= "$k=$v, ";
						}
						else
						{
							$key_value .= "$k='$v', ";
						}

						$key_value = rtrim($key_value, ", ");

						// so insert now
						$run_check = $db->runquery($check_sql);

						$column = $this->field_name($table);

						$update = $db->runquery("UPDATE $table SET $key_value WHERE $column = $id");
						if($update != false)
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
				}

				if($success == true)
				{
					echo json_encode(["status" => "success", "data" => "Record Updated successfully."]);
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