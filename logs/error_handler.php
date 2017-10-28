<?php
class error_handler
{
	private $mode = 0;

	public function __construct($id)
	{
		$this->mode = $id;
	}

	static function config($switch)
	{
		if($switch == 1)
		{
			// Hide all errors and log them
			error_reporting(0);
		}
		else
		{
			error_reporting(E_ALL);
		}

		return new self($switch);
	}

	// ok log error
	public function log_error($error)
	{
		if($this->mode == 1)
		{
			// log error
			$error_file = "logs/error_log.txt";
			$read = file_get_contents($error_file);

			$error_arr = explode("#", $read);
			unset($error_arr[0]);

			$error_arr = array_values($error_arr);

			$append = true;

			$time = date("M d, Y g:i a");

			foreach($error_arr as $k => $line)
			{
				if(trim($line) == trim("[$time] ".$error))
				{
					$append = false;
					break;
				}
				
			}

			if($append === true)
			{
				$error = "# [$time] $error\n";
				file_put_contents($error_file, $error, FILE_APPEND);
			}
		}
		else
		{
			exit('<link rel="stylesheet" type="text/css" href="assets/css/error.css"/>
					<div class="error-warning">'.$error.'</div>');
		}
	}

	// This happens when a page cannot be found
	public function page_not_found()
	{
		// Include 404 page 
		include_once("PageError/page404.phtml");
	}
}