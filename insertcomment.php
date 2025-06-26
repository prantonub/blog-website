<?php
session_start();
include "db.php";

if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_name'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $post_id = $_POST['post_id'];
    $user_name = $_SESSION['user_name'];  // use session username
    $comment = trim($_POST['comment']);

    if (!empty($comment)) {
        $stmt = $conn->prepare("INSERT INTO comments (post_id, user_name, comment) VALUES (?, ?, ?)");
        if (!$stmt) {
            die("Prepare failed: " . $conn->error);
        }
        $stmt->bind_param("iss", $post_id, $user_name, $comment);
        if (!$stmt->execute()) {
            die("Execute failed: " . $stmt->error);
        }
    }

    header("Location: displaypost.php");
    exit;
}
?>
