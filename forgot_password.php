<!DOCTYPE html>

<!-- =========================================== -->
<!-- Welcome to CheckIt, your personal portfolio -->
<!-- Flaherty			Bowditch			Chu  -->
<!-- =========================================== -->

<html lang="en">
<head>
	<meta charset="utf-8" />
    <title>CheckIt</title>
<!--     <link rel="stylesheet" type="text/css" href="CSS/check.css"> -->
 	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap-theme.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script> 
	<link href="http://getbootstrap.com/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="http://getbootstrap.com/examples/signin/signin.css" rel="stylesheet">
      	<script type="text/javascript">
        function emailValidate(){
			var theemail = document.getElementById("email1").value ;
			if (theemail.length < 1) {
				var errorrpt=document.getElementById("email1error");
				errorrpt.innerHTML = "Please enter an email";
				return false;
			} 
			var errorrpt=document.getElementById("email1error");
			errorrpt.innerHTML = "";
	
			return true;
		}
		
	
      </script>

<body>
	<nav class="navbar navbar-fixed-top navbar-inverse">
	  <div class="container">
		<div class="navbar-header">
		  <a class="navbar-brand">Checkit Stock Portfolio</a>
		</div>
		<div id="navbar" class="collapse navbar-collapse">
		  <ul class="nav navbar-nav">
			<li><a href='./index.php'>Home</a></li>
			<li><a href="./index2.php">Create Account</a></li>
			<li><a href="./checkit_signin.php">Sign In</a></li>
			<li><a href="./about.php">About</a></li>
		  </ul>
		</div><!-- /.nav-collapse -->
  	</div><!-- /.container -->
	</nav><!-- /.navbar -->		
</head>
<body>

	<?php
		forgotPasswordForm();
	?>
</body>
</html>
 
<?php
    function forgotPasswordForm(){
?>
		<fieldset><div class='container'><h1>Forgot Password?</h1></div>
			<br><br>
			<form method = "get" action = "include/checkit_password.php" onsubmit = "return emailValidate();">
				<div class='container'>
				<label for="email">Please enter your email: </label>
				<input type = "text" id = "email1" name = "email1">
				<span class="ereport" id="email1error"></span>
				<br><br>
				<input class='btn btn-default' type = "submit"  name = "submit_button" value = "Email New Password" >
				</div>
			</form>
		</fieldset>
		<?php
	}
?>