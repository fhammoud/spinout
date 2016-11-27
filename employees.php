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
<?php if (login_check($link) == true && htmlentities($_SESSION['username']) === 'mahdi64') : ?>
<div class="container">
	<div class="jumbotron">
		<h1>SpinOut Employees</h1>
	</div>
	
    <table class='employees-table'>
    	<tr class="heading"><td><h2>Username</h2></td><td><h2>E-mail</h2></td></tr>
        <?php
			$employees = $link->query('SELECT username, email FROM spinout_employees ORDER BY username');
			while ($row = mysqli_fetch_assoc($employees))
			{
				echo '<tr class="my-row"><td><h3>';
				echo $row['username'].'</h3></td><td><h3>'.$row['email'];
				echo '</h3></td></tr>';
			}
		?>
    </table>
    
    
</div>
<?php else : header('Location: main.php'); endif ?>
</body>