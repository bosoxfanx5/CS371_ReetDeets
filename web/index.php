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

$welcome = true;


if (isset($_REQUEST["logout"]) && $_REQUEST["logout"] == true) {
	session_unset($_SESSION["id"]);
	session_destroy();
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
//check session for visitor id


if (empty($_SESSION["id"])) {
	$sql1 = $db->prepare("INSERT INTO s_person (id) VALUES (uuid_generate_v4())");
	$sql1->execute();

	// 	//retrieve new person id
	$personID = $db->lastInsertId();

	$sql1 = $db->prepare("SELECT id FROM s_person WHERE autoinc='$personID'");
	$sql1->execute();
	$result1 = $sql1->fetch();
	$_SESSION["id"] = $result1["id"];
	//echo $result1["id"];
}


// //retrieve item id
// if (!empty($_GET['id'])) {
// 	$isContent = true;
// 	$sql = $db->prepare("SELECT * FROM s_saleable_item
// 		WHERE id = :id");
// 		$sql->execute(array(":id" => $_GET['id']));
// 		$result2 = $sql->fetch(PDO::FETCH_ASSOC);
// 		$itemID = $result2["id"];
//
// 		$personID = $_SESSION["id"];
// 		$sql1 = $db->prepare("INSERT INTO s_visited_items (visitor_id, item_id) VALUES ('$personID', '$itemID')");
// 		$sql1->execute();
// }
	$personID = "";
	$userFound = true;
	$validEmail = true;
	$emailSent = false;
	$confirmation = "";

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {

		/******************************************************************
		* User logging in authentication
		*******************************************************************/

		if (!empty($_POST["email"]) && !empty($_POST["password"])) {

			$personEmail = $_POST["email"];
			// query for email and password of user
			$sql0 = $db->prepare("SELECT id, email, psswd FROM s_person WHERE email='$personEmail'");
			$sql0->execute();
			$result = $sql0->fetch();

			// authenticate user provided info with database
			$authenticated = password_verify($_POST["password"], $result['psswd']);

			if ($result["email"] == $personEmail && $authenticated) {
				$_SESSION["loggedIn"] = true;
				$_SESSION["id"] = $result["id"];
				$_SESSION["email"] = $result["email"];
				$userFound = true;
				header( 'Location: https://mysterious-bayou-55662.herokuapp.com/Project/mobile.php' );
				die();
			} else {
				$userFound = false;
			}
		}

		/******************************************************************
		* Creation of new login account
		*******************************************************************/


		if (!empty($_POST["fname"]) && !empty($_POST["lname"]) && !empty($_POST["createEmail"])
		&& !empty($_POST["createPassword"])) {
			$fname = $_POST['fname'];
			$lname = $_POST['lname'];
			$gender = (int)$_POST['gender'];

			if ($gender == 1) {
				$prefix = "Mr.";
			} else {
				$prefix = "Ms.";
			}

			$cEmail = $_POST['createEmail'];
			$cPassword = $_POST['createPassword'];

			//hash the password
			$hashed = password_hash($cPassword, PASSWORD_DEFAULT);

			// if user already has a session id and is creating a new login
			if (!empty($_SESSION["id"])) {
				$personID = $_SESSION["id"];
				$sql = $db->prepare("UPDATE s_person SET fname='$fname', lname='$lname', prefix='$prefix', gender=$gender,
					email='$cEmail', psswd='$hashed' WHERE id='$personID'");

				$sql->execute();
				$_SESSION['email'] = $cEmail;
				$_SESSION["loggedIn"] = true;
				header( 'Location: https://mysterious-bayou-55662.herokuapp.com/Project/mobile.php' );
				die();

				} else {
					// if there isn't a session id for the user yet
					$sql = $db->prepare("INSERT INTO s_person (fname, lname, prefix, gender, email, psswd)
					VALUES ('$fname', '$lname','$prefix', $gender, '$cEmail', '$hashed')");

					$sql->execute();
					$_SESSION['email'] = $cEmail;
					$sql = $db->prepare("SELECT id FROM s_person WHERE email='$cEmail'");
					$sql->execute();
					$result2 = $sql->fetch();

					$_SESSION["id"] = $result2['id'];
					$_SESSION["loggedIn"] = true;
					header( 'Location: https://mysterious-bayou-55662.herokuapp.com/Project/mobile.php' );
					die();
				}
			}

		/******************************************************************
		* Forgot Password - My attempt at emailing a reset password link to the user.
		* This would work if Heroku allowed sending emails. I attempted to set up
		* the MailGun I installed using a domain that I own. However, for this Project
		* I didn't want to pay the monthly fee to have the email service through GoDaddy.
		*******************************************************************/
		$emailAttempt = false; //set this so that the code below doesn't ever run
		if ($emailAttempt) {
			$fEmail = $_POST["forgotEmail"];
			$qry = $db->prepare("SELECT id, prefix, lname, email FROM s_person WHERE email='$fEmail'");
			$qry->execute();
			$data = $qry->fetch();
			echo "Database: " . $data["email"];
			echo "Input: " . $_POST["forgotEmail"];
			if ($_POST["forgotEmail"] == $data["email"]) {

				$url = 'https://mysterious-bayou-55662.herokuapp.com/Project/reset_password.php?id=' . $data["id"]; //not sure how to construct this with security in mind

				$to = $data["email"];
				$subject = 'Reet Deets - Forgot Password';
				$message =  "Hello " . $data["prefix"] . " " . $data["lname"] . ", <br><br> Someone has requested a to reset your password. If this
				was not you, please ignore this email. If this was you who requested a password reset, please follow this link below:<br><br>" .
				$url . "<br><br>Thank you,<br>Your ReetDeets Team";

				// To send HTML mail, the Content-type header must be set
				$headers  = 'MIME-Version: 1.0' . "\r\n";
				$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

				// Add from to the header
				$headers .= 'From: Reet Deets Team <info@reetdeets.com>' . "\r\n";

				// Mail it
				if(mail($to, $subject, $message, $headers)) {
					// Message sent successfully
					$confirmation = '<p class="alert alert-success">Your message was sent successfully!</p>';
				} else {
					// Message was not successful
					$confirmation = '<p class="alert alert-danger">There was a problem sending your message. Please try again.</p>';
				}

			}
		}

		if (!empty($_POST["forgotEmail"])) {
			$fEmail = $_POST["forgotEmail"];
			$qry = $db->prepare("SELECT id, prefix, lname, email FROM s_person WHERE email='$fEmail'");
			$qry->execute();
			$data = $qry->fetch();

			if ($_POST["forgotEmail"] == $data["email"]) {
				header('Location: https://mysterious-bayou-55662.herokuapp.com/Project/reset_password.php?id=' . $data["id"]);
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

	<title>Front-end UI</title>

	<!-- Custom styles for this template -->
	<link href="css/sticky-footer-navbar.css" rel="stylesheet">
	<link rel="stylesheet" href="css/index.css">
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
			   <img class="img-responsive" src="img/barcode.gif">
       </div>
		</div>
  </div>

  <div class="container">
    <div class="row">
      <div class="col-xs-10 col-xs-offset-1">
        <form class="form-signin" method="POST" action="">
          <input type="text" class="form-control" name="barcode" placeholder="Enter the Barcode ID" required>
      </div>
    </div>
    <br>
    <div class="row text-center">
      <div class="col-xs-4 col-xs-offset-4">
        <button class="btn btn-success btn-lg" type="submit" type="submit">Submit</button>
      </form>
      </div>
    </div>
  </div>


  <div class="container">
    <div class="row text-center">
      <div class="col-xs-6">
        <button class="btn btn-warning btn-lg" id="login">Login</button>
      </div>
      <div class="col-xs-6">
          <button class="btn btn-primary btn-lg" id="createNew">Sign Up</button>
      </div>
    </div>
  </div>
  <br>

	<div id="creation" class="overlay">
		<a href="javascript:void(0)" id="closeCreate" class="closebtn">&times;</a>
		<div class="overlay-content">
			<div class="wrapper">
				<div class="form-group">
				<form class="form-signin" method="POST" action="">
					<h2>You want to join? Sweet!</h2>
					<p>Fill out the form and click submit.</p>
					<input type="text" class="form-control" name="fname" placeholder="First Name" required>
					<br>
					<input type="text" class="form-control" name="lname" placeholder="Last Name" required>
					<br>
					<div class="radio">
						<input type="radio" name="gender" value="1">
						<label class="control-label" for="Male">Male</label>
						<br>
						<input type="radio" name="gender" value="0">
						<label class="control-label" for="Female">Female</label>
						<br>
					</div>
					<input type="text" class="form-control" name="createEmail" placeholder="Email Address" required>
					<br>
					<input type="password" class="form-control" name="createPassword" placeholder="Password" required>
					<button id="submitCreate" class="btn btn-success" type="submit">Submit</button>
				</form>
				</div>
			</div>
		</div>
	</div>

	<div id="login" class="overlay">
		<a href="javascript:void(0)" id="closeLogin" class="closebtn">&times;</a>
		<div class="overlay-content">
			<div class="wrapper">
				<form class="form-signin" method="POST" action="">
					<h2 class="form-signin-heading">Please login</h2>
					<input type="text" class="form-control" name="email" placeholder="Email Address" required>
					<br>
					<input type="password" class="form-control" name="password" placeholder="Password" required>
					<br>
					<button class="btn btn-success" type="submit">Login</button>
					<a href="#" id="forgot">Forgot Password</a> or <a href="#" id="createNew">Create New Login</a>
					<?php if (!$userFound) {
						echo "<br><br><p id='loginError'>*Email address and/or password is incorrect.</p>";
					}
					?>
				</form>
			</div>
		</div>
	</div>



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
