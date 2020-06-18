<?php

require_once "include.php";

do_html_header("Resetting password");

// Creating short variable name
$username = $_POST['username'];

try {
    $password = reset_password($username);
    // so mailing function doesn't work properly
    notify_password($username, $password);
    echo '<p>Your new password has been emailed to you.</p>';
} catch (Exception $e) {
    echo '<p>Your password could not be reset - please try again later.<p>';
}

do_html_url('index.php', 'Login');
do_html_footer();
