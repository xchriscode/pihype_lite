<?php
class RestFul
{

	private $auth_column = ["username","password"];
	private $auth_table = "authentication";
	private $digest = true;
	protected $permission = true;
	private $contentType;

	public function serve()
	{
		$headers = getallheaders();
		$db = $this->{$this->connect_with};

		if(isset($headers['Content-Type']))
		{
			if($headers['Content-Type'] !== "application/json")
			{
				exit("Content-Type must be a json header. please set to application/json");
			}
			else
			{
				$this->contentType = $headers['Content-Type'];
			}
		}
		else
		{
			$this->contentType = "application/json";
		}

		if(isset($headers['Authorization']) && $this->digest == true)
		{
			$auth = utf8_decode($headers['Authorization']);
			$auth = trim(str_replace("Basic", '', $auth));

			$auth = base64_decode($auth);
			$auth = explode(":", $auth);
			$user = $auth[0];
			$key = $auth[1];

			// check for username and password
			if(!isset($_COOKIE['auth_cookie']))
			{
				$column1 = $this->auth_column[0];
				$column2 = $this->auth_column[1];

				$check = $db->verb("get/$this->auth_table/-w $column1 = '$user' and $column2 = '$key'");
				if($check !== false)
				{
					$cookie_back = "auth_cookie=".md5($key.$user)."; ";

					setcookie('auth_cookie',md5($key.$user));

					if($this->permission == true)
					{
						$cookie_back .= "auth_params={$check->permissions};";
						setcookie('auth_params', $check->permissions);	
					}
					
					header("Cookie: $cookie_back");
					$this->handleRequest();
				}
				else
				{
					echo json_encode(["data"=>"Invalid Credentials.","status"=>"error"]);
					setcookie("auth_cookie",'',time()-1);
					setcookie("auth_cookie",'',time()-1);

				}
			}
			else
			{
				$token = $_COOKIE['auth_cookie'];
				if($token == md5($key.$user))
				{
					$this->handleRequest();
				}
				else
				{
					echo json_encode(["data"=>"Invalid User. Authorization required","status"=>"error"]);
					setcookie("auth_cookie",'',time()-1);
					setcookie("auth_cookie",'',time()-1);

				}
			}		
		}
		else
		{
			if($this->digest == false)
			{
				$this->handleRequest();
			}
		}
		
	}	


	function handleRequest()
	{

		header("Access-Control-Allow-Origin: *");
		$verb = $_SERVER['REQUEST_METHOD'];
		header("Access-Control-Allow-Methods: $verb");

		$request = $this->requests;
		$table = isset($request[1]) ? $request[1] : "";
		$arg = isset($request[2]) ? $request[2] : "";

		$verb = strtolower($verb);
		$loc = "Restful Api/{$verb}_rest.php";

		if(file_exists($loc))
		{
			if($verb != "put"){ header("Content-Type: $this->contentType"); }
			
			include_once($loc);
			$verbc = ucfirst($verb)."Rest";

			if(class_exists($verbc))
			{
				$cw = $this->connect_with;
				if($verb == "put")
				{
					// @param1 = DB Table
					// @param2 = DB Object

					$rest = new $verbc($table, $this->{$cw});
				}
				else
				{
					// @param1 = DB Table
					// @param2 = GET ID
					// @param2 = DB Object

					$rest = new $verbc($table, $arg, $this->{$cw});
				}
			}
		}
	}


}