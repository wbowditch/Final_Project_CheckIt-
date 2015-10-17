<!DOCTYPE html>
<?php
include ('dbconn.php');
?>
<html>
<head>
	<title>CheckIt</title>
	 	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap-theme.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script> 
	<link href="http://getbootstrap.com/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="http://getbootstrap.com/examples/signin/signin.css" rel="stylesheet">
	 <link rel="stylesheet" type="text/css" href="CSS/check.css">

</head>
<body>
<nav class="navbar navbar-fixed-top navbar-inverse">
	  <div class="container">
		<div class="navbar-header">
		  <a class="navbar-brand">Checkit Stock Portfolio</a>
		</div>
		<div id="navbar" class="collapse navbar-collapse">
		  <ul class="nav navbar-nav">
			<li><a href="../profile.php">Profile</a></li>
			<li class="active"><a href="./buysell.php">Buy/Sell Stocks</a></li>
		  </ul>
		</div><!-- /.nav-collapse -->
	  </div><!-- /.container -->
	</nav><!-- /.navbar -->
	<h1></h1>
<?php

	ob_start();
	$dbc = connectToDB();
	$email = $_COOKIE['email'];
	$password = $_COOKIE['pass'];
	
	$sha_password = sha1($password);
	$profile_query = "select * from checkit where email = '$email' and password = '$sha_password'";
	$result = performQuery($dbc,$profile_query);
	$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
	
	$cash = $row['cash'];

	
	if( isset($_GET['buy'] ) ) {
		$email = $_GET['email'];
		$buy_amount = $_GET['buy'];
		$stock = $_GET['stock'];
		//echo $email;
		updateBuy($dbc,$buy_amount,$stock,$email);
	}
	if(isset($_GET['sell'] ) ) {
		$email = $_GET['email'];
		$sell_amount = $_GET['sell'];
		$stock = $_GET['stock'];
		updateSell($dbc,$sell_amount,$stock,$email);
	}
	
	if ( isset($_POST['first'])) {
		$first = $_POST['first'];
		$last = $_POST['last'];
		
		if ( $_POST['email'] != null ) {
			$email = $_POST['email'];
			
			$email_query = "select email from checkit where email = '$email'";
			$result = mysqli_query($dbc, $email_query) or die("bad query".mysqli_error($dbc));	
			$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
			$email1 = $row['email'];
			if( $email1 == null ) {
				if ( $_POST['password'] != null) {
					$password = $_POST['password'];
					
					if ( $_POST['password2'] != null) {
						$password2 = $_POST['password2'];
						
						if ($password == $password2) {
							createAccount($dbc,$first,$last,$email,sha1($password));
						}
						else echo "Passwords don't match";
					}
					else echo 'No Password2 Entered';
					
				}
				else echo 'No Password Entered';
			}
			else echo "<div class='container'>Email Already Exists</div>";
		}
		else echo "<div class='container'>No Email Entered</div>";
	}
?>

</body>
</html>
<?php
function updateBuy($dbc,$buy,$stock) {
	$email = $_COOKIE['email'];
	$password = $_COOKIE['pass'];
	
	$profile_query = "select * from checkit where email = '$email'";
    $result = performQuery($dbc,$profile_query);
    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        
    $first = $row['first'];
    $last = $row['last'];
    $cash = $row['cash'];
    $stocks = $row['stocks'];
    $stock_array = explode(" ",$stocks);
    $stock_name = array();
    $stock_owned = array();
    $i = 1;
    foreach($stock_array as $value){
    	if($i%2==0){
        	$stock_owned[] = $value;
        }
        else{
        	$stock_name[] = $value;
        }
        	$i= $i+1;
    }
    $tuple = stockInfo($stock);
	$cost = $tuple[0]*$buy;
	if ($cost >$cash){
		echo "<div class='container'>Insufficent Funds </div><br>";
		echo "<div class='container'>Cost: $cost </div><br>";
		echo "<div class='container'>Cash Avaliable: $cash</div>";
	}
	else {
		$updated_cash = $cash - $cost;
		$j = 0;
		$index = 0;
		$previously_owned = false;
		foreach($stock_name as $name){
			if(strcmp($name, $stock) == 0){
				$previously_owned = true;
				$index = $j;
			}
		$j++;
        }
		if($previously_owned){
			$updated_amount = $stock_owned[$index] + $buy;
			$stock_owned[$index] = $updated_amount;
			$updated_stocks = "";
			for($k = 0; $k<sizeof($stock_name); $k++) {
				$updated_stocks = $updated_stocks . $stock_name[$k] . " " . $stock_owned[$k]. " ";
				}
			$updated_stocks =substr($updated_stocks,0,strlen($updated_stocks)-1);
			}
		else if(strcmp($stocks,"")==0){
			$updated_stocks = $stock . " " . $buy;
		}
		else{
			$updated_stocks = $stocks . " " . $stock . " " . $buy;
			}
		$update_query = "UPDATE checkit SET stocks='$updated_stocks',cash = '$updated_cash' WHERE email = '$email'";
		$result2 = performQuery($dbc,$update_query);
		echo "<div class='container'><h1>Congratulations, you successfully purchased $buy shares of $stock</h1></div>";
			}
}
		
