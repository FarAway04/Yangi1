<?php
define('API_TOKEN', '7642182625:AAEnq_QiWxpfwhZZOXNCmjNOSOMAqs7TrU4');
define('ADMIN_ID', '5088940828');

$servername = 'dpg-d1aqpvmuk2gs7392jjgg-a.frankfurt-postgres.render.com';
$username = 'yangi1_user';
$password = 'hXBTGUr062Mt7WfqxlInh7KJihKUpm2I';
$dbname = 'yangi1';
$port = '5432';

try {
    $db = new PDO("pgsql:host=$servername;port=$port;dbname=$dbname", $username, $password);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>