<?php
include_once 'psl-config.php';   // As functions.php is not included
$link = new mysqli(HOST, USER, PASSWORD, DATABASE);

if ($link->connect_error) {
    die("Connection failed: " . $link->connect_error);
} 
else
	echo "Successfully Connected to: " . DATABASE;