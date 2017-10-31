<?php

// App class 
class App extends Bootloader
{
	public $packager;
	public $boot;
	public static $controller;
	private static $sent;
	public $model;
	private static $invalid_get_request;

	public function __construct()
	{
		// Save declared variables and kill later
		//  Load packager to App class 
		$packager = file_get_contents("packager.json");
		$packager = json_decode($packager);
		$packager->assets->javascripts .= ", pihype.js";
		$packager->assets->css .= ", pihype.css";
		$packager->hashtags = "#pihype, #pihype framework, #kodedapp, #xchriscode";
		$this->packager = $packager;
	}

	// Handle sent data
	final function send($data)
	{
		app::$sent = $data;
		return $this;
	}

	// Handle Returned modelData
	final function getModelData()
	{
		$modelData = Bootloader::$modelData;

		return Model::modelData($modelData);
	}


	// RenderNew function
	final function renderNew($path)
	{
		$c_path = explode("/", $path);
		$boot = (object) BootLoader::$helper;
		if(isset($_SESSION['post.json'])){ unset($_SESSION['post.json']); }
		$_SESSION['message_out'] = MessageAddon::$message;
		$_SESSION['message_out_code'] = MessageAddon::$messageCode;
		if(count($c_path) > 1)
		{
			header("location: {$boot->url}/{$path}");
		}
		else
		{
			header("location: {$boot->url}/{$boot->c_controller}/$path");
		}
	}

	// GET VERB from database
	public function get($verb)
	{
		$db = BootLoader::$helper['class']->{BootLoader::$helper['connectWith']};

		$split = explode("/", $verb);

		$param = strtoupper($split[0]);

		if($param != "PUT" && $param != "DELETE" && $param != "POST")
		{
			return $db->verb("get/$verb");
		}
		else
		{
			BootLoader::$helper['class']->message->error("Can only perform a GET request, $param not allowed in views");
			BootLoader::$helper['class']->addon->message->model_out();
		}
	}

	// Render view
	public function render($name, $data = "")
	{
		$packager = (object) $this->packager;
		$this->packager->load = $this;

		// get main assets file
		import("assets/assets");
		import("config/url.config");
		import("config/form.config");
		// include message addon

		// Variables avaliable to views
		$data_sent = app::$sent;
		$controller = BootLoader::$helper['class'];
		
		// load from another directory
		$asset = function($path)
		{
			$Assets = new Assets();
			return $Assets->load($path);
		};


		// load images
		$image = function($path)
		{
			$Assets = new Assets();
			$img = $Assets->image();
			return $img->load($path);
		};

		// load css
		$css = function($path)
		{
			$Assets = new Assets();
			$cs = $Assets->css();
			return $cs->load($path);
		};

		// load javascript
		$js = function($path)
		{
			$Assets = new Assets();
			$js = $Assets->js();
			return $js->load($path);
		};

		$data = $this;

		// set url
		$url = function($path)
		{
			$uri = new Url();
			return $uri->set($path);
		};

		// set output
		$out = function()
		{
			$out = is_object($this->controller) ? BootLoader::$helper['class']->addon->message->out() : "";
			return $out->message;	
		};

		
		$post = isset($_SESSION['post.json']) ? json_decode($_SESSION['post.json']) : new Form;

		if($name == 404 || $name == 204){ $this->packager->title .= " Page Error "; 
		$this->packager->assets->css .= ",error.css"; }

		include_once("assets/head.php");

		// Display messages from the model
		if(is_object(BootLoader::$helper['class']))
		{
			BootLoader::$helper['class']->addon->message->model_out();	
		}
		

		if($name == 404 || $name == 204)
		{
			include_once("PageError/page{$name}.phtml");
		}
		else
		{
			$name = explode("/", $name);
			$folder = "";

			// if length is 1, then we can use current controller
			if(count($name) == 1)
			{
				$name = BootLoader::$helper['c_controller']."/".$name[0];
				$folder = BootLoader::$helper['c_controller'];
			}
			else
			{
				$folder = $name[0];
				$name = implode("/", $name);
			}


			// check if view file can be found in the view/ directory
			if(file_exists("application/views/{$name}.phtml"))
			{
				// ok include view file
				include_once("application/views/{$name}.phtml");
			}
			else
			{
				// create a new view file
				if(is_dir("application/views/{$folder}"))
				{
					$fh = fopen("application/views/{$name}.phtml", "w");
					fwrite($fh, $name." view##");
					fclose($fh);
					// and include it
					include_once("application/views/{$name}.phtml");	
				}
				
			}	
		}

		// Developed by xchriscode #General public lincense
		$this->public_license();
		// Should not remove - will kill program if you do.

		// Include Page Footer
		include_once("assets/foot.php");

	}

	final public function app_css()
	{
		$css = explode(",",$this->packager->assets->css);
		$url = BootLoader::$helper['url'];

		if(isset($css[0]) && !empty($css[0]))
		{
			foreach($css as $key => $val)
			{
				$val = trim($val);

				if(preg_match("/^[http|https]+[:\/\/]/", $val) == true)
				{	
					// lets get the filename
					$filename = explode("/", $val);
					$filename = end($filename);

					echo "\n <!--$filename file autoloaded-->\n".'<link rel="stylesheet" type="text/css" href="'.$val.'"/>'."\n";
				}
				else
				{
					echo "\n <!--$val file autoloaded-->\n".'<link rel="stylesheet" type="text/css" href="'.$url.'/assets/css/'.$val.'"/>'."\n";	
				}
				
			}
		}
	}

	final public function app_js()
	{
		$js = explode(",",$this->packager->assets->javascripts);
		$url = BootLoader::$helper['url'];

		if(isset($js[0]) && !empty($js[0]))
		{
			foreach($js as $key => $val)
			{
				$val = trim($val);

				if(preg_match("/^[http|https]+[:\/\/]/", $val) == true)
				{	
					// lets get the filename
					$filename = explode("/", $val);
					$filename = end($filename);

					echo "\n <!--$filename file autoloaded-->\n".'<script type="text/javascript" src="'.$val.'"></script>'."\n";
				}
				else
				{
					echo "\n <!--$val file autoloaded-->\n".'<script type="text/javascript" src="'.$url.'/assets/js/'.$val.'"></script>'."\n";
				}
				
			}
		}
	}

	public function track_install()
	{
		
	}
}
?>