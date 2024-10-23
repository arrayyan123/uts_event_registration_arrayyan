<?php
session_start();
require_once __DIR__ .'/../../models/event.php';
require_once __DIR__ .'/../../models/user.php';
require_once __DIR__ .'/../../models/db.php';

$database = new Database();
$db = $database->getConnection();
$user = new User($db);
$event = new Event();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header('Location: login.php');
    exit();
}

if (isset($_GET['event_id'])) {
    $event_id = $_GET['event_id'];
    $event_data = $event->getEventById($event_id);
} else {
    header('Location: home.php');
    exit();
}

if (isset($_POST['register'])) {
    $user_id = $_SESSION['user_id'];
    $participant_name = $_POST['participant_name'];

    if ($event->registerUserForEvent($user_id, $event_id, $participant_name)) {
        header('Location: home.php?success=Successfully registered for the event!');
        exit();
    } else {
        $error_message = 'Registration failed. Please try again.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register for Event - Eventure</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../assets/style.css">
</head>
<body>
    <div class="container mt-5">
        <header class="mb-4 text-center">
            <h1>Register for Event</h1>
        </header>
        
        <section class="card">
            <img src="../../uploads/<?php echo htmlspecialchars($event_data['banner']); ?>" class="card-img-top" alt="Event Banner">
            <div class="card-body">
                <h2 class="card-title"><?php echo htmlspecialchars($event_data['event_name']); ?></h2>
                <p class="card-text"><?php echo htmlspecialchars($event_data['description']); ?></p>
                <p class="card-text"><strong>Date:</strong> <?php echo htmlspecialchars($event_data['event_date']); ?></p>
                <p class="card-text"><strong>Location:</strong> <?php echo htmlspecialchars($event_data['location']); ?></p>
                
                <form action="" method="POST">
                    <div class="mb-3">
                        <label for="participant_name" class="form-label">Your Name:</label>
                        <input type="text" id="participant_name" name="participant_name" class="form-control" required>
                    </div>
                    <button type="submit" name="register" class="btn btn-primary">Confirm Registration</button>
                </form>
                <?php if (isset($error_message)): ?>
                    <div class="alert alert-danger mt-3"><?php echo $error_message; ?></div>
                <?php endif; ?>
            </div>
        </section>
        
        <a href="home.php" class="btn btn-secondary mt-4">Back to Home</a>
    </div>
    <script>
        let startTime = Date.now();

        function logActivity() {
            let endTime = Date.now();
            let timeSpent = Math.round((endTime - startTime) / 1000); 

            let xhr = new XMLHttpRequest();
            xhr.open('POST', 'log_activity.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.send('time_spent=' + timeSpent + '&page=' + encodeURIComponent(window.location.pathname));
        }

        window.addEventListener('beforeunload', logActivity);
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
