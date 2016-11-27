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
        <li class="active"><a href="new-invoice.php">New Invoice</a></li>
        <li><a href="new-payment.php">New Payment</a></li>        
    </ul>
    	
        
        <?php
		$id = mysqli_real_escape_string($link, $_GET['id']);
		$query = $link->query('SELECT id, company FROM balances WHERE id='.$id);
		$row = mysqli_fetch_assoc($query);
		$company = $row['company'];
		//echo "<a href='edit-company.php?id=$row[id]'><h1>$company</h1></a>";
		echo "<h1>New Invoice for $company</h1>";
		
		if (isset($_POST['submit']))
		{	
			/*if (empty($_POST['new-company']))
			{
				$company = mysqli_real_escape_string($link,$_POST['company']);
			}
			else
			{
				$company = mysqli_real_escape_string($link,$_POST['new-company']);
			}*/
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
			
			$required = array($invoice, $food, $beverages, $shisha, $staff, $cleaning, $consumables, $other, $tax, $payment);
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
				//check if invoice already exists
				$query = $link->query('SELECT * FROM `invoices` WHERE Company = "'.$company.'" AND InvoiceNumber = '.$invoice);
				if (mysqli_num_rows($query) <= 0)
				{
					$query = "INSERT INTO invoices 
						(Company, Date, InvoiceNumber, Food, Beverages, Shisha, StaffFood, CleaningSupplies, Consumables, Other, Tax, PaymentMethod, Total)
						VALUES 
						('$company', '$date', '$invoice', '$food', '$beverages', '$shisha', '$staff', '$cleaning', '$consumables', '$other', '$tax', '$payment', '$total')";
					
					//add invoice if it does not exist
					if(!$link->query($query))
					{
						die("ERROR: " . $link->error);
					}
					else
					{
						$query = $link->query('SELECT company FROM balances WHERE company = "'.$company.'"');
						//check if company on invoice exists
						if (mysqli_num_rows($query) <= 0)
						{
							$query = "INSERT INTO balances (Company, Balance) VALUES ('$company', '$total')";
							//add company and balance if company does not exist
							if (!$link->query($query))
							{
								die("ERROR: " . $link->error);
							}
							else
							{
								echo "<h3>Invoice successfully submitted</h3>";
							}
						}
						else
						{
							$query = $link->query("SELECT balance FROM balances WHERE company='".$company."'");
							$result = mysqli_fetch_array($query);
							$balance = $result[0];
							$balance += $total;
							$query = 'UPDATE balances SET balance='.$balance.' WHERE company="'.$company.'"';
							
							//update balance if company exists
							if (!$link->query($query))
							{
								die("ERROR: " . $link->error);
							}
							else
							{
								echo "<h3>Invoice successfully submitted</h3>";
							}
						}
					}
				}
				else //invoice already exists
				{
					echo "<h3>Invoice ". $invoice . " for " . $company . " already exists</h3>";
				}
			}
			else //some values are missing
			{
				echo "<h3 style='color:#F00'>Please fill all fields</h3>";
			}
		}
		?>
        
        <form onSubmit="return validateForm();" name="invoice-form" class="my-form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        	<label for="company">Company</label><input type="text" name="company" value="<?php echo $company ?>" readonly />
        	<?php
				/*$result = $link->query('SELECT company FROM balances');
				echo "<select name='company'>";
				while ($row = mysqli_fetch_assoc($result))
				{
					if ($row['company'] === $company)
						echo "<option value='".$row['company']."' selected>";
					else
						echo "<option value='".$row['company']."'>";
					echo $row['company']."</option>";
				}
				echo "</select>";
				$link->close();*/
			?>
            <br>
        	<!--<label for="new-company">New Company</label><input type="text" name="new-company" value="<?php /*if (!empty($_POST['new-company'])) echo $company;*/ ?>"/><br>-->
            <label for="date">Date</label><input type="date" name="date" value="<?php echo $date; ?>"/><br>
            <label for="invoice-number">Invoice Number</label><input type="number" name="invoice-number" min="0" value="<?php echo $invoice; ?>"/><br>
        	<label for="food">Food</label><input class="toadd" type="number" name="food" step="0.01" min="0" value="<?php echo $food; ?>"/><br>
            <label for="beverages">Beverages</label><input class="toadd" type="number" name="beverages" step="0.01" min="0" value="<?php echo $beverages; ?>"/><br>
            <label for="shisha">Shisha</label><input class="toadd" type="number" name="shisha" step="0.01" min="0" value="<?php echo $shisha; ?>"/><br>
            <label for="staff-food">Staff Food</label><input class="toadd" type="number" name="staff-food" step="0.01" min="0" value="<?php echo $staff; ?>"/><br>
            <label for="cleaning-supplies">Cleaning Supplies</label><input class="toadd" type="number" name="cleaning-supplies" step="0.01" min="0" value="<?php echo $cleaning;?>"/><br>
            <label for="consumables">Consumables</label><input class="toadd" type="number" name="consumables" step="0.01" min="0" value="<?php echo $consumables; ?>"/><br>
            <label for="other">Other</label><input class="toadd" type="number" name="other" step="0.01" min="0" value="<?php echo $other; ?>"/><br>
            <label for="tax">Tax</label><input class="toadd" type="number" name="tax" step="0.01" min="0" value="<?php echo $tax; ?>"/><br>
            <label for="payment-method">Payment Method</label>
            	<input type="radio" class="myradio" name="payment-method" value="Cash" <?php if ($payment === 'Cash') echo 'checked'; ?>/>Cash
            	<input type="radio" class="myradio" name="payment-method" value="Credit" <?php if ($payment === 'Credit') echo 'checked'; ?>/>Credit<br>
        	<label for="total">Total</label><h4 class="total-amount">Total Amount</h4><br>
            <input type="submit" name="submit" value="Submit" class="btn btn-primary" />
        </form>
</div>
<?php else : header('Location: main.php'); endif ?>
</body>
</html>