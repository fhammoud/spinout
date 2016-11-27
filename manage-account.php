<?php
include_once 'includes/connect.php';
include_once 'includes/functions.php';
sec_session_start();
include_once 'includes/manage-account.inc.php';
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
	<h1>Manage Account</h1>
</div>
<table class="employees-table">
<?php
	$user = htmlentities($_SESSION['username']);
	$query = $link->query('SELECT username, email FROM spinout_employees WHERE username="'.$user.'"');
	$row = mysqli_fetch_assoc($query);
	echo '<tr class="my-row"><td><h3>Username</h3></td><td><h3>'.$row['username'].'</h3></td></tr>';
	echo '<tr class="my-row"><td><h3>E-mail</h3></td><td><h3>'.$row['email'].'</h3></td></tr>';
?>
</table>
 <?php
	if (!empty($error_msg)) {
		echo $error_msg;
	}
?>
<h2>Change E-mail</h2>
<form action="<?php echo esc_url($_SERVER['PHP_SELF']); ?>" 
      method="post" 
      name="change-email-form">
	<label for="new-email">New e-mail</label><input type="text" name="new-email" id="new-email"/><br/>
    <label for="password">Password</label><input type="password" name="password" id="password"/><br/>
    <input type="submit" class="submit-button" value="Submit" onclick="return formhash(this.form,
                                   										this.form.password);">
</form>

<h2>Change Password</h2>
<form action="<?php echo esc_url($_SERVER['PHP_SELF']); ?>" 
      method="post" 
      name="change-password-form">
	<label for="oldpw">Old Password</label><input type="password" name="oldpw" id="oldpw"/><br/>
    <label for="newpw">New Password</label><input type="password" name="newpw" id="newpw"/><br/>
    <label for="confirmpw">Confirm Password</label><input type="password" name="confirmpw" id="confirmpw"/><br/>
    <input type="submit" class="submit-button" value="Submit" onclick="return newpwhash(this.form,
                                   										this.form.oldpw,
                                   										this.form.newpw,
                                   										this.form.confirmpw);"/>
</form>
<a href="accounts.php"><div class="submit-button">Accounts</div></a>
</div>
<?php else : header('Location: main.php'); endif ?>
</body>
</html>