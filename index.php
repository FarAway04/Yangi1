<?php
require_once 'config.php';

// 🔑 Bot funksiyasi
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

// 🔑 Update olish
$update = json_decode(file_get_contents('php://input'), true);
$message = $update['message'] ?? null;
$text = $message['text'] ?? "";
$cid = $message['chat']['id'] ?? null;

// ✅ Asosiy menyu
$mainMenu = json_encode(['keyboard'=>[
    [['text'=>'🎬 KINOLAR'], ['text'=>'📡 KANALLAR']],
    [['text'=>'👤 ADMINLAR'], ['text'=>'⭐ SUPER USERLAR']],
    [['text'=>'📢 XABARLAR'], ['text'=>'📊 STATISTIKA']]
], 'resize_keyboard'=>true]);

// ✅ Orqaga tugmasi
$backBtn = json_encode(['keyboard'=>[
    [['text'=>'🔙 ORTGA']]
], 'resize_keyboard'=>true]);

// ✅ KINOLAR menyu
$kinoMenu = json_encode(['keyboard'=>[
    [['text'=>'➕ KINOLAR QO‘SHISH'], ['text'=>'❌ KINOLAR O‘CHIRISH']],
    [['text'=>'🔙 ORTGA']]
], 'resize_keyboard'=>true]);

// ✅ KANALLAR menyu
$kanalMenu = json_encode(['keyboard'=>[
    [['text'=>'➕ KANALLAR QO‘SHISH'], ['text'=>'❌ KANALLAR O‘CHIRISH']],
    [['text'=>'📋 KANALLAR RO‘YXATI']],
    [['text'=>'🔙 ORTGA']]
], 'resize_keyboard'=>true]);

// ✅ ADMINLAR menyu
$adminMenu = json_encode(['keyboard'=>[
    [['text'=>'➕ ADMIN QO‘SHISH'], ['text'=>'❌ ADMIN O‘CHIRISH']],
    [['text'=>'📋 ADMINLAR RO‘YXATI']],
    [['text'=>'🔙 ORTGA']]
], 'resize_keyboard'=>true]);

// ✅ SUPER USERLAR menyu
$superMenu = json_encode(['keyboard'=>[
    [['text'=>'➕ SUPER USER QO‘SHISH'], ['text'=>'❌ SUPER USER O‘CHIRISH']],
    [['text'=>'📋 SUPER USERLAR RO‘YXATI']],
    [['text'=>'🔙 ORTGA']]
], 'resize_keyboard'=>true]);

// ✅ XABARLAR menyu
$xabMenu = json_encode(['keyboard'=>[
    [['text'=>'✉️ XABAR YUBORISH'], ['text'=>'❌ XABAR O‘CHIRISH']],
    [['text'=>'🔙 ORTGA']]
], 'resize_keyboard'=>true]);


// ===================== 🔑 ASOSIY QISMLAR =====================

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
    bot('sendMessage', [
        'chat_id' => $cid,
        'text' => "🎬 Kinolar bo‘limi\nMavjud kinolar: N ta\nOxirgi qo‘shilgan kino: Ism - Kod",
        'reply_markup' => $kinoMenu
    ]);
}

elseif ($text == "➕ KINOLAR QO‘SHISH") {
    bot('sendMessage', [
        'chat_id' => $cid,
        'text' => "🎬 Kino faylini yuboring."
    ]);
}

elseif ($text == "❌ KINOLAR O‘CHIRISH") {
    bot('sendMessage', [
        'chat_id' => $cid,
        'text' => "❌ O‘chirmoqchi bo‘lgan kino kodini yuboring."
    ]);
}

// 📡 KANALLAR
elseif ($text == "📡 KANALLAR") {
    bot('sendMessage', [
        'chat_id' => $cid,
        'text' => "📡 Kanallar bo‘limi",
        'reply_markup' => $kanalMenu
    ]);
}

elseif ($text == "➕ KANALLAR QO‘SHISH") {
    bot('sendMessage', [
        'chat_id' => $cid,
        'text' => "Qo‘shmoqchi bo‘lgan Kanal USERNAMEni yuboring."
    ]);
}

elseif ($text == "❌ KANALLAR O‘CHIRISH") {
    bot('sendMessage', [
        'chat_id' => $cid,
        'text' => "O‘chirmoqchi bo‘lgan Kanal USERNAMEni yuboring."
    ]);
}

elseif ($text == "📋 KANALLAR RO‘YXATI") {
    bot('sendMessage', [
        'chat_id' => $cid,
        'text' => "Faol majburiy kanallar:\n@kanal1\n@kanal2\n..."
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
    bot('sendMessage', [
        'chat_id' => $cid,
        'text' => "Admin qilmoqchi bo‘lgan user ID raqamini yuboring."
    ]);
}

elseif ($text == "❌ ADMIN O‘CHIRISH") {
    bot('sendMessage', [
        'chat_id' => $cid,
        'text' => "O‘chirmoqchi bo‘lgan Admin ID sini yuboring."
    ]);
}

elseif ($text == "📋 ADMINLAR RO‘YXATI") {
    bot('sendMessage', [
        'chat_id' => $cid,
        'text' => "Hozirgi faol Adminlar:\n12345678\n87654321\nEski Adminlar:\n..."
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
    bot('sendMessage', [
        'chat_id' => $cid,
        'text' => "Qo‘shmoqchi bo‘lgan Super user ID raqamini yuboring."
    ]);
}

elseif ($text == "❌ SUPER USER O‘CHIRISH") {
    bot('sendMessage', [
        'chat_id' => $cid,
        'text' => "O‘chirmoqchi bo‘lgan Super user ID raqamini yuboring."
    ]);
}

elseif ($text == "📋 SUPER USERLAR RO‘YXATI") {
    bot('sendMessage', [
        'chat_id' => $cid,
        'text' => "Hozirgi faol Super userlar:\n12345678\n87654321\nEski Super userlar:\n..."
    ]);
}

// 📢 XABARLAR
elseif ($text == "📢 XABARLAR") {
    bot('sendMessage', [
        'chat_id' => $cid,
        'text' => "Xabarlar bo‘limi",
        'reply_markup' => $xabMenu
    ]);
}

elseif ($text == "✉️ XABAR YUBORISH") {
    bot('sendMessage', [
        'chat_id' => $cid,
        'text' => "Avval yubormoqchi bo‘lgan xabarni tayyorlab yuboring."
    ]);
}

elseif ($text == "❌ XABAR O‘CHIRISH") {
    bot('sendMessage', [
        'chat_id' => $cid,
        'text' => "O‘chirmoqchi bo‘lgan xabarni yuboring."
    ]);
}

// 📊 STATISTIKA
elseif ($text == "📊 STATISTIKA") {
    bot('sendMessage', [
        'chat_id' => $cid,
        'text' => "Botning hozirgi holati:\nUmumiy kinolar: N\nUserlar: N\nSuper userlar: N\nKanallar: N"
    ]);
}

// 🔙 ORTGA
elseif ($text == "🔙 ORTGA") {
    bot('sendMessage', [
        'chat_id' => $cid,
        'text' => "Asosiy menyuga qaytdingiz.",
        'reply_markup' => $mainMenu
    ]);
}

?>
