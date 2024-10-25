<?php

    $requestUri = isset($_SERVER['REQUEST_URI']) ? filter_var($_SERVER['REQUEST_URI'], FILTER_SANITIZE_URL) : '/';

    if ($requestUri === '/' || $requestUri === '') {
        include 'src/views/home.php';
        exit();
    }

    $requestUri = trim($requestUri, '/');

    if (strpos($requestUri, '..') !== false) {
        include 'src/views/404.php';
        exit();
    }

    if (file_exists("src/views/$requestUri.php")) {
        include "src/views/$requestUri.php";
    } else {
        include 'src/views/404.php';
    }

    exit();
?>
