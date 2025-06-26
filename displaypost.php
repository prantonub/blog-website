<?php
session_start();
include "db.php";

// Fetch all posts
$sql = "SELECT * FROM posts";
$result = mysqli_query($conn, $sql);

// Display each post
while ($row = mysqli_fetch_assoc($result)) {
    echo "<h3>{$row['tittle']}</h3>";
    echo "<p>{$row['content']}</p>";
    echo "<img src='image/{$row['image']}' width='300'><br><br>";

    // ✅ Add Update and Delete buttons
    echo "<a href='updatepost.php?id={$row['id']}'><button>Update</button></a> ";
    echo "<a href='deletepost.php?id={$row['id']}' onclick=\"return confirm('Are you sure you want to delete this post?');\"><button>Delete</button></a>";

    echo "<hr>";
}
?>
