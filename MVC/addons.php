<?php
class Addon
{
	private $boot = [];

	public function __construct($boot)
	{
		$this->boot = $boot;
	}

	public function __get($name)
	{
		import("assets/assets");
		$location = "addons/{$name}.addon.php";
		if(file_exists($location))
		{
			include_once($location);

			$assets = new Assets($this->boot['url']);

			$className = ucfirst($name)."Addon";

			if(class_exists($className))
			{
				$class = new $className;
				$class->image = $assets->image();
				return $class;
			}
		}
	}
}