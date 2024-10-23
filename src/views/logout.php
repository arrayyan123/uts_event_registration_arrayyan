
<?php
if (isset($_GET['logout'])) {
    session_start();
    session_destroy();
    header('Location: ../src/views/home.php');
    exit();
}
?>
