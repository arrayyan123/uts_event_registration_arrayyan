<?php
error_reporting(E_ALL); 
ini_set('display_errors', 1);
session_start();
require_once __DIR__ .'/../../models/user.php';
require_once __DIR__ .'/../../models/event.php';
require_once __DIR__ .'/../../vendor/autoload.php';
require_once __DIR__ .'/../../models/db.php';

$database = new Database();
$db = $database->getConnection();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'organizer') {
    header('Location: ../../src/views/home.php?message=Access_denied');
    exit();
}

$user = new User();
$event = new Event();
$events = $event->getAllEvents(); 

$user_info = $user->getUserById($_SESSION['user_id']);

$userName = htmlspecialchars($user_info['name']);
$profilePic = $user_info['profile_pic'] ?: 'default.png';
$userEmail = htmlspecialchars($user_info['email']);

$query = "SELECT COUNT(*) as total_events FROM events";
$stmt = $db->prepare($query);
$stmt->execute();
$total_events = $stmt->fetch(PDO::FETCH_ASSOC);

$query = "SELECT COUNT(*) as total_users FROM users";
$stmt = $db->prepare($query);
$stmt->execute();
$total_users = $stmt->fetch(PDO::FETCH_ASSOC);

if (isset($_GET['export']) && $_GET['export'] === 'excel') {
    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Set the header row
    $sheet->setCellValue('A1', 'Event ID');
    $sheet->setCellValue('B1', 'Event Name');
    $sheet->setCellValue('C1', 'Start Date');
    $sheet->setCellValue('D1', 'End Date');
    $sheet->setCellValue('E1', 'Price');
    $sheet->setCellValue('F1', 'Location');
    $sheet->setCellValue('G1', 'Status');

    $row = 2; 
    foreach ($events as $event_item) {
        $sheet->setCellValue('A' . $row, htmlspecialchars($event_item['id']));
        $sheet->setCellValue('B' . $row, htmlspecialchars($event_item['event_name']));
        $sheet->setCellValue('C' . $row, htmlspecialchars($event_item['start_date'])); // Start Date
        $sheet->setCellValue('D' . $row, htmlspecialchars($event_item['end_date']));   // End Date
        $sheet->setCellValue('E' . $row, htmlspecialchars($event_item['price']));      // Price
        $sheet->setCellValue('F' . $row, htmlspecialchars($event_item['location']));
        $sheet->setCellValue('G' . $row, htmlspecialchars($event_item['status']));
        $row++;
    }

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="events_list.xlsx"');
    header('Cache-Control: max-age=0');

    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
    $writer->save('php://output');
    exit();
}

