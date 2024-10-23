<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

require_once __DIR__ . '/../../models/event.php';
require_once __DIR__ . '/../../models/db.php';
require_once __DIR__ . '/../../models/user.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'organizer') {
    header('Location: ../views/home.php?message=Access_denied');
    exit();
}
 
$database = new Database();
$db = $database->getConnection();

$user = new User();
$event = new Event();
$users = $user->getAllUsers();

$user_info = $user->getUserById($_SESSION['user_id']);

$userName = htmlspecialchars($user_info['name']);
$profilePic = $user_info['profile_pic'] ?: 'default.png';
$userEmail = htmlspecialchars($user_info['email']);

$query = "SELECT COUNT(*) as total_users FROM users";
$stmt = $db->prepare($query);
$stmt->execute();
$total_users = $stmt->fetch(PDO::FETCH_ASSOC);

$query = "SELECT COUNT(*) as total_events FROM events";
$stmt = $db->prepare($query);
$stmt->execute();
$total_events = $stmt->fetch(PDO::FETCH_ASSOC);

if (isset($_GET['delete_id'])) {
    $delete_id = filter_input(INPUT_GET, 'delete_id', FILTER_VALIDATE_INT);

    if ($delete_id) {
        $query = "SELECT image_path FROM banner_promote WHERE id = :id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':id', $delete_id);
        $stmt->execute();
        $banner = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($banner && file_exists('../../uploads/banner_carousel/' . $banner['image_path'])) {
            unlink('../../uploads/banner_carousel/' . $banner['image_path']);
        }

        $query = "DELETE FROM banner_promote WHERE id = :id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':id', $delete_id);
        $stmt->execute();

        header('Location: banner_list.php?message=Banner deleted successfully.');
        exit();
    } else {
        header('Location: banner_list.php?message=Invalid ID provided.');
        exit();
    }
}

if (isset($_GET['action']) && $_GET['action'] === 'delete_all') {
    $query = "SELECT image_path FROM banner_promote";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $banners = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($banners as $banner) {
        if (file_exists('../../uploads/banner_carousel/' . $banner['image_path'])) {
            unlink('../../uploads/banner_carousel/' . $banner['image_path']);
        }
    }
    $query = "DELETE FROM banner_promote";
    $stmt = $db->prepare($query);
    $stmt->execute();

    $query = "ALTER TABLE banner_promote AUTO_INCREMENT = 1";
    $stmt = $db->prepare($query);
    $stmt->execute();

    echo "All banners deleted successfully.";
}

