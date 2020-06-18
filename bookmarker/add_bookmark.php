<?php

require_once 'include.php';

session_start();

// Start output html
do_html_header('Add Bookmark');

check_valid_user();

display_add_bm_form();
display_user_menu();
do_html_footer();
