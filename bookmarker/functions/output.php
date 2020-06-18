<?php

// Prints an HTML header
function do_html_header($title) 
{
?>
	<!doctype html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title><?php echo $title; ?></title>
		<link rel="icon" type="image/gif" href="img/bookmark.gif">
		<link rel="stylesheet" type="text/CSS" href="css/stylesheet.css"/>
		<script src="js/ajax.js" type="text/javascript"></script>
	</head>
	<body>
		<div class="content">
		<img src="img/bookmark.gif" alt="Bookmarker logo" border="0" align="left" valign="bottom" height="55" width="57" />
		<h1>Bookmarker</h1>
		<hr />
<?php
	if ($title) {
		do_html_heading($title);
	}
}

// Prints an HTML footer
function do_html_footer() 
{
?>
	</div>
	<!-- content ends here -->
	</body>
	</html>
<?php
}

// Prints a second heading
function do_html_heading($heading) 
{
?>
	<h2><?php echo $heading;?></h2>
<?php
}

// Outputs URL as link and br
function do_html_url($url, $name) 
{
?>
	<br /><a href="<?php echo $url;?>"><?php echo $name;?></a><br />
<?php
}

// Display some info about the website
function display_site_info() 
{
?>
   	<ul>
		<li>User registration or login is required to use this website!</li>
		<li>Add and remove your bookmarks!</li>
		<li>See recommended URLs based on your preferences!</li>
	</ul>
<?php
}

function display_login_form() 
{
?>
	<p><a href="register.php">Not a member?</a></p>
	<form method="post" action="member.php">
		<table bgcolor="#cccccc">
			<tr><td colspan="2">Members log in here:</td></tr>
			<tr>
				<td>Username:</td>
				<td><input type="text" name="username"/></td>
			</tr>
			<tr>
				<td>Password:</td>
				<td><input type="password" name="password"/></td>
			</tr>
			<tr>
				<td colspan="2" align="right">
					<input type="submit" value="Log in"/>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<a href="forgot_password.php">Forgot your password?</a>
				</td>
			</tr>
		</table>
	</form>
<?php
}

function display_registration_form() 
{
?>
	<form method="post" action="register_user.php">
		<table width="100%" bgcolor="#cccccc">
			<tr>
				<td>Email address:</td>
				<td><input type="text" name="email" size="30" maxlength="100"/></td>
			</tr>
			<tr>
				<td>Preferred username <br />(max 16 chars):</td>
				<td valign="top">
					<input type="text" name="username" size="16" maxlength="16"/>
				</td>
			</tr>
			<tr>
				<td>Password <br />(between 6 and 16 chars):</td>
				<td valign="top">
					<input type="password" name="password" size="16" maxlength="16"/>
				</td>
			</tr>
			<tr>
				<td>Confirm password:</td>
				<td><input type="password" name="password2" size="16" maxlength="16"/></td>
			</tr>
			<tr>
				<td></td>
				<td><input type="submit" value="Register"></td>
			</tr>
		</table>
	</form>
<?php
}

// Displays the menu options on this page
function display_user_menu() 
{
?>
	<hr />
	<a href="member.php">My Bookmarks</a> &nbsp;|&nbsp;
	<a href="add_bookmark.php">Add Bookmark</a> &nbsp;|&nbsp;
	<?php
	// only offer the delete option if bookmark table is on this page
	global $bm_table;
	if ($bm_table == true) {
	    // onsubmit="return confirm('Do you really want to submit the form?');"
        // onClick="bookmark_table.submit();"
        // onClick=\"confirmDeletion(bookmark_table);\"
		echo "<a href=\"#\" onClick=\"confirmDeletion(bookmark_table);\">Delete Bookmark</a> &nbsp;|&nbsp;";
	} else {
		echo "<span style=\"color: #cccccc\">Delete Bookmark</span> &nbsp;|&nbsp;";
	}
	?>
	<a href="recommend.php">Recommend URLs to me</a>
	<br /><br />	
	<a href="change_password.php">Change password</a> &nbsp;|&nbsp;
	<a href="logout.php">Logout</a>
	<hr />
<?php
}

