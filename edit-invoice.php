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
	<h1>Edit Invoice</h1>
    
	<?php
		$id = mysqli_real_escape_string($link, $_GET['id']);
		$query = $link->query('SELECT company FROM invoices WHERE id='.$id);
		$row = mysqli_fetch_array($query);
		$comp = $row[0];
		
		if (isset($_POST['edit']))
		{	
			$company = mysqli_real_escape_string($link,$_POST['company']);
			$date = mysqli_real_escape_string($link,$_POST['date']);
			$invoice = mysqli_real_escape_string($link,$_POST['invoice-number']);
			$food = mysqli_real_escape_string($link,$_POST['food']);
			$beverages = mysqli_real_escape_string($link,$_POST['beverages']);
			$shisha = mysqli_real_escape_string($link,$_POST['shisha']);
			$staff = mysqli_real_escape_string($link,$_POST['staff-food']);
			$cleaning = mysqli_real_escape_string($link,$_POST['cleaning-supplies']);
			$consumables = mysqli_real_escape_string($link,$_POST['consumables']);
			$other = mysqli_real_escape_string($link,$_POST['other']);
			$tax = mysqli_real_escape_string($link,$_POST['tax']);
			$payment = mysqli_real_escape_string($link,$_POST['payment-method']);
			$total = $food + $beverages + $shisha + $staff + $cleaning + $consumables + $other + $tax;
			
			$required = array($invoice, $food, $beverages, $shisha, $staff, $cleaning, $consumables, $other, $tax);
			$empty = false;
			
			if (empty($date))
				$empty = true;
			
			else
			{
				foreach($required as $key=>$value)
				{
					if (strlen(trim($value)) == 0)
					{
						$empty = true;
						break;
					}
				}
			}
			
			if (!$empty)
			{
				$query = $link->query('UPDATE invoices SET
										company="'.$company.'",
										date="'.$date.'",
										invoicenumber='.$invoice.',
										food='.$food.',
										beverages='.$beverages.',
										shisha='.$shisha.',
										stafffood='.$staff.',
										cleaningsupplies='.$cleaning.',
										consumables='.$consumables.',
										other='.$other.',
										tax='.$tax.',
										paymentmethod="'.$payment.'",
										total='.$total.' 
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
		
		$query = $link->query('SELECT * FROM invoices WHERE id='.$id);
		$row = mysqli_fetch_array($query);
	?>
    
    <form class="invoice-form" method="post" action="<?php echo 'edit-invoice.php?id='.$id; ?>">
    	<label for="company">Company</label>
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
        <label for="invoice-number">Invoice Number</label><input type="number" name="invoice-number" min="0" value="<?php echo $row[3]; ?>"><br>
        <label for="food">Food</label><input class="toadd" type="number" name="food" step="0.01" min="0" value="<?php echo $row[4]; ?>"><br>
        <label for="beverages">Beverages</label><input class="toadd" type="number" name="beverages" step="0.01" min="0" value="<?php echo $row[5]; ?>"><br>
        <label for="shisha">Shisha</label><input class="toadd" type="number" name="shisha" step="0.01" min="0" value="<?php echo $row[6]; ?>"><br>
        <label for="staff-food">Staff Food</label><input class="toadd" type="number" name="staff-food" step="0.01" min="0" value="<?php echo $row[7]; ?>"><br>
        <label for="cleaning-supplies">Cleaning Supplies</label><input class="toadd" type="number" name="cleaning-supplies" step="0.01" min="0" value="<?php echo $row[8]; ?>"><br>
        <label for="consumables">Consumables</label><input class="toadd" type="number" name="consumables" step="0.01" min="0" value="<?php echo $row[9]; ?>"><br>
        <label for="other">Other</label><input class="toadd" type="number" name="other" step="0.01" min="0" value="<?php echo $row[10]; ?>"><br>
        <label for="tax">Tax</label><input class="toadd" type="number" name="tax" step="0.01" min="0" value="<?php echo $row[11]; ?>"><br>
        <label for="payment-method">Payment Method</label><input type="radio" class="myradio" name="payment-method" value="Cash" 
			<?php if ($row[12] === 'Cash') echo "checked"; ?>>Cash
        <input type="radio" class="myradio" name="payment-method" value="Credit" <?php if ($row[12] === 'Credit') echo "checked"; ?>>Credit<br>
        <label for="total">Total</label><h4 class="total-amount">JD <?php echo $row[13]; ?></h4><br>
        <input type="submit" name="edit" value="Save" class="submit-button">
    </form>
    
    <?php
	$link->close();
	?>
</div>
<?php else : header('Location: main.php'); endif ?>
</body>
</html>