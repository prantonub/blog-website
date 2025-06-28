<?php
$files = scandir(__DIR__);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Home Page</title>
</head>
<body>
  <h1>MD Tauhidul Islam Pranto <br> ID: 41230201217</h1>

  <table border="1" cellpadding="5" cellspacing="0">
    <tr>
      <th>📄 Name</th>
      <th>🕒 Last Modified</th>
      <th>📦 Size</th>
    </tr>
    <?php
    foreach ($files as $file) {
      if ($file === '.' || $file === '..' || $file === 'index.php') continue;
      $filePath = __DIR__ . '/' . $file;
      $isDir = is_dir($filePath);
      $name = $isDir ? "[DIR] <a href='$file/'>$file</a>" : "[TXT] <a href='$file'>$file</a>";
      $modified = date("Y-m-d H:i", filemtime($filePath));
      $size = $isDir ? '-' : round(filesize($filePath) / 1024, 1) . ' KB';
      echo "<tr>
              <td>$name</td>
              <td>$modified</td>
              <td>$size</td>
            </tr>";
    }
    ?>
  </table>

  <p>Apache/2.4.58 | PHP/8.0.30 | Server: localhost</p>
</body>
</html>
