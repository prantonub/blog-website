<?php
session_start();
include "db.php";

// Check login
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_name'])) {
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
        header("Location: displaypost.php?comment=invalid_post");
        exit;
    }

    if (!empty($comment)) {
        $stmt = $conn->prepare("INSERT INTO comments (post_id, user_name, comment) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $post_id, $user_name, $comment);

        if ($stmt->execute()) {
            $stmt->close();
            header("Location: displaypost.php?comment=success");
            exit;
        } else {
            $stmt->close();
            header("Location: displaypost.php?comment=failed");
            exit;
        }
    } else {
        header("Location: displaypost.php?comment=empty");
        exit;
    }
} else {
    header("Location: displaypost.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>Leave a Comment</title>
<style>
    body {
        background: #f0f4f8;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        margin: 0; padding: 0;
        display: flex; justify-content: center; align-items: center;
        height: 100vh;
    }
    .comment-container {
        background: white;
        padding: 30px 40px;
        border-radius: 10px;
        box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        max-width: 400px;
        width: 100%;
        text-align: center;
    }
    h2 {
        margin-bottom: 20px;
        color: #333;
    }
    textarea {
        width: 100%;
        padding: 12px;
        font-size: 16px;
        border-radius: 6px;
        border: 1.8px solid #ccc;
        resize: vertical;
        margin-bottom: 20px;
        font-family: inherit;
    }
    textarea:focus {
        border-color: #3182ce;
        outline: none;
    }
    input[type="submit"] {
        background-color: #3182ce;
        color: white;
        border: none;
        padding: 12px 25px;
        font-size: 16px;
        font-weight: 600;
        border-radius: 6px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }
    input[type="submit"]:hover {
        background-color: #1e40af;
    }
    .error {
        color: #b91c1c;
        background: #fecaca;
        padding: 10px;
        border-radius: 6px;
        margin-bottom: 15px;
        font-weight: 600;
    }
    .success {
        color: #065f46;
        background: #d1fae5;
        padding: 10px;
        border-radius: 6px;
        margin-bottom: 15px;
        font-weight: 600;
    }
</style>
</head>
<body>
    <div class="comment-container">
        <h2>Leave a Comment</h2>
        <?php if (!empty($error)): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php elseif (!empty($success)): ?>
            <div class="success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>
        <form action="" method="POST">
            <input type="hidden" name="post_id" value="<?php echo htmlspecialchars($post_id); ?>">
            <textarea name="comment" rows="5" placeholder="Write your comment here..." required></textarea><br>
            <input type="submit" value="Submit Comment">
        </form>
    </div>
</body>
</html>
