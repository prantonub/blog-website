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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Posts</title>
    <style>
        body {
            background-color: #f4f7fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 20px;
        }
        .post {
            background: white;
            border-radius: 8px;
            padding: 25px 30px;
            margin-bottom: 40px;
            box-shadow: 0 6px 18px rgba(0,0,0,0.1);
            max-width: 720px;
            margin-left: auto;
            margin-right: auto;
        }
        .post h3 {
            margin-top: 0;
            color: #222;
            font-size: 26px;
        }
        .post p.content {
            font-size: 16px;
            line-height: 1.6;
            color: #444;
            white-space: pre-line;
            margin-bottom: 15px;
        }
        .post img {
            max-width: 100%;
            border-radius: 6px;
            margin-bottom: 20px;
        }
        .post .buttons {
            margin-bottom: 25px;
        }
        .post .buttons a {
            text-decoration: none;
            margin-right: 15px;
        }
        .post button {
            background-color: #3b82f6;
            border: none;
            color: white;
            padding: 10px 20px;
            font-weight: 600;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s ease;
        }
        .post button:hover {
            background-color: #2563eb;
        }
        form textarea {
            width: 100%;
            padding: 10px;
            font-size: 15px;
            border-radius: 6px;
            border: 1.5px solid #cbd5e0;
            resize: vertical;
            margin-bottom: 10px;
            font-family: inherit;
        }
        form input[type="submit"] {
            background-color: #10b981;
            border: none;
            color: white;
            padding: 10px 25px;
            font-weight: 600;
            border-radius: 6px;
            cursor: pointer;
            font-size: 15px;
            transition: background-color 0.3s ease;
        }
        form input[type="submit"]:hover {
            background-color: #047857;
        }
        .comments {
            margin-top: 25px;
            border-top: 1px solid #e2e8f0;
            padding-top: 15px;
        }
        .comments h4 {
            margin-bottom: 15px;
            color: #1e293b;
        }
        .comments p {
            margin-bottom: 12px;
            color: #475569;
            font-size: 15px;
        }
        .comments strong {
            color: #0f172a;
        }
        hr {
            border: none;
            border-top: 1px solid #e2e8f0;
            margin: 40px auto 0;
            max-width: 720px;
        }
    </style>
</head>
<body>

<?php
while ($post = mysqli_fetch_assoc($result)) {
    $post_id = $post['id'];
    ?>
    <div class="post">
        <h3><?php echo htmlspecialchars($post['tittle']); ?></h3>
        <p class="content"><?php echo nl2br(htmlspecialchars($post['content'])); ?></p>
        <?php if (!empty($post['image'])): ?>
            <img src="image/<?php echo htmlspecialchars($post['image']); ?>" alt="Post Image" />
        <?php endif; ?>

        <div class="buttons">
            <a href="updatepost.php?id=<?php echo $post_id; ?>"><button>Update</button></a>
            <a href="deletepost.php?id=<?php echo $post_id; ?>" onclick="return confirm('Are you sure you want to delete this post?');"><button>Delete</button></a>
        </div>

        <h4>Leave a Comment:</h4>
        <form action="insertcomment.php" method="POST">
            <input type="hidden" name="post_id" value="<?php echo $post_id; ?>" />
            <textarea name="comment" rows="3" required placeholder="Write your comment..."></textarea><br>
            <input type="submit" value="Add Comment" />
        </form>

        <?php
        // Fetch comments for this post
        $comment_sql = "SELECT user_name, comment FROM comments WHERE post_id = ? ORDER BY id DESC";
        $stmt = $conn->prepare($comment_sql);
        if ($stmt) {
            $stmt->bind_param("i", $post_id);
            $stmt->execute();
            $comment_result = $stmt->get_result();

            if ($comment_result->num_rows > 0) {
                echo '<div class="comments">';
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
