<?php
require_once 'config.php';

// 🔑 BOT funksiyasi
function bot($method, $datas = []) {
    $url = "https://api.telegram.org/bot" . API_TOKEN . "/" . $method;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $datas);
    $res = curl_exec($ch);
    curl_close($ch);
    return json_decode($res, true);
}

// 🔑 UPDATE olish
$update = json_decode(file_get_contents('php://input'), true);
$message = $update['message'] ?? null;
$text = $message['text'] ?? "";
$cid = $message['chat']['id'] ?? null;
$fid = $message['document']['file_id'] ?? null;

$step = []; // Step yozuv

// ✅ Asosiy menyu
$mainMenu = json_encode(['keyboard'=>[
    [['text'=>'🎬 KINOLAR'], ['text'=>'📡 KANALLAR']],
    [['text'=>'👤 ADMINLAR'], ['text'=>'⭐ SUPER USERLAR']],
    [['text'=>'📢 XABARLAR'], ['text'=>'📊 STATISTIKA']]
], 'resize_keyboard'=>true]);

$kinoMenu = json_encode(['keyboard'=>[
    [['text'=>'➕ KINOLAR QO‘SHISH'], ['text'=>'❌ KINOLAR O‘CHIRISH']],
    [['text'=>'🔙 ORTGA']]
], 'resize_keyboard'=>true]);

$kanalMenu = json_encode(['keyboard'=>[
    [['text'=>'➕ KANALLAR QO‘SHISH'], ['text'=>'❌ KANALLAR O‘CHIRISH']],
    [['text'=>'📋 KANALLAR RO‘YXATI']],
    [['text'=>'🔙 ORTGA']]
], 'resize_keyboard'=>true]);

$adminMenu = json_encode(['keyboard'=>[
    [['text'=>'➕ ADMIN QO‘SHISH'], ['text'=>'❌ ADMIN O‘CHIRISH']],
    [['text'=>'📋 ADMINLAR RO‘YXATI']],
    [['text'=>'🔙 ORTGA']]
], 'resize_keyboard'=>true]);

$superMenu = json_encode(['keyboard'=>[
    [['text'=>'➕ SUPER USER QO‘SHISH'], ['text'=>'❌ SUPER USER O‘CHIRISH']],
    [['text'=>'📋 SUPER USERLAR RO‘YXATI']],
    [['text'=>'🔙 ORTGA']]
], 'resize_keyboard'=>true]);

$xabMenu = json_encode(['keyboard'=>[
    [['text'=>'✉️ XABAR YUBORISH'], ['text'=>'❌ XABAR O‘CHIRISH']],
    [['text'=>'🔙 ORTGA']]
], 'resize_keyboard'=>true]);

// 🎬 START
if ($text == "/start") {
    bot('sendMessage', [
        'chat_id' => $cid,
        'text' => "👋 Botimizga Xush Kelibsiz!",
        'reply_markup' => $mainMenu
    ]);
}

// 🎬 KINOLAR
elseif ($text == "🎬 KINOLAR") {
    $stmt = $db->query("SELECT COUNT(*) FROM movies");
    $total = $stmt->fetchColumn();
    $stmt = $db->query("SELECT description FROM movies ORDER BY id DESC LIMIT 1");
    $last = $stmt->fetchColumn();
    bot('sendMessage', [
        'chat_id' => $cid,
        'text' => "Kinolar bo‘limi\nMavjud kinolar: $total ta\nOxirgi: $last",
        'reply_markup' => $kinoMenu
    ]);
}

elseif ($text == "➕ KINOLAR QO‘SHISH") {
    file_put_contents("step/$cid.step", "add_movie_file");
    bot('sendMessage', [
        'chat_id' => $cid,
        'text' => "Kino faylini yubor."
    ]);
}

elseif ($fid && file_exists("step/$cid.step") && file_get_contents("step/$cid.step") == "add_movie_file") {
    file_put_contents("step/$cid.step", "add_movie_desc|$fid");
    bot('sendMessage', [
        'chat_id' => $cid,
        'text' => "Endi tavsifni yubor."
    ]);
}

