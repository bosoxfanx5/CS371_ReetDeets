<!--
Host: ec2-54-163-246-165.compute-1.amazonaws.com
Database: de0qfpfe2sp27l
User: kjufgxkwzbdxoe
Port: 5432
Password: 7df3e724097d356a12363ec6ff37de41a1dce21c3c4767b88d5d7de61086d5df
URI: postgres://kjufgxkwzbdxoe:7df3e724097d356a12363ec6ff37de41a1dce21c3c4767b88d5d7de61086d5df@ec2-54-163-246-165.compute-1.amazonaws.com:5432/de0qfpfe2sp27l
Heroku CLI: heroku pg:psql postgresql-cubic-94519 --app rocky-everglades-86262-->

<?php
include 'session.php';
include 'dbconnect.php';
include 'authenticate.php';

$welcome = true;
$userFound = true;
$_SESSION["loggedIn"] = false;
$_SESSION["fname"] = "";


if (isset($_REQUEST["logout"]) && $_REQUEST["logout"] == true) {
	session_unset($_SESSION["id"]);
	session_unset($_SESSION["email"]);
	session_destroy();
	header( 'Location: https://mysterious-bayou-55662.herokuapp.com' );
	die();
}

if ($_SERVER['REQUEST_METHOD'] == 'GET') {

	$sql0 = $db->prepare("SELECT id, title FROM s_saleable_item");
	$sql0->execute();
	$result0 = $sql0->fetchAll(PDO::FETCH_ASSOC);

	if (!empty($_GET['id'])) {
		$isContent = true;
		$welcome = false;
		$sql = $db->prepare("SELECT * FROM s_saleable_item
			WHERE id = :id");
			$sql->execute(array(":id" => $_GET['id']));
			$result = $sql->fetch(PDO::FETCH_ASSOC);
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

	<title>Front-end UI</title>

	<!-- Custom styles for this template -->
	<link href="css/sticky-footer-navbar.css" rel="stylesheet">
	<link rel="stylesheet" href="css/index.css">
	<link rel="stylesheet" href="css/login.css">
	<!-- <link rel="stylesheet" href="login.css"> -->
</head>

<body>
	<!--
	_/    _/  _/_/_/_/    _/_/    _/_/_/    _/_/_/_/  _/_/_/
	_/    _/  _/        _/    _/  _/    _/  _/        _/    _/
	_/_/_/_/  _/_/_/    _/_/_/_/  _/    _/  _/_/_/    _/_/_/
	_/    _/  _/        _/    _/  _/    _/  _/        _/    _/
	_/    _/  _/_/_/_/  _/    _/  _/_/_/    _/_/_/_/  _/    _/
-->


<!-- Fixed navbar -->
  <div class="jumbotron">
    <div class="container">
      <div class="row">
        <div class="col-xs-10 col-xs-offset-1 text-center">
          <span><h1>RD|Reet Deets</h1></span>
					<?php //echo $_SESSION["fname"] . " " . $_SESSION["loggedIn"]; ?>
					<?php if(isset($_SESSION["email"])) : ?>
						<div class="row">
							<div class="col-xs-12 text-center">
								<p><?php echo "Welcome " .  $_SESSION["email"]; ?> </p>
							</div>
						</div>
					<?php endif ?>
        </div>
      </div>
    </div>
  </div>

<!--
_/_/_/      _/_/    _/_/_/    _/      _/
_/    _/  _/    _/  _/    _/    _/  _/
_/_/_/    _/    _/  _/    _/      _/
_/    _/  _/    _/  _/    _/      _/
_/_/_/      _/_/    _/_/_/        _/
-->
<!-- Begin page content -->
	<div class="container">
		<div class="row">
      <div class="col-xs-6 col-xs-offset-3">
			   <img class="img-responsive" src="img/barcode_sm.png">
       </div>
		</div>
  </div>

  <div class="container">
    <div class="row">
      <div class="col-xs-10 col-xs-offset-1">
        <form class="form-signin" method="GET" action="product.php">
          <input type="text" class="form-control" name="barcode" placeholder="Enter the Barcode ID" required>
      </div>
    </div>
    <br>
    <div class="row text-center">
      <div class="col-xs-4 col-xs-offset-4">
        <input class="btn btn-success btn-lg" type="submit">
      </form>
      </div>
    </div>
  </div>

	<?php if(empty($_SESSION["email"])) : ?>
  	<div class="container">
    	<div class="row text-center">
      	<div class="col-xs-6">
        	<button class="btn btn-warning btn-lg" id="loginBtn">Login</button>
      	</div>
      	<div class="col-xs-6">
          <button class="btn btn-primary btn-lg" id="createNew">Sign Up</button>
      	</div>
    	</div>
  	</div>
  	<br>
	<?php elseif (isset($_SESSION["email"])) : ?>
		<div class="container">
    	<div class="row text-center">
      	<div class="col-xs-4 col-xs-offset-4">
        	<a class="btn btn-warning btn-lg" id="loginBtn" href="https://mysterious-bayou-55662.herokuapp.com?logout=true">Logout</a>
      	</div>
    	</div>
  	</div>
  	<br>
	<?php endif ?>



	<div id="creation" class="overlay-creation">
		<div class="overlay-content">
			<div class="wrapper-creation">
				<div class="form-group">
				<form class="form-signin" method="POST" action="">
					<h2>You want to join? Sweet!</h2>
					<p>Fill out the form and click submit.</p>
					<input type="text" class="form-control" name="fname" placeholder="First Name" required>
					<br>
					<input type="text" class="form-control" name="lname" placeholder="Last Name" required>
					<br>
					<input type="text" class="form-control" name="createEmail" placeholder="Email Address" required>
					<br>
					<input type="password" class="form-control" name="createPassword" placeholder="Password" required>
					<button id="submitCreate" class="btn btn-success btn-lg" type="submit">Submit</button>
					<a href="javascript:void(0)" id="closeCreate" class="closebtn">&times;</a>
				</form>
				</div>
			</div>
		</div>
	</div>


	<div id="login" class="overlay">
		<div class="overlay-content">
			<div class="wrapper">
				<form class="form-signin" method="POST" action="">
					<h2 class="form-signin-heading">Please login</h2>
					<input type="text" class="form-control" name="email" placeholder="Email Address" required>
					<br>
					<input type="password" class="form-control" name="password" placeholder="Password" required>
					<br>
					<button class="btn btn-success btn-lg" type="submit">Login</button>
					<a href="#" id="forgot">Forgot Password</a>
					<a href="javascript:void(0)" id="closeLogin" class="closebtn">&times;</a>
					<?php if (!$userFound) {
						echo "<br><br><p id='loginError'>*Email address and/or password is incorrect.</p>";
					}
					?>
				</form>
			</div>
		</div>
	</div>

	<br><br>

<!--
_/_/_/_/    _/_/      _/_/    _/_/_/_/_/  _/_/_/_/  _/_/_/
_/        _/    _/  _/    _/      _/      _/        _/    _/
_/_/_/    _/    _/  _/    _/      _/      _/_/_/    _/_/_/
_/        _/    _/  _/    _/      _/      _/        _/    _/
_/          _/_/      _/_/        _/      _/_/_/_/  _/    _/
-->
<!-- Begin footer content -->
<footer class="footer">
	<div>
		<!-- Brand and toggle get grouped for better mobile display -->
		<!-- <div class="navbar-header"> -->
			<!-- Left Side -->
			<!-- <div class="btn-group"> -->
				<!-- <ul class="nav navbar-nav navbar-left text-center">
					&nbsp; -->
					<p style="color:white">Copyright 2017 Brooks Robison, All Rights Reserved</p>
				<!-- </ul> -->
			<!-- </div> -->
		<!-- </div> -->
		<!-- Center -->
		<!-- <div class="navbar-center navbar-brand" href="#"><a class="navbar-brand"></a></div> -->
		<!-- Collect the nav links, forms, and other content for toggling -->

	</div>
</footer>


<!-- Bootstrap core JavaScript -->
<!-- ================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<!-- Latest compiled and minified JavaScript -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
<script src="js/index.js"></script>
<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<!-- <script src="../../assets/js/ie10-viewport-bug-workaround.js"></script> -->
</body>
</html>
