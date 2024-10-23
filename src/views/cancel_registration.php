<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require_once __DIR__ . '/../../models/event.php';

if (!isset($_GET['event_id'])) {
    header('Location: profile.php');
    exit();
}

$event_id = $_GET['event_id'];
$user_id = $_SESSION['user_id'];

$event = new Event();
$event_detail = $event->getEventById($event_id); 

if (!$event_detail) {
    header('Location: profile.php?error=event_not_found');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cancel Registration - Eventure</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="../css/output.css">
</head>
<body>
    <div class="h-screen flex flex-col justify-center items-center text-center p-10 bg-red-600">
        <div class="bg-red-200 p-10">
            <h1>Cancel Registration for <?php echo htmlspecialchars($event_detail['event_name']); ?></h1>
            <p>Are you sure you want to cancel your registration for this event?</p>
        </div>
        <div class="flex flex-row mx-auto gap-8 m-3">
            <div class="bg-gray-400 p-4 rounded rounded-[20px] cursor-pointer">
                <form action="../../controllers/event_controller.php" method="POST">
                    <input type="hidden" name="event_id" value="<?php echo htmlspecialchars($event_id); ?>">
                    <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user_id); ?>">
                    <button type="submit" name="confirm_cancel_registration">Yes, Cancel Registration</button>
                </form>
            </div>
            <div class="bg-blue-400 p-4 rounded rounded-[20px] cursor-pointer">
                <a href="profile.php">No, Go Back</a>
            </div>
        </div>
    </div>
</body>
</html>
