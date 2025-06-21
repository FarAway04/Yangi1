<?php
require 'config.php';

function bot($method, $data=[]) {
    $url = "https://api.telegram.org/bot".API_TOKEN."/".$method;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,$url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
    curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
    $res = curl_exec($ch);
    curl_close($ch);
    return json_decode($res,true);
}

$update = json_decode(file_get_contents("php://input"), true);
$message = $update['message'] ?? [];
$cid = $message['chat']['id'] ?? null;
$uid = $message['from']['id'] ?? null;
$text = $message['text'] ?? "";
$fid = $message['document']['file_id'] ?? null;

$step_dir = "step";
if (!is_dir($step_dir)) mkdir($step_dir);

$is_admin = $db->query("SELECT 1 FROM admins WHERE user_id = '$uid'")->fetch();
$is_super = $db->query("SELECT 1 FROM superusers WHERE user_id = '$uid'")->fetch();

$ch_list = $db->query("SELECT username FROM channels")->fetchAll(PDO::FETCH_COLUMN);

$main = json_encode(['keyboard'=>[
    [['text'=>'🎬 KINOLAR'],['text'=>'📡 KANALLAR']],
    [['text'=>'👤 ADMINLAR'],['text'=>'⭐ SUPER USERLAR']],
    [['text'=>'📢 XABARLAR'],['text'=>'📊 STATISTIKA']]
],'resize_keyboard'=>true]);

$kino = json_encode(['keyboard'=>[
    [['text'=>'➕ KINOLAR QO‘SHISH'],['text'=>'❌ KINOLAR O‘CHIRISH']],
    [['text'=>'🔙 ORTGA']]
],'resize_keyboard'=>true]);

$kanal = json_encode(['keyboard'=>[
    [['text'=>'➕ KANALLAR QO‘SHISH'],['text'=>'❌ KANALLAR O‘CHIRISH']],
    [['text'=>'📋 KANALLAR RO‘YXATI'],['text'=>'🔙 ORTGA']]
],'resize_keyboard'=>true]);

$admin = json_encode(['keyboard'=>[
    [['text'=>'➕ ADMIN QO‘SHISH'],['text'=>'❌ ADMIN O‘CHIRISH']],
    [['text'=>'📋 ADMINLAR RO‘YXATI'],['text'=>'🔙 ORTGA']]
],'resize_keyboard'=>true]);

$super = json_encode(['keyboard'=>[
    [['text'=>'➕ SUPER USER QO‘SHISH'],['text'=>'❌ SUPER USER O‘CHIRISH']],
    [['text'=>'📋 SUPER USERLAR RO‘YXATI'],['text'=>'🔙 ORTGA']]
],'resize_keyboard'=>true]);

$xabar = json_encode(['keyboard'=>[
    [['text'=>'✉️ XABAR YUBORISH'],['text'=>'❌ XABAR O‘CHIRISH']],
    [['text'=>'🔙 ORTGA']]
],'resize_keyboard'=>true]);

// Majburiy obuna tekshirish
if (!$is_admin && !$is_super) {
    $ok = true;
    foreach ($ch_list as $ch) {
        $sub = bot('getChatMember', ['chat_id'=>"@$ch", 'user_id'=>$uid]);
        $status = $sub['result']['status'];
        if ($status != "member" && $status != "creator" && $status != "administrator") {
            $ok = false; break;
        }
    }
    if (!$ok) {
        $btns = [];
        foreach ($ch_list as $ch) {
            $btns[] = [['text'=>"@$ch", 'url'=>"https://t.me/$ch"]];
        }
        $btns[] = [['text'=>"✅ TEKSHIRISH", 'callback_data'=>"check"]];
        bot('sendMessage', [
            'chat_id'=>$cid,
            'text'=>"Botdan foydalanish uchun kanallarga obuna bo‘ling.",
            'reply_markup'=>json_encode(['inline_keyboard'=>$btns])
        ]);
        exit;
    }
}

// START
if ($text == "/start") {
    bot('sendMessage', ['chat_id'=>$cid, 'text'=>"👋 Botimizga Xush kelibsiz!", 'reply_markup'=>$main]);
}

// CRUD tugmalarini shu tartibda yoz (bu misol!)
if ($text == "🎬 KINOLAR") {
    $m = $db->query("SELECT COUNT(*) FROM movies")->fetchColumn();
    $l = $db->query("SELECT description FROM movies ORDER BY id DESC LIMIT 1")->fetchColumn();
    bot('sendMessage', ['chat_id'=>$cid, 'text'=>"Hozirda mavjud: $m ta\nOxirgi: $l", 'reply_markup'=>$kino]);
}

// ... Boshqa tugmalar ham xuddi shu tartibda ...

?>
