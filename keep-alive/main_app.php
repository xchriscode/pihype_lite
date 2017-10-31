<?php

// PHP Version
$version = phpversion();

class Main
{
	protected $license = null;
	protected $hash = null;
	public static $components = [];

	// Contain all ReadMe document
	protected function readme()
	{

	}

	// Contain general public license
	protected function public_license()
	{
		$tags = "\n<span style=\"display:none;\">\n #pihype, #xchriscode, #pihype mvc, \n #pihype php mvc, #pihype web, #Pihype, \n #PIHYPE, #PiHype, #Xchriscode, \n @Pihype, @pihype, @xchriscode </span>\n\n";
		echo $tags;
	}

}

// Headers config
header("x-powered-by: Pihype (PHP $version)");
header("techology: Pihype -v 1.1");
header("link: www.pihype.com");