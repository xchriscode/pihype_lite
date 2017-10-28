<?php
class Url
{
	private $url = null;

	function __construct($url)
	{
		$this->url = $url;
	}

	function set($path)
	{
		$path = $this->url.'/'.$path;
		return $path;
	}
}