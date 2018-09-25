<?php 

session_start();

include "includes/db.php";
include "includes/functions.php";

if(isset($_POST['submit'])) {
	$username = mysqli_real_escape_string($connection, $_POST['username']);
	$password = mysqli_real_escape_string($connection, $_POST['password']);

	if(!password_length_short($password) && !invalid_password($password)) {
		if(username_exists($username)) {

			$db_password = get_user_pass($username);

			if(password_verify($password, $db_password)) {
				$_SESSION['user'] = $username;
				$_SESSION['user_id'] = get_userid($username);
				$_SESSION['fullname'] = get_fullname($username);
				header('Location: index.php');
			} else {
				$errors['wrong'] = "Incorrect password";
			}
		} else {
			$errors['username'] = "Username does not exist";
		}
	} else {
		if(password_length_short($password)) {
			$errors['password_length'] = "Password needs to be at least 8 characters";
		}

		if(invalid_password($password)) {
			$errors['password_characters'] = "Password contains invalid characters";
		}
	}
}