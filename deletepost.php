<?php
session_start();
include "db.php";

// ✅ Make sure user is logged in and is an author or admin
if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] != "author" && $_SESSION['user_role'] != "admin")) {
    header("Location: login.php");
    exit;
}

// ✅ Check if ID is provided
if (isset($_GET['id'])) {
    $post_id = $_GET['id'];

    // Optional: check if this user owns the post (recommended for authors)
    $user_id = $_SESSION['user_id'];
    $role = $_SESSION['user_role'];

    if ($role === 'author') {
        $check = "SELECT * FROM posts WHERE id = '$post_id' AND author_id = '$user_id'";
    } else {
        $check = "SELECT * FROM posts WHERE id = '$post_id'";
    }

    $check_result = mysqli_query($conn, $check);

    if (mysqli_num_rows($check_result) > 0) {
        // ✅ Delete the post
        $sql = "DELETE FROM posts WHERE id = '$post_id'";
        $result = mysqli_query($conn, $sql);

        if ($result) {
            echo "✅ Post deleted successfully!";
        } else {
            echo "❌ Failed to delete post: " . $conn->error;
        }
    } else {
        echo "❌ You are not authorized to delete this post.";
    }
} else {
    echo "❌ Invalid request: Post ID is missing.";
}
?>
<br><br>
<a href="index.php"><button>Back to Posts</button></a>
