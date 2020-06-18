<?php

// Include function files for this application
require_once 'include.php';

// Create short variable names
$email = isset($_POST['email']) ? $_POST['email'] : '';
$username = isset($_POST['username']) ? $_POST['username'] : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';
$password2 = isset($_POST['password2']) ? $_POST['password2'] : '';
// Start session which may be needed later
// Start it now because it must go before headers
session_start();

try {
	// Check forms filled in
	if (!filled_out($_POST)) {
		throw new Exception('<p>You have not filled the form out correctly.</br> Please go back and try again.</p>');
	}

	// Email address not valid
	if (!valid_email($email)) {
		throw new Exception('<p>That is not a valid email address.<br /> Please go back and try again.</p>');
	}

	// Passwords not the same
	if ($password != $password2) {
		throw new Exception('<p>The passwords you entered do not match.<br /> Please go back and try again.</p>');
	}

	// Check password length is ok
	// OK if username truncates, but passwords will get
	// munged if they are too long.
	if ((strlen($password) < 6) || (strlen($password) > 16)) {
		throw new Exception('<p>Your password must be between 6 and 16 characters.<br/> Please go back and try again.</p>');
	}

	// Attempt to register
	// This function can also throw an exception
	register($username, $email, $password);
	// Register session variable
	$_SESSION['valid_user'] = $username;

	// Provide link to members page
	do_html_header('Registration successful');
	echo '<p>Your registration was successful.<br /> Go to the members page to start using the services!</p>';
	do_html_url('member.php', 'Go to members page');

	// End page
	do_html_footer();
} catch (Exception $e) {
	do_html_header('Problem:');
	echo $e->getMessage();
	do_html_footer();
	exit;
}
