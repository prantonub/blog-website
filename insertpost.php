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
        echo "<p class='error'>❌ Failed to fetch categories: {$conn->error}</p>";
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
                    echo "<p class='success'>✅ Post added successfully!</p>";
                    echo "<a href='displaypost.php'><button class='btn'>Display Page</button></a>";
                } else {
                    echo "<p class='error'>❌ Post insert failed: " . $stmt->error . "</p>";
                }

                $stmt->close();
            } else {
                echo "<p class='error'>❌ Category not found.</p>";
            }

            $stmt_cat->close();
        } else {
            echo "<p class='error'>❌ Please select a category.</p>";
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
    <style>
        body {
            background-color: #f0f4f8;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 100vh;
            padding-top: 50px;
        }
        .container {
            background: white;
            padding: 35px 40px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            max-width: 480px;
            width: 100%;
        }
        h2 {
            margin-bottom: 25px;
            color: #222;
            text-align: center;
        }
        input[type="text"],
        textarea,
        select,
        input[type="file"] {
            width: 100%;
            padding: 12px 15px;
            font-size: 16px;
            border: 1.8px solid #cbd5e0;
            border-radius: 8px;
            margin-bottom: 20px;
            font-family: inherit;
            transition: border-color 0.3s ease;
        }
        input[type="text"]:focus,
        textarea:focus,
        select:focus,
        input[type="file"]:focus {
            border-color: #3182ce;
            outline: none;
        }
        textarea {
            resize: vertical;
            min-height: 120px;
        }
        input[type="submit"], .btn {
            background-color: #3182ce;
            color: white;
            border: none;
            padding: 12px 25px;
            font-size: 16px;
            font-weight: 600;
            border-radius: 8px;
            cursor: pointer;
            width: 100%;
            transition: background-color 0.3s ease;
            display: inline-block;
            text-align: center;
            text-decoration: none;
        }
        input[type="submit"]:hover,
        .btn:hover {
            background-color: #1e40af;
        }
        p.success {
            background-color: #d1fae5;
            color: #065f46;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-weight: 600;
            text-align: center;
        }
        p.error {
            background-color: #fee2e2;
            color: #991b1b;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-weight: 600;
            text-align: center;
        }
        a {
            text-decoration: none;
            display: block;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Add New Post</h2>
        <form action="insertpost.php" method="POST" enctype="multipart/form-data">
            <input type="text" name="title" placeholder="Give the post title here!" required>

            <textarea name="content" rows="5" placeholder="Write the post here!" required></textarea>

            <select name="category_name" required>
                <option value="" disabled selected>Select Category</option>
                <?php if (isset($result)) {
                    while($row = mysqli_fetch_assoc($result)) { ?>
                        <option value="<?php echo htmlspecialchars($row['name']); ?>">
                            <?php echo htmlspecialchars($row['name']); ?>
                        </option>
                <?php } } ?>
            </select>

            <input type="file" name="image" accept="image/*" required>

            <input type="submit" name="submit" value="Add Post">
        </form>
    </div>
</body>
</html>
