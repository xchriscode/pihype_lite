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
Views generated are saved in application/views/ in a controller directory.
Views are rendered from the a controller with a method avaliable in the App module.
See example:
	
		$app = $this->app;
		// Render index view from home controller
		$app->render("index");
		
		// Render a view outside home controller
		$app->renderNew("about/index");
	
