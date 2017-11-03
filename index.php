<?php
/*
	Developer: Ifeanyi Amadi
	Country: Nigeria
	Version: 1.0.1 
	Website: www.pihype.com
	Company: Kodedapp
	Instagram: @xchriscode, Facebook: #xchriscode
	Project: Pihype PHP MVC framwork
	TelePhone: +2348183789446
	Email: info@pihype.com
*/

include_once("keep-alive/import_ka.php");

function pihype(){$args = func_get_arg(0); $args == "start-app" ? import('keep-alive/main_ka') : die("Missing Main Class. Cannot continue."); }

// Bootloader
import("loader/bootLoader");

// Include Controller class for rendering views and more.
import("MVC/appClass");

// Include Model File
import("MVC/modelClass");

// Include Addons File
import("MVC/addinClass");

// Instance of BootLoader class
$router = new Bootloader();

// Router
require("router/router.php");

// Start Application
$router->keep_alive();

// Include Post config file
import("config/post.config");
