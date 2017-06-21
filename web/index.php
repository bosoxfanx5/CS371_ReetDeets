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
	session_destroy();
	header( 'Location: https://mysterious-bayou-55662.herokuapp.com' );
	die();
}

// if ($_SERVER['REQUEST_METHOD'] == 'GET') {
//
// 	$sql0 = $db->prepare("SELECT id, title FROM s_saleable_item");
// 	$sql0->execute();
// 	$result0 = $sql0->fetchAll(PDO::FETCH_ASSOC);
//
// 	if (!empty($_GET['id'])) {
// 		$isContent = true;
// 		$welcome = false;
// 		$sql = $db->prepare("SELECT * FROM s_saleable_item
// 			WHERE id = :id");
// 			$sql->execute(array(":id" => $_GET['id']));
// 			$result = $sql->fetch(PDO::FETCH_ASSOC);
// 	}
// }

if (!empty($_GET["barcode"])) {
	$barcodeCheck = $_GET["barcode"];
	$sqlCheck = $db->prepare("SELECT id FROM s_saleable_item WHERE barcode='$barcodeCheck");
	$sqlCheck->execute();
	$check = $sqlCheck->fetch();

	if(empty($check["id"])) {
	} else {
		header('Location: https://mysterious-bayou-55662.herokuapp.com');
		$error = '<p style="color:red">Please Enter Valid Barcode</p>';
	}
}

$database = null;
$error = '<p style="color:red">Please Enter Valid Barcode</p>';

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
			  <?php echo $error ?>
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

	<?php include 'authenticate.php'; ?>

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
