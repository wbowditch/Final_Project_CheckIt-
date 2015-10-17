<?php
ob_start();
include ('include/dbconn.php');
$dbc = connectToDB();
$cookie_email = "email";
$cookie_pass  = "pass";
if (isset($_COOKIE[$cookie_email]) && isset($_COOKIE[$cookie_pass])){
      $email = $_COOKIE[$cookie_email];
      $password = $_COOKIE[$cookie_pass];
} else {
      $email = $_POST['email'];
      $password = $_POST['password'];
      if (validProfile($password,$email,$dbc)) {
        setcookie($cookie_email, $email, time() + (86400 * 30), "/"); // 86400 = 1 day
        setcookie($cookie_pass, $password, time() + (86400 * 30), "/"); // 86400 = 1 day
      }
}
function init(){
    $cookie_email = "email";
    $cookie_pass  = "pass"; 
    if (isset($_COOKIE[$cookie_email]) && isset($_COOKIE[$cookie_pass])){
      $email = $_COOKIE[$cookie_email];
      $password = $_COOKIE[$cookie_pass];
    } else {
        $email = $_POST['email'];
        $password = $_POST['password'];
    }
    $dbc = connectToDB();
    
    if (validProfile($password,$email,$dbc)) {
      //echo "Valid profile";
      displayProfile($dbc,$email,$password);
    }
    else {
      echo "<h1 class='container'>Invalid Password</h1>";
    }
  }
  
  function validProfile($password,$email,$dbc){
    $sha_password = sha1($password);
    $email_query = "select email,password from checkit where email = '$email' and password = '$sha_password'";
    // and password = '$sha_password'";
    $result = performQuery($dbc, $email_query);
    $rows = mysqli_num_rows($result);
    if($rows == 0)
      return false;
    else {
      return true;
    }
  }
  function stockInfo($stock_name) {
      $page = 'http://finance.yahoo.com/q?s=' . $stock_name;
        $content = file_get_contents($page);
        $stocklower = strtolower($stock_name);
        $value_pattern = "!yfs_l84_$stocklower\">([0-9,]+\.[0-9]*)!";
        $change_pattern = "!yfs_p43_$stocklower\">\\([0-9]{1,2}\\.[0-9]{2}%\\)!";
      
      preg_match_all($value_pattern, $content, $value_res);
      preg_match_all($change_pattern, $content, $change_res);
      
    $price = htmlentities($value_res[1][0]);
  
        $change =  htmlentities($change_res[0][0]);
        $change1 = substr($change,-7);
    $x = strpos($change1,"(");
        if($x===FALSE) {
          $change1 = "(" . $change1;
        }
        return array ($price,$change1);
    }
    ?>
    <!DOCTYPE html>
<html lang="en">
<head>
  <title>Profile</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
</head>
<body>

<nav class="navbar navbar-fixed-top navbar-inverse">
	  <div class="container">
		<div class="navbar-header">
		  <a class="navbar-brand">Checkit Stock Portfolio</a>
		</div>
		<div id="navbar" class="collapse navbar-collapse">
		  <ul class="nav navbar-nav">
			<li><a href="./index.php">Home</a></li>
			<li class="active"><a href="../profile.php">Profile</a></li>
		  </ul>
		</div><!-- /.nav-collapse -->
	  </div><!-- /.container -->
	</nav><!-- /.navbar -->
	<h1></h1>
