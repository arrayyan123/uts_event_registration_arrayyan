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
                <nav class="sm:bg-none bg-white absolute sm:border-none z-40 w-full px-2 sm:px-4 py-2.5 rounded dark:bg-gray-800 shadow">
                    <div class="container flex flex-wrap justify-between items-center mx-auto">
                        <a href="home.php" class="flex items-center">
                            <div class="rounded-full h-auto w-auto mx-2 bg-white">
                                <img src="../../assets/images/logo_eventure.png" class="w-[90px] h-[90px]" alt="">
                            </div>
                            <span class="self-center text-xl font-semibold whitespace-nowrap dark:text-white">
                                Eventure
                            </span>
                        </a>
                        <div class="flex items-center">
                            <button
                                id="menu-toggle"
                                type="button"
                                class="inline-flex items-center p-2 ml-3 text-sm text-gray-500 rounded-lg hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600 lg:hidden transition-transform duration-300 ease-in-out"
                            >
                                <span class="sr-only">Open main menu</span>
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M4 6h16M4 12h16m-7 6h7"
                                    />
                                </svg>
                            </button>
                        </div>

                        <div
                            class="hidden w-full lg:block lg:w-auto transition-all duration-500 ease-in-out transform origin-top"
                            id="mobile-menu"
                        >
                            <ul class="flex flex-col mt-4 dark:text-white text-black lg:flex-row lg:space-x-8 lg:mt-0 lg:items-center lg:text-sm md:font-medium gap-[20px]">
                                <form method="GET" action="home.php" class="flex flex-row text-black gap-2 items-center lg:w-auto w-full">
                                    <input type="text" name="search" placeholder="Search events..." class="border px-4 py-2 rounded-lg w-full sm:w-1/2 lg:w-2/3" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg">Search</button>
                                </form>
                                <?php if (isset($_SESSION['user_id'])): ?>
                                    <li>
                                        <a href="#landing-page" class="block hover:text-indigo-400" aria-current="page">Home</a>
                                    </li>
                                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'organizer'): ?>
                                        <li class=""><a href="dashboard.php" class="block hover:text-indigo-400">Dashboard</a></li>
                                    <?php endif; ?>
                                    <li class=""><a href="profile.php" class="block hover:text-indigo-400">Profile</a></li>
                                    <li class=""><a href="../../controllers/auth_controller.php?logout=true" class="block hover:text-indigo-400">Logout</a></li>
                                <?php else: ?>
                                    <li class=""><a href="login.php" class="block hover:text-indigo-400">Login</a></li>
                                    <li class=""><a href="register.php" class="block hover:text-indigo-400">Register</a></li>
                                <?php endif; ?>
                                <div class="user-info flex flex-row items-center gap-5">
                                    <?php if (isset($_SESSION['user_id'])): ?>
                                        <?php 
                                            $userInfo = $user->getUserById($_SESSION['user_id']);
                                            $profilePic = isset($userInfo['profile_pic']) ? htmlspecialchars($userInfo['profile_pic']) : 'default.png';
                                            $userName = isset($userInfo['name']) ? htmlspecialchars($userInfo['name']) : 'Guest';
                                        ?>
                                        <img src="../../uploads/profilepic/<?php echo $profilePic; ?>?<?php echo time(); ?>" alt="Profile Picture" width="40" height="40" class="rounded-full mb-2">
                                        <span><?php echo $userName; ?></span>
                                    <?php else: ?>
                                        <img src="../../uploads/profilepic/default.png" alt="Profile Picture" width="40" height="40" class="rounded-full mb-2">
                                        <span>Guest</span>
                                    <?php endif; ?>
                                </div>
                            </ul>
                        </div>
                    </div>
                </nav>
                <!-- Content Body -->
                <div class="flex-1 mt-[100px] overflow-auto p-4">
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
                                    <?php if (isset($_SESSION['user_id']) && $_SESSION['role'] === 'user' && ($event_detail['status']) === 'open'): ?>
                                        <button class="bg-indigo-500 text-white px-4 py-2 rounded">
                                            <a href="register_participant.php?event_id=<?php echo $event_detail['id']; ?>">Register</a>
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
