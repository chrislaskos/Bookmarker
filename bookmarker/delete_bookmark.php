<?php

require_once 'include.php';

session_start();

// Create short variable names
// $variable = if statement ? true : false;
$del_me = isset($_POST['del_me']) ? $_POST['del_me'] : array();
$valid_user = isset($_SESSION['valid_user']) ? $_SESSION['valid_user'] : '';

do_html_header('Deleting Bookmarks');
check_valid_user();

if (!filled_out($_POST)) {
    echo '<p>You have not chosen any bookmarks to delete.<br/>Please try again.</p>';
    display_user_menu();
    do_html_footer();
    exit;
} else {
    if (count($del_me) > 0) {
        foreach($del_me as $url) {
            if (delete_bookmark($valid_user, $url)) {
                echo '<p>Deleted '.htmlspecialchars($url).'.</p>';
            } else {
                echo '<p>Could not delete '.htmlspecialchars($url).'.</p>';
            }
        }
    } else {
        echo '<p>No bookmarks selected for deletion.</p>';
    }
}

// Get the bookmarks this user has saved
if ($url_array = get_user_urls($valid_user)) {
    display_user_urls($url_array);
}

display_user_menu();
do_html_footer();
