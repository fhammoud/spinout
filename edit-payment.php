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
	<h1>Edit Payment</h1>
    <?php
		$id = mysqli_real_escape_string($link, $_GET['id']);
		$query = $link->query('SELECT company FROM payments WHERE id='.$id);
		$row = mysqli_fetch_array($query);
		$comp = $row[0];
		
		if (isset($_POST['submit']))
		{
			$company = mysqli_real_escape_string($link,$_POST['company']);
			$date = mysqli_real_escape_string($link,$_POST['date']);
			$amount = mysqli_real_escape_string($link,$_POST['amount']);
			$method = mysqli_real_escape_string($link,$_POST['payment-method']);
			
			if (!empty($_POST['date']) && !empty($_POST['amount']) && !empty($_POST['payment-method']))
			{
				$query = $link->query('UPDATE payments SET 
										company="'.$company.'",
										date="'.$date.'",
										amount='.$amount.',
										type="'.$method.'"
										WHERE 
										id='.$id);
				
				if ($company !== $comp)
				{
					$payments = 0;
					$purchases = 0;
					$query = $link->query('SELECT amount FROM payments WHERE company="'.$comp.'"');
					while ($row = mysqli_fetch_array($query))
					{
						$payments += $row[0];
					}
					
					$query = $link->query('SELECT total FROM invoices WHERE company="'.$comp.'"');
					while ($row = mysqli_fetch_array($query))
					{
						$purchases += $row[0];
					}
					
					$balance = $purchases - $payments;
								
					$query = $link->query('UPDATE balances SET
											balance='.$balance.'
											WHERE company="'.$comp.'"');
				}
				
				$payments = 0;
				$purchases = 0;
				$query = $link->query('SELECT amount FROM payments WHERE company="'.$company.'"');
				while ($row = mysqli_fetch_array($query))
				{
					$payments += $row[0];
				}
				
				$query = $link->query('SELECT total FROM invoices WHERE company="'.$company.'"');
				while ($row = mysqli_fetch_array($query))
				{
					$purchases += $row[0];
				}
				
				$balance = $purchases - $payments;
							
				$query = $link->query('UPDATE balances SET
										balance='.$balance.'
										WHERE company="'.$company.'"');
	
				echo "<h3>Edit successful</h3>";
			}
			else
				echo "<h3 style='color:#F00'>Please fill all fields</h3>";
		}
		
		$query = $link->query('SELECT * FROM payments WHERE id='.$id);
		$row = mysqli_fetch_array($query);
	?>
    
    <form method="post" action="<?php echo 'edit-payment.php?id='.$id; ?>">
        <label for="company1">Company</label>
        <?php
			$result = $link->query('SELECT company FROM balances');
			echo "<select name='company'>";
			while ($row1 = mysqli_fetch_assoc($result))
			{
				if ($row1['company'] === $row[1])
					echo "<option value='".$row1['company']."' selected>";
				else
					echo "<option value='".$row1['company']."'>";
				echo $row1['company']."</option>";
			}
			echo "</select>";
		?>
		<br>
        <label for="date">Date</label><input type="date" name="date" value="<?php echo $row[2]; ?>"><br>
        <label for="amount">Amount</label><input class="toadd" type="number" name="amount" step="0.01" min="0" value="<?php echo $row[3]; ?>"><br>
        <label for="payment-method">Payment Method</label>
        	<input type="radio" class="myradio" name="payment-method" value="Cash" <?php if ($row[4] === 'Cash') echo 'checked'; ?>>Cash
        	<input type="radio" class="myradio" name="payment-method" value="Returns" <?php if ($row[4] === 'Returns') echo 'checked'; ?>>Returns<br>
        <input type="submit" name="submit" value="Save" class="submit-button">
    </form>
    <?php
		$link->close();
	?>
</div>
<?php else : header('Location: main.php'); endif ?>
</body>
</html>