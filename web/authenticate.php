<?php
include 'dbconnect.php';
include 'authForms.php';

if (!empty($_POST["email"]) && !empty($_POST["password"])) {

	$personEmail = $_POST["email"];
	// query for email and password of user
	$sql0 = $db->prepare("SELECT id, fname, email, psswd FROM s_person WHERE email='$personEmail'");
	$sql0->execute();
	$result = $sql0->fetch();

	// authenticate user provided info with database
	$authenticated = password_verify($_POST["password"], $result['psswd']);

	if ($result["email"] == $personEmail && $authenticated) {
		$_SESSION["loggedIn"] = true;
		$_SESSION["id"] = $result["id"];
		$_SESSION["email"] = $result["email"];
		$_SESSION["fname"] = $result["fname"];
		$userFound = true;
		header( 'Location: https://mysterious-bayou-55662.herokuapp.com' );
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
		$sql = $db->prepare("UPDATE s_person SET fname='$fname', lname='$lname', prefix='$prefix',
			email='$cEmail', psswd='$hashed' WHERE id='$personID'");

		$sql->execute();
		$_SESSION['email'] = $cEmail;
		$userFound = true;
		$_SESSION["loggedIn"] = true;
		header( 'Location: https://mysterious-bayou-55662.herokuapp.com' );
		die();

		} else {
			// if there isn't a session id for the user yet
			$sql = $db->prepare("INSERT INTO s_person (fname, lname, prefix, email, psswd)
			VALUES ('$fname','$lname','$prefix','$cEmail','$hashed')");

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
$database = null;
?>

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
</html>
