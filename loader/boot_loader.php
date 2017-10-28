<?php
session_start();

//Include Database Handler
import("db/__db__");

// Bootloader Class
class BootLoader extends DatabaseHandler
{
	public $boot = [];
	public $router_default;
	public $database;
	public static $modelData;
	public $controllers = "";
	public static $history_c;
	public $whenBox = [];

	public function __construct()
	{

	}

	// Secure When function (Handle session tracking)
	public final function when($controller)
	{
		$controllers = explode("|", $controller);
		foreach($controllers as $key => $cont)
		{
			if($cont != "")
			{
				$this->whenBox[$key] = $cont;
			}
		}
		return $this;
	}

	// when to actively use sessions
	public final function check_session($names)
	{
		$activeS = [];

		$names = explode(",", $names);

		foreach($this->whenBox as $key => $cont)
		{
			foreach($names as $key => $value)
			{
				$activeS[$cont][] = $value;
			}
		}

		$this->activeS = $activeS;

		return $this;
	}

	// when session not active, do_goto
	public final function else_goto($path)
	{
		$url = isset($_GET['app']) ? explode("/", rtrim($_GET['app'], "/ ")) : "";
		$cont = isset($url[0]) ? $url[0] : '';

		$controller_found = false;
		$controller_name = "";

		if(in_array($cont, $this->whenBox))
		{
			$controller_found = true;
			$controller_name = $cont;
		}


		if($controller_found == true)
		{
			$activeS = $this->activeS[$cont];

			$not_set = false;

			foreach($activeS as $k => $nm)
			{
				if(!isset($_SESSION[$nm]))
				{
					$not_set = true;
				}
			}

			if($not_set == true)
			{
				$url = $this->boot['url'];

				if($cont !== $controller_name)
				{
					header("location: {$url}/{$path}");	
				}
				
			}
		}
	}

	public function assets($file)
	{
		return $this->url."assets/$file";
	}

	public function controller_register($controllers)
	{
		$cnt = explode("-r", $controllers);

		$this->controllers = $cnt;

		foreach($cnt as $key => $controller)
		{
			if(!empty($controller))
			{
				if(substr(trim($controller), 0,1) == "@")
				{
					$this->secure[] = substr(trim($controller), 1);
					$controller = substr(trim($controller), 1);
				}

				if(!file_exists("application/controllers/".trim($controller)."_c.php"))
				{
					$fh = fopen("application/controllers/".trim($controller)."_c.php", "w+");
					fclose($fh);
					// create a new folder for views
					mkdir("application/views/".trim($controller));
				}
				else
				{
					// check if cannot find folder
					$dir = "application/views/".trim($controller);
					if(!is_dir($dir))
					{
						mkdir($dir);
					}
				}	
			}
			
		}
	}

