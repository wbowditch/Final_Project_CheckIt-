<?php
include("name_ticker.php");
$email = $_GET['email'];
if (isset($_GET['search'])){
  $stock = $_GET['search'];
  }
if (isset($_GET['buy_query'])){
  $buy_stock = $_GET['buy_query'];
  }
if (isset($_GET['sell_query'])){
  $sell_stock = $_GET['sell_query'];
  }
if (isset($_GET['cash'])){
  $cash = $_GET['cash'];
}
?>

<!DOCTYPE html>

<!-- =========================================== -->
<!-- Welcome to CheckIt, your personal portfolio -->
<!-- Flaherty     Bowditch      Chu  -->
<!-- =========================================== -->


<html lang="en">
<head>
     <meta charset="utf-8" />
     <title>CheckIt Stock Search</title>
     <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap-theme.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script> 
	<link href="http://getbootstrap.com/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="http://getbootstrap.com/examples/signin/signin.css" rel="stylesheet">
	 <link rel="stylesheet" type="text/css" href="CSS/check.css">
      <script type="text/javascript">
      
    function validateBuy(){
      var thebuy = document.getElementById("buy").value ;
      var buy_regex = /^\d+$/;
      if (thebuy.length < 1 || !buy_regex.test(thebuy) ) {
        var errorrpt=document.getElementById("buyerror");
        errorrpt.innerHTML = "Please enter a valid amount to buy";
        return false;
      } 
      var errorrpt=document.getElementById("buyerror");
      errorrpt.innerHTML = "";
  
      return true;
    }
    
    function validateSell(){
      var thesell = document.getElementById("sell").value ;
      var sell_regex = /^\d+$/;
      if (thesell.length < 1 || !thsell.test(thebuy) ) {
        var errorrpt=document.getElementById("sellerror");
        errorrpt.innerHTML = "Please enter a valid amount to buy";
        return false;
      } 
      var errorrpt=document.getElementById("sellerror");
      errorrpt.innerHTML = "";
  
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
			<li><a href="../profile.php">Profile</a></li>
			<li class="active"><a href="./stocksearch.php">Suggestions</a></li>
		  </ul>
		</div><!-- /.nav-collapse -->
	  </div><!-- /.container -->
	</nav><!-- /.navbar -->
	<h1></h1>
  <?php
  	global $ticker_name_arr;
    if( isset($stock)) {
      	$stock_check = name_ticker2($ticker_name_arr,$stock);
  	  	if($stock_check != False) {
  	  		suggestionsDisplay($stock_check,3,$email,0,0);
  	  	}
      	else if(isValid($stock)){
        	searchDisplay($stock);
      }
    }
    if( isset($buy_stock)) {
    	#echo "hello";
      	$stock_check = name_ticker2($ticker_name_arr,$buy_stock);
  	  	if($stock_check != False) {
  	  		suggestionsDisplay($stock_check,1,$email,$cash,0);
  	  		
  	  	}
      	else if(isValid($buy_stock)) {
        	buyDisplay($buy_stock,$email);
      }
    }
    if( isset($sell_stock)) {
    	if(isset($_GET['stocks'] ) ){
        	$stocks = $_GET['stocks'];
        	}
    	$stock_check = name_ticker2($ticker_name_arr,$sell_stock);
  	  	if($stock_check != False) {
  	  		suggestionsDisplay($stock_check,2,$email,$cash,$stocks);
  	  	}
    else if(isValid($sell_stock)) {
    		if(isset($_GET['stocks'] ) ){
        		$stocks = $_GET['stocks'];
        	}
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
        sellDisplay($sell_stock,$email,$stock_name);
      }
    }
        
      
    
  ?>
</body>
</html>
<?php
  function isValid($stock_name) {
      $page = 'http://finance.yahoo.com/q?s=' . $stock_name;
      $stocklower = strtolower($stock_name);
      $content = file_get_contents($page);
      $value_pattern = "!yfs_l84_$stocklower\">([0-9,]+\.[0-9]*)!";
      $change_pattern = "!yfs_p43_$stocklower\">\\([0-9]{1,2}\\.[0-9]{2}%\\)!";
      
      preg_match_all($value_pattern, $content, $value_res);
      preg_match_all($change_pattern, $content, $change_res);
      $error_pattern = "!no result!";
      preg_match_all($error_pattern, $content, $error_res);
      if (!isset($value_res[0][0])) {
        echo "<div class='container'>Invalid stock ticker.</div>";
        return False;
      }
      else {
        return True;
        }}
        
        
        
        
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
        die("Invalid stock ticker.");
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
    
function searchDisplay($stock){
      $tuple = stockInfo($stock);
      $stock = strtoupper($stock);
      echo "The stock $stock is $tuple[0] with a change of $tuple[1]<br><br>";
      $img_src = 'http://chart.finance.yahoo.com/t?s=' . $stock . '&lang=en-US®ion=US&width=600&height=360';
		
      echo "<img src=$img_src >";
      //echo "<br><a href='../profile.php'>Return to your Profile</a>";
	$rss_feed = "http://feeds.finance.yahoo.com/rss/2.0/headline?s=".$stock."&region=US&lang=en-US";
	$rss= new SimpleXMLElement(file_get_contents($rss_feed));
	$title = $rss->channel->title;
	echo "<h1 class='text-center'>$title</h1>";
	$items = $rss->channel->item;
		foreach ($items as $item) {
			echo "<div>
			<h2>$item->title</h2>\n";
			echo '<a class="form-inline" href="' . $item->link . '">' . $item->title . '</a><br>';
			echo $item->description . "<br><br>\n";
			echo "<br></div>";
		}
      
      }
    function buyDisplay($stock,$email){
      $tuple = stockInfo($stock);
      $stock = strtoupper($stock);
      //echo $email;
      echo "<div class='container'>The stock $stock is $tuple[0] with a change of $tuple[1]</div>";
      $img_src = 'http://chart.finance.yahoo.com/t?s=' . $stock . '&lang=en-US®ion=US&width=600&height=360';
      echo "<br><img src=$img_src >";
      $cash = $_GET['cash'];
      ?>
      <form method = "get" action = "checkit_ops.php" onsubmit='return validateBuy();'>
        <input type="hidden" name="stock" value="<?php echo $stock ?>"> 
        <input type="hidden" name="email" value="<?php echo $email ?>">
        <input type="hidden" name="cash" value= "<?php echo $cash ?>">
        <label for="buy">How many stocks do you want to buy?: </label>
        <input type = "text" id = "buy" name = "buy">
        <span class="ereport" id="buyerror"></span>
        <input type = "submit"  name = "buy_submit" value = "Buy">
        <a href="../profile.php">Profile</a>
    </form>
        <?php
      }
      
    function sellDisplay($stock,$email,$stock_name){
      $tuple = stockInfo($stock);
      $stock = strtoupper($stock);
      echo "<div class='container'>The stock $stock is $tuple[0] with a change of $tuple[1]</div>";
      $img_src = 'http://chart.finance.yahoo.com/t?s=' . $stock . '&lang=en-US®ion=US&width=600&height=360';
      echo "<br><img src=$img_src >";
      $previously_owned = false;
    foreach($stock_name as $name){
      if(strcmp($name, $stock) == 0){
        $previously_owned = true;
      }
    }
    if(!$previously_owned){
      echo "<div class='container'><br>You cannot sell $stock because you do not own any shares.</div>";
      }
    else{
        ?>
          <form method = "get" action = "checkit_ops.php" onsubmit='return validateSell();'>
            <input type="hidden" name="stock" value="<?php echo $stock ?>"> 
            <input type="hidden" name="email" value="<?php echo $email ?>">
            <input type="hidden" name="cash" value= "<?php echo $cash ?>">
            <label for="sell">How many stocks do you want to sell?: </label>
          <input type = "text" id = "sell" name = "sell">
          <span class="ereport" id="sellerror"></span>
          <input type = "submit"  name = "sell_submit" value = "Sell"><br>
          <a href="../profile.php">Profile</a>
        </form>
      <?php
      }
      }
      
      function suggestionsDisplay($stock_arr,$action,$email,$cash,$stocks){
      	echo "<h1>Stock ticker not found. Did you mean: </h1>";
      	if($action == 1) $query = "buy_query";
      	if($action ==2) {
      		$cash = $cash . "&stocks=" . urlencode($stocks);
      		$query = "sell_query";
      		}
      	foreach($stock_arr as $value) {
      			#echo $stocks;
      			if($action ==3) {
      			$url = 'http://cscilab.bc.edu/~oconnonx/CheckIt/include/stocksearch.php?email='.$email.'&search='.$value[0].'&submit_search=Search';
  	  			}
  	  			else{
  	  				$url = 'http://cscilab.bc.edu/~oconnonx/CheckIt/include/stocksearch.php?email='.$email."&cash=".$cash."&".$query."=".$value[0];
  	  				}
  	  			#echo $url;
  	  			echo "<a href = $url> $value[1] -> $value[0] </a>";
  	  			echo "<br>";
  	  			}
//   	  	echo "<br><a href='../profile.php'>Return to your Profile</a>";
  	  		}
?>