elseif (file_exists("step/$cid.step") && str_starts_with(file_get_contents("step/$cid.step"), "add_movie_desc")) {
    $fid = explode("|", file_get_contents("step/$cid.step"))[1];
    $stmt = $db->prepare("INSERT INTO movies (file_id, description) VALUES (?, ?)");
    $stmt->execute([$fid, $text]);
    unlink("step/$cid.step");
    bot('sendMessage', [
        'chat_id' => $cid,
        'text' => "Kino qo‘shildi!"
    ]);
}

elseif ($text == "❌ KINOLAR O‘CHIRISH") {
    file_put_contents("step/$cid.step", "del_movie");
    bot('sendMessage', [
        'chat_id' => $cid,
        'text' => "Kino ID sini yubor."
    ]);
}

elseif (file_exists("step/$cid.step") && file_get_contents("step/$cid.step") == "del_movie") {
    $stmt = $db->prepare("DELETE FROM movies WHERE id = ?");
    $stmt->execute([$text]);
    unlink("step/$cid.step");
    bot('sendMessage', [
        'chat_id' => $cid,
        'text' => "Kino o‘chirildi!"
    ]);
}

// 📡 KANALLAR
elseif ($text == "📡 KANALLAR") {
    bot('sendMessage', [
        'chat_id' => $cid,
        'text' => "Kanallar bo‘limi",
        'reply_markup' => $kanalMenu
    ]);
}

elseif ($text == "➕ KANALLAR QO‘SHISH") {
    file_put_contents("step/$cid.step", "add_channel");
    bot('sendMessage', [
        'chat_id' => $cid,
        'text' => "Kanal username ni yubor."
    ]);
}

elseif (file_exists("step/$cid.step") && file_get_contents("step/$cid.step") == "add_channel") {
    $stmt = $db->prepare("INSERT INTO channels (username) VALUES (?)");
    $stmt->execute([$text]);
    unlink("step/$cid.step");
    bot('sendMessage', [
        'chat_id' => $cid,
        'text' => "Kanal qo‘shildi!"
    ]);
}

elseif ($text == "❌ KANALLAR O‘CHIRISH") {
    file_put_contents("step/$cid.step", "del_channel");
    bot('sendMessage', [
        'chat_id' => $cid,
        'text' => "Kanal username ni yubor."
    ]);
}

elseif (file_exists("step/$cid.step") && file_get_contents("step/$cid.step") == "del_channel") {
    $stmt = $db->prepare("DELETE FROM channels WHERE username = ?");
    $stmt->execute([$text]);
    unlink("step/$cid.step");
    bot('sendMessage', [
        'chat_id' => $cid,
        'text' => "Kanal o‘chirildi!"
    ]);
}

elseif ($text == "📋 KANALLAR RO‘YXATI") {
    $stmt = $db->query("SELECT username FROM channels");
    $rows = $stmt->fetchAll(PDO::FETCH_COLUMN);
    $txt = implode("\n", $rows);
    bot('sendMessage', [
        'chat_id' => $cid,
        'text' => "Kanallar:\n$txt"
    ]);
}

// 👤 ADMINLAR
elseif ($text == "👤 ADMINLAR") {
    bot('sendMessage', [
        'chat_id' => $cid,
        'text' => "Adminlar bo‘limi",
        'reply_markup' => $adminMenu
    ]);
}

elseif ($text == "➕ ADMIN QO‘SHISH") {
    file_put_contents("step/$cid.step", "add_admin");
    bot('sendMessage', [
        'chat_id' => $cid,
        'text' => "Admin ID ni yubor."
    ]);
}

elseif (file_exists("step/$cid.step") && file_get_contents("step/$cid.step") == "add_admin") {
    $stmt = $db->prepare("INSERT INTO admins (user_id) VALUES (?)");
    $stmt->execute([$text]);
    unlink("step/$cid.step");
    bot('sendMessage', [
        'chat_id' => $cid,
        'text' => "Admin qo‘shildi!"
    ]);
}

