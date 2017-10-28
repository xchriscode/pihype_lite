<?php
// Handle REST GET Requests.
class GetRest
{
	public function __construct($tableName, $id, $db)
	{
		if($id == "")
		{
			$data = $db->verb("get/{$tableName}");	
		}
		else
		{
			$data = $db->verb("get/{$tableName}/$id");
		}
		

		if($data != false)
		{
			if($id != "")
			{
				$json = [];

				foreach($data as $key => $val)
				{
					$json[$key] = $val;
				}

				$json['status'] = "success";

				echo utf8_encode(json_encode($json));
			}
			else
			{
				$json = [];

				$i=1;

				while($d = $db->result($data))
				{
					$json['row'.$i] = $d;
					$i++;
				}

				echo json_encode($json)."\n";
			}
		}
		else
		{
			echo json_encode(["status"=>"error", "data"=> "$tableName Not found."]);
		}
	}
}