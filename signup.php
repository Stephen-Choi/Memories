<?php 

session_start();

include "includes/db.php";
include "includes/functions.php";

if(isset($_POST['submit'])) {
	$firstname = mysqli_real_escape_string($connection, $_POST['firstname']);
	$lastname = mysqli_real_escape_string($connection, $_POST['lastname']);
	$username = mysqli_real_escape_string($connection, $_POST['username']);
	$email = mysqli_real_escape_string($connection, $_POST['email']);
	$password = mysqli_real_escape_string($connection, $_POST['password']);

	$valid = true;

	if(username_exists($username)) {
		$errors['username'] = "Username is already taken";
	}

	if(invalid_username($username)) {
		$errors['username_characters'] = "Username contains invalid characters";
	}

	if(password_length_short($password)) {
		$errors['password_length'] = "Password needs to be at least 8 characters";
	}

	if(invalid_password($password)) {
		$errors['password_characters'] = "Password contains invalid characters";
	}

	if(email_exists($email)) {
		$errors['email'] = "This email is already registered";
	}

	foreach($errors as $error => $values) {
		if (!(empty($values))) {
			$valid = false;
		}
	}

	if($valid) {
		$options = [
		    'cost' => 12,
		];

		$password = password_hash($password, PASSWORD_BCRYPT, $options);

		register_user($username, $email, $firstname, $lastname, $password);
	}

	$check['visited'] = "visited";
}

?>

