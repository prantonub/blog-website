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
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Update Post</title>
    <style>
        body {
            background-color: #f7fafc;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 40px 20px;
            display: flex;
            justify-content: center;
        }
        .container {
            background: white;
            max-width: 520px;
            width: 100%;
            padding: 30px 40px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
        }
        h2 {
            margin-top: 0;
            color: #1e293b;
            text-align: center;
            margin-bottom: 30px;
            font-weight: 700;
            font-size: 28px;
        }
        label {
            display: block;
            font-weight: 600;
            margin-bottom: 8px;
            color: #334155;
        }
        input[type="text"],
        textarea,
        input[type="file"] {
            width: 100%;
            padding: 12px 14px;
            font-size: 16px;
            border: 1.8px solid #cbd5e1;
            border-radius: 8px;
            font-family: inherit;
            transition: border-color 0.3s ease;
            margin-bottom: 25px;
            box-sizing: border-box;
        }
        input[type="text"]:focus,
        textarea:focus,
        input[type="file"]:focus {
            outline: none;
            border-color: #3b82f6;
        }
        textarea {
            resize: vertical;
            min-height: 120px;
        }
        img {
            border-radius: 8px;
            margin-bottom: 20px;
            display: block;
            max-width: 150px;
            height: auto;
        }
        input[type="submit"] {
            background-color: #3b82f6;
            color: white;
            border: none;
            padding: 14px 0;
            font-size: 17px;
            font-weight: 700;
            border-radius: 10px;
            cursor: pointer;
            width: 100%;
            transition: background-color 0.3s ease;
        }
        input[type="submit"]:hover {
            background-color: #2563eb;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Update Post</h2>
        <form action="updatepost.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo $post['id']; ?>">

            <label for="tittle">Title:</label>
            <input type="text" id="tittle" name="tittle" value="<?php echo isset($post['tittle']) ? htmlspecialchars($post['tittle']) : ''; ?>" required>

            <label for="content">Content:</label>
            <textarea id="content" name="content" rows="5" required><?php echo isset($post['content']) ? htmlspecialchars($post['content']) : ''; ?></textarea>

            <label>Current Image:</label>
            <?php if (!empty($post['image'])): ?>
                <img src="image/<?php echo htmlspecialchars($post['image']); ?>" alt="Post Image">
            <?php else: ?>
                <p>No image uploaded</p>
            <?php endif; ?>

            <label for="image">Change Image:</label>
            <input type="file" id="image" name="image" accept="image/*">

            <input type="submit" name="update" value="Update Post">
        </form>
    </div>
</body>
</html>
