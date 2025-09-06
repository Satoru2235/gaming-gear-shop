<?php
// Database configuration file
// Modify these settings to match your environment.  
// The default configuration assumes a MySQL server running on localhost with no password.

$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'gaming_store';

// Create connection
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Check connection
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

// Set character encoding
$conn->set_charset('utf8');

?>