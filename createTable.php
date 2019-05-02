<?php

/**
 * Create table in DB to store messages
 *
 * If connection is not established or table cannot be created exits
 */
$credentials = parse_ini_file('config.ini');
$database_info = $credentials['database_details'];


$conn = new mysqli($database_info['servername'], $database_info['username'], $database_info['password'], $database_info['dbname']);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$sql = "CREATE TABLE IF NOT EXISTS results (
id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
timestamp BIGINT(13) NOT NULL,
value INT(7) NOT NULL
)";

if ($conn->query($sql) === TRUE) {
    echo "Table results created successfully";
} else {
    echo "Error creating table: " . $conn->error;
}

$conn->close();
