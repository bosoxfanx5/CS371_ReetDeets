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


$welcome = true;
$error = "";
$_SESSION["loggedIn"] = false;
$_SESSION["fname"] = "";


if (isset($_REQUEST["logout"]) && $_REQUEST["logout"] == true) {
	session_unset($_SESSION["id"]);
	session_unset($_SESSION["email"]);
   session_unset($_SESSION["previousEnabled"]);
   session_unset($_SESSION["nextEnabled"]);
   session_unset($_SESSION["index"]);
   session_unset($_SESSION["max"]);
   session_unset($_SESSION["codes"]);
	session_unset($_SESSION["error"]);
	session_destroy();
	header( 'Location: https://mysterious-bayou-55662.herokuapp.com' );
	die();
}

if(isset($_REQUEST["isError"])) {
	$error = '<p style="color:red">Please Enter Valid Barcode</p>';
}

include 'dbconnect.php';
$userFound = true;

echo '<script type="text/javascript"> alert("script works"); </script>';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	echo '<script type="text/javascript"> alert("GOT POST"); </script>';
	if (!empty($_POST["email"]) && !empty($_POST["password"])) {
		echo '<script type="text/javascript"> alert("got post data"); </script>';
		$personEmail = $_POST["email"];
		// query for email and password of user
		$sql0 = $db->prepare("SELECT id, fname, email, psswd FROM s_person WHERE email='$personEmail'");
		$sql0->execute();
		$result = $sql0->fetch();
		echo '<script type="text/javascript"> alert("login POST hit"); </script>';
		//$email = $result["email"];
		// echo $email;
		// echo "awesome";
		// authenticate user provided info with database
		$authenticated = password_verify($_POST["password"], $result['psswd']);

		if (($result["email"] == $personEmail) && $authenticated) {
			$_SESSION["loggedIn"] = true;
			$_SESSION["id"] = $result["id"];
			$_SESSION["email"] = $result["email"];
			$_SESSION["fname"] = $result["fname"];
			$userFound = true;
			echo '<script type="text/javascript"> alert("authenticated"); </script>';
			header( 'Location: https://mysterious-bayou-55662.herokuapp.com' );
			die();
		} else {
			echo '<script type="text/javascript"> alert("not authenticated"); </script>';
			$userFound = false;
			$_SESSION["userFound"] = false;
			header( 'Location: https://mysterious-bayou-55662.herokuapp.com' );
		}
		echo '<script type="text/javascript"> alert("post data IF exit"); </script>';
	}
	echo '<script type="text/javascript"> alert("post request IF exit"); </script>';
/******************************************************************
* Creation of new login account
*******************************************************************/


	if (!empty($_POST["fname"]) && !empty($_POST["lname"]) && !empty($_POST["createEmail"])
	&& !empty($_POST["createPassword"])) {
		$fname = $_POST['fname'];
		$lname = $_POST['lname'];
		$_SESSION["fname"] = $fname;


		// if ($gender == 1) {
		// 	$prefix = "Mr.";
		// } else {
		// 	$prefix = "Ms.";
		// }

		$cEmail = $_POST['createEmail'];
		$cPassword = $_POST['createPassword'];

		//hash the password
		$hashed = password_hash($cPassword, PASSWORD_DEFAULT);

		// if user already has a session id and is creating a new login
		if (!empty($_SESSION["id"])) {
			$personID = $_SESSION["id"];
			$sql = $db->prepare("UPDATE s_person SET fname='$fname', lname='$lname',
			email='$cEmail', psswd='$hashed' WHERE id='$personID'");

			$sql->execute();
			$_SESSION['email'] = $cEmail;
			$userFound = true;
			$_SESSION["loggedIn"] = true;
			header( 'Location: https://mysterious-bayou-55662.herokuapp.com' );
			die();

		} else {
			// if there isn't a session id for the user yet
			$sql = $db->prepare("INSERT INTO s_person (fname, lname, email, psswd)
			VALUES ('$fname','$lname','$cEmail','$hashed')");

			$sql->execute();
			$_SESSION['email'] = $cEmail;
			$sql = $db->prepare("SELECT id FROM s_person WHERE email='$cEmail'");
			$sql->execute();
			$result2 = $sql->fetch();

			$_SESSION["id"] = $result2['id'];
			$userFound = true;
			$_SESSION["loggedIn"] = true;
			header( 'Location: https://mysterious-bayou-55662.herokuapp.com' );
			die();
		}
	}
}
$database = null;
?>


<!DOCTYPE html>
<html lang="en">
	<?php include 'header.php'; ?>
	<link href="css/sticky-footer-navbar.css" rel="stylesheet">
<body>
	<!--
	_/    _/  _/_/_/_/    _/_/    _/_/_/    _/_/_/_/  _/_/_/
	_/    _/  _/        _/    _/  _/    _/  _/        _/    _/
	_/_/_/_/  _/_/_/    _/_/_/_/  _/    _/  _/_/_/    _/_/_/
	_/    _/  _/        _/    _/  _/    _/  _/        _/    _/
	_/    _/  _/_/_/_/  _/    _/  _/_/_/    _/_/_/_/  _/    _/
-->



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
      	<div class="col-xs-4 col-xs-offset-4">
			   <img class="img-responsive logo" src="img/barcode_smLogo.png">
			</div>
		</div>
  </div>



  <div class="container">
    <div class="row">
      <div class="col-xs-10 col-xs-offset-1">
        	<form class="form-signin" method="GET" action="product.php">
			  	<?php echo $error; ?>
				<?php if(isset($_SESSION["userFound"]) && $_SESSION["userFound"] == false) : ?>
					<p class="text-center" id='loginError'>*Email address and/or password is incorrect.</p>
					<?php session_unset($_SESSION["userFound"]); ?>
			  	<? endif ?>
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
        		<a class="btn btn-warning btn-lg" href="https://mysterious-bayou-55662.herokuapp.com?logout=true">Logout</a>
      	</div>
    	</div>
  	</div>
  	<br>
	<?php endif ?>

	<div id="creation" class="overlay-creation">
		<div class="overlay-content">
			<div class="wrapper-creation">
				<div class="form-group">
				<form class="form-signin" method="POST" action="index.php">
					<h2>You want to join? Sweet!</h2>
					<p>Fill out the form and click submit.</p>
					<input type="text" class="form-control" name="fname" placeholder="First Name" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" required>
					<br>
					<input type="text" class="form-control" name="lname" placeholder="Last Name" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" required>
					<br>
					<input type="text" class="form-control" name="createEmail" placeholder="Email Address" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" required>
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
				<form class="form-signin" method="POST" action="index.php">
					<h2 class="form-signin-heading">Please login</h2>
					<input type="text" class="form-control" name="email" placeholder="Email Address" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" required>
					<br>
					<input type="password" class="form-control" name="password" placeholder="Password" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" required>
					<br>
					<button class="btn btn-success btn-lg" type="submit">Login</button>
					<a href="javascript:void(0)" id="closeLogin" class="closebtn">&times;</a>
				</form>
				<?php if (!$userFound) {
							echo "<br><br><p id='loginError'>*Email address and/or password is incorrect.</p>";
						}
				?>
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
