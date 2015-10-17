<?php
function connectToDB(){
	$dbc= @mysqli_connect("localhost", "bowditcw", "H8zFAA2E", "bowditcw") or
					die("Connect failed: ". mysqli_connect_error());
	return $dbc;
}
function disconnectFromDB($dbc){
	mysqli_close($dbc);
}

function performQuery($dbc, $query){
	//echo "My query is >$query< <br>";
	$result = mysqli_query($dbc, $query) or die("BAD QUERY:<br> <a href='http://cscilab.bc.edu/~oconnonx/BCCSS/index.php?join=Join+BCCSS'>Try Again</a><br> Query error: " . mysqli_error($dbc));
	return $result;
}
?>