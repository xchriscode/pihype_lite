<?php
class DeleteRest
{
	public function __construct($table, $id, $db)
	{
		// do delete please
		if(is_integer($id))
		{
			$do_delete = $db->verb("delete/$table/$id");
			if($do_delete != false)
			{
				echo json_encode(["status"=>"success", "data"=>"1 Row affected. Deletion was successful."]);
			}
			else
			{
				echo json_encode(["status"=>"error","data"=>"Query failed. Record not found."]);
			}
		}
		else
		{
			// error
			echo json_encode(["status"=>"error","data"=>"Query failed. $id is not an integer."]);
		}
		
	}
}