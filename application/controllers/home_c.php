<?php

class Home
{
    public $un = "chris";

	public function index()
	{
		// IMPORT Modules
		// $mysqli = $this->mysqli;
		// $pdo = $this->pdo;
		$app = $this->app;
		$model = $this->model;
		$addon = $this->addon;
		$get = $this->get;
		$log = $this->message;

		$addon->mail->send();
		$req = $get->get('qre');

		//$mysqli->verb("get/users/1");
		//$mysqli->verb("get/users/-w userid = 1");
		//$mysqli->verb("put/users/","username:'mack',password:'1234'");
		//$mysqli->verb("delete/users/2");
		//$mysqli->verb("delete/users/-w userid = 1");
		//$mysqli->verb("post/users/1","username:'paul'");
		//$mysqli->verb("post/users/-w userid = 1","username:'paul'");

		//$pdo->verb("get/users/1");
		//$pdo->verb("put/users/","username:'sule'");

		$log->success("You just accessed our page");

		if($this->post)
		{
			$login = $model->login('auth', $this->post);
			if($login == "chris")
			{
				echo "post data: $login";	
				$app->renderNew("about");
			}
			else
			{
				$app->render("index");
			}
			
		}
		else
		{
			$app->render("index");
		}


		//$this->db->query("hello");

		//$this->app->send($login)->render("index");
	}
}