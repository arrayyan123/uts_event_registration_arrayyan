
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="../css/output.css">
</head>
<body>
    <div class="">
        <div class="">
            <div class="">
                <h2 class="text-center">Forgot Password</h2>
                <form action="../../controllers/forgot_password_controller.php" method="POST">
                    <div class="">
                        <label for="email" class="">Email</label>
                        <input type="email" class="" name="email" id="email" placeholder="Enter your email" required>
                    </div>
                    <div class="d-grid">
                        <button type="submit" name="submit" class="">Send Password Reset Link</button>
                    </div>
                </form>
                <?php if (isset($_GET['message'])): ?>
                    <p class=""><?php echo htmlspecialchars($_GET['message']); ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div>
        <button>
            <a href="login.php" class="btn btn-link">Back to Login</a>
        </button>
    </div>
</body>
</html>
