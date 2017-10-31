<?php
class Assets
{
	private $url;
	private $call = null;
	private $compress_size = 80;

	// Load images and compress them
	public function image($name = "")
	{
		if($name != "")
		{
			$this->call = "images";
			return $this->load($name);
		}
		else
		{
			return $this;
		}
	}

	// load css files from assets 
	public function css($name = "")
	{
		if($name != "")
		{
			$loc = BootLoader::$helper['url']."/assets/css/{$name}";
			return 	$loc;
		}
		else
		{
			return $this;
		}
	}

	// load javascript files
	public function js($name = "")
	{
		if($name != "")
		{
			$loc = BootLoader::$helper['url']."/assets/js/{$name}";
			return 	$loc;
		}
		else
		{
			return $this;
		}
	}

	// Load from assets folder
	public function load($file)
	{
		if(!empty($file))
		{
			$get_ext = explode(".", $file);
			$extension = strtoupper(end($get_ext));

			// check extensions
			if($extension == "JPG" || $extension == "JPEG" || $extension == "GIF" || $extension == "PNG")
			{
				$this->call = "images";
			}
			elseif($extension == "CSS")
			{
				$this->call = "css";
			}
			elseif($extension == "JS")
			{
				$this->call = "js";
			}


			// Image asked for?
			if($this->call == "images")
			{
				$source = BootLoader::$helper['url']."/assets/images/{$file}";
				$new_destination = "assets/images/compressed/{$file}";
				$image = $this->compress_image($source, $new_destination, $this->compress_size);
				return $image;
			}

			// CSS asked for?
			elseif($this->call == "css")
			{
				$loc = BootLoader::$helper['url']."/assets/css/{$file}";
				return 	$loc;
			}

			// JAVASCRIPT asked for?
			elseif($this->call == "js")
			{
				$loc = BootLoader::$helper['url']."/assets/js/{$file}";
				return 	$loc;
			}

			// Private Folder asked for?
			else
			{
				$loc = BootLoader::$helper['url']."/assets/{$file}";
				return 	$loc;
			}	
		}
	}

	private function compress_image($source, $new_destination, $compress)
	{
		$image = getimagesize($source);
		$image_header = get_headers($source,1);

		$newimage = "";

		if($image['bits'] > 0)
		{
			if($image_header['Content-Length'] >= 100000)
			{
				if($image['mime'] == "image/jpg" || $image['mime'] == "image/jpeg")
				{
					$newimage = imagecreatefromjpeg($source);
				}
				elseif($image['mime'] == "image/gif")
				{
					$newimage = imagecreatefromgif($source);
				}
				elseif($image['mime'] == "image/png")
				{
					$newimage = imagecreatefrompng($source);
				}

				$newimage = imagejpeg($newimage, $new_destination, $compress);
				return BootLoader::$helper['url'].'/'.$new_destination;
			}
			else
			{
				return $source;
			}	
		}
		else
		{
			return $source;
		}
		
	}
}