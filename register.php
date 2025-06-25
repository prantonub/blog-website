<?php
include "db.php";

$registrationSuccess = false;

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];  // Plain text for now
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
    <meta charset="UTF-8">
    <title>Register Form</title>
</head>
<body>
    <h2>User Registration</h2>

    <?php if ($registrationSuccess): ?>
        <p>✅ Registration successful!</p>
        <p><a href="login.php">Go to Login</a></p>
    <?php else: ?>
        <form action="" method="POST">
            <label for="name">Name:</label><br>
            <input type="text" id="name" name="name" required><br><br>

            <label for="email">Email:</label><br>
            <input type="email" id="email" name="email" required><br><br>

            <label for="password">Password:</label><br>
            <input type="password" id="password" name="password" required><br><br>

            <label for="role">Role:</label><br>
            <select id="role" name="role" required>
                <option value="subscriber">Subscriber</option>
                <option value="author">Author</option>
            </select><br><br>

            <button type="submit" name="submit">Register</button>
        </form>
    <?php endif; ?>

</body>
</html>