// Displays the HTML change password form
function display_password_form() 
{
?>
	<br />
	<form action="update_password.php" method="post">
		<table width="250" cellpadding="2" cellspacing="0" bgcolor="#cccccc">
			<tr>
				<td>Old password:</td>
				<td><input type="password" name="old_password" size="16" maxlength="16"/></td>
			</tr>
			<tr>
				<td>New password:</td>
				<td><input type="password" name="new_password" size="16" maxlength="16"/></td>
			</tr>
			<tr>
				<td>Repeat new password:</td>
				<td><input type="password" name="new_password2" size="16" maxlength="16"/></td>
			</tr>
			<tr>
				<td colspan="2" align="right">
					<input type="submit" value="Change password"/>
				</td>
			</tr>
		</table>
	</form>
	<br />
<?php
}

// Displays an HTML form to reset and email password
function display_forgot_form() 
{
?>
	<br />
	<form action="reset_password.php" method="post">
		<p style="background-color: #cccccc; padding: 10px;">
			Enter your username: &nbsp;
			<input type="text" name="username" size="16" maxlength="16"/>&nbsp;
			<input type="submit" value="Change password"/>
		</p>
	</form>
	<br />
<?php
}

// Display the table of URLs
function display_user_urls($url_array) 
{
	// Set global variable, so we can test later if this is on the page
	global $bm_table;
	$bm_table = true;
	?>
	<br />
	<form name="bookmark_table" action="delete_bookmark.php" method="post">
		<table width="100%" cellpadding="2" cellspacing="0">
	<?php
	$color = "#cccccc";
	echo "<tr bgcolor=\"".$color."\"><td><strong>Bookmark</strong></td>";
	echo "<td><strong>Created</strong></td>";
	echo "<td><strong>Delete?</strong></td></tr>";
	// var_dump($url_array);
	if ((is_array($url_array)) && (count($url_array) > 0)) {
		foreach ($url_array as $url) {
			if ($color == "#cccccc") {
				$color = "#ffffff";
			} else {
				$color = "#cccccc";
			}
			$datetime = date_create($url['created']);
			// Remember to call htmlspecialchars() when we are displaying user data
			echo "<tr bgcolor=\"".$color."\"><td><a href=\"".$url['url']."\" target=\"_blank\">".htmlspecialchars($url['url'])."</a></td>
                <td>".date_format($datetime, 'd/m/Y H:i:s')."</td>
				<td><input type=\"checkbox\" name=\"del_me[]\" value=\"".$url['url']."\"/></td></tr>";
		}
	} else {
		echo "<tr><td>No bookmarks on record</td></tr>";
	}
	?>
		</table>
	</form>
	<?php
}

// Display the form for people to enter a new bookmark in
// trying to change onClick to onSubmit...
// type=submit
function display_add_bm_form() 
{
	?>
	<script type="text/javascript">
	var myRequest = getXMLHTTPRequest();
	</script>
	<form name="bm_table" action="insert_bookmark.php" method="post">
		<p style="background-color: #cccccc; padding: 10px;">
			New Bookmark:&nbsp;
			<input type="text" name="new_url" value="http://" size="30" maxlength="255" id="new_url"/>&nbsp;
			<input type="button" value="Add bookmark" onClick="javascript:addNewBookmark();"/>
		</p>			
	</form>
	<div id="displayresult"></div>
	<?php
}

function display_recommended_urls($url_array) 
{
	// Similar output to display_user_urls
	// Instead of displaying the users bookmarks, display recomendation
	?>
	<br />
	<table width="300" cellpadding="2" cellspacing="0">
	<?php
	$color = "#cccccc";
	echo "<tr bgcolor=\"".$color."\">
	<td><strong>Recommendations</strong></td></tr>";
	if ((is_array($url_array)) && (count($url_array)>0)) {
		foreach ($url_array as $url) {
			if ($color == "#cccccc") {
				$color = "#ffffff";
			} else {
				$color = "#cccccc";
			}
			echo "<tr bgcolor=\"".$color."\">
			<td><a href=\"".$url."\" target=\"_blank\">".htmlspecialchars($url)."</a></td></tr>";
		}
	} else {
		echo "<tr><td>No recommendations for you today.</td></tr>";
	}
	?>
	</table>
	<?php
}
