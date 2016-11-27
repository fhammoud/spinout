<?php
	include_once 'includes/connect.php';
	include_once 'includes/functions.php';
	
	sec_session_start();
?>

<!DOCTYPE html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
 	<meta name="viewport" content="width=device-width, initial-scale=1"/>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css"/>
    <link rel="stylesheet" type="text/css" href="styles/stylesheet.css"/>
    <script type="text/JavaScript" src="js/script.js"></script>
    <script type="text/JavaScript" src="js/sha512.js"></script>
    <script type="text/JavaScript" src="js/forms.js"></script>
	<title>Phoenix Trading International</title>
</head>

<body>
<?php if (login_check($link) == true) : ?>
<div class="container">
    <div class="jumbotron">
        <p style="text-align:right">Logged in as <?php echo htmlentities($_SESSION['username']) ?>. <a href="includes/logout.php">Logout</a></p>
        <p style="text-align:right"><a href="manage-account.php">Manage Account</a></p>
        <?php
            if (htmlentities($_SESSION['username']) === 'mahdi64')
            {
                echo '<p style="text-align:right"><a href="employee-registration.php">Manage Employees</a></p>';
            }
        ?>
        <h1>SpinOut</h1>
    </div>
	<ul class="nav nav-tabs">
    	<li class="active"><a href="accounts.php">Purchases</a></li>
        <li><a href="#">Sales</a></li>       
   	</ul>
	<ul class="nav nav-tabs">
        <li><a href="accounts.php">Accounts</a></li>
        <li class="active"><a href="totals.php">Totals</a></li>
        <li><a href="new-invoice.php">New Invoice</a></li>
        <li><a href="new-payment.php">New Payment</a></li>        
    </ul>
	<?php
		if (isset($_POST['submit']))
		{
			$from = mysqli_real_escape_string($link,$_POST['from']);
			$to = mysqli_real_escape_string($link,$_POST['to']);
		}
		else
		{
			$from = date('Y-m') .'-01';
			$to = date('Y-m-d');
		}
		
		$query = $link->query('SELECT SUM(food) FROM invoices WHERE date BETWEEN "'.$from.'" AND "'.$to.'"');
		$food = mysqli_fetch_array($query);
		$query = $link->query('SELECT SUM(beverages) FROM invoices WHERE date BETWEEN "'.$from.'" AND "'.$to.'"');
		$bev = mysqli_fetch_array($query);
		$query = $link->query('SELECT SUM(shisha) FROM invoices WHERE date BETWEEN "'.$from.'" AND "'.$to.'"');
		$shisha = mysqli_fetch_array($query);
		$query = $link->query('SELECT SUM(stafffood) FROM invoices WHERE date BETWEEN "'.$from.'" AND "'.$to.'"');
		$staff = mysqli_fetch_array($query);
		$query = $link->query('SELECT SUM(cleaningsupplies) FROM invoices WHERE date BETWEEN "'.$from.'" AND "'.$to.'"');
		$cleaning = mysqli_fetch_array($query);
		$query = $link->query('SELECT SUM(consumables) FROM invoices WHERE date BETWEEN "'.$from.'" AND "'.$to.'"');
		$consumables = mysqli_fetch_array($query);
		$query = $link->query('SELECT SUM(other) FROM invoices WHERE date BETWEEN "'.$from.'" AND "'.$to.'"');
		$other = mysqli_fetch_array($query);
		$total = $food[0]+$bev[0]+$shisha[0]+$staff[0]+$cleaning[0]+$consumables[0]+$other[0];
	?>
	<h1>Totals</h1>
    <table id="accounts-table">
    	<tr><td></td><td><form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    	<label for='from' style='width:50px'>From</label><input type='date' name='from' value="<?php echo $from; ?>">
		<br><label for='to' style='width:50px'>To</label><input type='date' name='to' value="<?php echo $to; ?>">
		<br><input type='submit' name='submit' class="submit-button" value='Refresh'></td></tr>
    	<tr class="heading"><td><h2>Category</h2></td><td><h2>Total</h2></td></tr>
        <tr class="my-row"><td><h3>Food</h3></td><td><h3>JD <?php echo number_format($food[0], 2, '.', ','); ?></h3></td></tr>
        <tr class="my-row"><td><h3>Beverages</h3></td><td><h3>JD <?php echo number_format($bev[0], 2, '.', ','); ?></h3></td></tr>
        <tr class="my-row"><td><h3>Shisha</h3></td><td><h3>JD <?php echo number_format($shisha[0], 2, '.', ','); ?></h3></td></tr>
        <tr class="my-row"><td><h3>Staff Food</h3></td><td><h3>JD <?php echo number_format($staff[0], 2, '.', ','); ?></h3></td></tr>
        <tr class="my-row"><td><h3>Cleaning Supplies</h3></td><td><h3>JD <?php echo number_format($cleaning[0], 2, '.', ','); ?></h3></td></tr>
        <tr class="my-row"><td><h3>Consumables</h3></td><td><h3>JD <?php echo number_format($consumables[0], 2, '.', ','); ?></h3></td></tr>
        <tr class="my-row"><td><h3>Other</h3></td><td><h3>JD <?php echo number_format($other[0], 2, '.', ','); ?></h3></td></tr>
        <tr class="my-row" style="color:#F00"><td><h3>Total</h3></td><td><h3>JD <?php echo number_format($total, 2, '.', ','); ?></h3></td></tr>
    </table>
</div>
<?php else : header('Location: main.php'); endif ?>
</body>
</html>