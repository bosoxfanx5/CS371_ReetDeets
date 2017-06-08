<?php
	include 'session.php';
	include 'dbconnect.php';
	$confirmation = "";
	$success;
	$showForm = true;
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		if (isset($_GET["id"])) {
			if (isset($_POST["newPass"])) {
				$qry = $db->prepare("SELECT prefix, lname FROM s_person WHERE id='".$_GET["id"]."'");
				$qry->execute();
				$data = $qry->fetch();

				$newPass = $_POST["newPass"];
				$hashed = password_hash($newPass, PASSWORD_DEFAULT);

				$qry = $db->prepare("UPDATE s_person SET psswd='$hashed' WHERE id='".$_GET["id"]."'");
				$qry->execute();
				$success = true;

				if ($success) {
					$confirmation = '<p class="alert alert-success">Password was reset successfully!</p>';
					$showForm = false;
				} else {
					$confirmation = '<p class="alert alert-danger">There was a problem resetting your password. Please try again.</p>';
				}
			}
		}
	}
$database = null;
?>




<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
	<meta name="description" content="Front-end UI">
	<meta name="author" content="Brooks Robison">
	<link rel="icon" href="/favicon.ico">

	<title>Password Reset</title>

	<!-- Custom styles for this template -->
	<link href="sticky-footer-navbar.css" rel="stylesheet">
	<link rel="stylesheet" href="css/index.css">
	<link rel="stylesheet" href="css/login.css">
</head>

<body>
	<!-- Fixed navbar -->
	<nav class="navbar navbar-default" role="navigation">
		<!-- Brand and toggle get grouped for better mobile display -->
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-collapse-1">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			&nbsp;
			<!-- Left Side -->
			<div class="btn-group">
				<ul class="nav navbar-nav navbar-left">
					<li><a href="mobile.php"><img id="logo" src="rd.png"></a></li>
				</ul>
			</div>
		</div>

		<!-- Center -->
		<div class="navbar-center navbar-brand" href="#"><a class="navbar-brand"></a></div>
		<!-- Collect the nav links, forms, and other content for toggling -->

		<!-- Right Side -->
		<div class="collapse navbar-collapse" id="navbar-collapse-1">
			<ul class="nav navbar-nav navbar-right">
				<li><a href="mobile.php">Product View</a></li>
				<li><a href="login.php">Login</a></li>
			</ul>
		</div>
	</nav>
	<?php if($showForm) : ?>
	<div class="wrapper">
		<form class="form-signin" method="POST" action="">
			<h2 class="form-signin-heading">What's your new password?</h2>
			<br>
			<input type="password" class="form-control" name="newPass" placeholder="Password" required>
			<br>
			<button class="btn btn-success" id="subNew" type="submit">Submit</button>
		</form>
	</div>
	<?php endif ?>
	<?php echo $confirmation; ?>

	<!-- Begin footer content -->
	<footer class="footer">
		<nav class="navbar navbar-default">
			<!-- Brand and toggle get grouped for better mobile display -->
			<div class="navbar-header">
				<!-- Left Side -->
				<div class="btn-group">
					<ul class="nav navbar-nav navbar-left">
						&nbsp;
						<li><a href="#">Copyright 2017 Brooks Robison, All Rights Reserved</a></li>
					</ul>
				</div>
			</div>
			<!-- Center -->
			<div class="navbar-center navbar-brand" href="#"><a class="navbar-brand"></a></div>
			<!--Collect the nav links, forms, and other content for toggling-->
		</nav>
	</footer>
</body>

<!-- Bootstrap core JavaScript -->
<!--================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<!-- Latest compiled and minified JavaScript -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
<script src="login.js"></script>
<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<!-- <script src="../../assets/js/ie10-viewport-bug-workaround.js"></script> -->

</html>
