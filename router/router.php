<?php

// Set Database connection
$router->db_config = "-h localhost; -u root; -p ; -n";
$router->connect_with = "mysqli"; // mysqli or pdo

// URL Mapping
$router->url = "";
$router->golive = 0; // This would end error reporting and log all errrors when set to 1

// Set default Controller and view
$router->router_default = "pihype/home";

// Register Controller
$router->controller_register("-r pihype");

// Set Secure Pages/Controllers
$router->when("#controller")->check_session("#session_vars")->else_goto("#path");
// When working with sessions and restricted pages, you might wanna use this
/*
	Explain code above:

	When a GET request for account controller is made,
	check for userid and memberid in $_SESSION array
	else if not found, redirect user to home/login
*/

?>