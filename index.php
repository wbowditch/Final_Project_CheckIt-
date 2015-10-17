<!DOCTYPE html>

<!-- =========================================== -->
<!-- Welcome to CheckIt, your personal portfolio -->
<!-- Flaherty			Bowditch			Chu  -->
<!-- =========================================== -->


<html lang="en">
<head>
	<meta charset="utf-8" />
    <title>CheckIt</title>
    <link rel="stylesheet" type="text/css" href="CSS/check.css">
 	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap-theme.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script> 
	<link href="http://getbootstrap.com/dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="http://getbootstrap.com/examples/signin/signin.css" rel="stylesheet">
	 <style>

 	 </style>

  	 <script type="text/javascript">
     
     	function validate(){
			var validFirst = validateFirst();
       		var validLast = validateLast();
			var validEmail = validateEmail();
			var validPassword = validatePassword();
			
			if (!validFirst) return false;
       		if (!validLast) return false;
			if (!validEmail) return false;
			if (!validPassword) return false;
			return true;
		}

		function validateFirst(){
			var thename= document.getElementById("first").value ;
			
			if (thename.length < 1) {
				var errorrpt=document.getElementById("firsterror");
				errorrpt.innerHTML = "Please enter a first name";
				return false;
			} 
			var errorrpt=document.getElementById("firsterror");
			errorrpt.innerHTML = "";
	
			return true;
		}
		
		function validateLast(){
			var thename= document.getElementById("last").value;
			
			if (thename.length < 1) {
				var errorrpt=document.getElementById("lasterror");
				errorrpt.innerHTML = "Please enter a last name";
				return false;
			} 
			var errorrpt=document.getElementById("lasterror");
			errorrpt.innerHTML = "";
	
			return true;
		}
		
		function validateEmail(){
			var theemail= document.getElementById("email").value ;
			var emailregex=/^[A-Za-z0-9]{1}\\w*@[a-zA-Z]{1}\\w*\\.(com|gov|edu)$/;
			
			if (!emailregex.test(theemail)) {
				var errorrpt=document.getElementById("emailerror");
				errorrpt.innerHTML = "Please enter a valid email";
				return false;
			} 
			var errorrpt=document.getElementById("emailerror");
			errorrpt.innerHTML = "";

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

	<?php

	if (isset($_GET['about'])){
		displayAbout();
	}
	if(isset($_GET['create'])){
			displaynewAccountForm();
	}
	displayHome();
		?>

</body>
</html>
<?php
    function displayHome(){
?>
<!-- 
		<div class='jumbotron'><h1 class='page-header text-center'>CheckIt Stock Portfolio</h1></div>
 -->
<!-- 
		<form method="get">
			<input class='btn btn-sm btn-default' type="submit" name="about" value="About CheckIt">
		</form>
 -->
		<nav class="navbar navbar-fixed-top navbar-inverse">
			  <div class="container">
				<div class="navbar-header">
				  <a class="navbar-brand">Checkit Stock Portfolio</a>
				</div>
				<div id="navbar" class="collapse navbar-collapse">
				  <ul class="nav navbar-nav">
				  
					<li class="active"><a href='./index.php'>Home</a></li>
					<?php 
				 	 if (!isset($_COOKIE['email'] )) {
						echo '<li><a href="./index2.php">Create Account</a></li>
							<li><a href="./checkit_signin.php">Sign In</a></li>					
							<li><a href="./about.php">About</a></li>';
						}
					else{
						echo '<li><a href="./profile.php">Profile</a></li>';
						}
					?>
				  </ul>
				</div><!-- /.nav-collapse -->
			  </div><!-- /.container -->
			</nav><!-- /.navbar -->
<!-- 
		<?php
		if (!isset($_COOKIE['email'])){ echo "

		<form method='get' action='checkit_signin.php'>
			<input class='btn btn-lg btn-primary' type='submit' name='signin' value='Sign In'>
		</form>
		<form method='get' action='./include/index2.php'>
			<input class='btn btn-sm btn-default' type='submit' name='create' value='Create Account'>
		</form>
		";
		} else { echo "
			<form action='profile.php'>
				<input class='btn btn-sm btn-primary' type='submit' name='signin' value='Profile'>
			</form> ";
				echo "
      		<br><form method='get' action='./include/clearcookies.php'>
        		<input class='btn btn-sm btn-default' type='submit' name='logout' value='Logout'>
      		</form>";
		}

		?>
 -->

		<h1><div id="rightwhite"> Your Chat Feed Today! </div></h1><br><br><br>
		<div id="right">
		
		<div class="form-inline">
			<input class="form-control"type="text" name="textBox" id="textBox" placeholder="Send a message..."/>	
			<button class="form-control form-inline" onclick="sendMessage()">Send</button>
		</div>
		<div id="chat"></div>

		</div>
		<script src="http://cdn.pubnub.com/pubnub-3.7.1.min.js"></script>
		<script src="/~oconnonx/CheckIt/js/main.js"></script>

 <!-- 
 	<img src="https://castlehillview.files.wordpress.com/2015/01/stock-market-3.jpg" alt="main page" height="400" width="500"><br>
		<p>
			Image source: https://zacharydiamond.files.wordpress.com/2014/12/ski-mask-hacker-2.jpg?w=470&h=140&crop=1
		</p>
 -->
 		<?php
 		$nasdaq = "http://chart.finance.yahoo.com/t?s=%5eIXIC&lang=en-US&region=US&width=300&height=180";
		$dow_jones = "http://chart.finance.yahoo.com/t?s=%5eDJI&lang=en-US&region=US&width=300&height=180";
		$sp500 = "http://chart.finance.yahoo.com/t?s=%5eGSPC&lang=en-US&region=US&width=300&height=180";
		echo "<div class='container'>";
     	echo "<img src=$nasdaq >";
     	echo "<img src=$dow_jones >";
      	echo "<img src=$sp500 >";
      	echo "</div>";
      	?>
      	<div class='container'>
		<div class='form-inline'>
			<h1>Some important links</h1><br>
			<a class='form-control' href="https://cs.bc.edu">Here is BC Comp Sci</a>
			<a class='form-control' href="http://linkedin.com/in/jflah">Click here to network with me</a>
			<a class='form-control' href="https://github.com/JFlah/Final-Project-CheckIt">Our github</a>
		</div></div>

	<!--RSS below -->

	<?php
	//initialize news source

	$rss_feed = "http://rss.nytimes.com/services/xml/rss/nyt/Business.xml";

	$rss= new SimpleXMLElement(file_get_contents($rss_feed));
	$title = $rss->channel->title;
// 	echo "<div class='jumbotron'><h1>$title</h1></div>";
	
	echo "<br><div class=1col-xs-12 col-sm-9'>";
	echo "<div class='container'>";
    echo "<div class='row-offcanvas row-offcanvas-right'>";
	$items = $rss->channel->item;
	$i = 0;
	echo "<div class='row'>";
		foreach ($items as $item) {
			if($i%3 == 0){
				echo "</div><div class='row'>";
			}
			echo "<div class='col-xs-6 col-lg-4'>
			<h2>$item->title</h2>\n";
			echo '<a class="form-inline" href="' . $item->link . '">' . $item->title . '</a><br>';
			echo $item->description . "<br><br>\n";
			echo "</div>";
			$i++;
		}
	echo "</div></div>
	</div>
	</div>";
	}  
      
function displayAbout(){
?>
<?php
}

?>