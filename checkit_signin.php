<!DOCTYPE html>

<!-- saved from url=(0040)http://getbootstrap.com/examples/signin/ -->
<html lang="en" hola_ext_inject="ready"><head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="icon" href="http://getbootstrap.com/favicon.ico">
    <title>CheckIt SignIn</title>
    <link href="http://getbootstrap.com/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="http://getbootstrap.com/examples/signin/signin.css" rel="stylesheet">
    <script src="./Signin_files/ie-emulation-modes-warning.js"></script><style type="text/css"></style>
    <?php
        include ('include/dbconn.php');
    ?>
  </head>
  <body hola-ext-player="1">
		<nav class="navbar navbar-fixed-top navbar-inverse">
		  <div class="container">
			<div class="navbar-header">
			  <a class="navbar-brand">Checkit Stock Portfolio</a>
			</div>
			<div id="navbar" class="collapse navbar-collapse">
			  <ul class="nav navbar-nav">
			  	<li><a href='./index.php'>Home</a></li>
				<li><a href="./index2.php">Create Account</a></li>
				<li class="active"><a href="./checkit_signin.php">Sign In</a></li>
				<li><a href="./about.php">About</a></li>
			  </ul>
			</div><!-- /.nav-collapse -->
		  </div><!-- /.container -->
		</nav><!-- /.navbar -->
		<br><br>
    <div class="container">
      <form class="form-signin" method="post" action="profile.php">
        <h2 class="form-signin-heading">Please sign in</h2>
        <input type="hidden" name = "stocks">
        <label for="email" class="sr-only">Email address</label>
        <input type="email" id="email" name="email" class="form-control" placeholder="Email address" required="" autofocus="">
        <label for="password" class="sr-only">Password</label>
        <input type="password" id="password" name="password" class="form-control" placeholder="Password" required="">
        <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
	    <a href="forgot_password.php">Forgot your password?</a>
      </form>
    </div> <!-- /container -->

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="./Signin_files/ie10-viewport-bug-workaround.js"></script>
  

</body></html>  

