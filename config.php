<?php
// 🔑 Bot token — Render environment'dan oladi
define('API_TOKEN', getenv('API_TOKEN'));

// 🔑 PostgreSQL uchun o‘zgaruvchilar
$host = getenv('DB_HOST');
$port = getenv('DB_PORT');
$dbname = getenv('DB_NAME');
$user = getenv('DB_USER');
$password = getenv('DB_PASSWORD');

// 🔑 Bazaga ulanish (PostgreSQL DSN)
try {
    $db = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $user, $password);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
