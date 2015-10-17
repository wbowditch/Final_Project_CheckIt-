<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Example of Bootstrap 3 Vertical Form Layout</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap-theme.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script> 
<link href="http://getbootstrap.com/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="http://getbootstrap.com/examples/signin/signin.css" rel="stylesheet">
<style type="text/css">
    .bs-example{
    	margin: 30px;
    }
</style>
<script type="text/javascript">
     
     	function validate(){
			var validPassword = validatePassword();
			
			if (!validPassword) return false;
			return true;
		}	
		function validatePassword(){
			var pass1= document.getElementById("password").value ;
			var pass2= document.getElementById("password2").value ;
			
			if (pass1 != pass2) {
				var errorrpt=document.getElementById("passerror");
				errorrpt.innerHTML = "Passwords Don't Match";
				return false;
			} 
		    if (pass1.length < 1) {
				var errorrpt1=document.getElementById("pass1error");
				errorrpt1.innerHTML = "Please enter a password";
				return false;
			} 
			if (thepass2.length < 1) {
				var errorrpt=document.getElementById("passerror");
				errorrpt.innerHTML = "Please enter a password";
				return false;
			} 
			var errorrpt=document.getElementById("passerror");
			errorrpt.innerHTML = "";
			var errorrpt1=document.getElementById("pass1error");
			errorrpt1.innerHTML = "";
	
			return true;
		}
      </script>
</head>
<body>
	<nav class="navbar navbar-fixed-top navbar-inverse">
		  <div class="container">
			<div class="navbar-header">
			  <a class="navbar-brand">Checkit Stock Portfolio</a>
			</div>
			<div id="navbar" class="collapse navbar-collapse">
			  <ul class="nav navbar-nav">
			  	<li><a href='./index.php'>Home</a></li>
				<li class="active"><a href="./index2.php">Create Account</a></li>
				<li><a href="./checkit_signin.php">Sign In</a></li>
				<li><a href="./about.php">About</a></li>
			  </ul>
			</div><!-- /.nav-collapse -->
		  </div><!-- /.container -->
		</nav><!-- /.navbar -->
<div class='container'>
<div class="bs-example">
    <form method="post" action = "./include/checkit_ops.php" onsubmit = "return validate();">
        <div class="form-group">
            <label for="inputName">Please enter your first name: </label>
            <input type="name" class="form-control" id="inputName" name = "first" placeholder="First Name" required="">
        </div>
        <div class="form-group">
            <label for="inputName">Please enter your last name: </label>
            <input type="name" class="form-control" id="inputName" name="last" placeholder="Last Name" required="">
        </div>

        <div class="form-group">
            <label for="inputEmail">Please enter your email: </label>
            <input type="email" class="form-control" id="inputEmail" name="email" placeholder="Email" required="">
        </div>
        <div class="form-group">
            <label for="inputPassword">Please enter your password: </label>
            <input type="password" class="form-control" id="inputPassword" name="password" placeholder="Password" required>
            <span class="ereport" id="pass1error"></span>
        </div>        
        <div class="form-group">
            <label for="inputPassword">Please confirm your password: </label>
            <input type="password" class="form-control" id="inputPassword2" data-match="#inputPassword" data-match-error="Whoops, these don't match" 
            name="password2" placeholder="Confirm Password" required>
			<span class="ereport" id="passerror"></span>
        </div>
        <div class="checkbox">
            <label><input type="checkbox"> Remember me</label>
        </div>
        <button type="submit" class="btn btn-primary">Login</button>

    </form>
</div>
</div>
</body>
</html>                                		