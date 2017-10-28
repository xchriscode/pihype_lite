<?php
class MessageAddon
{
	private static $message;
	private static $messageCode; //1 = success, 2 = warning, 3 = error

	// when there is an error
	public function error($msg)
	{
		$message = '<div class="alert alert-danger pihype-alert" id="pihype_alert">'.ucfirst($msg).'</div>';
		MessageAddon::$message = $message;
		MessageAddon::$messageCode = 3;
	}

	// when it is a success
	public function success($msg)
	{
		$message = '<div class="alert alert-success pihype-alert" id="pihype_alert">'.ucfirst($msg).'</div>';
		MessageAddon::$message = $message;
		MessageAddon::$messageCode = 1;
	}

	// when it is a warning
	public function warning($msg)
	{
		$message = '<div class="alert alert-warning pihype-alert" id="pihype_alert">'.ucfirst($msg).'</div>';
		MessageAddon::$message = $message;
		MessageAddon::$messageCode = 2;
	}

	//display message
	public function out()
	{	
		$session_used = false;

		$message = empty(MessageAddon::$message) ? isset($_SESSION['message_out']) ? $_SESSION['message_out'] : '' : MessageAddon::$message;
		
		$code = empty(MessageAddon::$messageCode) ? isset($_SESSION['message_out_code']) ? $_SESSION['message_out_code'] : '' : MessageAddon::$messageCode;

		if(isset($_SESSION['message_out_code']))
		{
			unset($_SESSION['message_out'], $_SESSION['message_out_code']);
		}

		$obj =  ["message" => $message, "code" => $code];

		return (object) $obj;
	}
}