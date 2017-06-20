<?php
include 'session.php';
include 'dbconnect.php';






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




if (!empty($_GET["barcode"])) {
   $barcode = $_GET["barcode"];
   $_SESSION["codes"][] = $barcode;


   $_SESSION["max"] = sizeof($_SESSION["codes"]);
   $_SESSION["index"] = sizeof($_SESSION["codes"]) - 1;

   if ($_SESSION["max"] > 1 && $_SESSION["index"] != 0) {
      $_SESSION["previousEnabled"] = true;
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
   $_SESSION["index"] += 1;
   $barcode = $_SESSION["codes"][$_SESSION["index"]];

   if (($_SESSION["index"] + 1) == $_SESSION["max"] && $_SESSION["index"] != 0) {
      $_SESSION["nextEnabled"] = false;
      $_SESSION["previousEnabled"] = true;
   } else {
      $_SESSION["nextEnabled"] = true;
   }
}

// echo $_SESSION["index"] . "<br>";
// echo $_SESSION["max"];

$sql0 = $db->prepare("SELECT title, price, listinfo1, listinfo2, listinfo3, listinfo4, image FROM s_saleable_item WHERE barcode='$barcode'");
$sql0->execute();
$result = $sql0->fetch();
$image = '<img class="img-responsive" src=' . $result["image"] . '>';

$sql2 = $db->prepare("SELECT id FROM s_saleable_item WHERE barcode='$barcode'");
$sql2->execute();
$result2 = $sql2->fetch();
$itemID = $result2["id"];


$personID = $_SESSION["id"];
$sql2 = $db->prepare("INSERT INTO s_visited_items (visitor_id, item_id) VALUES ('$personID', '$itemID')");
$sql2->execute();
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
  <!-- <link href="css/sticky-footer-navbar.css" rel="stylesheet"> -->
  <link rel="stylesheet" href="css/product.css">
  <link rel="stylesheet" href="css/index.css">
  <link rel="stylesheet" href="css/login.css">

</head>

<body>

  <nav class="navbar navbar-default">
    <div class="container">
      <div class="navbar-header">
        <?php if(!empty($_SESSION["email"])) : ?>
          <span class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar"><?php echo $_SESSION["email"] ?></span> <!-- change to php email -->
          <a class="navbar-brand" href="#"><h4 style="color:black">RD|ReetDeets</h4></a>
        <?php else :?>
          <span class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">Options</span>
          <a class="navbar-brand" href="#"><p style="color:black">RD|ReetDeets</p></a>
        <?php endif ?>
      </div>
      <div class="collapse navbar-collapse pull-right" id="myNavbar">
      <?php if(empty($_SESSION["email"])) : ?>
        <ul class="nav navbar-nav navbar-right">
          <li><a href="#" id="createNew"><span class="glyphicon glyphicon-user"></span> Sign Up</a></li>
          <li><a href="#" id="loginBtn"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>
        </ul>
      <?php else : ?>
        <ul class="nav navbar-nav navbar-right">
          <li><a id="loginBtn" href="https://mysterious-bayou-55662.herokuapp.com?logout=true"><span class="glyphicon glyphicon-log-out"></span>Logout</a></li>
        </ul>
      <?php endif ?>
      </div>
    </div>
  </nav>

  <div class="container">
    <div class="row">
      <div class="col-xs-9">
        <p><?php echo $result["title"] ?></p>
      </div>
    </div>
    <div class="row">
      <div class="col-xs-6">
        <?php echo $image ?>
      </div>
      <div class="col-xs-6">
         <ul id="product-info">
            <?php if (isset($result["listinfo1"])) : ?>
               <li><span><?php echo $result["listinfo1"] ?></span></li>
            <?php endif ?>
            <?php if (isset($result["listinfo2"])) : ?>
               <li><span><?php echo $result["listinfo2"] ?></span></li>
            <?php endif ?>
            <?php if (isset($result["listinfo3"])) : ?>
               <li><span><?php echo $result["listinfo3"] ?></span></li>
            <?php endif ?>
            <?php if (isset($result["listinfo4"])) : ?>
               <li><span><?php echo $result["listinfo4"] ?></span></li>
            <?php endif ?>
        </ul>
      </div>
    </div>
    <div class="row">
      <div class="col-xs-6 text-center">
        <h2><span><?php echo '$' . $result["price"] ?><span></h2s>
      </div>
      <div class="col-xs-6 text-center">
        <form method="GET" action="productDetails.php">
          <button class="btn btn-warning btn-md" type="submit"><span>Product<br>Features</span></button>
        </form>
      </div>
    </div>
  </div>

  <br>

  <div class="container">
    <div class="row">
      <div class="col-xs-10 col-xs-offset-1 review-container">
        <br>
        <div class="container review"> <!-- make each container review a link -->
          <div class="row">
            <div class="col-xs-4 ">
              <img class="img-responsive" src="img/5stars.png">
            </div>
          </div>
          <div class="row">
            <div class="col-xs-12">
              <h4>Best TV I've ever owned!</h4>
              <p><span>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor
                incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud
                exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute
                irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla
                pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia
                deserunt mollit anim id est laborum.</span></p>
            </div>
          </div>
        </div>
        <br>
        <div class="container review">
          <div class="row">
            <div class="col-xs-4 ">
              <img class="img-responsive" src="img/3stars.png">
            </div>
          </div>
          <div class="row">
            <div class="col-xs-12">
              <h4>It's pretty good, but...</h4>
              <p><span>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor
                incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud
                exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute
                irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla
                pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia
                deserunt mollit anim id est laborum.</span></p>
            </div>
          </div>
        </div>
        <br>
        <div class="container review">
          <div class="row">
            <div class="col-xs-4 ">
              <img class="img-responsive" src="img/1stars.png">
            </div>
          </div>
          <div class="row">
            <div class="col-xs-12">
              <h4>Not very good...</h4>
              <p><span>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor
                incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud
                exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute
                irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla
                pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia
                deserunt mollit anim id est laborum.</span></p>
            </div>
          </div>
        </div>
        <br>
        <div class="container review">
          <div class="row">
            <div class="col-xs-4 ">
              <img class="img-responsive" src="img/4stars.png">
            </div>
          </div>
          <div class="row">
            <div class="col-xs-12">
              <h4>Great purchase!</h4>
              <p><span>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor
                incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud
                exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute
                irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla
                pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia
                deserunt mollit anim id est laborum.</span></p>
            </div>
          </div>
        </div>
        <br><br>
      </div>
    </div>
  </div>

  <br><br>

  <div class="container">
      <div class="row">
         <div class="col-xs-4">
            <?php if ($_SESSION["previousEnabled"] == false) : ?>
               <a class="btn btn-danger btn-lg" type="submit" disabled><span>Previous</span></a>
            <?php else : ?>
               <a class="btn btn-danger btn-lg" type="submit" href="https://mysterious-bayou-55662.herokuapp.com/product.php?previous=true"><span>Previous</span></a>
            <?php endif ?>
         </div>
         <div class="col-xs-4">
            <?php if ($_SESSION["nextEnabled"] == false) : ?>
               <a class="btn btn-success btn-lg" type="submit" disabled><span>Next</span></a>
            <?php else :?>
               <a class="btn btn-success btn-lg" type="submit" href="https://mysterious-bayou-55662.herokuapp.com/product.php?next=true"><span>Next</span></a>
            <? endif ?>
         </div>
         <form method="POST" action="index.php">
            <div class="col-xs-4">
               <button class="btn btn-primary btn-lg" type="submit"><span>New<br>Lookup</span></button>
            </div>
         </form>
      </div>
   </div>


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






  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
  <script src="js/index.js"></script>
</body>
</html>
