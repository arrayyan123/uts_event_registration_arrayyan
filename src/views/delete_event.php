<?php
require_once __DIR__ .'/../models/event.php';

$event = new Event();

if (isset($_GET['id'])) {
    $event_id = $_GET['id'];

    $delete_result = $event->deleteEvent($event_id);

    if ($delete_result) {
        header('Location: events_lists.php?message=Event deleted');
    } else {
        header('Location: events_lists.php?error=Failed to delete event');
    }
} else {
    header('Location: events_lists.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Event - Eventure</title>
    <link rel="stylesheet" href="../../assets/style.css">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
    <h1>Delete Event: <?php echo $event_detail['event_name']; ?></h1>
    <p>Are you sure you want to delete this event?</p>

    <form action="../../controllers/event_controller.php" method="POST">
        <input type="hidden" name="event_id" value="<?php echo $event_detail['id']; ?>">
        <button type="submit" name="confirm_delete_event">Yes, Delete Event</button>
    </form>

    <a href="events_lists.php">No, Go Back</a>
</body>
</html>
