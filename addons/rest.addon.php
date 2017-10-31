<?php
class RestAddon
{
	// GET, POST, PUT DELETE
	private $user = "";
	private $pass = "";
	private $headers = "";

	public function __construct()
	{
		$this->headers = [
		'content-type: application/json',
		'authorization: Basic '.base64_encode($this->user.':'.$this->pass),
		'cookie-cache: no-cache'
		];
	}

	// Postman
	public function postman($url, $data = "")
	{
		if(!empty($url))
		{
			$meth_c = strpos($url, "-");
			$meth = strtoupper(trim(substr($url, 0,$meth_c)));
			$url = trim(substr($url, $meth_c+1));

			$ch = curl_init($url);


			if($meth == "GET" || $meth == "DELETE")
			{
				curl_setopt_array($ch, array(
				CURLOPT_HTTPHEADER => $this->headers,
				CURLOPT_TIMEOUT => 30,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_CUSTOMREQUEST => $meth
				));	
			}
			else
			{
				if(!is_object($data) || is_array($data))
				{
					$data = json_encode($data);
				}

				curl_setopt_array($ch, array(
				CURLOPT_HTTPHEADER => $this->headers,
				CURLOPT_TIMEOUT => 30,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_CUSTOMREQUEST => $meth,
				CURLOPT_POSTFIELDS => $data
				));
			}
			
			$response = curl_exec($ch);
			$error = curl_error($ch);
			curl_close($ch);

			if($error)
			{
				$message = $this->addon->message;
				$message->warning($error);
			}
			else
			{
				return $response;
			}
		}
	}

}