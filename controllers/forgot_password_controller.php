<?php
ob_start();
require __DIR__ .'/../models/db.php'; 
require __DIR__ .'/../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

date_default_timezone_set('Asia/Jakarta');

$database = new Database();
$db = $database->getConnection();

if (isset($_POST['email'])) {
    $email = trim($_POST['email']); 

    $stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        $token = bin2hex(random_bytes(50));
        $expires_at = date("Y-m-d H:i:s", strtotime('+1 hour'));

        $stmt = $db->prepare("INSERT INTO password_resets (email, token, expires_at) VALUES (?, ?, ?)");
        $stmt->execute([$email, $token, $expires_at]);
        //buat address testing aja sebelum hosting
        $reset_link = "https://eventure.duniacerita.com/src/views/reset_password.php?token=" . $token;

        $mail = new PHPMailer(true);
        
        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'ryan.art326@gmail.com';
            $mail->Password   = 'vhpp ally vbtw kkdg';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            $mail->SMTPDebug = 0; 
            $mail->setFrom('ryan.art326@gmail.com', 'event_organizer');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Password Reset Request';
            $mail->Body    = "klik link ini untuk reset password kamu: <a href='$reset_link'>$reset_link</a>";
            $mail->AltBody = "klik link ini untuk reset password kamu: $reset_link";

            $mail->send();
            header("Location: ../src/views/forgot_password.php?message=Check your email for the password reset link.");
            exit();
        } catch (Exception $e) {
            header("Location: ../src/views/forgot_password.php?message=Email sending failed: {$mail->ErrorInfo}");
            exit();
        }
    } else {
        header("Location: ../src/views/forgot_password.php?message=Email address not found.");
        exit(); 
    }
}

ob_end_flush();
?>
