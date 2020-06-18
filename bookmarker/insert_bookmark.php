<?php

require_once 'include.php';

session_start();

// Create short variable name
$new_url = $_POST['new_url'];

// Don't need them because of Ajax
// do_html_header('Adding bookmark');
// check_valid_user();

// Check if the form is filled
if (!filled_out($_POST)) {
    echo '<p class="warning">Form not completely filled out.</p>';
} else {
    // The form is filled
    // and correction of the URL format
    // if needed
    if (strstr($new_url, 'http://') === false) {
        $new_url = 'http://'.$new_url;
    }

    // continue checking if URL is valid
    if (!(@fopen($new_url, 'r'))) {
        echo '<p class="warning">Not a valid URL.</p>';
    } else {
        // if valid continue and add it
        add_bookmark($new_url);
        echo '<p class="success">Bookmark added.</p>';
    }
}

// no matter the state of the request
// get the bookmarks this user has saved
if ($url_array = get_user_urls($_SESSION['valid_user'])) {
    display_user_urls($url_array);
}

// Don't need them because of Ajax
// display_user_menu();
// do_html_footer();
