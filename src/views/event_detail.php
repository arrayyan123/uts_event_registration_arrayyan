<?php
error_reporting(E_ALL); 
ini_set('display_errors', 1);
session_start();
require_once __DIR__ .'/../../models/event.php';
require_once __DIR__ .'/../../models/user.php';
require_once __DIR__ .'/../../models/db.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: home.php');
    exit;
}

$event = new Event();
$event_detail = $event->getEventById($_GET['id']);

$database = new Database();
$db = $database->getConnection();
$user = new User($db);

if (!$event_detail) {
    header('Location: home.php');
    exit;
}

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $is_registered = $event->getRegisteredEventsByUserId($user_id, $_GET['id']); // Cek apakah user sudah terdaftar pada event ini
}

function getStatusClass($status) {
    switch ($status) {
        case 'open':
            return 'bg-blue-500';  
        case 'close':
            return 'bg-orange-500';
        case 'cancel':
            return 'bg-red-500';   
        default:
            return 'bg-gray-500';   
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Detail - Eventure</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="stylesheet" href="../css/output.css?v=<?php echo time(); ?>">
</head>
<body>
    <div class="bg-gray-100">
        <div class="h-screen flex overflow-hidden bg-gray-200">
            <!-- Content -->
            <div class="flex-1 flex flex-col overflow-hidden">
                <!-- Navbar -->
                 
                <!-- Content Body -->
                <div class="flex-1 overflow-auto p-4">
                    <div class="bg-white p-4 rounded shadow">
                        <div class="flex flex-row justify-between">
                            <h1 class="text-2xl font-semibold"><?php echo htmlspecialchars($event_detail['event_name']); ?></h1>
                            <div class="p-3 rounded-[20px] <?php echo getStatusClass($event_detail['status']); ?>">
                                <h3 class="text-white"><?php echo htmlspecialchars(ucfirst($event_detail['status'])); ?></h3>
                            </div>
                        </div>
                        <p class="mt-2"><?php echo htmlspecialchars($event_detail['description']); ?></p>
                        <p class="card-text">Start Date: <?php echo htmlspecialchars($event_detail['start_date']); ?></p>
                        <p class="card-text">End Date: <?php echo htmlspecialchars($event_detail['end_date']); ?></p>
                        <p class="card-text"><strong>Price:</strong> <?php echo htmlspecialchars($event_detail['price']); ?></p>
                        <p><strong>Date:</strong> <?php echo htmlspecialchars($event_detail['event_date']); ?></p>
                        <p><strong>Time:</strong> <?php echo htmlspecialchars($event_detail['event_time']); ?></p>
                        <p><strong>Location:</strong> <?php echo htmlspecialchars($event_detail['location']); ?></p>
                        <img src="../../uploads/<?php echo htmlspecialchars($event_detail['banner']); ?>" alt="Event Banner" class="mt-4 max-w-xs">
                        
                        <div class="mt-4">
                            <?php if ($event_detail['status'] === 'open' && isset($_SESSION['user_id']) && $_SESSION['role'] == 'user'): ?>
                                <?php if ($is_registered): ?>
                                    <div class="text-green-500">You are already registered for this event.</div>
                                    <form action="../../controllers/event_controller.php" method="POST">
                                        <input type="hidden" name="event_id" value="<?php echo $event_detail['id']; ?>">
                                        <button type="submit" name="cancel_registration" class="bg-red-500 text-white px-4 py-2 rounded">Cancel Registration</button>
                                    </form>
                                <?php else: ?>
                                    <?php if (isset($_SESSION['user_id']) && $_SESSION['role'] === 'user' && ($event['status']) === 'open'): ?>
                                        <button class="bg-indigo-500 text-white px-4 py-2 rounded">
                                            <a href="register_participant.php?event_id=<?php echo $event['id']; ?>">Register</a>
                                        </button>
                                    <?php endif; ?>
                                <?php endif; ?>
                            <?php elseif ($event_detail['status'] === 'cancel'): ?>
                                <div class="text-red-500">This event has been canceled and is no longer accepting registrations.</div>
                            <?php elseif ($event_detail['status'] === 'open'): ?>
                                <p><a href="login.php" class="text-indigo-500 hover:underline">Login</a> to register for this event.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                    <a href="home.php" class="block mt-4 text-indigo-500 hover:underline">Back to Home</a>
                </div>
            </div>
        </div>
        <script>
            const sidebar = document.getElementById('sidebar');
            const openSidebarButton = document.getElementById('open-sidebar');

            openSidebarButton.addEventListener('click', (e) => {
                e.stopPropagation();
                sidebar.classList.toggle('-translate-x-full');
            });

            document.addEventListener('click', (e) => {
                if (!sidebar.contains(e.target) && !openSidebarButton.contains(e.target)) {
                    sidebar.classList.add('-translate-x-full');
                }
            });
        </script>
    </div>
    <script>
        // Menampilkan status pengguna di konsol
        const userRole = <?php echo json_encode(isset($_SESSION['role']) ? $_SESSION['role'] : 'guest'); ?>;
        console.log("User Role:", userRole);

        // Jika Anda ingin menampilkan ID pengguna juga
        const userId = <?php echo json_encode(isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null); ?>;
        console.log("User ID:", userId);
    </script>
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
