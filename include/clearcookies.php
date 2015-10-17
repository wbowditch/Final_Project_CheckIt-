<?php
	if (isset($_COOKIE['email'])){
		unset($_COOKIE['email']);
		unset($_COOKIE['pass']);

		setcookie('email',null,-1, '/');
		setcookie('pass',null,-1, '/');

	}

	header("Location: ../index.php");

?>