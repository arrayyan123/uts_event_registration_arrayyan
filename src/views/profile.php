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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css" />
    <style>
        #image {
            max-width: 100%;
        }

        .checkmark {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background-color: #28a745;
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
<body>
    <div class="container">
        <h1><?php echo htmlspecialchars($user_info['name']); ?>'s Profile</h1>

        <!-- Modal -->
        <div class="modal fade" id="checkmarkModal" tabindex="-1" aria-labelledby="checkmarkModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content text-center">
                    <div class="modal-body">
                        <div class="checkmark show checkmark-animation"></div>
                        <h5 class="mt-2">Profile Updated Successfully!</h5>
                    </div>
                </div>
            </div>
        </div>

        <form action="profile.php" method="POST" enctype="multipart/form-data" id="profileForm">
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($user_info['name']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user_info['email']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="profile_pic" class="form-label">Profile Picture</label>
                <input type="file" class="form-control" id="profile_pic" name="profile_pic" accept="image/*">
                <div class="mt-2">
                    <img src="../../uploads/profilepic/<?php echo htmlspecialchars($user_info['profile_pic'] ?: 'default.png'); ?>?<?php echo time(); ?>" class="rounded-circle" width="150" height="150" id="preview">
                </div>
                <canvas id="canvas" style="display: none;"></canvas>
            </div>
            <input type="hidden" name="croppedImage" id="croppedImage">
            <button type="submit" class="btn btn-primary">Update Profile</button>
        </form>
        
        <h2>Your Registered Events</h2>
        <ul class="list-group">
            <?php if (empty($registered_events)): ?>
                <li class="list-group-item">No registered events found.</li>
            <?php else: ?>
            <!--Tombol cancel registrasi event-->
                <?php foreach ($registered_events as $event): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <?php echo htmlspecialchars($event['event_name']); ?>
                        <a href="cancel_registration.php?event_id=<?php echo $event['id']; ?>" class="btn btn-danger btn-sm">Cancel</a>
                    </li>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>
        
        <a href="home.php" class="btn btn-secondary mt-3">Back to Home</a>

        <?php if ($user_info['role'] === 'organizer'): ?>
            <a href="dashboard.php" class="btn btn-secondary mt-3">Back to Dashboard</a>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>
    <script>
        let cropper;
        const imageInput = document.getElementById('profile_pic');
        const previewImage = document.getElementById('preview');
        const croppedImageInput = document.getElementById('croppedImage');
        if (<?php echo json_encode($show_checkmark); ?>) {
            const checkmarkModal = new bootstrap.Modal(document.getElementById('checkmarkModal'));
            checkmarkModal.show();
            setTimeout(() => {
                checkmarkModal.hide();
            }, 3000);
        }

        imageInput.addEventListener('change', function(event) {
            const files = event.target.files;
            const done = (url) => {
                previewImage.src = url;
            };
            const reader = new FileReader();
            reader.onload = function(e) {
                done(e.target.result);
            };
            reader.readAsDataURL(files[0]);

            if (cropper) {
                cropper.destroy();
            }

            previewImage.onload = function() {
                cropper = new Cropper(previewImage, {
                    aspectRatio: 1,
                    viewMode: 1,
                    ready: function() {
                        cropper.crop();
                    }
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
