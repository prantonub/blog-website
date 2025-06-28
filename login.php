<?php
session_start();
include "db.php";

$login_message = "";
$login_success = false;

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        $login_message = "<p class='error'>❌ Error: {$conn->error}</p>";
    } else {
        if (mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);

            if ($password === $user['password']) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_role'] = $user['role'];

                $login_success = true;
                $login_message = "<div class='success'>
                ✅ Login Successful! Welcome, " . htmlspecialchars($user['name']) . ".<br>
                <a href='dashboard.php'>Go to Dashboard</a><br>
                <a href='http://localhost/blogpost/'>Go to Blog Home</a>
                </div>";
            } else {
                $login_message = "<p class='error'>❌ Incorrect password.</p>";
            }
        } else {
            $login_message = "<p class='error'>❌ No user found with this email.</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Login Form</title>
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: #f5f7fa;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }

    .login-container {
      background: #fff;
      padding: 40px;
      border-radius: 12px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
      width: 100%;
      max-width: 400px;
    }

    .login-container h2 {
      text-align: center;
      margin-bottom: 24px;
      color: #333;
    }

    label {
      display: block;
      margin-bottom: 6px;
      color: #444;
      font-weight: 500;
    }

    input[type="email"],
    input[type="password"] {
      width: 100%;
      padding: 12px;
      margin-bottom: 20px;
      border: 1px solid #ccc;
      border-radius: 8px;
      font-size: 16px;
      transition: 0.3s ease;
    }

    input[type="email"]:focus,
    input[type="password"]:focus {
      border-color: #4a90e2;
      outline: none;
      box-shadow: 0 0 0 3px rgba(74, 144, 226, 0.2);
    }

    button {
      width: 100%;
      padding: 12px;
      background-color: #4a90e2;
      border: none;
      border-radius: 8px;
      color: white;
      font-size: 16px;
      font-weight: 600;
      cursor: pointer;
      transition: 0.3s ease;
    }

    button:hover {
      background-color: #3b7cd9;
    }

    .error {
      background: #ffe0e0;
      color: #c0392b;
      padding: 10px;
      margin-bottom: 20px;
      border-left: 5px solid #e74c3c;
      border-radius: 5px;
    }

    .success {
      background: #e0ffe5;
      color: #2ecc71;
      padding: 15px;
      margin-bottom: 20px;
      border-left: 5px solid #27ae60;
      border-radius: 5px;
    }

    a {
      display: inline-block;
      margin-top: 8px;
      color: #4a90e2;
      text-decoration: none;
    }

    a:hover {
      text-decoration: underline;
    }

    .btn-again {
      margin-top: 20px;
    }
  </style>
</head>
<body>
  <div class="login-container">
    <?= $login_message ?>

    <?php if (!$login_success): ?>
      <h2>User Login</h2>
      <form action="login.php" method="POST">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" required />

        <label for="password">Password</label>
        <input type="password" id="password" name="password" required />

        <button type="submit" name="login">Login</button>
      </form>
    <?php else: ?>
      <!-- Show only the button again -->
      <button class="btn-again">Login</button>
    <?php endif; ?>
  </div>
</body>
</html>
