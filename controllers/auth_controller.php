<?php
session_start();
require_once __DIR__ .'/../models/user.php';
require_once __DIR__ .'/../models/db.php';

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
        header('Location: ../src/views/login.php');
        exit();
    } else {
        $error_message = 'Registration failed';
        header('Location: register.php?error=' . urlencode($error_message));
        exit();
    }
}

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $user_data = $user->loginUser($email, $password);

    if ($user_data) {
        $_SESSION['user_id'] = $user_data['id'];
        $_SESSION['role'] = $user_data['role'];
        header('Location: ../src/views/home.php');
        exit();
    } else {
        $error_message = 'Invalid email or password';
        header('Location: ../src/views/login.php?error=' . urlencode($error_message));
        exit();
    }
}

if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header('Location: ../src/views/login.php');
    exit();
}
?>
