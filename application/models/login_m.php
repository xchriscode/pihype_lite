<?php
class Login
{
	function auth($data = "")
	{
		//$get = $this->db->query("select * from me");
		if($data != "")
		{
			return $data->fullname;
		}
	}
}