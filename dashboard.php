<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
} else {
    $name = $_SESSION['user_name'];
    $role = $_SESSION['user_role'];
    echo "Welcome to Our Dashboard. Your name is: $name and your role is: $role. <a href='logout.php'>Log Out</a>";
}
?>