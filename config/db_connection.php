<?php
//finally we have to cover up this thing up !!

$host = 'localhost';
$username = 'root';
$password = '';
$database = 'dynamic_forms';

// Create database connection
$db = new mysqli($host, $username, $password, $database);

// Check connection
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}
?>
