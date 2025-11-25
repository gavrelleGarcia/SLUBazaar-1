<?php
// reporting 
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);


$host = 'localhost';
$username = 'root';
$password = '';
$database = 'slubazaar';

try {

    $mysqli = new mysqli($host, $username, $password, $database);
    $mysqli->set_charset('utf8mb4');
    return $mysqli;
} catch(mysqli_sql_exception $e) {
    die("Database Connection Failed: " . $e->getMessage());
}