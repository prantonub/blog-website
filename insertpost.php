<?php
session_start();
include "db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

if ($_SESSION['user_role'] == "author") {
    $sql = "SELECT * FROM categories";
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        echo "Error!: {$conn->error}";
    } else {
        if (isset($_POST['submit'])) {
            $tittle = $_POST['title'];
            $content = $_POST['content'];

            // ✅ Check category selection
            $category_name = isset($_POST['category_name']) ? $_POST['category_name'] : '';

            $image_name = $_FILES['image']['name'];
            $temp_location = $_FILES['image']['tmp_name'];
            $upload_folder = "image/";

            // ✅ Ensure folder exists
            if (!is_dir($upload_folder)) {
                mkdir($upload_folder, 0777, true);
            }

            if (!empty($image_name)) {
                move_uploaded_file($temp_location, $upload_folder . $image_name);
            }

            if (!empty($category_name)) {
                // ✅ Get category ID
                $sql1 = "SELECT id FROM categories WHERE name = '$category_name'";
                $result1 = mysqli_query($conn, $sql1);

                if ($result1 && mysqli_num_rows($result1) > 0) {
                    $row = mysqli_fetch_assoc($result1);
                    $idforcategory = $row['id'];

                    // ✅ Insert post
                    $sql2 = "INSERT INTO posts (tittle, content, author_id, category_id, image)
                             VALUES ('$tittle', '$content', '$user_id', '$idforcategory', '$image_name')";

                    $result2 = mysqli_query($conn, $sql2);
                    if ($result2) {
                        echo "✅ Post added successfully! <br><a href='displaypost.php'><button>Display Page</button></a>";
                    } else {
                        echo "❌ Post insert failed: " . $conn->error;
                    }
                } else {
                    echo "❌ Category not found.";
                }
            } else {
                echo "❌ Please select a category.";
            }
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
    <meta charset="UTF-8">
    <title>Insert Post</title>
</head>
<body>
    <h2>Add New Post</h2>
    <form action="insertpost.php" method="POST" enctype="multipart/form-data">
        <input type="text" name="title" placeholder="Give the post title here!" required><br><br>

        <textarea name="content" rows="5" cols="40" placeholder="Write the post here!" required></textarea><br><br>

        <select name="category_name" required>
            <option value="" disabled selected>Select Category</option>
            <?php if (isset($result)) {
                while($row = mysqli_fetch_assoc($result)) { ?>
                    <option value="<?php echo $row['name']; ?>">
                        <?php echo $row['name']; ?>
                    </option>
            <?php } } ?>
        </select><br><br>

        <input type="file" name="image" accept="image/*" required><br><br>

        <input type="submit" name="submit" value="Add Post">
    </form>
</body>
</html>
