<?php

require_once 'db.php';

// Extract from the database all the URLs this user has stored
function get_user_urls($username) 
{
    $conn = db_connect();
    $result = $conn->query("SELECT url, created
                            FROM bookmarks
                            WHERE username = '".$username."'");

    if (!$result) {
        return false;
    }

    // Create an array of the URLs
    $url_array = array();

    for ($count = 1; $row = $result->fetch_row(); ++$count) {
        $url_array[$count]['url'] = $row[0];
        $url_array[$count]['created'] = $row[1];
    }
    /*while ($row = $result->fetch_row()) {
        $url_array = array(
            "url" => $row[0],
            "created" => $row[1]
        );
    }*/

    return $url_array;
}

// Add new bookmark to the database
function add_bookmark($new_url) 
{
    echo "<p>Attempting to add ".htmlspecialchars($new_url)." ...</p>";
    $valid_user = $_SESSION['valid_user'];

    $conn = db_connect();

    // Check not to repeat a bookmark
    $result = $conn->query("SELECT * FROM bookmarks
                            WHERE username='$valid_user'
                            AND url='".$new_url."'");

    if ($result && ($result->num_rows > 0)) {
        throw new Exception('Bookmark already exists.');
    }

    // Insert the new bookmark
    if (!$conn->query("INSERT INTO bookmarks VALUES ('".$valid_user."', '".$new_url."', NOW())")) {
        throw new Exception('Bookmark could not be inserted.');
    }

    return true;
}

// Delete one URL from the database
function delete_bookmark($user, $url) 
{
    $conn = db_connect();

    // Delete the bookmark
    if (!$conn->query("DELETE FROM bookmarks WHERE username='".$user."' AND url='".$url."'")) {
        throw new Exception('Bookmark could not be deleted');
    }

    return true;
}

function recommend_urls($valid_user, $popularity = 1) 
{
    // Provide semi intelligent recommendations to people
    // If they have a URL in common with other users, they may like
    // other URLs that these people like
    $conn = db_connect();

    // find other matching users
    // with a url the same as you
    // as a simple way of excluding people's private pages, and
    // increasing the chance of recommending appealing URLs, we
    // specify a minimum popularity level
    // if $popularity = 1, then more than one person must have
    // a URL before we will recomend it

    // The IN operator allows us to specify multiple values in a WHERE clause.
    // The IN operator is a shorthand for multiple OR conditions.

    // The SELECT DISTINCT basically returns the rest of the users

    // The final SELECT returns urls

    $query = "SELECT url
              FROM bookmarks
              WHERE username IN
                (SELECT DISTINCT(b2.username)
                FROM bookmarks b1, bookmarks b2
                WHERE b1.username = '".$valid_user."'
                AND b1.username != b2.username
                AND b1.url = b2.url)
                AND url NOT IN
                    (SELECT url
                     FROM bookmarks
                     WHERE username = '".$valid_user."')
                     GROUP BY url
                     HAVING COUNT(url) > ".$popularity;

    if (!($result = $conn->query($query))) {
        throw new Exception('<p>Could not find any bookmarks to recommend.</p>');
    }

    if ($result->num_rows == 0) {
        throw new Exception('<p>Could not find any bookmarks to recommend.</p>');
    }

    $urls = array();
    // Build an array of the relevant urls
    for ($count=0; $row = $result->fetch_object(); $count++) {
        $urls[$count] = $row->url;
    }

    return $urls;
}
