<?php
class Startup
{
	public function home()
	{
		$app = $this->app;
		$app->render("welcome_to_pihype");
	}
}