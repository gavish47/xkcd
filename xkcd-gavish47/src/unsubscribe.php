<?php
require_once __DIR__ . '/functions.php';

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    if (unsubscribeEmail($email)) {
        $message = "✅ $email has been unsubscribed.";
    } else {
        $message = "❌ Couldn't unsubscribe. Email not found.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Unsubscribe</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f4f7f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .box {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.1);
            width: 360px;
            text-align: center;
        }
        input {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            font-size: 16px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }
        button {
            padding: 12px;
            width: 100%;
            background: #e74c3c;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
        }
        .message {
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <div class="box">
        <h2>🚫 Unsubscribe</h2>
        <form method="POST">
            <input type="email" name="email" placeholder="Enter your email to unsubscribe" required>
            <button type="submit">Unsubscribe</button>
        </form>
        <?php if ($message): ?>
            <div class="message"><?= $message ?></div>
        <?php endif; ?>
    </div>
</body>
</html>
