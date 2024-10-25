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
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="../css/output.css">
</head>
<body class="bg-gradient-to-r from-purple-500 to-indigo-600 flex items-center justify-center min-h-screen">
    <div class="bg-white rounded-lg shadow-lg p-8 max-w-md w-full">
        <h2 class="text-2xl font-semibold text-center mb-6">Reset Password</h2>
        <form action="../../controllers/reset_password_controller.php" method="POST">
            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700">New Password</label>
                <input type="password" class="mt-1 block w-full p-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" name="password" id="password" placeholder="Enter your new password" required>
            </div>
            <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
            <div>
                <button type="submit" name="submit" class="w-full py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition duration-200">Reset Password</button>
            </div>
        </form>
    </div>
</body>
</html>