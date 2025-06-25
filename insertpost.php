

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
