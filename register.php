<?php
include "db.php";

$registrationSuccess = false;

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];  // Plain text for now; consider hashing for production
    $role = $_POST['role'];

    $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $password, $role);
    $result = $stmt->execute();

    if (!$result) {
        echo "❌ Error: " . $stmt->error;
    } else {
        $registrationSuccess = true;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Register Form</title>
    <style>
        /* Basic reset */
        * {
            box-sizing: border-box;
        }

        body {
            background: #f0f4f8;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            background: white;
            padding: 30px 40px;
            border-radius: 8px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.1);
            width: 360px;
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 25px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #555;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"],
        select {
            width: 100%;
            padding: 10px 12px;
            border: 1.8px solid #ddd;
            border-radius: 5px;
            font-size: 15px;
            transition: border-color 0.3s ease;
            margin-bottom: 20px;
        }

        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="password"]:focus,
        select:focus {
            border-color: #007BFF;
            outline: none;
        }

        button {
            width: 100%;
            padding: 12px;
            background: #007BFF;
            border: none;
            border-radius: 6px;
            color: white;
            font-size: 17px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        button:hover {
            background: #0056b3;
        }

        p.success {
            text-align: center;
            background: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 6px;
            border: 1px solid #c3e6cb;
        }

        p.success a {
            color: #155724;
            font-weight: 600;
            text-decoration: none;
        }

        p.success a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>User Registration</h2>

        <?php if ($registrationSuccess): ?>
            <p class="success">✅ Registration successful!<br>
            <a href="login.php">Go to Login</a></p>
        <?php else: ?>
            <form action="" method="POST">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required />

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required />

                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required />

                <label for="role">Role:</label>
                <select id="role" name="role" required>
                    <option value="subscriber">Subscriber</option>
                    <option value="author">Author</option>
                </select>

                <button type="submit" name="submit">Register</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
