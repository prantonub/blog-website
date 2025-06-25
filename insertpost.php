<?php
session_start();
include "db.php";

// Show PHP errors (development only)
error_reporting(E_ALL);
ini_set('display_errors', 1);

$message = "";

// Handle form submission
if (isset($_POST['submit'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);
    $category = (int) $_POST['category'];

    // Handle image upload
    $image = $_FILES['image']['name'];
    $tmp_name = $_FILES['image']['tmp_name'];
    $upload_dir = "uploads/";

    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $image_path = $upload_dir . basename($image);
    move_uploaded_file($tmp_name, $image_path);

    // Insert post into database
    $sql = "INSERT INTO posts (tittle, content, category_id, image) 
            VALUES ('$title', '$content', '$category', '$image')";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        $message = "✅ Post added successfully!";
    } else {
        $message = "❌ Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Insert Post</title>
</head>
<body>
    <?php if (!empty($message)) : ?>
        <p><?php echo $message; ?></p>
    <?php endif; ?>

    <form action="insertpost.php" method="POST" enctype="multipart/form-data">
        <input type="text" name="title" placeholder="Give The post Title here!" required><br><br>

        <textarea name="content" rows="5" cols="40" placeholder="Write the post here!" required></textarea><br><br>

        <select name="category" required>
            <option value="">Select Category</option>
            <option value="1">Fashion and beauty</option>
            <option value="2">Technology</option>
            <option value="3">Food</option>
            <option value="4">Travel</option>
        </select><br><br>

        <input type="file" name="image" required><br><br>

        <input type="submit" name="submit" value="add post">
    </form>
</body>
</html>
