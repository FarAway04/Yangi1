<?php
define('API_TOKEN', getenv('API_TOKEN'));

$host = getenv('DB_HOST');
$dbname = getenv('DB_NAME');
$user = getenv('DB_USER');
$pass = getenv('DB_PASSWORD');
$port = getenv('DB_PORT');

try {
    $db = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $user, $pass);
} catch (PDOException $e) {
    exit("DB ERROR: " . $e->getMessage());
}
?>
