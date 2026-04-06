<?php
$host = 'localhost';
$dbname = 'eventsphere';
$username = 'root';
$password = ''; // default XAMPP password is empty

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    if ($e->getCode() == 1049) { // Database not found
        // Attempt to create database if not exists
        try {
            $temp_conn = new PDO("mysql:host=$host", $username, $password);
            $temp_conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $temp_conn->exec("CREATE DATABASE IF NOT EXISTS `$dbname`");
            $temp_conn->exec("USE `$dbname`");
            // Also run the schema setup
            $schema = file_get_contents(__DIR__ . '/db/schema.sql');
            $temp_conn->exec($schema);
            $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $pe) {
            die("Database Setup Failed: " . $pe->getMessage());
        }
    } else {
        die("Connection failed: " . $e->getMessage());
    }
}
?>
