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
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/style.css">
</head>
<body class="bg-gray-100 p-4">
    <div class="container mx-auto mt-5">
        <header class="mb-4 text-center">
            <h1 class="text-3xl font-bold">Register for Event</h1>
        </header>
        
        <section class="bg-white shadow-md rounded-lg overflow-hidden">
            <img src="../../uploads/<?php echo htmlspecialchars($event_data['banner']); ?>" class="w-full h-64 object-cover" alt="Event Banner">
            <div class="p-6">
                <h2 class="text-xl font-semibold"><?php echo htmlspecialchars($event_data['event_name']); ?></h2>
                <p class="mt-2 text-gray-700 truncate"><?php echo htmlspecialchars($event_data['description']); ?></p>
                <p class="mt-2 text-gray-700"><strong>Date:</strong> <?php echo htmlspecialchars($event_data['event_date']); ?></p>
                <p class="mt-2 text-gray-700"><strong>Location:</strong> <?php echo htmlspecialchars($event_data['location']); ?></p>
                
                <form action="" method="POST" class="mt-4">
                    <div class="mb-4">
                        <label for="participant_name" class="block text-sm font-medium text-gray-700">Your Name:</label>
                        <input type="text" id="participant_name" name="participant_name" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-blue-300" required>
                    </div>
                    <button type="submit" name="register" class="w-full py-2 bg-blue-600 text-white font-semibold rounded-md hover:bg-blue-500">Confirm Registration</button>
                </form>
                <?php if (isset($error_message)): ?>
                    <div class="mt-3 p-2 bg-red-200 text-red-800 rounded-md"><?php echo $error_message; ?></div>
                <?php endif; ?>
            </div>
        </section>
        
        <a href="home.php" class="mt-4 inline-block w-full py-2 text-center bg-gray-300 text-gray-800 font-semibold rounded-md hover:bg-gray-400">Back to Home</a>
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
</body>
</html>

