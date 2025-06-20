<?php
// Bot token - Render environment'dan oladi
define('API_TOKEN', getenv('API_TOKEN'));

// Bazaga ulanish (MySQL misol uchun)
$servername = getenv('DB_SERVER');
$username = getenv('DB_USERNAME');
$password = getenv('DB_PASSWORD');
$dbname = getenv('DB_NAME');

try {
    $db = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
