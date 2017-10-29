<?php
if(isset($_GET['qr']) && isset($_SESSION['post.json']))
{
	if(isset($_SESSION['remove_log']))
	{
		unset($_SESSION['post.json'], $_SESSION['remove_log']);
	}
}