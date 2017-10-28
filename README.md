# Pihype LITE
## A PHP MVC Web Framework for most secure, faster development.

@ Version 1.0.1
@ Developed by Xchriscode.

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

# Views
Views generated are saved in application/views/, in a controller directory.
Views are rendered from the a controller with a method avaliable in the App module.
See example:
	
		$app = $this->app;
		// Render index view from home controller
		$app->render("index");
		
		// Render a view outside home controller
		$app->renderNew("about/index");

If view not found, would be created when system runs.


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




