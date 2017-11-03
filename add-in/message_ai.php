<?php
class Message
{
	private static $message = [];
	public static $messageCode = []; //1 = success, 2 = warning, 3 = error
	public static $switch = 0;

	// when there is an error
	public function error($msg, $switch = "")
	{
		if(!empty($msg))
		{
			if(!empty($switch)){Message::$switch = 1;}
			$message = '<div class="alert alert-danger pihype-alert" id="">'.ucfirst($msg).'</div>';
			Message::$message[] = $message;
			Message::$messageCode[] = 3;

			if($switch != "")
			{
				Message::$switch = 2;
			}	
		}
	}

	// when it is a success
	public function success($msg, $switch = "")
	{
		if(!empty($msg))
		{
			if(!empty($switch)){Message::$switch = 1;}
			$message = '<div class="alert alert-success pihype-alert" id="">'.ucfirst($msg).'</div>';
			Message::$message[] = $message;
			Message::$messageCode[] = 1;
			$_SESSION['remove_log'] = true;

			if($switch != "")
			{
				Message::$switch = 2;
			}	
		}
		
	}

	// when it is a warning
	public function warning($msg, $switch = "")
	{
		if(!empty($msg))
		{
			if(!empty($switch)){Message::$switch = 1;}
			$message = '<div class="alert alert-warning pihype-alert" id="">'.ucfirst($msg).'</div>';
			Message::$message[] = $message;
			Message::$messageCode[] = 2;

			if($switch != "")
			{
				Message::$switch = 2;
			}
		}
	}

	
	//display message
	public function out($for = "")
	{	
		if(Message::$switch == 2)
		{
			$session_used = false;
			$message = empty(Message::$message) ? isset($_SESSION['message_out']) ? $_SESSION['message_out'] : '' : Message::$message;
			
			$code = empty(Message::$messageCode) ? isset($_SESSION['message_out_code']) ? $_SESSION['message_out_code'] : '' : Message::$messageCode;

			if(isset($_SESSION['message_out_code']))
			{
				unset($_SESSION['message_out'], $_SESSION['message_out_code']);
			}

			$id = 0;

			if(is_array($message) and count($message) > 0)
			{
				$obj = [];

				foreach($message as $k => $report)
				{
					$color = "";
					$body = "";

					if($code[$k] == 1){$color = "#00FF00"; 
					$body = '<span class="alert-wrapper pull-left"><img src="'.$this->image->load('icons/success.png').'"/>
					<h1>Success !</h1>
					'.$report.'</span> <span class="pull-right">
					<i class="hide-alert" onclick="hide_alert('.$id.')">&times;</i></span> <div class="clearfix"></div>';}

					elseif($code[$k] == 2){$color = "#FFCC00";
					$body = '<span class="alert-wrapper pull-left"><img src="'.$this->image->load('icons/warning-icon.png').'"/>
					<h1>Warning !</h1>
					'.$report.'</span> <span class="pull-right">
					<i class="hide-alert" onclick="hide_alert('.$id.')">&times;</i></span> <div class="clearfix"></div>';}

					elseif($code[$k] == 3){$color = "#FF9494";
					$body = '<span class="alert-wrapper pull-left"><img src="'.$this->image->load('icons/error-icon.png').'"/>
					<h1>Error !</h1>
					'.$report.'</span> <span class="pull-right">
					<i class="hide-alert" onclick="hide_alert('.$id.')">&times;</i></span> <div class="clearfix"></div>';}

					$output = '<div id="pihype_alert" class="alert'.$id.' pihype_alert"  style="background:'.$color.'">'.$body.'</div>';

					$obj[] =  ["message" => $output];
					$id++;

				}

				return (object) $obj;
			}
		}
		else
		{
			return (object) ['message' => ''];
		}
		
	}

	public function model_out()
	{
		if(Message::$switch == 1)
		{
			$session_used = false;

			$message = empty(Message::$message) ? isset($_SESSION['message_out']) ? $_SESSION['message_out'] : '' : Message::$message;
			
			$code = empty(Message::$messageCode) ? isset($_SESSION['message_out_code']) ? $_SESSION['message_out_code'] : '' : Message::$messageCode;

			if(isset($_SESSION['message_out_code']))
			{
				unset($_SESSION['message_out'], $_SESSION['message_out_code']);
			}

			$id = 0;

			if(is_array($message) and count($message) > 0)
			{
				foreach($message as $k => $report)
				{
					$color = "";
					$body = "";

					if($code[$k] == 1){$color = "#00FF00"; 
					$body = '<span class="alert-wrapper pull-left"><img src="'.$this->image->load('icons/success.png').'"/>
					<h1>Success !</h1>
					'.$report.'</span> <span class="pull-right">
					<i class="hide-alert" onclick="hide_alert('.$id.')">&times;</i></span> <div class="clearfix"></div>';}

					elseif($code[$k] == 2){$color = "#FFCC00";
					$body = '<span class="alert-wrapper pull-left"><img src="'.$this->image->load('icons/warning-icon.png').'"/>
					<h1>Warning !</h1>
					'.$report.'</span> <span class="pull-right">
					<i class="hide-alert" onclick="hide_alert('.$id.')">&times;</i></span> <div class="clearfix"></div>';}

					elseif($code[$k] == 3){$color = "#FF9494";
					$body = '<span class="alert-wrapper pull-left"><img src="'.$this->image->load('icons/error-icon.png').'"/>
					<h1>Error !</h1>
					'.$report.'</span> <span class="pull-right">
					<i class="hide-alert" onclick="hide_alert('.$id.')">&times;</i></span> <div class="clearfix"></div>';}

					echo '<div id="defult_message" class="alert'.$id.' pihype_alert" style="background:'.$color.'">'.$body.'</div>';
					$id++;
				}
			}

						
		}

	}
}