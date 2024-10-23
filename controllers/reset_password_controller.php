<?php
require __DIR__ .'/../models/db.php'; 

$database = new Database();
$db = $database->getConnection();

if (isset($_POST['password']) && isset($_POST['token'])) {
    $password = $_POST['password'];
    $token = $_POST['token'];

    $stmt = $db->prepare("SELECT * FROM password_resets WHERE token = ? AND expires_at > NOW()");
    $stmt->execute([$token]);
    $reset = $stmt->fetch();

    if ($reset) {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $db->prepare("UPDATE users SET password = ? WHERE email = ?");
        $stmt->execute([$password_hash, $reset['email']]);

        $stmt = $db->prepare("DELETE FROM password_resets WHERE email = ?");
        $stmt->execute([$reset['email']]);

        echo "Password reset successfully! You can now <a href='../src/views/login.php'>login</a>.";
    } else {
        echo "Invalid or expired token.";
    }
}
?>
