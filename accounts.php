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
        <li class="active"><a href="accounts.php">Accounts</a></li>
        <li><a href="totals.php">Totals</a></li>
        <li><a href="new-invoice.php">New Invoice</a></li>
        <li><a href="new-payment.php">New Payment</a></li>        
    </ul>
    <h1>Accounts</h1>
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
	?>
    
	<table id='accounts-table'>
	
	<tr><td></td><td><form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <label for='from' style='width:50px'>From</label><input type='date' name='from' value="<?php echo $from; ?>">
	<br><label for='to' style='width:50px'>To</label><input type='date' name='to' value="<?php echo $to; ?>">
	<br><input type='submit' name='submit' class="submit-button" value='Refresh'></td></tr>
	<tr class="heading"><td><h2>Company</h2></td><td><h2>Total</h2></td><td><h2>Balance</h2></td></tr>
<?php
	$companies = $link->query('SELECT id, company FROM balances ORDER BY company');
	$sum = 0;
	$sum1 = 0;
			
	while ($row = mysqli_fetch_assoc($companies))
	{
		echo "<tr class='my-row'>";
		echo "<td><a href='company.php?id=$row[id]'><h3>" . $row['company'] . "</h3></a></td>";
		echo "<td>JD ";
		
		$query = 'SELECT SUM(total) FROM invoices WHERE company = "'.$row['company'].'" AND date BETWEEN "'.$from.'" AND "'.$to.'"';
		$result = $link->query($query);
		$row1 = mysqli_fetch_array($result);
		echo number_format($row1[0], 2, '.', ',');
		$sum += $row1[0];
		
		echo "</td>";
		
		$query = 'SELECT balance FROM balances WHERE company = "'.$row['company'].'"';
		$result = $link->query($query);
		$row1 = mysqli_fetch_assoc($result);
		echo "<td>JD ".number_format($row1['balance'], 2, '.', ',')."</td>";
		$sum1 += $row1['balance'];
		
		echo "</tr>";
	}
	echo "<tr class='my-row'><td><h3>Total</h3></td><td>JD ".number_format($sum, 2, '.', ',')."</td><td>JD ".number_format($sum1, 2, '.', ',')."</td></tr>";
	echo "</table>";
	$link->close();
?>
</div>
<?php else : header('Location: main.php'); endif ?>
</body>
</html>