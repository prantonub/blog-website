<?php
session_start();
include "db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Validate POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $post_id = intval($_POST['post_id'] ?? 0);
    $user_name = $_SESSION['user_name'];
    $comment = trim($_POST['comment'] ?? '');

    // Check if post exists
    $stmt_check = $conn->prepare("SELECT id FROM posts WHERE id = ?");
    $stmt_check->bind_param("i", $post_id);
    $stmt_check->execute();
    $check_result = $stmt_check->get_result();
    $stmt_check->close();

    if ($check_result->num_rows === 0) {
        header("Location: displaypost.php?comment=invalid_post&post_id=" . $post_id);
        exit;
    }

    if (!empty($comment)) {
        $stmt = $conn->prepare("INSERT INTO comments (post_id, user_name, comment) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $post_id, $user_name, $comment);

        if ($stmt->execute()) {
            $stmt->close();
            header("Location: displaypost.php?comment=success&post_id=" . $post_id);
            exit;
        } else {
            $stmt->close();
            header("Location: displaypost.php?comment=failed&post_id=" . $post_id);
            exit;
        }
    } else {
        header("Location: displaypost.php?comment=empty&post_id=" . $post_id);
        exit;
    }
} else {
    header("Location: displaypost.php");
    exit;
}
?>
