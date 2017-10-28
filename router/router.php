<?php

// Set Database connection
$router->db_config = "-h localhost; -u root; -p ; -n snapiefund";
$router->connect_with = "mysqli";

// URL Mapping
$router->url = "http://127.0.0.1/2017-works/success/snapiefund";
$router->golive = 0;

// Set default Controller and view
$router->router_default = "home/index";

// Register Controller
$router->controller_register("-r home -r account -r shop");

// Set Secure Pages/Controllers
$router->when("account")->check_session("userid,memberid")->else_goto("account/login");

?>