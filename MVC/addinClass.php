<?php
class Addin
{
	public function __get($name)
	{
		import("assets/assets");
		$location = "add-in/{$name}_ai.php";
		if(file_exists($location))
		{
			include_once($location);

			$assets = new Assets();

			$className = ucfirst($name);
			$ai = new Addin();

			if(class_exists($className))
			{
				$class = new $className;
				$class->image = $assets->image();
				$class->ai = $ai;
				$class->db = Bootloader::$helper['activedb'];
				$class->{Bootloader::$helper['connectWith']} = Bootloader::$helper['activedb']; 
				return $class;
			}
			else
			{
				
				$ai->message->error("$location add-in cannot be loaded. Class $className not found.");
				return false;
			}
		}
	}
}