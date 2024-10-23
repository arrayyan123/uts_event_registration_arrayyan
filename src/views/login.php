
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Eventure</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #add8e6; /* Baby blue background */
        }
        .card {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            border-radius: 10px;
        }
        .position-relative {
            position: relative;
        }
        .toggle-password {
            cursor: pointer;
            position: absolute;
            right: 15px;
            top: 38px; 
            z-index: 1;
            color: #aaa; 
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card shadow">
            <div class="card-body">
                <h2 class="card-title text-center">Login</h2>
                <form action="../../controllers/auth_controller.php" method="POST">
                    <div class="mb-3 position-relative">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" id="email" placeholder="Enter your email" required>
                    </div>
                    <div class="mb-3 position-relative">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" name="password" id="password" placeholder="Enter your password" required>
                        <span class="toggle-password" id="toggle-password">
                            <i class="bi bi-eye" id="eye-icon" style="font-size: 20px;"></i>
                        </span>
                    </div>
                    <div class="d-grid">
                        <button type="submit" name="login" class="btn btn-primary">Login</button>
                    </div>
                </form>

                <p class="mt-3">
                    <a href="forgot_password.php">Forgot Password?</a>
                </p>
                
                <p class="text-center mt-3">Don't have an account? <a href="register.php">Register here</a>.</p>

                <?php if (isset($_GET['error'])): ?>
                    <p class="text-danger text-center mt-2"><?php echo htmlspecialchars($_GET['error']); ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const passwordInput = document.getElementById('password');
        const togglePassword = document.getElementById('toggle-password');
        const eyeIcon = document.getElementById('eye-icon');

        togglePassword.addEventListener('click', function() {
            // Toggle the password visibility
            const isPasswordVisible = passwordInput.type === 'text';
            passwordInput.type = isPasswordVisible ? 'password' : 'text';
            // Toggle the eye icon
            eyeIcon.classList.toggle('bi-eye');
            eyeIcon.classList.toggle('bi-eye-slash');
        });
    </script>
</body>
</html>
