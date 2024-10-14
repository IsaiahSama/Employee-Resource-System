<?php 

$servername = "localhost";
$username = "comp3385";
$password = "FrameworksPassword2024!";
$dbname = "task_management_system";

$conn = new mysqli($servername, $username, $password, $dbname);
$conn->options(MYSQLI_OPT_INT_AND_FLOAT_NATIVE, 1);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}