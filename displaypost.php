<?php
session_start();
include "db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_name = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Anonymous';

$sql = "SELECT * FROM posts ORDER BY id DESC";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Error fetching posts: " . $conn->error);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Posts</title>
</head>
<body>

<?php
while ($post = mysqli_fetch_assoc($result)) {
    $post_id = $post['id'];
    ?>
    <div>
        <h3><?php echo htmlspecialchars($post['tittle']); ?></h3>
        <p><?php echo nl2br(htmlspecialchars($post['content'])); ?></p>
        <?php if (!empty($post['image'])): ?>
            <img src="image/<?php echo htmlspecialchars($post['image']); ?>" alt="Post Image" style="max-width: 100%;"><br>
        <?php endif; ?>

        <a href="updatepost.php?id=<?php echo $post_id; ?>">Update</a> |
        <a href="deletepost.php?id=<?php echo $post_id; ?>" onclick="return confirm('Are you sure you want to delete this post?');">Delete</a>

        <h4>Leave a Comment:</h4>
        <form action="insertcomment.php" method="POST">
            <input type="hidden" name="post_id" value="<?php echo $post_id; ?>" />
            <textarea name="comment" rows="3" required placeholder="Write your comment..."></textarea><br>
            <input type="submit" value="Add Comment" />
        </form>

        <?php
        $comment_sql = "SELECT user_name, comment FROM comments WHERE post_id = ? ORDER BY id DESC";
        $stmt = $conn->prepare($comment_sql);
        if ($stmt) {
            $stmt->bind_param("i", $post_id);
            $stmt->execute();
            $comment_result = $stmt->get_result();

            if ($comment_result->num_rows > 0) {
                echo '<div>';
                echo '<h4>Comments:</h4>';
                while ($comment = $comment_result->fetch_assoc()) {
                    echo "<p><strong>" . htmlspecialchars($comment['user_name']) . ":</strong> " .
                         htmlspecialchars($comment['comment']) . "</p>";
                }
                echo '</div>';
            }
            $stmt->close();
        }
        ?>
    </div>
    <hr>
<?php
}
?>

</body>
</html>
