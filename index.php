<?php
require_once 'config.php';

// Bot funksiyasi
function bot($method, $datas = []) {
    $url = "https://api.telegram.org/bot" . API_TOKEN . "/" . $method;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $datas);
    $res = curl_exec($ch);
    curl_close($ch);
    return json_decode($res);
}

// Update
$update = json_decode(file_get_contents('php://input'));
$message = $update->message ?? $update->callback_query->message ?? null;
$text = $message->text ?? $update->callback_query->data ?? "";
$cid = $message->chat->id ?? null;

// Tugmalar
$menu = json_encode(['keyboard'=>[
    [['text'=>'🎬 KINOLAR']], [['text'=>'📡 KANALLAR']],
    [['text'=>'👤 ADMINLAR']], [['text'=>'⭐ SUPER USERLAR']],
    [['text'=>'📢 XABARLAR']], [['text'=>'📊 STATISTIKA']]
], 'resize_keyboard'=>true]);

if($text == "/start") {
    bot('sendMessage', [
        'chat_id' => $cid,
        'text' => "👋 Xush kelibsiz!",
        'reply_markup' => $menu
    ]);
}
// Qolgan CRUD va logika mana shu joyga qo‘shiladi.
?>
