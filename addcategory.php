<?php
session_start();
include "db.php";

// Debug: Show session values if needed
// var_dump($_SESSION);

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Check if user is admin
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== "admin") {
    header("Location: dashboard.php");
    exit();
}

// Handle form submission
$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    if (!empty($_POST['name'])) {
        $name = mysqli_real_escape_string($conn, $_POST['name']);

        $sql = "INSERT INTO categories (name) VALUES ('$name')";
        $result = mysqli_query($conn, $sql);

        if ($result) {
            $message = "✅ Category added successfully.";
        } else {
            $message = "❌ Error: " . $conn->error;
        }
    } else {
        $message = "⚠️ Please enter a category name.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Category</title>
</head>
<body>
    <h2>Add Category</h2>

    <?php if (!empty($message)) : ?>
        <p><?php echo $message; ?></p>
    <?php endif; ?>

    <form action="" method="POST">
        <input type="text" name="name" placeholder="Category name" required>
        <input type="submit" name="submit" value="Add Category">
    </form>

    <br>
    <a href="dashboard.php">Go to Dashboard</a>
</body>
</html>