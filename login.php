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
