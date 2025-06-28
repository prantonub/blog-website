<?php
session_start();
include "db.php";

// ✅ Make sure user is logged in and is an author or admin
if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] != "author" && $_SESSION['user_role'] != "admin")) {
    header("Location: login.php");
    exit;
}

$message = "";
$messageClass = "";

if (isset($_GET['id'])) {
    $post_id = $_GET['id'];
    $user_id = $_SESSION['user_id'];
    $role = $_SESSION['user_role'];

    // Optional: check if this user owns the post (recommended for authors)
    if ($role === 'author') {
        $check = "SELECT * FROM posts WHERE id = ? AND author_id = ?";
        $stmt = $conn->prepare($check);
        $stmt->bind_param("ii", $post_id, $user_id);
    } else {
        $check = "SELECT * FROM posts WHERE id = ?";
        $stmt = $conn->prepare($check);
        $stmt->bind_param("i", $post_id);
    }

    $stmt->execute();
    $check_result = $stmt->get_result();

    if ($check_result->num_rows > 0) {
        // Delete related comments first
        $delete_comments = $conn->prepare("DELETE FROM comments WHERE post_id = ?");
        $delete_comments->bind_param("i", $post_id);
        $delete_comments->execute();

        // Then delete the post
        $delete_post = $conn->prepare("DELETE FROM posts WHERE id = ?");
        $delete_post->bind_param("i", $post_id);

        if ($delete_post->execute()) {
            $message = "✅ Post deleted successfully!";
            $messageClass = "success";
        } else {
            $message = "❌ Failed to delete post: " . $conn->error;
            $messageClass = "error";
        }
    } else {
        $message = "❌ You are not authorized to delete this post.";
        $messageClass = "error";
    }
} else {
    $message = "❌ Invalid request: Post ID is missing.";
    $messageClass = "error";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Delete Post</title>
</head>
<body>
    <div>
        <p><?php echo htmlspecialchars($message); ?></p>
        <a href="displaypost.php">Back to Posts</a>
    </div>
</body>
</html>
