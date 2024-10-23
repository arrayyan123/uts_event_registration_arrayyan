<?php
session_start();
require_once __DIR__ .'/../../models/event.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'organizer') {
    header('Location: ../views/home.php?message=Access_denied');
    exit();
}

$event = new Event();

if (isset($_GET['event_id'])) {
    $event_id = $_GET['event_id'];
    $participants = $event->getParticipantsByEventId($event_id);
    $event_data = $event->getEventById($event_id);
} else {
    header('Location: events_lists.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Participants for <?php echo htmlspecialchars($event_data['event_name']); ?> - Eventure</title>
    <link rel="stylesheet" href="../css/output.css">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
    <h1>Participants for <?php echo htmlspecialchars($event_data['event_name']); ?></h1>
    <a href="events_lists.php">Back to Events List</a>

    <div class="participants-list ">
        <?php if (empty($participants)): ?>
            <p>No participants registered for this event.</p>
        <?php else: ?>
            <div class="mx-auto max-w-screen-lg px-4 py-8 sm:px-8">
                <div class="overflow-y-hidden rounded-lg border">
                    <table class="w-full">
                        <tr class="bg-blue-600 text-left text-xs font-semibold uppercase tracking-widest text-white">
                            <th class="px-5 py-3">Participant Name</th>
                            <th class="px-5 py-3">User ID</th>
                            <th class="px-5 py-3">Registration Date</th>
                        </tr>
                        <?php foreach ($participants as $participant): ?>
                            <tr class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm"><?php echo htmlspecialchars($participant['participant_name']); ?></td>
                                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm"><?php echo htmlspecialchars($participant['user_id']); ?></td>
                                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm"><?php echo htmlspecialchars($participant['registration_date']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
