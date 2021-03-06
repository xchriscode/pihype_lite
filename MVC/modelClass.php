<?php

class Model
{
	public $EH = null;
	public $activedb = null;
	public $modelData;
	public $boot;
	public $post = false;
	protected $controller;
	public $caller = "Model";


	function __construct()
	{
		if(isset($_SESSION['post.json']))
		{
			$post = json_decode($_SESSION['post.json']);
			$this->post = $post;
		}
	}


	public function __call($meth, $args)
	{
		// check if file exits in models/
		$models_dir = "application/models/{$meth}_m.php";
		if(file_exists($models_dir))
		{
			// ok 
			import($models_dir);

			$class = ucfirst($meth);
			$model = new $class;
			$model->{BootLoader::$helper['connectWith']} = BootLoader::$helper['activedb'];
			$model->db = BootLoader::$helper['activedb'];
			$model->ai = BootLoader::$helper['class']->ai;

			// maximum of 2 arguments
			// argument 1 is the method
			$method = preg_replace('/[\/]/', '', $args[0]);

			if(method_exists($class, $method))
			{
				Message::$switch = 1;
				$model->message = BootLoader::$helper['class']->ai->message;
				$this->modelData = $model->{$method}(@$args[1]);

				BootLoader::$modelData[$method] = $this->modelData;
				return $this->modelData;
			}
			else
			{
				BootLoader::$helper['EH']->log_error("Cannot find method -{$method} in $models_dir");
			}

		}
		else
		{
			BootLoader::$helper['EH']->log_error("Cannot find model -{$meth} in /models");
		}
	}

	public static function modelData($data)
	{
		$modeld = new ModelData();
		$modeld->data = $data;
		return $modeld;
	}
}

// Load Model data
class ModelData
{
	public $data = null;

	public function load($name)
	{
		if(is_array($this->data))
		{
			if(array_key_exists($name, $this->data))
			{
				return $this->data[$name];
			}	
		}
		else
		{
			return false;
		}
		
	}
}