<?php
require __DIR__ . "\convertValues.php";
$url = "https://zl04pypnbk.execute-api.eu-west-1.amazonaws.com/prod/results";

$response = file_get_contents($url);
$response = json_decode($response);


print_r($response);

$routing_keys = convertValues::convertValue($response);

print_r("$routing_keys");


//// Create connection
//        $hostname = "candidaterds.n2g-dev.net";
//        $username = "candidate";
//        $password = "9jcnMWrjf6zBFLmwjfYEVRb6";
//        $conn = new mysqli($hostname, $username, $password);
//// Check connection
//        if ($conn->connect_error) {
//            die("Connection failed: " . $conn->connect_error);
//        }
//
//// Print connection details
//        print_r("\n");
//        print_r($conn);
//
//        $databases = mysqli_query($conn, "SHOW DATABASES");
//
//// Print result of query
//        print_r("\n");
//        print_r($databases);
//        print_r("\n");
//
//// Print each item of array
//        while ($row = mysqli_fetch_array($databases)) {
//            print_r($row[0] . "\n");
//        }

