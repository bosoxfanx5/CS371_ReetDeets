<?php
$backBar = $_REQUEST["barcode"];

$backLink = '<a href="product.php?backBar=true" class="btn btn-warning btn-lg" >Back</a>';
?>

<!DOCTYPE html>
<html lang="en">
<?php include 'header.php'; ?>
<link href="css/sticky-footer-navbar.css" rel="stylesheet">
<body>
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

	<div class="text-center">
		<h1>Coming Soon!</h1>
		<br>
		<?php echo $backLink; ?>
	</div>


	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	<script src="js/index.js"></script>
</body>
</html>
