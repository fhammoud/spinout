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
<?php
	$id = mysqli_real_escape_string($link, $_GET['id']);
	$query = $link->query('SELECT id, company FROM balances WHERE id='.$id);
	$row = mysqli_fetch_assoc($query);
	$company = $row['company'];
	echo "<a href='edit-company.php?id=$row[id]'><h1>$company</h1></a>";
?>
	<h2>Invoices</h2>
    <a href='new-invoice.php?id=<?php echo $id; ?>'><button class='btn btn-lg btn-primary'>Add an invoice</button></a>
	<table id='company-table'>
    <tr class="heading">
    	<td><h3>Date</h3></td><td><h3>Invoice Number</h3></td><td><h3>Payment Method</h3></td><td><h3>Tax</h3></td><td><h3>Total</h3></td>
    </tr>
    <?php
		$query = $link->query('SELECT id, date, invoicenumber, tax, paymentmethod, total FROM invoices WHERE company="'.$company.'" ORDER BY date');
		$sum_tax = 0;
		$sum_total = 0;
		while ($row = mysqli_fetch_assoc($query))
		{
			echo "<tr class='my-row'>";
			echo "<td><a href='edit-invoice.php?id=$row[id]'><div class='cell-link'>".$row['date']."</div></a></td>
				  <td><a href='edit-invoice.php?id=$row[id]'><div class='cell-link'>".$row['invoicenumber']."</div></a></td>
				  <td><a href='edit-invoice.php?id=$row[id]'><div class='cell-link'>".$row['paymentmethod']."</div></a></td>
				  <td><a href='edit-invoice.php?id=$row[id]'><div class='cell-link'>JD ".number_format($row['tax'], 2, '.', ',')."</div></a></td>
				  <td><a href='edit-invoice.php?id=$row[id]'><div class='cell-link'>JD ".number_format($row['total'], 2, '.', ',')."</div></a></td>";
			echo "</tr>";
			$sum_tax += $row['tax'];
			$sum_total += $row['total'];
		}
		echo "<tr class='my-row-footer'><td><h3>Total</h3></td><td></td><td></td>";
		echo "<td><strong>JD ".number_format($sum_tax, 2, '.', ',')."</td><td><strong>JD ".number_format($sum_total, 2, '.', ',')."</td></tr>";
	?>
    <tr><td><h2>Payments</h2></td></tr>
    <tr class="heading">
    	<td><h3>Date</h3></td><td></td><td><h3>Payment Method</h3></td><td></td><td><h3>Amount</h3></td>
    </tr>
    <?php
		$query = $link->query('SELECT id, date, amount, type FROM payments WHERE company="'.$company.'" ORDER BY date');
		$sum_amount = 0;
		while ($row = mysqli_fetch_assoc($query))
		{
			echo "<tr class='my-row'>";
			echo "<td><a href='edit-payment.php?id=$row[id]'><div class='cell-link'>".$row['date']."</div></a></td>
				  <td><a href='edit-payment.php?id=$row[id]'><div class='cell-link'> - </div></a></td>
				  <td><a href='edit-payment.php?id=$row[id]'><div class='cell-link'>".$row['type']."</div></a></td>
				  <td><a href='edit-payment.php?id=$row[id]'><div class='cell-link'></div> - </a></td>
				  <td><a href='edit-payment.php?id=$row[id]'><div class='cell-link'>JD ".number_format($row['amount'], 2, '.', ',')."</div></a></td>";
			echo "</tr>";
			$sum_amount += $row['amount'];
		}
		echo "<tr class='my-row-footer'><td><h3>Total</h3></td><td></td><td></td><td></td>";
		echo "<td><strong>JD ".number_format($sum_amount, 2, '.', ',')."</td></tr>";
	?>
    
    <tr><td><h2>Balance</h2></td></tr>
    <tr class="heading">
    	<td><h3>Company</h3></td><td></td><td><h3>Invoices</h3></td><td><h3>Payments</h3></td><td><h3>Balance</h3></td>
    </tr>
    <?php
		$query = $link->query('SELECT balance FROM balances WHERE company="'.$company.'"');
		
		echo "<tr class='my-row-footer'><td><h4>".$company."</h4></td><td></td>";
		echo "<td>JD ".number_format($sum_total, 2, '.', ',')."</td><td>JD ".number_format($sum_amount, 2, '.', ',')."</td>";
		$balance = $sum_total - $sum_amount;
		$row = mysqli_fetch_assoc($query);
		if ($balance == $row['balance'])
		{
			echo "<td style='color:#F00'><strong>JD ".number_format($balance, 2, '.', ',')."</td></tr>";
		}
		else
		{
			echo "<td style='color:#F00'>ERROR with balance</td></tr>";
		}
		
		$link->close();
	?>
    </table>
</div>
<?php else : header('Location: main.php'); endif ?>
</body>
</html>