<?php
// 1. Get the Bot Token from an Environment Variable (Secure)
$botToken = getenv('BOT_TOKEN');
$website = "https://api.telegram.org/bot" . $botToken;

// 2. Read the incoming message from Telegram
$content = file_get_contents("php://input");
$update = json_decode($content, TRUE);

// If no update, it means a human is visiting the page in a browser
if (!$update) {
    echo "<h1>Bot Status: Online</h1>";
    echo "Your Render URL is: https://" . $_SERVER['HTTP_HOST'] . "/index.php";
    exit;
}

$chatId = $update["message"]["chat"]["id"];
$message = $update["message"]["text"];

// 3. Bot Commands
if ($message == "/ls") {
    $ls_result = shell_exec("ls -l");
    $reply = "📁 Files in Render container:\n\n" . ($ls_result ?: "No files found.");
} elseif ($message == "/start") {
    $reply = "Hello! I am your PHP bot running on Render. Send /ls to see my files.";
} else {
    $reply = "You said: " . $message;
}

// 4. Send the reply using a simple Webhook Response
header("Content-Type: application/json");
echo json_encode([
    "method" => "sendMessage",
    "chat_id" => $chatId,
    "text" => $reply
]);
?>