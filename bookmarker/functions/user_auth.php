<?php

require_once 'db.php';

function register($username, $email, $password) 
{
	// Register new person with db
	// return true or error message

	// Connect to db
	$conn = db_connect();

	// Check if username is unique
	$result = $conn->query("SELECT * FROM users WHERE username='".$username."'");

	if (!$result) {
		throw new Exception('Could not execute query.');
	}

	if ($result->num_rows > 0) {
		throw new Exception('That username is taken - go back and choose another one.');
	}

	// If OK, put in db
	$result = $conn->query("INSERT INTO users VALUES
							('".$username."', SHA1('".$password."'), '".$email."', NOW(), NOW())");

	if (!$result) {
		throw new Exception('Could not register you in the database - please try again later.');
	}

	return true;
}

function login($username, $password) 
{
	// Check username and password with db
	// if yes, return true
	// else throw exception

	// connect to db
	$conn = db_connect();

	// check if username is unique
	$result = $conn->query("SELECT * FROM users
							WHERE username='".$username."'
							AND password = SHA1('".$password."')");

	if (!$result) {
		throw new Exception('Could not log you in.');
	}

	if ($result->num_rows > 0) {
		return true;
	} else {
		throw new Exception('Could not log you in.');
	}
}

function check_valid_user() 
{
	// See if somebody is logged in and notify them if not
	if (isset($_SESSION['valid_user'])) {
		echo "<p>Logged in as <b>".$_SESSION['valid_user']."</b>.</p>";
	} else {
		// they are not logged in
		do_html_heading('Problem:');
		echo 'You are not logged in.<br />';
		do_html_url('index.php', 'Login');
		do_html_footer();
		exit;
	}
}

function change_password($username, $old_password, $new_password) 
{
	// Change password for username/old_password to new_password
	// return true or false

	// If the old password is right
	// change their password to new_password and return true
	// else throw an Exception
	login($username, $old_password);
	$conn = db_connect();
	$result = $conn->query("UPDATE users
							SET password = SHA1('".$new_password."')
							WHERE username = '".$username."'");

	if (!$result) {
		throw new Exception('Password could not be changed.');
	} else {
		return true;  // changed successfully
	}
}

function get_random_word($min_length, $max_length) 
{
	// Get a random word between the two lengths
	// and return it

	// Generate a random word with letters from the alphabet
	// $word = '';
	$word = array_merge(range('a', 'z'), range('A', 'Z'));
	shuffle($word);

	$length = rand($min_length, $max_length);

	return substr(implode($word), 0, $length);

	// return $word;
}

function reset_password($username) 
{
	// Set password for username to a random value
	// Return the new password or false on failure
	// Get a random word between 6 and 13 chars in length
	$new_password = get_random_word(6, 13);

	if ($new_password == false) {
		throw new Exception('Could not generate new password.');
	}

	// Add a number  between 0 and 999 to it
	// to make it a slightly better password
	$rand_number = rand(0, 999);
	$new_password .= $rand_number;

	// Set user's password to this in database or return false
    // Temporarily removed SHA1()
	$conn = db_connect();
	$result = $conn->query("UPDATE users
							SET password = SHA1('".$new_password."')
							WHERE username = '".$username."'");

	if (!$result) {
		throw new Exception('Could not change password.');  // not changed
	} else {
		return $new_password;  // changed successfully
	}
}

function notify_password($username, $password) 
{
	// notify the user that their password has been changed

	$conn = db_connect();
    $result = $conn->query("SELECT email FROM user
                            WHERE username = '".$username."'");

    if (!$result) {
		throw new Exception('Could not find email address.');
    } else if ($result->num_rows == 0) {
		throw new Exception('Could not find email address.');
		// Username not in database
    } else {
		$row = $result->fetch_object();
		$email = $row->email;
		$from = "From: support@bookmarker \r\n";
		$msg = "Your Bookmarker password has been changed to ".$password."\r\n"
			  ."Please change it next time you log in.\r\n";

		if (mail($email, 'Bookmarker login information', $msg, $from)) {
			return true;
		} else {
			throw new Exception('Could not send email.');
		}
    }
}
