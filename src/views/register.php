<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once __DIR__ .'/../../models/user.php';
require_once __DIR__ .'/../../models/db.php'; 

$database = new Database();
$db = $database->getConnection();
$user = new User($db); 

if (isset($_POST['register'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    if (!in_array($role, ['user', 'organizer'])) {
        $error_message = 'Invalid role selected';
        header('Location: register.php?error=' . urlencode($error_message));
        exit();
    }

    if ($user->isEmailExists($email)) {
        $error_message = 'Email already exists';
        header('Location: register.php?error=' . urlencode($error_message));
        exit();
    }

    if ($user->registerUser($name, $email, $password, $role)) {
        header('Location: ../views/login.php');
        exit();
    } else {
        $error_message = 'Registration failed';
        header('Location: register.php?error=' . urlencode($error_message));
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Eventure</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #add8e6; /* Baby blue */
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
            right: 15px; /* Adjust position of the eye icon */
            top: 38px; /* Adjust based on the input height */
            z-index: 1;
            color: #aaa; /* Color for the eye icon */
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card shadow">
            <div class="card-body">
                <h2 class="card-title text-center">Register</h2>
                <form action="register.php" method="POST">
                    <div class="mb-3 position-relative">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" name="name" id="name" placeholder="Enter your name" required>
                    </div>
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
                    <div class="mb-3">
                        <label for="role" class="form-label">Role</label>
                        <select class="form-select" name="role" id="role" required>
                            <option value="user">User</option>
                            <option value="organizer">Event Organizer</option>
                        </select>
                    </div>
                    <div class="d-grid">
                        <button type="submit" name="register" class="btn btn-primary">Register</button>
                    </div>
                </form>
                <p class="text-center mt-3">Already have an account? <a href="login.php">Login here</a>.</p>
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


