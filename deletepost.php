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
    <style>
        body {
            background-color: #f9fafb;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .container {
            background: white;
            padding: 30px 40px;
            border-radius: 10px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
            max-width: 400px;
            text-align: center;
        }
        .message {
            font-weight: 600;
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 25px;
            font-size: 16px;
        }
        .success {
            background-color: #d1fae5;
            color: #065f46;
            border: 1px solid #10b981;
        }
        .error {
            background-color: #fee2e2;
            color: #991b1b;
            border: 1px solid #ef4444;
        }
        a button {
            padding: 12px 25px;
            font-size: 16px;
            border: none;
            background-color: #3b82f6;
            color: white;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        a button:hover {
            background-color: #2563eb;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="message <?php echo $messageClass; ?>">
            <?php echo $message; ?>
        </div>
        <a href="displaypost.php"><button>Back to Posts</button></a>
    </div>
</body>
</html>
