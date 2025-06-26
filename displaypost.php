<?php
session_start();
include "db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Logged-in user name from session
$user_name = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Anonymous';

$sql = "SELECT * FROM posts ORDER BY id DESC";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Error fetching posts: " . $conn->error);
}

while ($post = mysqli_fetch_assoc($result)) {
    $post_id = $post['id'];

    echo "<h3>" . htmlspecialchars($post['tittle']) . "</h3>";
    echo "<p>" . nl2br(htmlspecialchars($post['content'])) . "</p>";
    echo "<img src='image/" . htmlspecialchars($post['image']) . "' width='300'><br><br>";

    echo "<a href='updatepost.php?id={$post_id}'><button>Update</button></a> ";
    echo "<a href='deletepost.php?id={$post_id}' onclick=\"return confirm('Are you sure you want to delete this post?');\"><button>Delete</button></a>";

    // Comment form - only textarea, no name or email input
    echo "
        <h4>Leave a Comment:</h4>
        <form action='insertcomment.php' method='POST'>
            <input type='hidden' name='post_id' value='{$post_id}'>
            <textarea name='comment' rows='3' cols='50' required placeholder='Write your comment...'></textarea><br>
            <input type='submit' value='Add Comment'>
        </form>
    ";

    // Fetch comments for this post
    $comment_sql = "SELECT user_name, comment FROM comments WHERE post_id = ? ORDER BY id DESC";
    $stmt = $conn->prepare($comment_sql);
    if (!$stmt) {
        die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
    }
    $stmt->bind_param("i", $post_id);
    $stmt->execute();
    $comment_result = $stmt->get_result();

    if ($comment_result->num_rows > 0) {
        echo "<h4>Comments:</h4>";
        while ($comment = $comment_result->fetch_assoc()) {
            echo "<p><strong>" . htmlspecialchars($comment['user_name']) . ":</strong> " .
                 htmlspecialchars($comment['comment']) . "</p>";
        }
    }

    echo "<hr>";
}
?>
