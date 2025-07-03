<?php
session_start();

require_once __DIR__ . '/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function generateVerificationCode(): string {
    return str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
}

function sendVerificationEmail(string $email, string $code): bool {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'gavish224@gmail.com';
        $mail->Password   = 'dgok wdqo zoty viul';  // App password
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        $mail->setFrom('no-reply@example.com', 'XKCD Subscription');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = 'Your Verification Code';
        $mail->Body = "
            <div style='font-family: Arial, sans-serif;'>
                <h2 style='color: #2c3e50;'>Your verification code:</h2>
                <p style='font-size: 18px;'><strong>$code</strong></p>
                <p>If you didn’t request this, ignore the email.</p>
            </div>
        ";

        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}

function registerEmail(string $email): bool {
    $file = __DIR__ . '/registered_emails.txt';
    $emails = file_exists($file) ? file($file, FILE_IGNORE_NEW_LINES) : [];
    if (!in_array($email, $emails)) {
        return file_put_contents($file, $email . PHP_EOL, FILE_APPEND);
    }
    return false;
}

function unsubscribeEmail(string $email): bool {
    $file = __DIR__ . '/registered_emails.txt';
    if (!file_exists($file)) return false;

    $emails = file($file, FILE_IGNORE_NEW_LINES);
    $emails = array_filter($emails, fn($e) => trim($e) !== trim($email));

    return file_put_contents($file, implode(PHP_EOL, $emails) . PHP_EOL);
}

function verifyCode($email, $code): bool {
    return isset($_SESSION['verification'][$email]) && $_SESSION['verification'][$email] === $code;
}

function fetchAndFormatXKCDData(): string {
    $rand = rand(1, 2800);
    $url = "https://xkcd.com/$rand/info.0.json";
    $json = @file_get_contents($url);
    if ($json === false) return "<p>Failed to fetch XKCD comic.</p>";

    $data = json_decode($json, true);
    $img = htmlspecialchars($data['img']);
    $alt = htmlspecialchars($data['alt']);
    return "
        <div style='font-family: sans-serif; text-align: center;'>
            <h2>🤖 Here’s your random XKCD!</h2>
            <img src=\"$img\" alt=\"$alt\" style='max-width:100%; border-radius:8px;'>
            <p><em>$alt</em></p>
            <p><a href='http://localhost:8000/unsubscribe.php' style='color:#e74c3c;'>Unsubscribe</a></p>
        </div>
    ";
}

function sendXKCDUpdatesToSubscribers(): void {
    $file = __DIR__ . '/registered_emails.txt';
    if (!file_exists($file)) return;

    $emails = file($file, FILE_IGNORE_NEW_LINES);
    $content = fetchAndFormatXKCDData();

    foreach ($emails as $email) {
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'gavish224@gmail.com';
            $mail->Password   = 'dgok wdqo zoty viul';
            $mail->SMTPSecure = 'tls';
            $mail->Port       = 587;

            $mail->setFrom('no-reply@example.com', 'XKCD Subscription');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Your XKCD Comic';
            $code = generateVerificationCode();
$_SESSION['verification'][$email] = $code;
$unsubscribeLink = "http://localhost:8000/unsubscribe.php";

$mail->Body = $content . "
    <p style='margin-top:20px;font-size:14px;color:gray;'>
        If you wish to unsubscribe, <a href='$unsubscribeLink'>click here</a> and use the code: <strong>$code</strong>
    </p>";


            $mail->send();
        } catch (Exception $e) {
            continue;
        }
    }
}
