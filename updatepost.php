<?php
session_start();
include "db.php";  // your DB connection file

// Check if form submitted
if (isset($_POST['update'])) {
    $id = $_POST['id'];  // hidden input for post id
    $tittle = $_POST['tittle'];
    $content = $_POST['content'];

    // Handle image update if a new file is uploaded
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image_name = $_FILES['image']['name'];
        $image_tmp = $_FILES['image']['tmp_name'];
        $image_folder = "image/" . $image_name;
        move_uploaded_file($image_tmp, $image_folder);

        // Update with new image, tittle, and content
        $sql = "UPDATE posts SET tittle=?, content=?, image=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $tittle, $content, $image_name, $id);
    } else {
        // Update tittle and content only, no image change
        $sql = "UPDATE posts SET tittle=?, content=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $tittle, $content, $id);
    }

    if ($stmt->execute()) {
        $_SESSION['message'] = "Post updated successfully!";
        header("Location: displaypost.php"); // redirect to posts listing page
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
    }
}

// Get post id from URL to pre-fill the form
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM posts WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $post = $result->fetch_assoc();
    } else {
        echo "Post not found.";
        exit();
    }
} else {
    echo "No post ID specified.";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Post</title>
</head>
<body>
    <h2>Update Post</h2>
    <form action="updatepost.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo $post['id']; ?>">

        <label>Tittle:</label><br>
        <input type="text" name="tittle" value="<?php echo isset($post['tittle']) ? htmlspecialchars($post['tittle']) : ''; ?>" required><br><br>

        <label>Content:</label><br>
        <textarea name="content" rows="5" cols="40" required><?php echo isset($post['content']) ? htmlspecialchars($post['content']) : ''; ?></textarea><br><br>

        <label>Current Image:</label><br>
        <?php if (!empty($post['image'])): ?>
            <img src="image/<?php echo htmlspecialchars($post['image']); ?>" width="150" alt="Post Image"><br>
        <?php else: ?>
            No image<br>
        <?php endif; ?>

        <label>Change Image:</label><br>
        <input type="file" name="image" accept="image/*"><br><br>

        <input type="submit" name="update" value="Update Post">
    </form>
</body>
</html>
