<?php
define('API_TOKEN', getenv('API_TOKEN'));

$servername = getenv('DB_HOST');   // ✅ HOST
$username = getenv('DB_USER');     // ✅ USERNAME
$password = getenv('DB_PASSWORD'); // ✅ PASSWORD
$dbname = getenv('DB_NAME');       // ✅ DB NAME
$port = getenv('DB_PORT');         // ✅ PORT

try {
    $db = new PDO("pgsql:host=$servername;port=$port;dbname=$dbname", $username, $password);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