	// Start application
	public function keep_alive()
	{
		header("host: {$this->boot['url']}");

		// Include Error handler
		import("logs/error_handler");
		$EH = error_handler::config($this->golive);

		// Set error handler and make it avaliable for use across script!
		$this->EH = $EH;
		$this->boot['EH'] = $EH;
		$this->boot['controllers'] = $this->controllers;


		// Start Database 
		$database = $this->connect();
		$this->boot['activedb'] = $database; 
		$this->boot['connectWith'] = $this->connect_with;


		// Set default controller
		$default = explode("/", $this->router_default);
		if(count($default) == 2)
		{
			$m_controller = $default[0];
			$m_view = $default[1];
		}
		else
		{
			$m_controller = "";
			$m_view = "";
		}

		$url = isset($_GET['app']) ? explode("/", rtrim($_GET['app'], "/ ")) : "";

		$cont = isset($url[0]) ? $url[0] : $m_controller;
		$view = isset($url[1]) ? $url[1] : $m_view;
		$arg = isset($url[2]) ? $url[2] : "";


		// REST API Request
		if($cont == "rest" || $cont == "REST")
		{
			$rest_loc = "RestfulApi/restapi.php";
			if(file_exists($rest_loc))
			{
				// Include Restful class 
				include_once($rest_loc);
				// instantiate Rest class
				$rest = new RestFul();
				// Ready Database object
				$rest->connect_with = $this->connect_with;
				$rest->{$this->connect_with} = $database;
				// Ready all GET/URI requets
				$rest->requests = $url;
				// Serve REST now
				$rest->serve();
			}
		}
		else
		{
			// NOT A RESTFUL REQUEST
			if(isset($_GET['qr'])){ $view = $_GET['qr'];}
			if(isset($_GET['qa'])){ $arg = $_GET['qa'];}

			if(substr($cont, 0,1) != "@")
			{ 
				file_put_contents("logs/url.txt", "$cont/$view/$arg");
			}
			else
			{
				$url = explode("/", file_get_contents("logs/url.txt"));
				$cont = $url[0];
				$view = $url[1];
				$arg = $url[2] || "";

				$postData = "";
				foreach($_POST as $key => $val)
				{
					$val = strip_tags(htmlspecialchars(stripslashes($val)));
					$postData[$key] = $val;
				}
				
				$postData = json_encode($postData);

				$_SESSION['post.json'] = $postData;

				$other = !empty($arg) ? "&qa={$arg}" : "";
				header("location: {$this->url}/{$cont}?qr={$view}{$other}");
			}

			$invalid_controller = false;


			$this->boot['c_controller'] = $cont;

			$this->boot['active_c'] = $cont;
			$this->boot['active_v'] = $view;

			include_once("config/get.config.php");

			// check if file exists
			if(file_exists("application/controllers/{$cont}_c.php"))
			{
				import("application/controllers/{$cont}_c");

				if(class_exists($cont))
				{
					$class = new $cont;
				
					// load extensions
					$class->model = new Model($this->boot);
					$class->addon = new Addon($this->boot);
					$class->app = new App($this->boot, $class);
					$class->post = $class->model->post;
					$class->get = new SanitizeGet();
					$class->{$this->connect_with} = $database;
					$class->message = $class->addon->message;

					// load view requested
					if(method_exists($class, $view))
					{
						$class->{$view}($arg);	
					}
					else
					{
						// load 404 error here
						$class->app->render(404);
					}
					
				}
				else
				{
					// load 204 error
					$app = new App($this->boot);
					$app->render(204);
				}
			}
			else
			{
				$invalid_controller = true;
				// load 404 error
				$app = new App($this->boot);
				$app->render(404);
			}


			if($invalid_controller === true)
			{
				import("application/controllers/{$m_controller}_c");

				if(class_exists($m_controller))
				{
					$class = new $m_controller;

					// load extensions
					$class->model = new Model($this->boot);
					$class->addon = new Addon($this->boot);
					$class->app = new App($this->boot, $class);
					$class->post = $class->model->post;
					$class->get = new SanitizeGet();
					$class->{$this->connect_with} = $database;
					$class->message = $class->addon->message;

					$class->{$m_view}($arg);
				}
				else
				{
					$EH->log_error("Controller -{$m_controller} not found/ready. Please check /controllers");
				}
			}
		}
		
	}

	// Setter
	public function __set($name, $value)
	{	
		if($name == "db_config")
		{
			$this->connect_var = $value;
		}
		elseif($name == "connect_with")
		{
			$this->connect_with = $value;
		}
		else
		{
			$this->boot[$name] = $value;	
		}
	}


	// get from boot array

	public function __get($name)
	{
		if(is_array($this->boot))
		{
			if(isset($this->boot[$name]))
			{
				return $this->boot[$name];
			}	
		}
		
	}



	public function __destruct()
	{
		if(is_array($this->boot))
		{
			foreach($this->boot as $key => $value)
			{
				unset($this->boot[$key]);
			}	
		}
	
	}

}