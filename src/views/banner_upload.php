<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../../models/event.php';
require_once __DIR__ . '/../../models/user.php';
require_once __DIR__ . '/../../models/db.php';

$database = new Database();
$db = $database->getConnection();

$user = new User();
$event = new Event();
$users = $user->getAllUsers();
$user_info = $user->getUserById($_SESSION['user_id']);

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'organizer') {
    header('Location: ../views/home.php?message=Access_denied');
    exit();
}

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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $target_dir = "../../uploads/banner_carousel/";
    $original_file_name = basename($_FILES["banner_image"]["name"]);
    $imageFileType = strtolower(pathinfo($original_file_name, PATHINFO_EXTENSION));

    $new_file_name = uniqid('banner_', true) . '.' . $imageFileType;
    $target_file = $target_dir . $new_file_name;
    $uploadOk = 1;

    $check = getimagesize($_FILES["banner_image"]["tmp_name"]);
    if ($check === false) {
        $uploadOk = 0;
    }
    if (!in_array($imageFileType, ['jpg', 'png', 'jpeg', 'webp'])) {
        $uploadOk = 0;
    }

    $subtitle = isset($_POST['subtitle']) ? ($_POST['subtitle']) : '';
    $description = isset($_POST['description']) ? ($_POST['description']) : '';

    if ($uploadOk == 1) {
        if (move_uploaded_file($_FILES["banner_image"]["tmp_name"], $target_file)) {
            require_once '../../models/db.php';
            $database = new Database();
            $db = $database->getConnection();
            $sql_check = "SELECT COUNT(*) as count FROM banner_promote WHERE image_path = :image_path";
            $stmt_check = $db->prepare($sql_check);
            $stmt_check->bindParam(':image_path', $new_file_name);
            $stmt_check->execute();
            $result = $stmt_check->fetch(PDO::FETCH_ASSOC);
            if ($result['count'] == 0) {
                $sql_insert = "INSERT INTO banner_promote (image_path, subtitle, description) 
                           VALUES (:image_path, :subtitle, :description)";
                $stmt_insert = $db->prepare($sql_insert);
                $stmt_insert->bindParam(':image_path', $new_file_name);
                $stmt_insert->bindParam(':subtitle', $subtitle);
                $stmt_insert->bindParam(':description', $description);
                $stmt_insert->execute();
                
                echo "Banner uploaded successfully.";
            } else {
                echo "This banner image already exists in the database.";
            }
        } else {
            echo "There was an error uploading the file.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.css"  rel="stylesheet" />
    <link rel="stylesheet" href="../css/output.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Banner Upload</title>
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
            <form action="banner_upload.php" method="post" enctype="multipart/form-data" class="space-y-4">
                <div id="drop_area" class="flex items-center justify-center w-full h-32 border-2 border-dashed border-gray-300 rounded-lg cursor-pointer hover:bg-gray-100">
                    <input type="file" name="banner_image" id="banner_image" class="hidden" required>
                    <p class="text-gray-500">Drag and drop your image here or click to upload</p>
                </div>
                <input type="text" name="subtitle" placeholder="Enter subtitle" required
                   class="w-full p-2 border border-gray-300 rounded-lg dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                <!-- Description Textarea -->
                <textarea name="description" placeholder="Enter description" required
                      class="w-full p-2 border border-gray-300 rounded-lg dark:border-gray-600 dark:bg-gray-700 dark:text-white"></textarea>
                <!-- Image preview -->
                <div class="mt-4">
                    <img id="image_preview" src="" alt="Image preview" class="hidden max-w-xs max-h-52 border border-gray-300 rounded-lg"/>
                </div>

                <input type="submit" value="Upload Banner" name="submit" class="px-4 py-2 text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg cursor-pointer">
            </form>
            <button class="p-4 mt-4 text-white text-center rounded-xl bg-red-600">
                <a href="home.php">back to home</a>
            </button>
        </div>
    </div>
    <!-- script untuk image preview nya dan drag drop gambar nya ;) -->
    <script>
        const dropArea = document.getElementById('drop_area');
        const inputFile = document.getElementById('banner_image');
        const imagePreview = document.getElementById('image_preview');

        dropArea.addEventListener('click', () => {
            inputFile.click();
        });

        dropArea.addEventListener('dragover', (event) => {
            event.preventDefault();
            dropArea.classList.add('bg-gray-100');
        });

        dropArea.addEventListener('dragleave', () => {
            dropArea.classList.remove('bg-gray-100');
        });

        dropArea.addEventListener('drop', (event) => {
            event.preventDefault();
            dropArea.classList.remove('bg-gray-100');
            const files = event.dataTransfer.files;
            if (files.length) {
                inputFile.files = files;
                const file = files[0];
                updateImagePreview(file);
            }
        });

        inputFile.addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                updateImagePreview(file);
            }
        });

        function updateImagePreview(file) {
            imagePreview.src = URL.createObjectURL(file);
            imagePreview.classList.remove('hidden');
        }
    </script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.js"></script>
</body>
</html>

