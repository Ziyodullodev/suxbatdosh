<?php


$config = require_once "config.php";
require_once "functions.php";
require_once "Telegram.php";

$tg = new Telegram(['token' => $config['token']]);
$updates = $tg->get_webhookUpdates();

if (!empty($updates)) {

    if (!empty($updates['message']['chat']['id'])) {
        $chatData = $updates['message']['chat'];
        $message_id = $updates['message']['message_id'];
    } elseif (!empty($updates['callback_query']['message']['chat']['id'])) {
        $chatData = $updates['callback_query']['message']['chat'];
        $message_id = $updates['callback_query']['message']['message_id'];
    } else {
        $tg->send_message("Xatolik yuz berdi", "848796050");
        exit();
    }
    $tg->set_chatId($chatData['id']);
    $chat_id = $chatData['id'];
    $name = $chatData['first_name'];
    $user = getUserConfig("users/$chat_id.json", "step");
    if (is_null($user)){
        $tg->send_message("Ismingizni kiriting:");
        setUserConfig("users/$chat_id.json", "step", "name");
        exit();
    }
    if ($user == "name"){
        setUserConfig("users/$chat_id.json", "name", $updates['message']['text']);
        $tg->send_message("Ismingiz qabul qilindi");
        setUserConfig("users/$chat_id.json", "step", "age");
        $tg->send_message("Yoshingizni kiriting:");
        exit();
    }
    elseif ($user == "age"){
        setUserConfig("users/$chat_id.json", "age", $updates['message']['text']);
        $tg->send_message("Yoshingizni qabul qilindi");
        setUserConfig("users/$chat_id.json", "step", "where");
        $tg->send_message("Qayerda o'qiysiz:");
        exit();
    }
    elseif ($user == "where"){
        setUserConfig("users/$chat_id.json", "where", $updates['message']['text']);
        $tg->send_message("Bosh menu");
        setUserConfig("users/$chat_id.json", "step", "menu");
        exit();
    }
    
    if ($updates['message']['text'] == "/start") {
        $tg->send_message("Assalomu alaykum, $name");
        exit();
    }
    if ($updates['message']['text'] == "/menu") {
        $tg->send_message("Bosh menu");
        exit();
    }
    $words = [
        "salom yaxshimisiz",
        "qalesiz",
        "nima gap",
        "qalaysiz",
        "nima gaplar",
        "uydagilar yaxshimi",
        "O'qishlar qalay",
        "qalaysiz",
        "kayfiyatingiz yaxshimi",
        "Xayr",
        "Hayr",
        "Yaxshi kun",
        "Hayot qanday?",
        "Men yaxshi yemagandim",
        "Iltimos",
        "Juda yaxshi",
        "O'rganishni sevaman",
        "Qandaydir savol bo'lsa, so'rang",
        "Nima qilmoqchisiz?",
        "Meni tushunmadingizmi?",
        "O'zbek tilini o'rganmoqchiman",
        "Ishlayapman",
        "Yordam bering",
        "Sizni ko'rishgandimdan xursandman",
        "Bugun qanday kun?",
        "Ishlar qalay?",
        "Yomg'ir yagayotgan",
        "Uyg'onayotganman",
        "Meni qanday yordam bera olishim mumkin?",
        "Yomon kun",
        "Sog' bo'ling",
        "Nima gap, do'st?",
        "Tatuda oqishlar qalay?",
        "demak, siz komputerni bilasiz",
        "Sizga qaysi fasl yoqadi"
    ];
    
    $tg->send_message($words[rand(0, count($words) - 1)]);
} else {
    $tg->set_webhook("https://e55c-195-158-3-178.ngrok-free.app/hook.php");
    echo "set webhook success";
    die();
}