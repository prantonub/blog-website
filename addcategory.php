<?php
// Author: Pranto Khan
session_start();
include "db.php";

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
    <meta charset="UTF-8" />
    <title>Add Category</title>
    <style>
        /* Reset */
        * {
            box-sizing: border-box;
        }

        body {
            background-color: #f7fafc;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .container {
            background: #fff;
            padding: 30px 40px;
            border-radius: 10px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.1);
            width: 360px;
            text-align: center;
        }

        h2 {
            margin-bottom: 25px;
            color: #2d3748;
        }

        input[type="text"] {
            width: 100%;
            padding: 12px 15px;
            font-size: 16px;
            border: 1.8px solid #cbd5e0;
            border-radius: 6px;
            margin-bottom: 20px;
            transition: border-color 0.3s ease;
        }

        input[type="text"]:focus {
            border-color: #3182ce;
            outline: none;
        }

        input[type="submit"] {
            width: 100%;
            padding: 12px;
            background-color: #3182ce;
            border: none;
            border-radius: 6px;
            color: white;
            font-weight: 600;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #2c5282;
        }

        p.message {
            padding: 12px;
            margin-bottom: 20px;
            border-radius: 6px;
            font-weight: 600;
        }

        p.message.success {
            background-color: #c6f6d5;
            color: #276749;
        }

        p.message.error {
            background-color: #fed7d7;
            color: #9b2c2c;
        }

        p.message.warning {
            background-color: #faf089;
            color: #975a16;
        }

        a {
            text-decoration: none;
            color: #3182ce;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        a:hover {
            color: #2c5282;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Add Category</h2>

        <?php if (!empty($message)) : ?>
            <?php
                // Determine message class
                $msgClass = 'success';
                if (str_contains($message, '❌')) {
                    $msgClass = 'error';
                } elseif (str_contains($message, '⚠️')) {
                    $msgClass = 'warning';
                }
            ?>
            <p class="message <?php echo $msgClass; ?>"><?php echo $message; ?></p>
        <?php endif; ?>

        <form action="" method="POST">
            <input type="text" name="name" placeholder="Category name" required>
            <input type="submit" name="submit" value="Add Category">
        </form>

        <br>
        <a href="dashboard.php">Go to Dashboard</a>
    </div>
</body>
</html>
