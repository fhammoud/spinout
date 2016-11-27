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
        <li><a href="totals.php">Totals</a></li>
        <li><a href="new-invoice.php">New Invoice</a></li>
        <li class="active"><a href="new-payment.php">New Payment</a></li>        
    </ul>
    	<h1>Make a Payment</h1>
        <?php
		if (isset($_POST['submit1']))
		{
			$company1 = mysqli_real_escape_string($link,$_POST['company1']);
			$date1 = mysqli_real_escape_string($link,$_POST['date1']);
			$amount = mysqli_real_escape_string($link,$_POST['amount1']);
			$method = mysqli_real_escape_string($link,$_POST['payment-method1']);
			
			if (!empty($_POST['date1']) && !empty($_POST['amount1']) && !empty($_POST['payment-method1']))
			{
				$query = $link->query('SELECT * FROM `invoices` WHERE Company = "'.$company1.'" AND InvoiceNumber = '.$invoice);
				if (mysqli_num_rows($query) <= 0)
				{
					$query = "INSERT INTO payments (Company, Date, Amount, Type) VALUES ('$company1', '$date1', '$amount', '$method')";
					if(!$link->query($query))
					{
						die("ERROR: " . $link->error);
					}
					else
					{
						$query = "SELECT balance FROM balances WHERE company = '" . $company1 . "'";
						$result = $link->query($query);
						$row = mysqli_fetch_array($result);
						$balance = $row[0];
						$balance -= $amount;
						$query = "UPDATE balances SET balance = ".$balance." WHERE company = '".$company1."'";
						
						if (!$link->query($query))
						{
							die("ERROR: " . $link->error);
						}
						else
						{
							echo "<h3>Payment successfully submitted</h3>";
						}
					}
				}
				else
				{
					echo "<h3>Invoice ". $invoice . " for " . $company1 . " already exists</h3>";
				}
			}
			else
			{
				echo "<h3 style='color:#F00'>Please fill all fields</h3>";
			}
		}
		?>
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <label for="company1">Company</label>
            <?php
                $result = $link->query('SELECT company FROM balances');
                echo "<select name='company1'>";
                while ($row = mysqli_fetch_assoc($result))
                {
                    if ($row['company'] === $company1)
						echo "<option value='".$row['company']."' selected>";
					else
						echo "<option value='".$row['company']."'>";
					echo $row['company']."</option>";
                }
                echo "</select>";
				$link->close();
            ?>
            <br>
            <label for="date1">Date</label><input type="date" name="date1" value="<?php echo $date1; ?>"><br>
            <label for="amount1">Amount</label><input class="toadd" type="number" name="amount1" step="0.01" min="0" value="<?php echo $amount; ?>"><br>
            <label for="payment-method1">Payment Method</label>
            	<input type="radio" class="myradio" name="payment-method1" value="Cash" <?php if ($method === 'Cash') echo 'checked'; ?>>Cash
            	<input type="radio" class="myradio" name="payment-method1" value="Returns" <?php if ($method === 'Returns') echo 'checked'; ?>>Returns<br>
            <input type="submit" name="submit1" value="Submit" class="btn btn-primary">
        </form>
</div>
<?php else : header('Location: main.php'); endif ?>
</body>
</html>