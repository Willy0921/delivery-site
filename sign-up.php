<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js">
<!--<![endif]-->

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Minimal and Clean Sign up / Login and Forgot Form by FreeHTML5.co</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="Free HTML5 Template by FreeHTML5.co" />
	<meta name="keywords" content="free html5, free template, free bootstrap, html5, css3, mobile first, responsive" />
	<meta name="author" content="FreeHTML5.co" />

	<!-- 
	//////////////////////////////////////////////////////

	FREE HTML5 TEMPLATE 
	DESIGNED & DEVELOPED by FreeHTML5.co
		
	Website: 		http://freehtml5.co/
	Email: 			info@freehtml5.co
	Twitter: 		http://twitter.com/fh5co
	Facebook: 		https://www.facebook.com/fh5co

	//////////////////////////////////////////////////////
	 -->

	<!-- Facebook and Twitter integration -->
	<meta property="og:title" content="" />
	<meta property="og:image" content="" />
	<meta property="og:url" content="" />
	<meta property="og:site_name" content="" />
	<meta property="og:description" content="" />
	<meta name="twitter:title" content="" />
	<meta name="twitter:image" content="" />
	<meta name="twitter:url" content="" />
	<meta name="twitter:card" content="" />
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<!-- Place favicon.ico and apple-touch-icon.png in the root directory -->
	<link rel="shortcut icon" href="favicon.ico">

	<link href='https://fonts.googleapis.com/css?family=Open+Sans:400,700,300' rel='stylesheet' type='text/css'>

	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/animate.css">
	<link rel="stylesheet" href="css/style.css">

	<!-- Modernizr JS -->
	<script src="js/modernizr-2.6.2.min.js"></script>
	<!-- FOR IE9 below -->
	<!--[if lt IE 9]>
	<script src="js/respond.min.js"></script>
	<![endif]-->

</head>

<body>
	<?php
	error_reporting(E_ERROR | E_PARSE);
	session_start();
	$_SESSION['login_fail'] = 0;
	if ($_SESSION['alert_mes'] != "" && $_SESSION['sign_up_suc'] == 0) {
		$_SESSION['sign_up_suc'] = 0;
		$mes = $_SESSION['alert_mes'];
		$_SESSION['alert_mes'] = "";
		echo "<script type='text/javascript'>alert('$mes');</script>";
	}
	?>
	<div class="container">

		<div class="row">
			<div class="col-md-4 col-md-offset-4">


				<!-- Start Sign In Form -->
				<form action="sign-up_page.php" class="fh5co-form animate-box" data-animate-effect="fadeIn">
					<h2>Sign Up</h2>

					<!-- <div class="form-group">
							<div class="alert alert-success" role="alert">Your info has been saved.</div>
						</div> -->
					<div class="form-group">
						<label for="name" class="sr-only">Name</label>
						<input type="text" class="form-control" id="name" placeholder="Name" autocomplete="off" name="Name">
					</div>
					<div class="form-group">
						<label for="name" class="sr-only">phonenumber</label>
						<input type="text" class="form-control" id="phonenumber" placeholder="PhoneNumber" autocomplete="off" name="Phonenumber">
					</div>
					<div class="form-group">
						<label for="Account" class="sr-only">Account</label>
						<input type="text" class="form-control" id="Account" placeholder="Account" autocomplete="off" name="Account" oninput="checkAccount()">
						<span id="check"></span>
					</div>
					<div class="form-group">
						<label for="password" class="sr-only">Password</label>
						<input type="password" class="form-control" id="password" placeholder="Password" autocomplete="off" name="Password">
					</div>
					<div class="form-group">
						<label for="re-password" class="sr-only">Re-type Password</label>
						<input type="password" class="form-control" id="re-password" placeholder="Re-type Password" autocomplete="off" name="re-password">
					</div>
					<div class="form-group">
						<label for="latitude" class="sr-only">latitude</label>
						<input type="text" class="form-control" id="latitude" placeholder="Latitude" autocomplete="off" name="Latitude">
					</div>
					<div class="form-group">
						<label for="longitude" class="sr-only">longitude</label>
						<input type="text" class="form-control" id="longitude" placeholder="longitude" autocomplete="off" name="Longitude">
					</div>

					<div class="form-group">
						<p>Already registered? <a href="index.php">Sign In</a></p>
					</div>
					<div class="form-group">
						<input type="submit" value="Sign Up" class="btn btn-primary">
					</div>

				</form>
				<!-- END Sign In Form -->

			</div>
		</div>
		<div class="row" style="padding-top: 60px; clear: both;">
			<div class="col-md-12 text-center">
				<p><small>&copy; All Rights Reserved. Designed by <a href="https://freehtml5.co">FreeHTML5.co</a></small></p>
			</div>
		</div>
	</div>

	<!-- jQuery -->
	<script src="js/jquery.min.js"></script>
	<!-- Bootstrap -->
	<script src="js/bootstrap.min.js"></script>
	<!-- Placeholder -->
	<script src="js/jquery.placeholder.min.js"></script>
	<!-- Waypoints -->
	<script src="js/jquery.waypoints.min.js"></script>
	<!-- Main JS -->
	<script src="js/main.js"></script>

	<script type="text/javascript">
		console.log("test");

		function checkAccount() {

			jQuery.ajax({
				url: "check_availability.php",
				data: {
					'account': $("#Account").val(),
					'state': 'user'
				},

				type: "POST",
				success: function(data) {
					console.log()
					document.getElementById("check").innerHTML = data
				},
				error: function() {}
			});
		}
	</script>
</body>

</html>