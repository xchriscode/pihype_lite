<?php

// App class 
class App extends Bootloader
{
	public $packager;
	public $boot;
	private $controller;
	private $sent;
	public $model;

	public function __construct($boot = "", $controller = "")
	{
		// Save called controller. 
		$this->controller = $controller;
		// Save declared variables and kill later
		$this->boot = $boot;
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
		$this->sent = $data;
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
		$boot = (object) $this->boot;
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

	// Render view
	public function render($name, $data = "")
	{
		$packager = (object) $this->packager;
		$this->packager->load = $this;

		// get main assets file
		import("assets/assets");
		import("config/url.config");
		// include message addon

		// Variables avaliable to views
		$Controller = $this->controller;
		$Assets = new Assets($this->boot['url']);
		$Image = $Assets->image();
		$Css = $Assets->css();
		$Js = $Assets->js();
		$Sent = $this->sent || $data;
		$Model = $this->getModelData();
		$Url = new Url($this->boot['url']);
		$Out = is_object($this->controller) ? $this->controller->addon->message->out() : "";

		if($name == 404 || $name == 204){ $this->packager->title .= " Page Error "; 
		$this->packager->assets->css .= ",error.css"; }

		include_once("assets/head.php");

		// Developed by xchriscode #General public lincense
		$this->public_license();
		// Should not remove - will kill program if you do.

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
				$name = $this->boot['c_controller']."/".$name[0];
				$folder = $this->boot['c_controller'];
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

		// Include Page Footer
		include_once("assets/foot.php");

	}

	final public function app_css()
	{
		$css = explode(",",$this->packager->assets->css);
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
					echo "\n <!--$val file autoloaded-->\n".'<link rel="stylesheet" type="text/css" href="'.$this->url.'/assets/css/'.$val.'"/>'."\n";	
				}
				
			}
		}
	}

	final public function app_js()
	{
		$js = explode(",",$this->packager->assets->javascripts);
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
					echo "\n <!--$val file autoloaded-->\n".'<script type="text/javascript" src="'.$this->url.'/assets/js/'.$val.'"></script>'."\n";
				}
				
			}
		}
	}


	public function redir($path)
	{
		header("location: {$this->url}{$path}");
	}

}
?>