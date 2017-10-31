<?php
session_start();

// Import function, handles file inclusions
function import($path)
{
	// check if extension specified
	$npath = explode("/", $path);
	$end = end($npath);

	if(substr($end, 0,1) == ".")
	{
		// extension used
		if(file_exists($path))
		{
			include_once($path);
		}
		else
		{
			file_put_contents("logs/error_log.txt", substr($end,1)." cannot be load. Reason: dosen't exists in directory.", FILE_APPEND);
			echo "FILE ERROR: ".substr($end,1);
		}
	}
	else
	{
		$loc = $path.".php";
		if(file_exists($loc))
		{
			include_once($loc);
		}
		else
		{
			// now check if a specific file asked
			if(strpos($npath[1], ".") >= 0)
			{
				if(file_exists($path))
				{
					include_once($path);
				}
				else
				{
					file_put_contents("logs/error_log.txt", $npath[1]." cannot be load. Reason: dosen't exists in directory.", FILE_APPEND);
					echo "FILE ERROR: ".$npath[1].".php";
				}
			}
			else
			{
				file_put_contents("logs/error_log.txt", $end." cannot be load. Reason: dosen't exists in directory.", FILE_APPEND);
				echo "FILE ERROR: ".$end.".php";	
			}
			
		}
	}
}