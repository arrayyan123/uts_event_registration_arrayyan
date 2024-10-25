<?php
session_start();
require_once __DIR__ .'/../../models/db.php';

if (!isset($_SESSION['user_id'])) {
    exit('User not logged in');
}

$user_id = $_SESSION['user_id'];
$page = $_POST['page'];
$time_spent = (int) $_POST['time_spent']; 

$database = new Database();
$db = $database->getConnection();

$query = "INSERT INTO user_activity (user_id, page, time_spent) VALUES (?, ?, ?)";
$stmt = $db->prepare($query);
$stmt->execute([$user_id, $page, $time_spent]);

echo 'Activity logged';
?>
