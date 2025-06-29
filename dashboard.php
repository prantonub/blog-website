<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
} else {
    $name = htmlspecialchars($_SESSION['user_name']);
    $role = htmlspecialchars($_SESSION['user_role']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Dashboard</title>
    <style>
        /* Reset */
        * {
            box-sizing: border-box;
        }

        body {
            background-color: #eef2f7;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .dashboard-container {
            background: white;
            padding: 30px 40px;
            border-radius: 10px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.1);
            max-width: 450px;
            width: 100%;
            text-align: center;
        }

        h1 {
            color: #333;
            margin-bottom: 20px;
        }

        p {
            font-size: 18px;
            color: #555;
            margin-bottom: 30px;
        }

        strong {
            color: #007BFF;
        }

        a {
            text-decoration: none;
            color: #007BFF;
            font-weight: 600;
            margin: 0 10px;
            transition: color 0.3s ease;
        }

        a:hover {
            color: #0056b3;
        }

        .links {
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <h1>Welcome to Our Dashboard</h1>
        <p>Your name is: <strong><?php echo $name; ?></strong> and your role is: <strong><?php echo $role; ?></strong>.</p>
        <div class="links">
            <a href="http://localhost/blogpost/">Go to Blog Home</a> |
            <a href="logout.php">Log Out</a>
        </div>
    </div>
</body>
</html>
