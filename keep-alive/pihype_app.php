<?php

// PHP Version
$version = phpversion();

class Pihype
{
	protected $license = null;
	protected $hash = null;

	// Contain all ReadMe document
	protected function readme()
	{

	}

	// Contain general public license
	protected function public_license()
	{
		
	}
}

// Headers config
header("x-powered-by: Pihype (PHP $version)");
header("techology: Pihype -v 1.1");
header("link: www.pihype.com");