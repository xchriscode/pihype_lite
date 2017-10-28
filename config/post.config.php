<?php
if(isset($_GET['qr']) && isset($_SESSION['post.json']))
{
	unset($_SESSION['post.json']);
}