elseif ($text == "❌ ADMIN O‘CHIRISH") {
    file_put_contents("step/$cid.step", "del_admin");
    bot('sendMessage', [
        'chat_id' => $cid,
        'text' => "Admin ID ni yubor."
    ]);
}

elseif (file_exists("step/$cid.step") && file_get_contents("step/$cid.step") == "del_admin") {
    $stmt = $db->prepare("DELETE FROM admins WHERE user_id = ?");
    $stmt->execute([$text]);
    unlink("step/$cid.step");
    bot('sendMessage', [
        'chat_id' => $cid,
        'text' => "Admin o‘chirildi!"
    ]);
}

elseif ($text == "📋 ADMINLAR RO‘YXATI") {
    $stmt = $db->query("SELECT user_id FROM admins");
    $rows = $stmt->fetchAll(PDO::FETCH_COLUMN);
    $txt = implode("\n", $rows);
    bot('sendMessage', [
        'chat_id' => $cid,
        'text' => "Adminlar:\n$txt"
    ]);
}

// ⭐ SUPER USERLAR
elseif ($text == "⭐ SUPER USERLAR") {
    bot('sendMessage', [
        'chat_id' => $cid,
        'text' => "Super userlar bo‘limi",
        'reply_markup' => $superMenu
    ]);
}

elseif ($text == "➕ SUPER USER QO‘SHISH") {
    file_put_contents("step/$cid.step", "add_super");
    bot('sendMessage', [
        'chat_id' => $cid,
        'text' => "Super user ID ni yubor."
    ]);
}

elseif (file_exists("step/$cid.step") && file_get_contents("step/$cid.step") == "add_super") {
    $stmt = $db->prepare("INSERT INTO superusers (user_id) VALUES (?)");
    $stmt->execute([$text]);
    unlink("step/$cid.step");
    bot('sendMessage', [
        'chat_id' => $cid,
        'text' => "Super user qo‘shildi!"
    ]);
}

elseif ($text == "❌ SUPER USER O‘CHIRISH") {
    file_put_contents("step/$cid.step", "del_super");
    bot('sendMessage', [
        'chat_id' => $cid,
        'text' => "Super user ID ni yubor."
    ]);
}

elseif (file_exists("step/$cid.step") && file_get_contents("step/$cid.step") == "del_super") {
    $stmt = $db->prepare("DELETE FROM superusers WHERE user_id = ?");
    $stmt->execute([$text]);
    unlink("step/$cid.step");
    bot('sendMessage', [
        'chat_id' => $cid,
        'text' => "Super user o‘chirildi!"
    ]);
}

elseif ($text == "📋 SUPER USERLAR RO‘YXATI") {
    $stmt = $db->query("SELECT user_id FROM superusers");
    $rows = $stmt->fetchAll(PDO::FETCH_COLUMN);
    $txt = implode("\n", $rows);
    bot('sendMessage', [
        'chat_id' => $cid,
        'text' => "Super userlar:\n$txt"
    ]);
}

// 📊 STATISTIKA
elseif ($text == "📊 STATISTIKA") {
    $m = $db->query("SELECT COUNT(*) FROM movies")->fetchColumn();
    $a = $db->query("SELECT COUNT(*) FROM admins")->fetchColumn();
    $s = $db->query("SELECT COUNT(*) FROM superusers")->fetchColumn();
    $c = $db->query("SELECT COUNT(*) FROM channels")->fetchColumn();
    bot('sendMessage', [
        'chat_id' => $cid,
        'text' => "Statistika:\nKinolar: $m\nAdminlar: $a\nSuper userlar: $s\nKanallar: $c"
    ]);
}

// 🔙 ORTGA
elseif ($text == "🔙 ORTGA") {
    bot('sendMessage', [
        'chat_id' => $cid,
        'text' => "Asosiy menyu",
        'reply_markup' => $mainMenu
    ]);
}
?>
