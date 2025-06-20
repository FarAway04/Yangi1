<?php
require_once 'config.php';

// So'rovni olish
$update = json_decode(file_get_contents('php://input'), true);

$message = $update['message'] ?? null;

if ($message) {
    $chat_id = $message['chat']['id'];
    $text = $message['text'];

    // Tugmalar
    $menu = json_encode([
        'keyboard' => [
            [['text' => '🎬 KINOLAR']], [['text' => '📡 KANALLAR']],
            [['text' => '👤 ADMINLAR']], [['text' => '⭐ SUPER USERLAR']],
            [['text' => '📢 XABARLAR']], [['text' => '📊 STATISTIKA']]
        ],
        'resize_keyboard' => true
    ]);

    if ($text == "/start") {
        $reply = "👋 Xush kelibsiz!";
    } else {
        $reply = "Siz yubordingiz: $text";
    }

    // Bu yerda curl o'rniga file_get_contents eng yaxshi
    file_get_contents("https://api.telegram.org/bot".API_TOKEN."/sendMessage?chat_id=$chat_id&text=" . urlencode($reply) . "&reply_markup=$menu");
}
