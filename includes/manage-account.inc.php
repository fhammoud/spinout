<?php
include_once 'connect.php';
include_once 'psl-config.php';

$error_msg = "";
 
if (isset($_POST['new-email'], $_POST['p'])) {
    // Sanitize and validate the data passed in
    $email = filter_input(INPUT_POST, 'new-email', FILTER_SANITIZE_EMAIL);
    $email = filter_var($email, FILTER_VALIDATE_EMAIL);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Not a valid email
        $error_msg .= '<p class="error">The email address you entered is not valid</p>';
    }
 	
    $password = filter_input(INPUT_POST, 'p', FILTER_SANITIZE_STRING);
    if (strlen($password) != 128) {
        // The hashed pwd should be 128 characters long.
        // If it's not, something really odd has happened
        $error_msg .= '<p class="error">Invalid password configuration.</p>';
    }
 
    // Username validity and password validity have been checked client side.
    // This should should be adequate as nobody gains any advantage from
    // breaking these rules.
    //
 	
    $prep_stmt = "SELECT id FROM spinout_employees WHERE email = ? LIMIT 1";
    $stmt = $link->prepare($prep_stmt);
 
   // check existing email  
    if ($stmt) {
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->store_result();
 
        if ($stmt->num_rows == 1) {
            // A user with this email address already exists
            $error_msg .= '<p class="error">A user with this email address already exists.</p>';
                        $stmt->close();
        }
                $stmt->close();
    } else {
        $error_msg .= '<p class="error">Database error Line 39</p>';
                $stmt->close();
    }
 
    // TODO: 
    // We'll also have to account for the situation where the user doesn't have
    // rights to do registration, by checking what type of user is attempting to
    // perform the operation.
 
    if (empty($error_msg)) {
		$username = htmlentities($_SESSION['username']);
		
		 if ($stmt = $link->prepare("SELECT password, salt 
        FROM spinout_employees
       WHERE username = ?
        LIMIT 1")) {
			$stmt->bind_param('s', $username);  // Bind "$username" to parameter.
			$stmt->execute();    // Execute the prepared query.
			$stmt->store_result();
	 
			// get variables from result.
			$stmt->bind_result($db_password, $salt);
			$stmt->fetch();
	 
			// hash the password with the unique salt.
			$password = hash('sha512', $password . $salt);
			
			if ($stmt->num_rows == 1) {
				if ($db_password == $password) {
					// Update the new email in the database 
					if ($insert_stmt = $link->prepare("UPDATE spinout_employees SET email=? WHERE username=?")) {
						$insert_stmt->bind_param('ss', $email, $username);
						// Execute the prepared query.
						if (! $insert_stmt->execute()) {
							header('Location: ../error.php?err=Registration failure: UPDATE');
						}
					}
					header('Location: ./manage-account.php');
				}
				else
				{
					$error_msg .= '<p class="error">Incorrect Password</p>';
                	$stmt->close();
				}
			}
		}
    }
}

if (isset($_POST['p1'], $_POST['p2'])) {
	
    // Sanitize and validate the data passed in
    $pw1 = filter_input(INPUT_POST, 'p1', FILTER_SANITIZE_STRING);
    if (strlen($pw1) != 128) {
        // The hashed pwd should be 128 characters long.
        // If it's not, something really odd has happened
        $error_msg .= '<p class="error">Invalid password configuration.</p>';
    }
	
	$pw2 = filter_input(INPUT_POST, 'p2', FILTER_SANITIZE_STRING);
    if (strlen($pw2) != 128) {
        // The hashed pwd should be 128 characters long.
        // If it's not, something really odd has happened
        $error_msg .= '<p class="error">Invalid password configuration.</p>';
    }
 
    // TODO: 
    // We'll also have to account for the situation where the user doesn't have
    // rights to do registration, by checking what type of user is attempting to
    // perform the operation.
 
    if (empty($error_msg)) {
		$username = htmlentities($_SESSION['username']);
		
		 if ($stmt = $link->prepare("SELECT password, salt 
        FROM spinout_employees
       WHERE username = ?
        LIMIT 1")) {
			$stmt->bind_param('s', $username);  // Bind "$username" to parameter.
			$stmt->execute();    // Execute the prepared query.
			$stmt->store_result();
	 
			// get variables from result.
			$stmt->bind_result($db_password, $salt);
			$stmt->fetch();
	 
			// hash the password with the unique salt.
			$pw1 = hash('sha512', $pw1 . $salt);
			
			if ($stmt->num_rows == 1) {
				if ($db_password == $pw1) {
					// Create a random salt
					//$random_salt = hash('sha512', uniqid(openssl_random_pseudo_bytes(16), TRUE)); // Did not work
					$random_salt = hash('sha512', uniqid(mt_rand(1, mt_getrandmax()), true));
					
					// Create salted password 
					$pw2 = hash('sha512', $pw2 . $random_salt);
					
					// Update the new email in the database 
					if ($insert_stmt = $link->prepare("UPDATE spinout_employees SET password=?, salt=? WHERE username=?")) {
						$insert_stmt->bind_param('sss', $pw2, $random_salt, $username);
						// Execute the prepared query.
						if (! $insert_stmt->execute()) {
							header('Location: ../error.php?err=Registration failure: UPDATE');
						}
					}
					header('Location: ./manage-account.php');
				}
				else
				{
					$error_msg .= '<p class="error">Incorrect Old Password</p>';
                	$stmt->close();
				}
			}
		}
    }
}