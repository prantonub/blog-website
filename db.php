<?php
$server = "localhost";
$user = "root";
$pass = "";
$dbname = "blogpostdb";

$conn = new mysqli($server, $user, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
    echo "Error! Connection failed: " . $conn->connect_error;
} 
?>