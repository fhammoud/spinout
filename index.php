<?php
include_once 'includes/connect.php';
include_once 'includes/functions.php';
 
sec_session_start();
 
if (login_check($link) == true) {
    $logged = 'in';
} else {
    $logged = 'out';
}
?>
<!DOCTYPE html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
 	<meta name="viewport" content="width=device-width, initial-scale=1"/>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css"/>
    <!--<link rel="stylesheet" type="text/css" href="styles/stylesheet.css"/>-->
    <script type="text/JavaScript" src="js/script.js"></script>
    <script type="text/JavaScript" src="js/sha512.js"></script>
    <script type="text/JavaScript" src="js/forms.js"></script>
	<title>Phoenix Trading International</title>
</head>

<body>
<div class="container">
	<div class="jumbotron">
    	<!--<img src="images/PTI logo.png">-->
    	<h1>SpinOut</h1>
        <h3>Please log in</h3>
    <?php
        if (isset($_GET['error'])) {
            echo '<p class="error">Error Logging In!</p>';
        }
    ?>         
        <form name="login_form" action="includes/process_login.php" method="post">
        	<label for="username">Username</label><input name="username" type="text" id="username"/><br>
            <label for="password">Password</label><input name="password" type="password" id="password"/><br>
            <input class="btn btn-primary" type="submit" name="submit" value="Submit" onclick="formhash(this.form, this.form.password);"/>
        </form>
	<?php
        if (login_check($link) == true)
		{
			echo '<p>Currently logged ' . $logged . ' as ' . htmlentities($_SESSION['username']) . '.</p>';
 			echo '<p>Do you want to change user? <a href="includes/logout.php">Log out</a>.</p>';
        }
		else
		{
        	echo '<p>Currently logged ' . $logged . '.</p>';
            //echo "<p>If you don't have a login, please <a href='registration.php'>register</a></p>";
      	}
	?>
    </div>
</div>
</body>
</html>