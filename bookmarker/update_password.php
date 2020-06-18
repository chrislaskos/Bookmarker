<?php

require_once 'include.php';

session_start();
do_html_header('Changing password');

// Create short variable names
$old_password = $_POST['old_password'];
$new_password = $_POST['new_password'];
$new_password2 = $_POST['new_password2'];

try {
    check_valid_user();
    if (!filled_out($_POST)) {
        throw new Exception('You have not filled out the form completely. Please try again.');
    }

    if ($new_password != $new_password2) {
        throw new Exception('Passwords entered were not the same. Not changed.');
    }

    if ((strlen($new_password) > 16) || (strlen($new_password) < 6)) {
        throw new Exception('New password must be between 6 and 16 characters. Try again.');
    }

    // Attempt update
    change_password($_SESSION['valid_user'], $old_password, $new_password);
    echo 'Password changed.';
} catch (Exception $e) {
    echo $e->getMessage();
}

display_user_menu();
do_html_footer();
