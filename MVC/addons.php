<?php
class Addon
{
	public function __get($name)
	{
		import("assets/assets");
		$location = "addons/{$name}.addon.php";
		if(file_exists($location))
		{
			include_once($location);

			$assets = new Assets();

			$className = ucfirst($name)."Addon";

			if(class_exists($className))
			{
				$class = new $className;
				$class->image = $assets->image();
				MessageAddon::$switch = 1;
				$class->addon = new Addon();
				return $class;
			}
			else
			{
				return false;
			}
		}
	}
}