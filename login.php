<?php
session_start();
include "db.php";

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        echo "❌ Error: {$conn->error}";
    } else {
        if (mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);

            if ($password === $user['password']) {
                echo "✅ Login Successful! Welcome, " . $user['name'] . ". Go to <a href='dashboard.php'>Dashboard</a>";
            } else {
                echo "❌ Incorrect password.";
            }

        } else {
            echo "❌ No user found with this email.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Login Form</title>
</head>
<body>
  <h2>User Login</h2>
  <form action="login.php" method="POST">
    <label for="email">Email:</label><br />
    <input type="email" id="email" name="email" required /><br /><br />

    <label for="password">Password:</label><br />
    <input type="password" id="password" name="password" required /><br /><br />

    <button type="submit" name="login">Login</button>
  </form>
</body>
</html>
