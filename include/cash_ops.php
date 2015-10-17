<?php

include('dbconn.php');

$dbc = connectToDB();

$cash = $_GET['cash'];
$email = $_GET['email'];

if (isset($_GET['deposit'])){
	$deposit_amount = $_GET['amount'];
	$updatedcash = $cash + $deposit_amount;
	$depositQuery = "update checkit set cash='$updatedcash' where email='$email'";

	$d_result = performQuery($dbc, $depositQuery);
}

if (isset($_GET['withdraw'])){
	$withdraw_amount = $_GET['amount'];
	$updatedcash = $cash - $withdraw_amount;

	if ($updatedcash < 0) {
		die();
	}
	$withdrawQuery = "update checkit set cash='$updatedcash' where email='$email'";

	$w_result = performQuery($dbc, $withdrawQuery);
}

header("Location: ../profile.php");

?>