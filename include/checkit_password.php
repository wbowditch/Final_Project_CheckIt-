<!DOCTYPE html>
<html>
<head>
	<title>Forgot Password</title>
</head>
<body>
<?php
	/* Use your own database information */
	$dbc= @mysqli_connect("localhost", "bowditcw", "H8zFAA2E", "bowditcw") or
					die("Connect failed3: ". mysqli_connect_error());

	if ( $_GET['email1'] != null ) {
		$email = $_GET['email1'];
			
		$email_query = "select email,first from checkit where email = '$email'";
		$result = mysqli_query($dbc, $email_query) or die("bad query".mysqli_error($dbc));	
		$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
		$email1 = $row['email'];
		$first = $row['first'];
		if( $email1 == null ) {
			echo "Email does not exist!";
			}
		else {
			sendPassword($email,$first,$dbc);
			}
		echo "<br><br>";
		echo "<a href='http://cscilab.bc.edu/~oconnonx/CheckIt/'>Back to Checkit</a>";
		}
?>

</body>
</html>
<?php
function performQuery($dbc, $query){
	$result = mysqli_query($dbc, $query) or die("bad query".mysqli_error($dbc));
	return $result;
}

function createpassword() {// start with an empty password
	$password="";
	
	//define possible characters
	$possible="23456789abcdefghjklmnpwrstuvwxyz";
	
	$password="";
	$length=8;
	
	for ($i=1; $i<=$length; $i++){
		$pick=rand(0, strlen($possible)-1);
		
		// pick a random character from the possible characters
		$passchar=substr($possible, $pick, 1);
		
		$password .= $passchar;
	}
	return $password;
}


function sendPassword($email,$first,$dbc) {
	$to = $email;
	$subject = 'New Password';
	$password = createpassword();
	$body = "Hi $first !
	 Your new password for CheckIt is $password 
Love,
CheckIt";
	$headers = 'From: bowditcw@bc.edu';
	if ( mail($to,$subject,$body,$headers) )
		echo "A new password was sent to $to";
	else
		echo " Mail was not sent ";
	$query = "UPDATE checkit SET password=sha1('$password') WHERE email='$email';";
	performQuery($dbc, $query);
	#echo "<br><br>";
	#echo "<a href='http://cscilab.bc.edu/~bowditcw/club'>Back to Home Page</a>";
	}
?>
