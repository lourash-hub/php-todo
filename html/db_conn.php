<?php

require_once realpath(__DIR__ . "/vendor/autoload.php");

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Use environment variables
$servername = getenv('MYSQL_IP') ?: 'db'; // 'db' is the service name of the MySQL container
$username = getenv('MYSQL_USER');
$password = getenv('MYSQL_PASS');
$dbname = getenv('MYSQL_DBNAME');

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
