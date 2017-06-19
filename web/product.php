<?php
include 'session.php';
include 'dbconnect.php';

$barcode = $_GET["barcode"];
echo $barcode;

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
          <span class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">tester@test.com</span> <!-- change to php email -->
          <a class="navbar-brand" href="#"><p style="color:black">RD|ReetDeets</p></a>
        <?php else :?>
          <span class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">Options</span>
          <a class="navbar-brand" href="#"><p style="color:black">RD|ReetDeets</p></a>
        <?php endif ?>
      </div>
      <div class="collapse navbar-collapse pull-right" id="myNavbar">
      <?php if(empty($_SESSION["email"])) : ?>
        <ul class="nav navbar-nav navbar-right">
          <li><a href="#"><span class="glyphicon glyphicon-user"></span> Sign Up</a></li>
          <li><a href="#"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>
        </ul>
      <?php else : ?>
        <ul class="nav navbar-nav navbar-right">
          <li><a href="#"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
        </ul>
      <?php endif ?>
      </div>
    </div>
  </nav>

  <div class="container">
    <div class="row">
      <div class="col-xs-9">
        <p>VIZIO - 60" Class - LED - 2160p - with Chromecast Built-in - 4K Ultra HD</p> <!--change to php product name (limit characters)-->
      </div>
    </div>
    <div class="row">
      <div class="col-xs-6">
        <img class="img-responsive" src="img/tv.jpg"> <!--change to php img source -->
      </div>
      <div class="col-xs-6">
        <ul id="product-info"> <!--change to php product info (limit 4, limit characters)-->
          <li><span>2 x AA batteries</span></li>
          <li><span>HDMI cable</span></li>
          <li><span>Owners manual</span></li>
          <li><span>Remote Control</span></li>
        </ul>
      </div>
    </div>
    <div class="row">
      <div class="col-xs-6 text-center">
        <h3><span>$749.99<span></h3> <!-- change to php price -->
      </div>
      <div class="col-xs-6 text-center">
        <form method="GET" action="productDetails.php">
          <button class="btn btn-warning btn-md" type="submit">Product<br> Features</button>
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
    <form method="GET" action="">
      <div class="row">
        <div class="col-xs-4">
          <button class="btn btn-danger btn-lg" type="submit" disabled>Previous</button>
        </div>
        <div class="col-xs-4">
          <button class="btn btn-success btn-lg" type="submit">Next</button>
        </div>
        <div class="col-xs-4">
          <a href="index.php"><button class="btn btn-primary btn-lg" type="submit"><span>New <br>Lookup</span></button></a>
        </div>
      </div>
    </form>
  </div>








  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
  <script src="js/index.js"></script>
</body>
</html>
