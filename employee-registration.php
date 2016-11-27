<?php
include_once 'includes/employee-register.inc.php';
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
            <!-- Registration form to be output if the POST variables are not
            set or if the registration script caused an error. -->
            <h1>SpinOut Employees</h1>
        </div>
        <?php
        if (!empty($error_msg)) {
            echo $error_msg;
        }
        ?>
        
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
        
        <h1>Register New Employee</h1>
    
        <ul>
            <li>Usernames may contain only digits, upper and lower case letters and underscores</li>
            <li>Emails must have a valid email format</li>
            <li>Passwords must be at least 6 characters long</li>
            <li>Passwords must contain
                <ul>
                    <li>At least one uppercase letter (A..Z)</li>
                    <li>At least one lower case letter (a..z)</li>
                    <li>At least one number (0..9)</li>
                </ul>
            </li>
            <li>Your password and confirmation must match exactly</li>
        </ul>
        <form action="<?php echo esc_url($_SERVER['PHP_SELF']); ?>" 
                method="post" 
                name="registration_form">
            <label for="username">Username</label><input type='text' 
                name='username' 
                id='username' /><br>
            <label for="email">Email</label><input type="text" name="email" id="email" /><br>
            <label for="password">Password</label><input type="password"
                             name="password" 
                             id="password"/><br>
            <label for="confirmpwd">Confirm password</label><input type="password" 
                                     name="confirmpwd" 
                                     id="confirmpwd" /><br>
            <input class="submit-button"
            	   type="button" 
                   value="Register" 
                   onclick="return regformhash(this.form,
                                   this.form.username,
                                   this.form.email,
                                   this.form.password,
                                   this.form.confirmpwd);" /> 
        </form>
        <a href="accounts.php"><div class="submit-button">Accounts</div></a>
        </div>
    </body>
    <?php else : header('Location: main.php'); endif ?>
</html>