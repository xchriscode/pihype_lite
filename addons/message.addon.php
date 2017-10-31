<?php
class MessageAddon
{
	private static $message = "";
	public static $messageCode = ""; //1 = success, 2 = warning, 3 = error
	public static $switch = 0;

	// when there is an error
	public function error($msg, $switch = "")
	{
		if(!empty($msg))
		{
			$message = '<div class="alert alert-danger pihype-alert" id="">'.ucfirst($msg).'</div>';
			MessageAddon::$message = $message;
			MessageAddon::$messageCode = 3;

			if($switch != "")
			{
				MessageAddon::$switch = 2;
			}	
		}
	}

	// when it is a success
	public function success($msg, $switch = "")
	{
		if(!empty($msg))
		{
			$message = '<div class="alert alert-success pihype-alert" id="">'.ucfirst($msg).'</div>';
			MessageAddon::$message = $message;
			MessageAddon::$messageCode = 1;
			$_SESSION['remove_log'] = true;

			if($switch != "")
			{
				MessageAddon::$switch = 2;
			}	
		}
		
	}

	// when it is a warning
	public function warning($msg, $switch = "")
	{
		if(!empty($msg))
		{
			$message = '<div class="alert alert-warning pihype-alert" id="">'.ucfirst($msg).'</div>';
			MessageAddon::$message = $message;
			MessageAddon::$messageCode = 2;

			if($switch != "")
			{
				MessageAddon::$switch = 2;
			}
		}
	}

	
	//display message
	public function out($for = "")
	{	
		if(MessageAddon::$switch == 2)
		{
			$session_used = false;
			$message = empty(MessageAddon::$message) ? isset($_SESSION['message_out']) ? $_SESSION['message_out'] : '' : MessageAddon::$message;
			
			$code = empty(MessageAddon::$messageCode) ? isset($_SESSION['message_out_code']) ? $_SESSION['message_out_code'] : '' : MessageAddon::$messageCode;

			if(isset($_SESSION['message_out_code']))
			{
				unset($_SESSION['message_out'], $_SESSION['message_out_code']);
			}

			$color = "";
			$body = "";

			if($code == 1){$color = "#00FF00"; 
			$body = '<span class="alert-wrapper pull-left"><img src="'.$this->image->load('icons/success.png').'"/>
			<h1>Success !</h1>
			'.$message.'</span> <span class="pull-right">
			<i class="hide-alert" onclick="hide_alert(\'pihype_alert\')">&times;</i></span> <div class="clearfix"></div>';}

			elseif($code == 2){$color = "#FFCC00";
			$body = '<span class="alert-wrapper pull-left"><img src="'.$this->image->load('icons/warning-icon.png').'"/>
			<h1>Warning !</h1>
			'.$message.'</span> <span class="pull-right">
			<i class="hide-alert" onclick="hide_alert(\'pihype_alert\')">&times;</i></span> <div class="clearfix"></div>';}

			elseif($code == 3){$color = "#FF9494";
			$body = '<span class="alert-wrapper pull-left"><img src="'.$this->image->load('icons/error-icon.png').'"/>
			<h1>Error !</h1>
			'.$message.'</span> <span class="pull-right">
			<i class="hide-alert" onclick="hide_alert(\'pihype_alert\')">&times;</i></span> <div class="clearfix"></div>';}

			$output = '<div id="pihype_alert" style="background:'.$color.'">'.$body.'</div>';

			$obj =  ["message" => $output, "code" => $code];

			return (object) $obj;
		}
		else
		{
			return (object) ['message' => ''];
		}
		
	}

	public function model_out()
	{
		if(MessageAddon::$switch == 1)
		{
			$session_used = false;
			$message = empty(MessageAddon::$message) ? isset($_SESSION['message_out']) ? $_SESSION['message_out'] : '' : MessageAddon::$message;
			
			$code = empty(MessageAddon::$messageCode) ? isset($_SESSION['message_out_code']) ? $_SESSION['message_out_code'] : '' : MessageAddon::$messageCode;

			if(isset($_SESSION['message_out_code']))
			{
				unset($_SESSION['message_out'], $_SESSION['message_out_code']);
			}

			$color = "";
			$body = "";

			if($code == 1){$color = "#00FF00"; 
			$body = '<span class="alert-wrapper pull-left"><img src="'.$this->image->load('icons/success.png').'"/>
			<h1>Success !</h1>
			'.$message.'</span> <span class="pull-right">
			<i class="hide-alert" onclick="hide_alert(\'defult_message\')">&times;</i></span> <div class="clearfix"></div>';}

			elseif($code == 2){$color = "#FFCC00";
			$body = '<span class="alert-wrapper pull-left"><img src="'.$this->image->load('icons/warning-icon.png').'"/>
			<h1>Warning !</h1>
			'.$message.'</span> <span class="pull-right">
			<i class="hide-alert" onclick="hide_alert(\'defult_message\')">&times;</i></span> <div class="clearfix"></div>';}

			elseif($code == 3){$color = "#f20";
			$body = '<span class="alert-wrapper pull-left"><img src="'.$this->image->load('icons/error-icon.png').'"/>
			<h1>Error !</h1>
			'.$message.'</span> <span class="pull-right">
			<i class="hide-alert" onclick="hide_alert(\'defult_message\')">&times;</i></span> <div class="clearfix"></div>';}

			echo '<div id="defult_message" style="background:'.$color.'">'.$body.'</div>';			
		}
	}
}