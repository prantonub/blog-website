<?php
session_start();
include "db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$user_role = $_SESSION['user_role'] ?? '';

if ($user_role === "author") {
    $category_query = "SELECT * FROM categories";
    $result = mysqli_query($conn, $category_query);

    if (!$result) {
        echo "<p>❌ Failed to fetch categories: {$conn->error}</p>";
    }

    if (isset($_POST['submit'])) {
        $tittle = $_POST['title'] ?? '';
        $content = $_POST['content'] ?? '';
        $category_name = $_POST['category_name'] ?? '';

        // Handle image upload
        $image_name = $_FILES['image']['name'] ?? '';
        $temp_location = $_FILES['image']['tmp_name'] ?? '';
        $upload_folder = "image/";

        if (!is_dir($upload_folder)) {
            mkdir($upload_folder, 0777, true);
        }

        if (!empty($image_name)) {
            move_uploaded_file($temp_location, $upload_folder . $image_name);
        }

        if (!empty($category_name)) {
            // Get category ID securely
            $stmt_cat = $conn->prepare("SELECT id FROM categories WHERE name = ?");
            $stmt_cat->bind_param("s", $category_name);
            $stmt_cat->execute();
            $result1 = $stmt_cat->get_result();

            if ($result1 && $result1->num_rows > 0) {
                $row = $result1->fetch_assoc();
                $idforcategory = $row['id'];

                // Insert post securely
                $stmt = $conn->prepare("INSERT INTO posts (tittle, content, author_id, category_id, image) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("sssis", $tittle, $content, $user_id, $idforcategory, $image_name);

                if ($stmt->execute()) {
                    echo "<p>✅ Post added successfully!</p>";
                    echo "<a href='displaypost.php'><button>Display Page</button></a>";
                } else {
                    echo "<p>❌ Post insert failed: " . $stmt->error . "</p>";
                }

                $stmt->close();
            } else {
                echo "<p>❌ Category not found.</p>";
            }

            $stmt_cat->close();
        } else {
            echo "<p>❌ Please select a category.</p>";
        }
    }
} else {
    header("Location: dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Insert Post</title>
</head>
<body>
    <h2>Add New Post</h2>
    <form action="insertpost.php" method="POST" enctype="multipart/form-data">
        <input type="text" name="title" placeholder="Give the post title here!" required><br><br>

        <textarea name="content" rows="5" placeholder="Write the post here!" required></textarea><br><br>

        <select name="category_name" required>
            <option value="" disabled selected>Select Category</option>
            <?php if (isset($result)) {
                while($row = mysqli_fetch_assoc($result)) { ?>
                    <option value="<?php echo htmlspecialchars($row['name']); ?>">
                        <?php echo htmlspecialchars($row['name']); ?>
                    </option>
            <?php } } ?>
        </select><br><br>

        <input type="file" name="image" accept="image/*" required><br><br>

        <input type="submit" name="submit" value="Add Post">
    </form>
</body>
</html>
