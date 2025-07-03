<?php
session_start();
require_once __DIR__ . '/functions.php';

$message = '';
$step = 'email';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['email'])) {
        $email = trim($_POST['email']);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $message = '❌ Invalid email address.';
        } else {
            $code = generateVerificationCode();
            $_SESSION['verification'][$email] = $code;
            if (sendVerificationEmail($email, $code)) {
                $_SESSION['pending_email'] = $email;
                $step = 'verify';
                $message = "📨 Verification code sent to <strong>$email</strong>";
            } else {
                $message = "❌ Failed to send verification email.";
            }
        }
    } elseif (isset($_POST['code'])) {
        $email = $_SESSION['pending_email'] ?? '';
        $code = trim($_POST['code']);
        if (verifyCode($email, $code)) {
            registerEmail($email);
            unset($_SESSION['verification'][$email]);
            unset($_SESSION['pending_email']);
            $message = "✅ $email successfully subscribed to XKCD Comics!";
            $step = 'done';
        } else {
            $message = "❌ Incorrect verification code.";
            $step = 'verify';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>XKCD Comic Subscription</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: #eef2f7;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }

    .container {
      background: #fff;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 8px 30px rgba(0,0,0,0.1);
      width: 360px;
      text-align: center;
    }

    h2 {
      margin-bottom: 20px;
      color: #2c3e50;
    }

    input {
      width: 100%;
      padding: 12px;
      margin: 10px 0;
      font-size: 16px;
      border-radius: 8px;
      border: 1px solid #ccc;
    }

    button {
      padding: 12px;
      width: 100%;
      border: none;
      background-color: #3498db;
      color: white;
      font-size: 16px;
      border-radius: 8px;
      cursor: pointer;
      transition: 0.3s ease;
    }

    button:hover {
      background-color: #2980b9;
    }

    .message {
      margin-top: 20px;
      font-size: 15px;
      color: #333;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>📬 XKCD Comic Signup</h2>

    <?php if ($step === 'email'): ?>
      <form method="POST">
        <input type="email" name="email" placeholder="Enter your email" required>
        <button type="submit">Send Verification Code</button>
      </form>

    <?php elseif ($step === 'verify'): ?>
      <form method="POST">
        <input type="text" name="code" placeholder="Enter verification code" required>
        <button type="submit">Verify & Subscribe</button>
      </form>

    <?php else: ?>
      <p>🎉 You're now subscribed!</p>
    <?php endif; ?>

    <?php if ($message): ?>
      <div class="message"><?= $message ?></div>
    <?php endif; ?>
  </div>
</body>
</html>
