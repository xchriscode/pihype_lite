<?php
class SanitizeGet
{
	public function get($key)
	{
		$getData = [];

		if(isset($_GET[$key]))
		{
			foreach($_GET as $k => $val)
			{
				$getData[$k] = strip_tags($val);
				$getData[$k] = preg_replace("/[)\(\?<\>\;]/", '', $getData[$k]);
			}
			$getData = (object) $getData;
			return $getData;
		}
		else
		{
			return false;
		}
	}
}