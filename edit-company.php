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
	$query = $link->query('SELECT company FROM balances WHERE id='.$id);
	$row = mysqli_fetch_assoc($query);
	$name = $row['company'];
	
	if (isset($_POST['submit']))
	{
		$newname = mysqli_real_escape_string($link, $_POST['name']);
		
		if (!empty($newname))
		{
			$query = $link->query('UPDATE balances SET company="'.$newname.'" WHERE company="'.$name.'"');
			$query = $link->query('UPDATE invoices SET company="'.$newname.'" WHERE company="'.$name.'"');
			$query = $link->query('UPDATE payments SET company="'.$newname.'" WHERE company="'.$name.'"');
			$link->close();
			$name = $newname;
		}
	}
	
	echo "<h1>$name</h1>";
	
	?>
    <h2>Edit Company Name</h2>
    <form method="post" action="<?php echo 'edit-company.php?id='.$id; ?>">
    	<label for="name">Name</label><input type="text" name="name" value="<?php echo $name; ?>"><br>
        <input type="submit" name="submit" value="Save" class="submit-button">
    </form>
    
    <?php
		if (empty($newname) && isset($_POST['submit']))
			echo "<h3 style='color:#F00'>Please fill all fields</h3>";
		else if (isset($_POST['submit']))
			echo '<h3>Company name successfully changed.</h3>';
	?>
    <a href="<?php echo 'company.php?id='.$id; ?>">
    	<div class="submit-button">
			Back
		</div>
	</a>
</div>
<?php else : header('Location: main.php'); endif ?>
</body>
</html>