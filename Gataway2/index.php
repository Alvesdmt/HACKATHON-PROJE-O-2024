<?php
session_start();

if (isset($_SESSION['user'])) {
    header("Location: /resources/views/dashboard.php");
    exit;
} else {
    header("Location: resources/views/login.php");
    exit;
}
