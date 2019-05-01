 <?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "test";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname, 3306);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// sql to create table
$sql = "CREATE TABLE IF NOT EXISTS results (
id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
timestamp BIGINT(13) NOT NULL,
value INT(7) NOT NULL
)";

if ($conn->query($sql) === TRUE) {
    echo "Table rawResults created successfully";
} else {
    echo "Error creating table: " . $conn->error;
}

$conn->close();
