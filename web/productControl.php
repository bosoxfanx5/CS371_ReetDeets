<?php
include 'session.php';
include 'dbconnect.php';

/******************************************************************
* AUTHENTICATION
*******************************************************************/
$userFound = true;

/******************************************************************
* LOGIN
*******************************************************************/
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


   /******************************************************************
   * END AUTHENTICATION
   *******************************************************************/


$_SESSION["previousEnabled"] = false;
$_SESSION["nextEnabled"] = false;


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

if(isset($_SESSION["email"])) {
   $email = $_SESSION["email"];
   $sql = $db->prepare("SELECT id FROM s_person WHERE email='$email'");
   $sql->execute();
   $idResult = $sql->fetch();

   $_SESSION["id"] = $idResult["id"];
} else {
   $sql1 = $db->prepare("INSERT INTO s_person (id) VALUES (uuid_generate_v4())");
	$sql1->execute();

	//retrieve new person id
	$personID = $db->lastInsertId();

	$sql1 = $db->prepare("SELECT id FROM s_person WHERE autoinc='$personID'");
	$sql1->execute();
	$result1 = $sql1->fetch();
	$_SESSION["id"] = $result1["id"];
}


if(isset($_REQUEST["backbar"])) {
	$barcode = $_REQUEST["backbar"];
}

if (!empty($_GET["barcode"])) {
	$barcodeCheck = $_GET["barcode"];
	$sqlCheck = $db->prepare("SELECT COUNT(*) FROM s_saleable_item WHERE barcode='$barcodeCheck' LIMIT 1");
	$sqlCheck->execute();
	//$check = $sqlCheck->fetch();

	if($sqlCheck->fetchColumn()) {
		$barcode = $_GET["barcode"];
		//$_SESSION["barcode"] = $_GET["barcode"];

		$_SESSION["codes"][] = $barcode;



	   $_SESSION["max"] = sizeof($_SESSION["codes"]);
	   $_SESSION["index"] = sizeof($_SESSION["codes"]) - 1;

	   if ($_SESSION["max"] > 1 && $_SESSION["index"] != 0) {
	      $_SESSION["previousEnabled"] = true;
	   }
	} else {
		//$_SESSION["error"] = '<p style="color:red">Please Enter Valid Barcode</p>';
		header('Location: https://mysterious-bayou-55662.herokuapp.com?isError=true');
	}

   // foreach ($_SESSION["codes"] as $code) {
   //    echo $code;
   // }
   // echo sizeof($_SESSION["codes"]);
   // echo $barcode != $_SESSION["codes"][0];

}

if(isset($_REQUEST["previous"])) {
   $_SESSION["nextEnabled"] = true;
   $_SESSION["index"] -= 1;
   $barcode = $_SESSION["codes"][$_SESSION["index"]];

   if ($_SESSION["index"] == 0 && $_SESSION["max"] > 1) {
      $_SESSION["previousEnabled"] = false;
      $_SESSION["nextEnabled"] = true;
   } else {
      $_SESSION["previousEnabled"] = true;
   }
}

if(isset($_REQUEST["next"])){
   $_SESSION["previousEnabled"] = true;

   if(!(($_SESSION["index"] + 1) >= $_SESSION["max"])) {
      $_SESSION["index"] += 1;
      $barcode = $_SESSION["codes"][$_SESSION["index"]];
   }

   if (($_SESSION["index"] + 1) == $_SESSION["max"] && $_SESSION["index"] != 0) {
      $_SESSION["nextEnabled"] = false;
      $_SESSION["previousEnabled"] = true;
   } else {
      $_SESSION["nextEnabled"] = true;
   }
}
$reviewLink = '<a href="review.php?barcode=' . $barcode . '" id="reviewLink">';
// echo $_SESSION["index"] . "<br>";
// echo $_SESSION["max"];

if(isset($barcode)) {
	$sql0 = $db->prepare("SELECT title, price, listinfo1, listinfo2, listinfo3, listinfo4, image FROM s_saleable_item WHERE barcode='$barcode'");
	$sql0->execute();
	$result = $sql0->fetch();
	$image = '<img class="img-responsive" src=' . $result["image"] . '>';

	$sql2 = $db->prepare("SELECT id FROM s_saleable_item WHERE barcode='$barcode'");
	$sql2->execute();
	$result2 = $sql2->fetch();
	$itemID = $result2["id"];


	$personID = $_SESSION["id"];
	$sql3 = $db->prepare("INSERT INTO s_visited_items (visitor_id, item_id) VALUES ('$personID', '$itemID')");
	$sql3->execute();
}

$database = null;

?>
