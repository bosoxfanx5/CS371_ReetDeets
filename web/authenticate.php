<?php
include 'dbconnect.php';
$userFound = true;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	if (!empty($_POST["email"]) && !empty($_POST["password"])) {
		$personEmail = $_POST["email"];
		strtolower($personEmail);
		// query for email and password of user
		$sql0 = $db->prepare("SELECT id, fname, email, psswd FROM s_person WHERE email='$personEmail'");
		$sql0->execute();
		$result = $sql0->fetch();

		//$email = $result["email"];
		// echo $email;
		// echo "awesome";
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
			$_SESSION["userFound"] = false;
			header( 'Location: https://mysterious-bayou-55662.herokuapp.com' );
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
}
$database = null;
?>

<div id="creation" class="overlay-creation">
	<div class="overlay-content">
		<div class="wrapper-creation">
			<div class="form-group">
			<form class="form-signin" method="POST" action="">
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
			<form class="form-signin" method="POST" action="">
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
