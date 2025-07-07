<?php
$servername = "localhost";
$username = "root";
$password = ""; // default for XAMPP
$dbname = "money_mate";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>