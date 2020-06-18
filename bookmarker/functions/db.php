<?php

function db_connect() 
{
	$result = new mysqli('localhost', 'bookmark_user', 'password', 'bookmarker');
	
	if (!$result) {
		throw new Exception('Could not connect to database server');
	} else {
	    return $result;
	}
}
