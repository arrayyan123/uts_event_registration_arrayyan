<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

ob_start();
session_start();
require_once __DIR__ .'/../models/db.php';
require_once __DIR__ .'/../models/event.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../../src/views/login.php');
    exit;
}

$event = new Event();

if (isset($_POST['create_event'])) {
    $event_name = htmlspecialchars($_POST['event_name']);
    $event_date = htmlspecialchars($_POST['event_date']);
    $start_date = htmlspecialchars($_POST['start_date']);
    $end_date = htmlspecialchars($_POST['end_date']);
    $event_time = htmlspecialchars($_POST['event_time']);
    $location = htmlspecialchars($_POST['location']);
    $description = htmlspecialchars($_POST['description']);
    $max_participants = intval($_POST['max_participants']);
    $price = floatval($_POST['price']);
    $status = 'open';

    $target_dir = '../uploads/';
    $target_file = $target_dir . basename($_FILES["banner"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    $check = getimagesize($_FILES["banner"]["tmp_name"]);
    if ($check === false) {
        echo "File is not an image.";
        $uploadOk = 0;
    }

    if ($_FILES["banner"]["size"] > 2000000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    if (!in_array($imageFileType, ['jpg', 'png', 'jpeg', 'gif'])) {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    if ($uploadOk === 0) {
        echo "Sorry, your file was not uploaded.";
    } else {
        if (move_uploaded_file($_FILES["banner"]["tmp_name"], $target_file)) {
            $event->createEvent($event_name, $event_date, $start_date, $end_date, $event_time, $location, $description, $max_participants, $price, basename($target_file), 'open');
            $event->generateExcelFile(); 
            header('Location: ../src/views/events_lists.php');
            exit;
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}

if (isset($_POST['edit_event'])) {
    $event_id = intval($_POST['event_id']); 
    $event_name = htmlspecialchars($_POST['event_name']);
    $event_date = htmlspecialchars($_POST['event_date']);
    $start_date = htmlspecialchars($_POST['start_date']);
    $end_date = htmlspecialchars($_POST['end_date']);
    $event_time = htmlspecialchars($_POST['event_time']);
    $location = htmlspecialchars($_POST['location']);
    $description = htmlspecialchars($_POST['description']);
    $max_participants = intval($_POST['max_participants']);
    $price = floatval($_POST['price']);

    $banner = $_FILES['banner']['name'];
    $target_dir = "../uploads/";
    $target_file = $target_dir . basename($banner);

    if ($_FILES['banner']['error'] !== UPLOAD_ERR_OK) {
        echo "Error uploading file: " . $_FILES['banner']['error'];
    } elseif (!empty($banner)) {
        $check = getimagesize($_FILES['banner']['tmp_name']);
        if ($check !== false) {
            if (move_uploaded_file($_FILES['banner']['tmp_name'], $target_file)) {
                // Berhasil upload
            } else {
                echo "File upload failed.";
            }
        } else {
            echo "File is not an image.";
        }
    } else {
        $banner = $event->getEventById($event_id)['banner'];
    }

    $event->updateEvent($event_id, $event_name, $event_date, $start_date, $end_date, $event_time, $location, $description, $max_participants, $price, $banner);
    header('Location: ../src/views/events_lists.php');
    exit();
}

if (isset($_GET['delete_event']) && isset($_GET['event_id'])) {
    $event_id = $_GET['event_id'];
    $pdo = new PDO('mysql:host=localhost;dbname=event_registration_system', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    try {
        $sql = "DELETE FROM events WHERE id = :event_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':event_id', $event_id, PDO::PARAM_INT);
        $stmt->execute();
        
        $sql = "SELECT COUNT(*) FROM events";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $row_count = $stmt->fetchColumn();
        
        if ($row_count == 0) {
            $sql = "ALTER TABLE events AUTO_INCREMENT = 1";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
        }
        header('Location: ../src/views/events_lists.php');
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

if (isset($_POST['register_event'])) {
    $user_id = $_SESSION['user_id'];
    $event_id = $_POST['event_id'];
    
    $event = new Event();
    $event->registerEvent($user_id, $event_id);
    header('Location: ../src/views/register_participant.php?event_id=$event_id');
}

if (isset($_GET['cancel_event']) && isset($_GET['event_id'])) {
    $event_id = intval($_GET['event_id']);
    $user_id = $_SESSION['user_id']; 

    if ($event->cancelRegistration($event_id, $user_id)) {
        header('Location: ../src/views/profile.php?message=Event cancel');
        exit();
    } else {
        echo "Error canceling the event.";
    }
}

if (isset($_POST['confirm_cancel_registration']) && isset($_POST['event_id'])) {
    $event_id = intval($_POST['event_id']);
    $user_id = $_SESSION['user_id'];

    if ($event->cancelRegistration($event_id, $user_id)) {
        $database = new Database();
        $conn = $database->getConnection();

        try {
            $sql = "DELETE FROM event_participants WHERE event_id = :event_id AND user_id = :user_id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':event_id', $event_id, PDO::PARAM_INT);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->execute();

            header('Location: ../src/views/profile.php?message=Registration canceled');
            exit();
        } catch (PDOException $e) {
            echo "Error canceling the registration: " . $e->getMessage();
        }
    } else {
        echo "Error canceling the registration.";
    }
}

if (isset($_POST['cancel_registration'])) {
    $event_id = $_POST['event_id'];
    $user_id = $_SESSION['user_id'];

    if ($event->cancelRegistration($event_id, $user_id)) {
        header('Location: ../src/views/profile.php');
        exit;
    } else {
        echo "Failed to cancel registration.";
    }
}

if (isset($_POST['update_status'])) {
    $event_id = intval($_POST['event_id']);
    $status = htmlspecialchars($_POST['status']); 
    var_dump($status); 

    $database = new Database();
    $conn = $database->getConnection();

    if (in_array($status, ['open', 'close', 'cancel'])) {
        $sql = "UPDATE events SET `status` = :status WHERE id = :event_id";
        $params = [
            ':status' => $status,
            ':event_id' => $event_id
        ];
        $success = $database->execute($sql, $params);

        if ($success) {
            header('Location: ../src/views/events_lists.php?message=Status updated');
            exit();
        } else {
            echo "Failed to update status.";
        }
    } else {
        echo "Invalid status provided.";
    }
}

ob_end_flush();
?>