$query = "SELECT id, image_path, subtitle, description FROM banner_promote";
$stmt = $db->prepare($query);
$stmt->execute();
$banners = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Banner List - Eventure</title>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.css"  rel="stylesheet" />
    <link rel="stylesheet" href="../css/output.css?v=<?php echo time(); ?>">
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
            <!--konten di dalam sini -->
            <h1 class="text-xl font-bold mb-4">Uploaded Banners</h1>
            <table class="min-w-full border-collapse block md:table">
                <thead class="block md:table-header-group">
                    <tr class="border border-grey-500 md:border-none block md:table-row absolute -top-full md:top-auto -left-full md:left-auto  md:relative ">
                        <th class="bg-gray-600 p-2 text-white font-bold md:border md:border-grey-500 text-left block md:table-cell">ID</th>
                        <th class="bg-gray-600 p-2 text-white font-bold md:border md:border-grey-500 text-left block md:table-cell">Title</th>
                        <th class="bg-gray-600 p-2 text-white font-bold md:border md:border-grey-500 text-left block md:table-cell">description</th>
                        <th class="bg-gray-600 p-2 text-white font-bold md:border md:border-grey-500 text-left block md:table-cell">Image</th>
                        <th class="bg-gray-600 p-2 text-white font-bold md:border md:border-grey-500 text-left block md:table-cell">Action</th>
                    </tr>
                </thead>
                <tbody class="block md:table-row-group">
                    <?php foreach ($banners as $banner): ?>
                        <tr class="bg-gray-300 border border-grey-500 md:border-none block md:table-row">
                            <td class="p-2 md:border md:border-grey-500 text-left block md:table-cell"><?php echo htmlspecialchars($banner['id']); ?></td>
                            <td><?php echo isset($banner['subtitle']) ? htmlspecialchars($banner['subtitle']) : 'No subtitle'; ?></td>
                            <td><?php echo isset($banner['description']) ? htmlspecialchars($banner['description']) : 'No description'; ?></td>
                            <td class="p-2 md:border md:border-grey-500 text-left block md:table-cell">
                                <img src="../../uploads/banner_carousel/<?php echo htmlspecialchars($banner['image_path']); ?>" alt="Banner" width="100" height="50">
                            </td>
                            <td class="p-2 md:border md:border-grey-500 text-left block md:table-cell">
                                <a href="edit_banner.php?id=<?php echo $banner['id']; ?>" class="px-3 py-1 text-sm text-white bg-blue-500 rounded hover:bg-blue-700">Edit</a>

                                <!--change this button so before deleting, the modal pop up-->
                                <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 border border-red-500 rounded" onclick="openDeleteModal(<?php echo $banner['id']; ?>)">Delete</button>

                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div class="my-2">
                <button id="deleteAllButton" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg">
                    Delete All Banners
                </button>
                <button class="py-2 px-4 mt-7 bg-blue-500 font-bold text-white rounded-lg">
                    <a href="dashboard.php">Back to Dashboard</a>
                </button>
            </div>
            
            <!--Delete by id modal-->
            <div id="deleteIdModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
                <div id="modalContentId" class="bg-white p-6 rounded-lg shadow-lg max-w-sm w-full">
                    <h2 class="text-xl font-semibold mb-4">Confirm Deletion</h2>
                    <p>Are you sure you want to delete this banner? This action cannot be undone.</p>
                    <div class="flex justify-end mt-4">
                        <button id="confirmDeleteId" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded mr-2">Yes</button>
                        <button id="cancelDeleteId" class="bg-gray-300 hover:bg-gray-400 text-gray-700 font-bold py-2 px-4 rounded">No</button>
                    </div>
                </div>
            </div>

            <!--Delete All modal-->
            <div id="deleteAllModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden backdrop-blur-sm transition-opacity duration-300">
                <div id="modalContent" class="bg-white p-6 rounded-lg shadow-lg max-w-sm w-full transform transition-all duration-300 scale-0 opacity-0">
                    <h2 class="text-xl font-semibold mb-4">Confirm Deletion</h2>
                    <p>Are you sure you want to delete all banners? This action cannot be undone.</p>
                    <div class="flex justify-end mt-4">
                        <button id="confirmDeleteAll" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded mr-2">Yes</button>
                        <button id="cancelDeleteAll" class="bg-gray-300 hover:bg-gray-400 text-gray-700 font-bold py-2 px-4 rounded">No</button>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        function openDeleteModal(bannerId) {
            const modal = document.getElementById('deleteIdModal');
            const confirmButton = document.getElementById('confirmDeleteId');
            
            modal.classList.remove('hidden');
            confirmButton.onclick = function() {
                window.location.href = 'banner_list.php?delete_id=' + bannerId;
            };
        }

        // Delete all banners modal
        document.getElementById('deleteAllButton').addEventListener('click', function() {
            const modal = document.getElementById('deleteAllModal');
            const modalContent = document.getElementById('modalContent');
            
            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.classList.remove('opacity-0');
                modalContent.classList.remove('scale-0', 'opacity-0');
                modalContent.classList.add('scale-100', 'opacity-100');
            }, 10);
        });

        document.getElementById('confirmDeleteAll').addEventListener('click', function() {
            window.location.href = "?action=delete_all";
        });

        document.getElementById('cancelDeleteAll').addEventListener('click', function() {
            const modal = document.getElementById('deleteAllModal');
            const modalContent = document.getElementById('modalContent');
            
            modalContent.classList.remove('scale-100', 'opacity-100');
            modalContent.classList.add('scale-0', 'opacity-0');
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        });

        // Delete banner by ID modal
        document.querySelectorAll('a[href^="banner_list.php?delete_id="]').forEach(link => {
            link.addEventListener('click', function(event) {
                event.preventDefault();
                const bannerId = this.getAttribute('href').split('=')[1];
                const modal = document.getElementById('deleteIdModal');
                const modalContent = document.getElementById('modalContentId');
                const confirmButton = document.getElementById('confirmDeleteId');

                confirmButton.setAttribute('data-id', bannerId);
                modal.classList.remove('hidden');

                setTimeout(() => {
                    modal.classList.remove('opacity-0');
                    modalContent.classList.remove('scale-0', 'opacity-0');
                    modalContent.classList.add('scale-100', 'opacity-100');
                }, 10);
            });
        });

        document.getElementById('cancelDeleteId').addEventListener('click', function() {
            const modal = document.getElementById('deleteIdModal');
            const modalContent = document.getElementById('modalContentId');
            
            modalContent.classList.remove('scale-100', 'opacity-100');
            modalContent.classList.add('scale-0', 'opacity-0');
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        });

        document.getElementById('confirmDeleteId').addEventListener('click', function() {
            const bannerId = this.getAttribute('data-id');
            window.location.href = 'banner_list.php?delete_id=' + bannerId;
        });
    </script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.js"></script>
</body>
</html>
