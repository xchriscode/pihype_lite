<?php
/*
	Developer: Ifeanyi Amadi
	Country: Nigeria
	Version: 1.0.1 
	Website: www.pihype.com
	Company: Kodedapp
	Instagram: @xchriscode, Facebook: #xchriscode
	Project: Pihype PHP MVC framwork
	Phone: +2348183789446
*/

include_once("keep-alive/import_app.php");

function pihype(){$args = func_get_arg(0); $args == "start-app" ? import('keep-alive/pihype_app') : die("Missing Pihype Class. Cannot continue."); }

// Bootloader
import("loader/boot_loader");

// Include Controller class and View
import("MVC/app");

// Include Model File
import("MVC/model");

// Include Addons File
import("MVC/addons");

// Instance of BootLoader class
$router = new Bootloader();

// Router
require("router/router.php");

// Start Application
$router->keep_alive();

// Include Post config file
import("config/post.config");
