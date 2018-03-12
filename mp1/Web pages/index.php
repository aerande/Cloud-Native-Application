<?php session_start(); ?>

<!DOCTYPE html>
<html>
	<head>
		<title>Index Page</title>
		<meta charset="utf-8">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
		<style>
			body {
				background-image: url("aws_back.jpg");
				background-repeat: no-repeat;
			}
			.first_div{
				position: relative;
				height: 190px;
				text-align: center;
				padding-top: 70px;
			}
			.second_div{
				padding-bottom: 30px;
				text-align: center;
			}
			a{
				margin-left: 65px;
				margin-right: 65px;
			}
		</style>
	</head>
	<body>
		<div class="first_div">
			<h1>Welcome to the cloud-native application!</h1>
		</div>
		<div class="second_div">
			<a href="index.php" class="btn btn-primary">Home</a>
			<a href="gallery.php" class="btn btn-primary">Gallery</a>
			<a href="submit.php" class="btn btn-primary">Submit</a>
		</div>
	</body>
</html>