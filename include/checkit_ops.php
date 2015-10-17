<!DOCTYPE html>
<?php
include ('dbconn.php');
?>
<html>
<head>
	<title>CheckIt</title>
</head>
<body>

<?php
	if (isset($_GET['cash'])){
		$cash = $_GET['cash'];
	}
	$dbc= @mysqli_connect("localhost", "bowditcw", "H8zFAA2E", "bowditcw") or
					die("Connect failed3: ". mysqli_connect_error());
					
	
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
			else echo "Email Already Exists
						<br><a href='../index.php'>Go Back</a>";
		}
		else echo 'No Email Entered';
	}
?>

</body>
</html>
<?php
function createAccount($dbc,$first,$last,$email,$password){
	$query = "INSERT INTO checkit VALUES ('$first','$last','0.0','', '$password','$email')";
	$result = performQuery($dbc, $query);
	if ( ! $result )
		echo "<br>Oops! Something went wrong";
	else
		echo "<br>Congratulations on joining CheckIt!";
	echo "<a href='http://cscilab.bc.edu/~oconnonx/CheckIt/index.php'>Back to Home Page</a>";
}
function updateBuy($dbc,$buy,$stock,$email) {
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
		echo "Insufficent Funds: <br>";
		echo "Cost: $cost <br>";
		echo "Cash Avaliable: $cash";
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
		echo "Congratulations, you successfully purchased $buy shares of $stock";
		echo "<br><a href='../profile.php'>Return to your Profile</a>";
			}
}
		
function updateSell($dbc,$sell,$stock,$email) {
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
		 die("You cannot sell $sell shares because you only own $stock_owned[$index]");
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
		echo "Congratulations, you successfully sold $sell shares of $stock";
		echo "<br><a href='../profile.php'>Return to your Profile</a>";		
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