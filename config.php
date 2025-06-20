<?php
// Bot token
define('API_TOKEN', 'TOKENINGNI_BU_YERGA_QO‘Y');

// Bazaga ulanish (MySQL misol uchun)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bot_db";

try {
    $db = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