function getStatusClass($status) {
    switch ($status) {
        case 'open':
            return 'blue-500';
        case 'close':
            return 'orange-500';
        case 'cancel':
            return 'red-500';
        default:
            return 'gray-500';
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View All Users - Eventure</title>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.css"  rel="stylesheet" />
    <link rel="stylesheet" href="../css/output.css">
    <script src="https://cdn.tailwindcss.com"></script>

</head>
<body>
    <nav class="fixed top-0 z-50 w-full bg-white border-b border-gray-200 dark:bg-gray-800 dark:border-gray-700">
        <div class="px-3 py-3 lg:px-5 lg:pl-3">
            <div class="flex items-center justify-between">
            <div class="flex items-center justify-start rtl:justify-end">
                <button data-drawer-target="logo-sidebar" data-drawer-toggle="logo-sidebar" aria-controls="logo-sidebar" type="button" class="inline-flex items-center p-2 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600">
                    <span class="sr-only">Open sidebar</span>
                    <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path clip-rule="evenodd" fill-rule="evenodd" d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"></path>
                    </svg>
                </button>
                <a href="dashboard.php" class="flex items-center ms-2 md:me-24">
                    <ion-icon class="text-2xl mr-3" name="infinite-outline"></ion-icon>
                    <span class="self-center text-xl font-semibold sm:text-2xl whitespace-nowrap dark:text-white">Admin Organizer</span>
                </a>
            </div>
            <div class="flex items-center">
                <div class="flex items-center ms-3">
                    <div>
                        <button type="button" class="flex text-sm bg-gray-800 rounded-full focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600" aria-expanded="false" data-dropdown-toggle="dropdown-user">
                            <span class="sr-only">Open user menu</span>
                            <img class="w-8 h-8 rounded-full" src="../../uploads/profilepic/<?php echo $profilePic; ?>" alt="user photo">
                        </button>
                    </div>
                    <div class="z-50 hidden my-4 text-base list-none bg-white divide-y divide-gray-100 rounded shadow dark:bg-gray-700 dark:divide-gray-600" id="dropdown-user">
                        <div class="px-4 py-3" role="none">
                            <p class="text-sm text-gray-900 dark:text-white" role="none">
                                <?php echo $userName; ?>
                            </p>
                            <p class="text-sm font-medium text-gray-900 truncate dark:text-gray-300" role="none">
                                <?php echo $userEmail; ?>
                            </p>
                        </div>
                        <ul class="py-1" role="none">
                            <li>
                                <a href="profile.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white" role="menuitem">Profile</a>
                            </li>
                            <li>
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white" role="menuitem">Settings</a>
                            </li>
                            <li>
                                <a href="../../controllers/auth_controller.php?logout=true" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white" role="menuitem">Sign out</a>
                            </li>
                        </ul>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </nav>

    <aside id="logo-sidebar" class="fixed top-0 left-0 z-40 w-64 h-screen pt-20 transition-transform -translate-x-full bg-white border-r border-gray-200 sm:translate-x-0 dark:bg-gray-800 dark:border-gray-700" aria-label="Sidebar">
        <div class="h-full px-3 pb-4 overflow-y-auto bg-white dark:bg-gray-800">
            <ul class="space-y-2 font-medium">
                <li>
                    <a href="dashboard.php" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                    <svg class="w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 21">
                        <path d="M16.975 11H10V4.025a1 1 0 0 0-1.066-.998 8.5 8.5 0 1 0 9.039 9.039.999.999 0 0 0-1-1.066h.002Z"/>
                        <path d="M12.5 0c-.157 0-.311.01-.565.027A1 1 0 0 0 11 1.02V10h8.975a1 1 0 0 0 1-.935c.013-.188.028-.374.028-.565A8.51 8.51 0 0 0 12.5 0Z"/>
                    </svg>
                    <span class="ms-3">Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="create_event.php" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                        <ion-icon class="text-2xl text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" name="apps-outline"></ion-icon>
                        <span class="flex-1 ms-3 whitespace-nowrap">Create Event</span>
                        <span class="inline-flex items-center justify-center px-2 ms-3 text-sm font-medium text-gray-800 bg-gray-100 rounded-full dark:bg-gray-700 dark:text-gray-300">Pro</span>
                    </a>
                </li>
                <li>
                    <a href="events_lists.php" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                        <ion-icon class="text-2xl text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" name="football-outline"></ion-icon>
                        <span class="flex-1 ms-3 whitespace-nowrap">View All Events</span>
                        <span class="inline-flex items-center justify-center w-3 h-3 p-3 ms-3 text-sm font-medium text-blue-800 bg-blue-100 rounded-full dark:bg-blue-900 dark:text-blue-300"><?php echo htmlspecialchars($total_events['total_events']); ?></span>
                    </a>
                </li>
                <li>
                    <a href="view_users.php" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                    <svg class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 18">
                        <path d="M14 2a3.963 3.963 0 0 0-1.4.267 6.439 6.439 0 0 1-1.331 6.638A4 4 0 1 0 14 2Zm1 9h-1.264A6.957 6.957 0 0 1 15 15v2a2.97 2.97 0 0 1-.184 1H19a1 1 0 0 0 1-1v-1a5.006 5.006 0 0 0-5-5ZM6.5 9a4.5 4.5 0 1 0 0-9 4.5 4.5 0 0 0 0 9ZM8 10H5a5.006 5.006 0 0 0-5 5v2a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-2a5.006 5.006 0 0 0-5-5Z"/>
                    </svg>
                        <span class="flex-1 ms-3 whitespace-nowrap">Users</span>
                        <span class="inline-flex items-center justify-center w-3 h-3 p-3 ms-3 text-sm font-medium text-blue-800 bg-blue-100 rounded-full dark:bg-blue-900 dark:text-blue-300"><?php echo htmlspecialchars($total_users['total_users']); ?></span>
                    </a>
                </li>
                <li>
                    <a href="banner_upload.php" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                        <ion-icon class="text-2xl text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" name="sparkles-outline"></ion-icon>
                        <span class="flex-1 ms-3 whitespace-nowrap">Upload Banner</span>
                    </a>
                </li>
                <li>
                    <a href="banner_list.php" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                    <svg class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 18">
                        <path d="M14 2a3.963 3.963 0 0 0-1.4.267 6.439 6.439 0 0 1-1.331 6.638A4 4 0 1 0 14 2Zm1 9h-1.264A6.957 6.957 0 0 1 15 15v2a2.97 2.97 0 0 1-.184 1H19a1 1 0 0 0 1-1v-1a5.006 5.006 0 0 0-5-5ZM6.5 9a4.5 4.5 0 1 0 0-9 4.5 4.5 0 0 0 0 9ZM8 10H5a5.006 5.006 0 0 0-5 5v2a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-2a5.006 5.006 0 0 0-5-5Z"/>
                    </svg>
                    <span class="flex-1 ms-3 whitespace-nowrap">Banner Lists</span>
                    </a>
                </li>
            </ul>
        </div>
    </aside>
    <div class="p-4 sm:ml-64">
        <div class="p-4 border-2 border-gray-200 border-dashed rounded-lg dark:border-gray-700 mt-14">
            <div class="container">
                <div class="flex flex-col justify-center items-center">
                    <h1 class="text-center font-bold text-xl my-3">All Events</h1>
                    <div class="">
                        <a href="dashboard.php" class="py-2 px-4 bg-red-500 rounded-xl text-white">Back to Dashboard</a>
                        <a href="events_lists.php?export=excel" class="py-2 px-4 bg-green-500 rounded-xl text-white">Export to Excel</a>
                    </div>
                </div>
                <div class="events-list grid grid-cols-1 sm:grid-cols-1 lg:grid-cols-2 xl:grid-cols-2 gap-1 w-full">
                    <?php if (empty($events)): ?>
                        <div class="alert alert-warning" role="alert">
                            No events found.
                        </div>
                    <?php else: ?>
                        <?php foreach ($events as $event_item): ?>
                            <div class="m-4">
                                <div class="p-3 h-full bg-gray-300 rounded-xl flex flex-col shadow-xl">
                                    <div class="flex flex-row items-center justify-between">
                                        <h3 class="text-2xl font-bold">
                                            <?php echo htmlspecialchars($event_item['event_name']); ?> 
                                        </h3>
                                        <span class="px-3 py-2 text-white font-bold rounded-xl <?php echo 'bg-' . getStatusClass($event_item['status']); ?>"><?php echo htmlspecialchars(ucfirst($event_item['status'])); ?></span>
                                    </div>
                                    <div class="py-2 px-4 bg-white my-2 rounded-xl grid grid-cols-1 sm:grid-cols-1 lg:grid-cols-1 xl:grid-cols-2 gap-1">
                                        <p class="">Start Date: <?php echo htmlspecialchars($event_item['start_date']); ?></p>
                                        <p class="">End Date: <?php echo htmlspecialchars($event_item['end_date']); ?></p>
                                        <p class="">Time: <?php echo htmlspecialchars($event_item['event_time']);?></p>
                                        <p class="">Price: <?php echo htmlspecialchars($event_item['price']); ?></p>
                                        <p class="">Location: <?php echo htmlspecialchars($event_item['location']); ?></p>
                                    </div>
                                    <img src="../../uploads/<?php echo isset($event_item['banner']) && !empty($event_item['banner']) ? htmlspecialchars($event_item['banner']) : 'default.png'; ?>" 
                                    alt="<?php echo htmlspecialchars($event_item['event_name']); ?>" 
                                    class="w-auto rounded-xl">
                                    <div class="">
                                        <div class="flex lg:flex-row flex-col lg:items-center items-start my-3 gap-3">
                                            <span class="px-4 py-2 bg-blue-500 rounded-xl text-white"><a href="edit_event.php?id=<?php echo htmlspecialchars($event_item['id']); ?>" class="">Edit</a></span>
                                            <span class="px-4 py-2 bg-red-500 rounded-xl text-white"><a href="../../controllers/event_controller.php?delete_event=true&event_id=<?php echo htmlspecialchars($event_item['id']); ?>" 
                                            class="" 
                                            onclick="return confirm('Are you sure you want to delete this event?');">Delete</a></span>
                                            <span class="px-4 py-2 bg-orange-500 rounded-xl text-white"><a href="view_participants.php?event_id=<?php echo htmlspecialchars($event_item['id']); ?>" class="">View Participants</a></span>
                                        </div>
                                        <div>
                                            <!-- Dropdown for changing event status -->
                                            <form action="../../controllers/event_controller.php" method="POST" class="">
                                                <input type="hidden" name="event_id" value="<?php echo htmlspecialchars($event_item['id']); ?>">
                                                <select name="status" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" aria-label="Event status">
                                                    <option value="open" <?php echo $event_item['status'] === 'open' ? 'selected' : ''; ?>>Open</option>
                                                    <option value="close" <?php echo $event_item['status'] === 'close' ? 'selected' : ''; ?>>Close</option>
                                                    <option value="cancel" <?php echo $event_item['status'] === 'cancel' ? 'selected' : ''; ?>>Cancel</option>
                                                </select>
                                                <button class="px-3 py-2 bg-blue-500 rounded-xl text-white my-3" type="submit" name="update_status" class="">Update Status</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.js"></script>
</body>
</html>