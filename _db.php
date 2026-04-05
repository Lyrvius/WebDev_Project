<?php
$host = '127.0.0.1';
$port = 3306;
$database = 'hotel_project';
$username = 'root';
$password = '';

$dsn = "mysql:host=$host;port=$port;dbname=$database;charset=utf8mb4";

try {
    // Створення екземпляру PDO
    $db = new PDO($dsn, $username, $password);
    
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
} catch(PDOException $e) {
    die("Error connecting to database: " . $e->getMessage());
}
?>