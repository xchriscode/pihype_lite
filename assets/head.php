<!DOCTYPE html>
<html>
<head>
	<title><?=$packager->title?></title>
	<meta charset="utf-8">
	<meta name="author" content="<?=$packager->author?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="keywords" content="<?=$packager->keywords?>">
	<meta name="description" content="<?=$packager->description?>">
	<!-- load css -->
	<?=$packager->load->app_css()?>

</head>
<body>
<div class="well">
<h1>Pihype Blog</h1>
<ul class="lists">
	<li><a class="btn btn-primary" href="<?=$Url->set('home/post')?>">Post New</a></li>
</ul>
</div>