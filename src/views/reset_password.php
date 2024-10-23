<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require __DIR__ .'/../../models/db.php'; 

$database = new Database();
$db = $database->getConnection();

$token = $_GET['token'] ?? '';

$stmt = $db->prepare("SELECT * FROM password_resets WHERE token = ? AND expires_at > NOW()");
$stmt->execute([$token]);
$reset = $stmt->fetch();

if (!$reset) {
    echo "Invalid or expired token.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Eventure</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="card shadow mt-5">
            <div class="card-body">
                <h2 class="card-title text-center">Reset Password</h2>
                <form action="../../controllers/reset_password_controller.php" method="POST">
                    <div class="mb-3">
                        <label for="password" class="form-label">New Password</label>
                        <input type="password" class="form-control" name="password" id="password" placeholder="Enter your new password" required>
                    </div>
                    <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
                    <div class="d-grid">
                        <button type="submit" name="submit" class="btn btn-primary">Reset Password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
