<?php
include ('include/dbconn.php');
?>
<!DOCTYPE html>

<!--
	CheckIt Admin Console
	Flaherty Chu Bowditch
 -->

<html lang="en">
<head>
     <meta charset="utf-8" />
     <title>CheckIt Admin Console</title>
     <link rel="stylesheet" type="text/css" href="CSS/check.css">
     <script type="text/javascript">


     		<!-- ===================================== -->
     		<!-- Functions to validate admin mail form -->
     		<!-- ===================================== -->

     		function validateadmin(){
     			var validSub = validateSubject();
     			var validBody = validateBody();
     			var validPW = validateAdminPassword();
     			if (validSub && validBody && validPW){
     				return true;
     			}
     			return false;
     		}

     		function validateSubject() {
     			var thesubject = document.getElementById('adminsubject').value;

     			if (thesubject.length < 1) {
     				var errorrpt=document.getElementById('adminsubjecterror');
     				errorrpt.innerHTML = "Please enter a subject";
     				return false;
     			}
     			var errorrpt=document.getElementById('adminsubjecterror');
     			errorrpt.innerHTML = "";

     			return true;
     		}

     		function validateBody(){
     			var thebody = document.getElementById('adminemail').value;

     			if (thebody.length < 1){
     				var errorrpt=document.getElementById('adminemailerror');
				    errorrpt.innerHTML = "Please enter a message";
				    return false;
				}
				var errorrpt=document.getElementById('adminemailerror');
				errorrpt.innerHTML = "";

     			return true;
     		}

     		function validateAdminPassword() {
     			var thepassword = document.getElementById('adminpassword').value;

     			if (thepassword.length < 1){
     				var errorrpt=document.getElementById("adminpassworderror");
					errorrpt.innerHTML = "Please enter a password";
	 				return false;
     			}

     			var errorrpt=document.getElementById("adminpassworderror");
				errorrpt.innerHTML = "";

	 			return true;
     		}
     	</script>
</head>
<body>

	<h1>Admin Console</h1>

	<?php
		displayadmin();
		if (isset($_POST['adminsend'])){
			handleadmin();
		}
	?>

</body>
</html>

<?php

function displayadmin() {
?>
	<fieldset>
		<table>
			<tr>
				<th>First Name</th>
				<th>Last Name</th>
				<th>Email</th>
			</tr>
			<?php
			$dbc = connectToDB();
			$tableQuery = "SELECT first, last, email from checkit";
			$result = performQuery($dbc, $tableQuery);

			$i = 0;

			while (@extract(mysqli_fetch_array($result, MYSQLI_ASSOC))){
				if ($i % 2 == 0){
					echo "<tr bgcolor='#6A5ACD'><td>$first</td>";
					echo "<td>$last</td>";
					echo "<td>$email</td>";
					echo "</tr>";
				} else {
					echo "<tr bgcolor='#FFD700'><td>$first</td>";
					echo "<td>$last</td>";
					echo "<td>$email</td>";
					echo "</tr>";
				}

				$i++;

			}
			disconnectFromDB($dbc);

			?>
		</table>
	</fieldset>



	<form method="post" name="adminform" onsubmit="return validateadmin()">
		<fieldset id="fieldid"><legend>Create Group Mail</legend>
			Subject:
			<input type='text' name='adminsubject' id='adminsubject'>
			<span id='adminsubjecterror'></span><br>
			Body:
			<input type='text' name='adminemail' id='adminemail'>
			<span id='adminemailerror'></span><br>
			Admin Password:
			<input type='password' name='adminpassword' id='adminpassword'>
			<span id='adminpassworderror'></span><br>
			<input class='buttonleft' type='submit' name='adminsend' id='adminsend' value='Send Mail'><br>

			<a href="./profile.php">Profile</a><br>
			<a href="./index.php">Home Page</a><br>

		</fieldset>
	</form>

<?php
}

// handleadmin
function handleadmin(){
	// Make sure all got filled out

	if (!isset($_POST['adminsubject']) || !isset($_POST['adminemail']) || !isset($_POST['adminpassword']) ) {
		die("<h1>Please fill out all the fields.</h1><br>");
	}

	// Get everything
	$subject = $_POST['adminsubject'];
	$body = $_POST['adminemail'];
	$pw = $_POST['adminpassword'];
	$shapw = sha1( $pw );


	if ($shapw != '1785ed6ccf537856a2e5d0935a1ffb2dde2d3ab5'){
		die("<h1>Wrong password</h1><br>");
	}

	$dbc = connectToDB();
	$tableQuery = "SELECT email from checkit";
	$result = performQuery($dbc, $tableQuery);

	while (@extract(mysqli_fetch_array($result, MYSQLI_ASSOC))){
		$to = $email;
		$subject = $subject;
		$body = $body;
		$headers = "From: oconnonx@bc.edu";

		mail($to, $subject, $body, $headers);

	}

	echo "<h1>Mail sent</h1>";

	disconnectFromDB($dbc);
}
?>