function updateSell($dbc,$sell,$stock) {
	$email = $_COOKIE['email'];
	$profile_query = "select * from checkit where email = '$email'";
    $result = performQuery($dbc,$profile_query);
    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        
    $first = $row['first'];
    $last = $row['last'];
    $cash = $row['cash'];
    $stocks = $row['stocks'];
    $stock_array = explode(" ",$stocks);
    $stock_name = array();
    $stock_owned = array();
    $i = 1;
    foreach($stock_array as $value){
    	if($i%2==0){
        	$stock_owned[] = $value;
        }
        else{
        	$stock_name[] = $value;
        }
        	$i= $i+1;
    }
    $tuple = stockInfo($stock);
	$earnings = $tuple[0]*$sell;
	$updated_cash = $cash + $earnings;
	$j = 0;
	$index = 0;
	foreach($stock_name as $name){
	if(strcmp($name, $stock) == 0){
			$index = $j;
		}
	$j++;
	}
	if($stock_owned[$index]<$sell){
		 die("<div class='container'><h1>You cannot sell $sell shares because you only own $stock_owned[$index]</h1></div>");
		}
	if($stock_owned[$index]==$sell){
		unset($stock_owned[$index]);
		$stock_owned = array_values($stock_owned);
		unset($stock_name[$index]);
		$stock_name = array_values($stock_name);
		
		$updated_stocks = "";
		for($k = 0; $k<sizeof($stock_name); $k++) {
			$updated_stocks = $updated_stocks . $stock_name[$k] . " " . $stock_owned[$k]. " ";
			}
		$updated_stocks =substr($updated_stocks,0,strlen($updated_stocks)-1);
		}
	else{
		$updated_amount = $stock_owned[$index] - $sell;
		$stock_owned[$index] = $updated_amount;
		$updated_stocks = "";
		for($k = 0; $k<sizeof($stock_name); $k++) {
			$updated_stocks = $updated_stocks . $stock_name[$k] . " " . $stock_owned[$k]. " ";
			}
		$updated_stocks =substr($updated_stocks,0,strlen($updated_stocks)-1);
	}
		$update_query = "UPDATE checkit SET stocks='$updated_stocks',cash = '$updated_cash' WHERE email = '$email'";
		$result2 = performQuery($dbc,$update_query);
		echo "<div class='container'><h1>Congratulations, you successfully sold $sell shares of $stock</h1></div>";
}
		
function stockInfo($stock_name) {
  		$page = 'http://finance.yahoo.com/q?s=' . $stock_name;
    	$content = file_get_contents($page);
    	$stocklower = strtolower($stock_name);
    	$value_pattern = "!yfs_l84_$stocklower\">([0-9,]+\.[0-9]*)!";
    	$change_pattern = "!yfs_p43_$stocklower\">\\([0-9]{1,2}\\.[0-9]{2}%\\)!";
      
    	preg_match_all($value_pattern, $content, $value_res);
    	preg_match_all($change_pattern, $content, $change_res);
    	$error_pattern = "!no result!";
    	preg_match_all($error_pattern, $content, $error_res);
    	//echo "Error res is " . $error_res . "<br>";
    	//echo "error res [o][o] is " . $error_res[0][0] . "<br>";
    	if (!isset($value_res[0][0])) {
    		die("Invalid stock ticker, please
    				<a href='http://cscilab.bc.edu/~oconnonx/CheckIt/index.php'>Try again</a>");
    	}
      
      	$change =  htmlentities($change_res[0][0]);
      	$change1 = substr($change,-7);
		$x = strpos($change1,"(");
      	if($x===FALSE) {
      		$change1 = "(" . $change1;
      	}
		$price = htmlentities($value_res[1][0]);
		
			
      	return array ($price,$change1);
    }
    
?>