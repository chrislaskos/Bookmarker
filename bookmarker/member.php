<?php

// Include functions for this application
require_once 'include.php';
session_start();

// Create short variable names
$username = isset($_POST['username']) ? $_POST['username'] : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';

if ($username && $password) {
	// They have just tried logging in
	try {
		login($username, $password);
		// if they are in the database register the user id
		$_SESSION['valid_user'] = $username;
	} catch(Exception $e) {
		// Unsuccessful login
		do_html_header('Problem:');
		echo '<p>You could not be logged in. You must be logged in to view this page.</p><br />';
		do_html_url('index.php', 'Login');
		do_html_footer();
		exit;
	}
}

do_html_header('My Bookmarks');
check_valid_user();

// Get the content for this user
if ($url_array = get_user_urls($_SESSION['valid_user'])) {
	display_user_urls($url_array);
}

// Give menu of options
display_user_menu();

do_html_footer();