<?php
      function displayProfile($dbc,$email,$password) {
       $sha_password = sha1($password);
        $profile_query = "select * from checkit where email = '$email' and password = '$sha_password'";
        $result = performQuery($dbc,$profile_query);
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        
        $first = $row['first'];
        $last = $row['last'];
        $cash = $row['cash'];
        $stocks = $row['stocks'];
        if(strcmp($stocks,"")==0){
          echo "<div class='container'>You do not own any stocks!</div>";
        }
        else{
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
        }$name_price = array();
          $name_change = array();
          foreach($stock_name as $value){
            $type = stockInfo($value);
            $name_price[$value] = $type[0];
            $name_change[$value] = $type[1];
          }
      echo "<h1>Hello $first $last!</h1><br>\n";
      ?>
      <div class = "container">
      <h2>Your Portfolio</h2>
      <table class = "table">
      <thread>
        <tr>
          <th>Ticker</th>
          <th>Value</th>
          <th>Change</th>
          <th>Amount Owned</th>
        </tr>
    </thread>
      <?php
      $sum = 0;
      $total_change = 0;
      $class = "success";
      for ( $i = 0; $i < sizeof($stock_name); $i++ ) {
      	$class = $class == "success" ? "info": "success";
        $stock = $stock_name[$i];
        $sum = $sum + ($name_price[$stock])*($stock_owned[$i]);
        $total_change = $total_change + substr($name_change[$stock],1,strlen($name_change[$stock])-1);
        echo "<tbody>
        	<tr class = $class>
              <td>$stock</td><td>$name_price[$stock]</td><td>$name_change[$stock]</td><td>$stock_owned[$i]</td>
            </tr> ";
            
      }
      $average_change = $total_change/sizeof($stock_name);
      $average_change = substr($average_change,0,4);
      ?>
	  </tbody>
      </table>
      </div>
      <?php
      if (strcmp($email, "oconnonx@bc.edu") == 0 ||
          strcmp($email, "bowditcw@bc.edu") == 0 ||
          strcmp($email, "churo@bc.edu")    == 0  ) {
          echo "<br><form class='form-inline container' action='./admin_console.php'>
                    <div class='form-group'>
                    <input class='form-control' type='submit' value='Admin Console'>
                    </div>
                    </form>
                ";
      }
      
      echo "
      <br><form class='form-inline container' method='get' action='./include/clearcookies.php'>
        <div class='form-group'>
        <input class='form-control' type='submit' name='logout' value='Logout'>
        </div>
      </form>
      ";
        $name_price = array();
          $name_change = array();
          foreach($stock_name as $value){
            $type = stockInfo($value);
            $name_price[$value] = $type[0];
            $name_change[$value] = $type[1];
          }
      ?>
    
      <?php
      
      
      echo "<div class='container'>Portfolio Value: &#36;$sum<br>\n";
      echo "Average Change: $average_change&#37;<br></div>\n";
        }
       
        //print_r($stock_name);
      ?>
    <br><h1 class='container'>Buy Stock</h1>
    <form class="form-inline container" method='get' action='include/stocksearch.php' onsubmit='return validate2();'>
            <div class="form-group">
        <input type="hidden" name="email" value= "<?php echo $email ?>">
        <input type="hidden" name="cash" value= "<?php echo $cash ?>">
            <input class="form-control" id='buy_query' type='text' name='buy_query' placeholder='Enter stock ticker'><br>
            <span class="ereport" id="searcherror2"></span><br>
            <input class="form-control" type='submit' name='buy_search' value='Buy'>
          </div>
        </form>
        <br>


        <br>
        <h1 class='container'>Sell Stock</h1>
      <form class="form-inline container" method='get' action='include/stocksearch.php' onsubmit='return validate3();'>
        <div class="form-group">
        <input type="hidden" name="email" value= "<?php echo $email ?>">
        <input type="hidden" name="cash" value= "<?php echo $cash ?>">
        <input type="hidden" name="stocks" value= "<?php echo $stocks ?>">
            <input id='sell_query' class="form-control" type='text' name='sell_query' placeholder='Enter stock ticker'><br>
            <span class="ereport" id="searcherror3"></span><br>
            <input class="form-control" type='submit' name='sell_search' value='Sell'>
          </div>
        </form>
        <br><br>
        <h1 class='container'>Search Stock</h1>
        <form class="form-inline container" method='get' action='include/stocksearch.php' onsubmit='return validate();'>
            <div class="form-group">
          <input type="hidden" name="email" value= "<?php echo $email ?>">
            <input class="form-control" id='search' type='text' name='search' placeholder='Enter stock ticker'><br>
            <span class="ereport" id="searcherror"></span><br>
            <input class="form-control" type='submit' name='submit_search' value='Search'>
          </div>
        </form><br>
        <div class='form-inline' id="right">
      <h5> Your Chat Feed Today! </h5>
      <input class="form-control" type="text" name="textBox" id="textBox" placeholder="Send a message..."/>
      <button class="form-control" onclick="sendMessage()">Send</button>
      <div id="chat"></div>
    </div>
      <script src="http://cdn.pubnub.com/pubnub-3.7.1.min.js"></script>
      <script src="/~oconnonx/CheckIt/js/main.js"></script>

        <form class="form-inline container" method='get' action='include/cash_ops.php'>
            <input class="form-control" type="text" name="amount" placeholder="Enter amount">
            <input class="form-control" type="submit" name="deposit" value="Deposit">
            <input class="form-control" type="submit" name="withdraw" value="Withdraw">
            <input type="hidden" name="cash" value= "<?php echo $cash ?>">
            <input type="hidden" name="email" value="<?php echo $email ?>">

        </form>
        <?php
          
    echo "<br><div class='container'>Cash: &#36;$cash</div><br>\n";
        
          
      }
?>

<!-- =========================================== -->
<!-- Welcome to CheckIt, your personal portfolio -->
<!-- Flaherty     Bowditch      Chu  -->
<!-- =========================================== -->

<!DOCTYPE html>
<html lang="en">
<head>
     <link href="http://getbootstrap.com/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
     <link href="http://getbootstrap.com/examples/signin/signin.css" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
     <script src="./Signin_files/ie-emulation-modes-warning.js"></script><style type="text/css"></style>
     <meta charset="utf-8" />
     <title>CheckIt</title>
     <link rel="stylesheet" type="text/css" href="CSS/check.css">
     <script>
           function validate(){
            var validSearch = validateSearch();
            if (!validSearch) return false;
            return true;
          }
          function validateSearch(){
            var thesearch= document.getElementById("search").value ;
            
            if (thesearch.length < 1 || thesearch=='Enter stock ticker') {
              var errorrpt=document.getElementById("searcherror");
              errorrpt.innerHTML = "Please enter a stock ticker";
              return false;
            } 
            var errorrpt=document.getElementById("searcherror");
            errorrpt.innerHTML = "";
        
            return true;
          }
          
          function validate2(){
            var validSearch = validateSearch2();
            if (!validSearch) return false;
            return true;
          }
          function validateSearch2(){
            var thesearch= document.getElementById("buy_query").value ;
            
            if (thesearch.length < 1 || thesearch=='Enter stock ticker') {
              var errorrpt=document.getElementById("searcherror2");
              errorrpt.innerHTML = "Please enter a stock ticker";
              return false;
            } 
            var errorrpt=document.getElementById("searcherror2");
            errorrpt.innerHTML = "";
        
            return true;
          }
          
          function validate3(){
            var validSearch = validateSearch3();
            if (!validSearch) return false;
            return true;
          }
          function validateSearch3(){
            var thesearch= document.getElementById("sell_query").value ;
            
            if (thesearch.length < 1 || thesearch=='Enter stock ticker') {
              var errorrpt=document.getElementById("searcherror3");
              errorrpt.innerHTML = "Please enter a stock ticker";
              return false;
            } 
            var errorrpt=document.getElementById("searcherror3");
            errorrpt.innerHTML = "";
        
            return true;
          }
      </script>
</head>
<body>
  <?php
    init();
  ?>
</body>
</html>