<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="../css/output.css">
</head>
<body class="bg-gradient-to-r from-purple-500 to-indigo-600 flex items-center justify-center min-h-screen">
    <div class="bg-white rounded-lg shadow-lg p-8 max-w-md w-full">
        <h2 class="text-2xl font-semibold text-center mb-6">Forgot Password</h2>
        <form action="../../controllers/forgot_password_controller.php" method="POST">
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700">Enter your email</label>
                <input type="email" 
                       class="mt-1 block w-full p-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200" 
                       name="email" 
                       id="email" 
                       placeholder="Enter your email" 
                       required>
            </div>
            <div>
                <button type="submit" name="submit" 
                        class="w-full py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition duration-200">Send Reset Link
                </button>
            </div>
        </form>
        <?php if (isset($_GET['message'])): ?>
            <p class="mt-4 text-red-600 text-sm text-center"><?php echo htmlspecialchars($_GET['message']); ?></p>
        <?php endif; ?>
        <div class="mt-6 text-center">
            <a href="login.php" class="text-indigo-600 hover:text-indigo-500 text-sm">Remembered your password? Login</a>
        </div>
    </div>
</body>
</html>
