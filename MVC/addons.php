<?php
class Addon
{
	public function __get($name)
	{
		$location = "addons/{$name}.addon.php";
		if(file_exists($location))
		{
			include_once($location);

			$className = ucfirst($name)."Addon";

			if(class_exists($className))
			{
				$class = new $className;
				return $class;
			}
		}
	}
}