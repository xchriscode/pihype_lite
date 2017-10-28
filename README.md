# Pihype
## A Better PHP MVC Framework for secure, fast, dynamic web development.

@ Version 1.0.1 Beta

Developed by Xchriscode.

@ Features And Benefits

1. Image Optimization (Compresses all the images to give you a fast page load time)
2. DRY Coding
3. Overloading Design Pattern & Singleton Pattern
4. DNS database string connection
5. Package installer > package.json
6. Fast Page load time.
7. Email and SMS helper class
8. Safe SQL queries that prevents you from hackers
9. JavaScript & CSS autoloader
10. Fast SQL queries
11. Perfect MVC design pattern.
12. RESFUL API built in function
13. Addon, shared class/function supports
14. Fully MVC
15. Dynamic error reporting and logs
16. Completely Object Oriented
17. Easy to implement, easy to share
18. Supports MYSQLI and PDO (Built in Functions) quick to setup and use.


@ Documentation

# Controllers
Controllers are registered in the router/router.php file
	
	// Register Controller
	$router->controller_register("-r home -r account -r shop");
The -r is used to register a controller name for proper routing.
Controllers generated are saved in application/controllers/
They have 
	
	_c.php extension

# How to create a controller
in application/controllers/
filaname: home_c.php
	
	class Home
	{
		// index method to interact with model and render a view or views
		public function index()
		{
			//Load Modules
			$app = $this->app;
			$db = $this->mysqli; // if connect_with is mysqli
			$addon = $this->addon; 
			$message = $this->message;
			$model = $this->model;

			//If you want to log a message and generate output when view has been rendered
			$message->success("message here");
			$message->warning("You should be good!");
			$message->error("Authorization failed!");

			//Render a view
			$app->render("index"); or $app->render("home/index"); or $app->render("about/index")
			// If in another controller, you can use this.
			// This will force a redirect
			$app->renderNew("account/login");

			// How to call a model from a controller
			$login = $model->login("/users"); // users is a method in login class
		}
	}
	
# Models
Models are saved in application/model/
They have 

	_m.php extension
Models can be accessed from a controller like this:
	
	$model = $this->model;
	// And we specify the name of the model to get access to its objects
	// Assume we have a model called home_m.php with a method called users
	$model->home("/users");
	// That's how fast we could work with models

# How form sends data to a model from a view
	
	<form action="@login/users" method="post">
		// input tags and more
	</form>

Data sent is first handled by the controller, so must be declared in the controller before the model would process the request.
How to declare in the controller
	
		$login = $model->login("/users");
		// If there is any special data you want to send back to the view you can use the method below
		$app->send($login)->render("home/login");
		// And in your view you can receive the sent data with the variable $Sent


# Views
Views generated are saved in application/views/, in a controller directory.
Views are rendered from the a controller with a method avaliable in the App module.
See example:
	
		$app = $this->app;
		// Render index view from home controller
		$app->render("index");
		
		// Render a view outside home controller
		$app->renderNew("about/index");

# How to display errors, call images, js, videos, mp3, css etc from a view 
	
	// Asssume file name is application/views/home/index.phtml

	// GET css
	<link rel="stylesheet" href="<?=$Css->load('main')?>"/>
	// GET Javascript
	<script src="<?=$Js->load('bootstrap')?>"></script>
	// GET image
	<img src="<?=Image->load('pihype.jpg')?>"/>
	// Load other files from assets folder
	<video><source src="<?=$Assets->load('media/video.mp4')?>"></source></video>
	// Set a link
	<a href="<?=$Url->set('home/login')?>"> Login </a>
	// Display a message
	<?=$Out->message?>
	// And much more..

If view not found, would be created when script runs.


# Packager
The packager.json file would help you set-up header, css, javascripts, meta tags etc.
Packager file can be found in the root directory. 

		{
			"title":"",
			"keywords":"",
			"version":"1.0",
			"description":"",
			"framework":"Pihype Lite aplha",
			"website":"www.pihype.com",
			"author":"",
			"assets":{
				"javascripts":"bootstrap.js",
				"css":"bootstrap.css"
			}
		}


# Header & Footer
Header and footer file can found in assets/ directory. 

# Database
Pihype supports MYSQLI and PDO, can be configured in the router/router.php file. 
See example below:

	// Set Database connection
	$router->db_config = "-h localhost; -u root; -p ; -n snapiefund";
	$router->connect_with = "mysqli"; 

	/* 
	  -h host
	  -u user
	  -p password
	  -n name
	*/

# Database methods
Database methods are avalible for all you models and controllers, below are list of avaliable methods for mysqli and pdo.
	
	$db = $this->mysqli;
	// or if pdo
	$db = $this->pdo;

	// Make a select query
	$db->verb("get/users/1");
	// This makes a select query and get the record with an id of 1
	// Optional params
	$db->verb("get/users/-w userid = 2 and username = 'pihype'");
	-w = where
	-o = order by
	-l = limit

This returns result[s] as an object


	Make an insertion
	$db->verb("put/users","username:'paul', fullname:'chris allison'");
	Returns number of affected row / true

	Make a deletion
	$db->verb("delete/users/1");
	Returns true if query was successful and false otherwise.

	Make an update
	$db->verb("update/users/2","username:'mickke'");
	Returns true or false

	Other Methods:
	$db->rows(query) # Returns the number of rows in that query
	$db->runquery(sql) # Performs Query and returns Query Object
	$db->result(query, Param) # Returns an object by default, but when param set, will return an array


# Working with RESTFUL APIs
Pihype has it ready for you. With a little configuration you are good to serve restful requests.

	Open: RestfulApi/restapi.php

	private $auth_column = ["username","password"];
	private $auth_table = "authentication";
	private $digest = true;

	// auth_column : for db authentication. USER and PW
	// auth_table : Name of db table where query would check to see if user can be permitted to use the API
	// digest: True if Basic Auth is required to access make PUT/GET/DELETE/POST calls
	

	Avaliable Methods
	1. GET
	2. POST
	3. PUT (Avoids data duplication, supports multiple requests)
	4. DELETE
	Returns JSON encoded data, takes JSON formatted data.
