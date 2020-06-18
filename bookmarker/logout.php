<?php

require_once 'include.php';

session_start();
$old_user = $_SESSION['valid_user'];

// Store to test if they *were* logged in
unset($_SESSION['valid_user']);
$result_dest = session_destroy();

// Start output html
do_html_header('Logging Out');

if (!empty($old_user)) {
    if ($result_dest) {
        // if they were logged in and are now logged out
        echo '<p>Logged out.</p><br />';
        do_html_url('index.php', 'Login');
    } else {
        // they were logged in and could not be logged out
        echo '<p>Could not log you out.</p><br />';
    }
} else {
    // if they weren't logged in but came to this page somehow
    echo '<p>You were not logged in, and so have not been logged out.</p><br />';
    do_html_url('index.php', 'Login');
}

do_html_footer();
