<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

require_once __DIR__ .'/../../models/user.php';
require_once __DIR__ .'/../../models/event.php';

$user = new User();
$event = new Event();

$user_info = $user->getUserById($_SESSION['user_id']);
$registered_events = $event->getRegisteredEventsByUserId($_SESSION['user_id']);
$show_checkmark = false;

if (isset($_GET['success']) && $_GET['success'] == '1') {
    $show_checkmark = true;
}

if (isset($_GET['cancel_registration']) && $_GET['cancel_registration'] == '1') {
    $event_id = $_GET['event_id'];
    if ($event->cancelRegistration($event_id, $_SESSION['user_id'])) {
        header("Location: cancel_registration.php?event_id=$event_id");
        exit();
    } else {
         error_log("Failed to cancel registration for event ID: " . $event_id);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $file_name = $user_info['profile_pic']; 

    if (isset($_POST['croppedImage'])) {
        $data = $_POST['croppedImage'];

        if (strpos($data, 'data:image/jpeg') === 0) {
            $ext = 'jpg';
            $data = str_replace('data:image/jpeg;base64,', '', $data);
        } elseif (strpos($data, 'data:image/png') === 0) {
            $ext = 'png';
            $data = str_replace('data:image/png;base64,', '', $data);
        } else {
            die('Unsupported image type.');
        }

        $data = str_replace(' ', '+', $data);
        $data = base64_decode($data);

        $file_name = $_SESSION['user_id'] . "_profile_pic." . $ext; 
        $target_dir = "../../uploads/profilepic/";

        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0755, true);
        }
        if (file_put_contents($target_dir . $file_name, $data) === false) {
            die('Failed to save the profile picture.');
        }
    }    
    if ($user->updateProfile($_SESSION['user_id'], $name, $email, $file_name)) {
        $_SESSION['profile_updated'] = true;
        header('Location: profile.php');
        exit();
    } else {
        error_log("Failed to update profile for user ID: " . $_SESSION['user_id']);
    }
}

$show_checkmark = isset($_SESSION['profile_updated']) ? $_SESSION['profile_updated'] : false;
if ($show_checkmark) {
    unset($_SESSION['profile_updated']);
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile - Eventure</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css" />
    <style>
        #image {
            max-width: 100%;
        }

        .checkmark {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background-color: #22c55e;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            margin: 0 auto;
        }

        .checkmark::after {
            content: '';
            position: absolute;
            width: 10px;
            height: 20px;
            border: solid white;
            border-width: 0 4px 4px 0;
            transform: rotate(45deg);
            opacity: 0;
            transition: opacity 0.4s ease-in-out;
        }

        .checkmark.show::after {
            opacity: 1;
        }

        .checkmark-animation {
            animation: scale 0.5s forwards;
        }

        @keyframes scale {
            0% {
                transform: scale(0);
            }
            50% {
                transform: scale(1.2);
            }
            100% {
                transform: scale(1);
            }
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="max-w-4xl mx-auto p-4">
        <h1 class="text-2xl font-semibold mb-6"><?php echo htmlspecialchars($user_info['name']); ?>'s Profile</h1>

        <!-- Modal -->
        <div id="checkmarkModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
            <div class="bg-white p-6 rounded-lg text-center">
                <div class="checkmark show checkmark-animation"></div>
                <h5 class="mt-2 text-lg">Profile Updated Successfully!</h5>
            </div>
        </div>

        <form action="profile.php" method="POST" enctype="multipart/form-data" id="profileForm" class="bg-white p-6 rounded-lg shadow-md">
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                <input type="text" id="name" name="name" class="mt-1 block w-full p-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" value="<?php echo htmlspecialchars($user_info['name']); ?>" required>
            </div>
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" id="email" name="email" class="mt-1 block w-full p-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" value="<?php echo htmlspecialchars($user_info['email']); ?>" required>
            </div>
            <div class="mb-4">
                <label for="profile_pic" class="block text-sm font-medium text-gray-700">Profile Picture</label>
                <input type="file" id="profile_pic" name="profile_pic" accept="image/*" class="mt-1 block w-full text-sm text-gray-900 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                <div class="mt-4">
                    <img src="../../uploads/profilepic/<?php echo htmlspecialchars($user_info['profile_pic'] ?: 'default.png'); ?>?<?php echo time(); ?>" class="rounded-full w-36 h-36 object-cover" id="preview">
                </div>
                <canvas id="canvas" class="hidden"></canvas>
            </div>
            <input type="hidden" name="croppedImage" id="croppedImage">
            <button type="submit" class="w-full py-2 px-4 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Update Profile</button>
        </form>

        <h2 class="text-xl font-semibold mt-8 mb-4">Your Registered Events</h2>
        <ul class="bg-white rounded-lg shadow-md">
            <?php if (empty($registered_events)): ?>
                <li class="p-4 text-gray-600">No registered events found.</li>
            <?php else: ?>
                <?php foreach ($registered_events as $event): ?>
                    <li class="p-4 flex justify-between items-center border-b border-gray-200">
                        <?php echo htmlspecialchars($event['event_name']); ?>
                        <a href="cancel_registration.php?event_id=<?php echo $event['id']; ?>" class="py-1 px-3 bg-red-600 text-white rounded-lg hover:bg-red-700">Cancel</a>
                    </li>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>

        <a href="home.php" class="block mt-6 text-center py-2 px-4 bg-gray-600 text-white rounded-lg hover:bg-gray-700">Back to Home</a>

        <?php if ($user_info['role'] === 'organizer'): ?>
            <a href="dashboard.php" class="block mt-4 text-center py-2 px-4 bg-gray-600 text-white rounded-lg hover:bg-gray-700">Back to Dashboard</a>
        <?php endif; ?>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>
    <script>
        let cropper;
        const imageInput = document.getElementById('profile_pic');
        const previewImage = document.getElementById('preview');
        const croppedImageInput = document.getElementById('croppedImage');

        <?php if ($show_checkmark): ?>
            document.getElementById('checkmarkModal').classList.remove('hidden');
            setTimeout(() => {
                document.getElementById('checkmarkModal').classList.add('hidden');
            }, 3000);
        <?php endif; ?>

        imageInput.addEventListener('change', function(event) {
            const files = event.target.files;
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImage.src = e.target.result;
            };
            reader.readAsDataURL(files[0]);

            if (cropper) {
                cropper.destroy();
            }

            previewImage.onload = function() {
                cropper = new Cropper(previewImage, {
                    aspectRatio: 1,
                    viewMode: 1,
                });
            };
        });

        document.getElementById('profileForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const canvas = cropper.getCroppedCanvas();
            if (canvas) {
                croppedImageInput.value = canvas.toDataURL();
            }
            this.submit();
        });
    </script>
</body>
</html>

