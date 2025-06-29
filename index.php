<?php
$files = scandir(__DIR__);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>📂 Blogpost Directory</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: #f5f7fa;
      margin: 0;
      padding: 20px;
    }

    h1 {
      text-align: center;
      color: #333;
      margin-bottom: 30px;
    }

    .container {
      max-width: 900px;
      margin: 0 auto;
      background: #fff;
      border-radius: 10px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.1);
      padding: 20px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 10px;
    }

    th, td {
      text-align: left;
      padding: 12px 16px;
      border-bottom: 1px solid #eee;
    }

    th {
      background-color: #f0f2f5;
      color: #444;
      font-weight: 600;
    }

    tr:hover {
      background-color: #f9fbff;
    }

    a {
      color: #0066cc;
      text-decoration: none;
    }

    a:hover {
      text-decoration: underline;
    }

    .folder {
      color: #ff9800;
      font-weight: bold;
    }

    .file {
      color: #2c3e50;
    }

    footer {
      text-align: center;
      margin-top: 30px;
      color: #999;
      font-size: 14px;
    }
  </style>
</head>
<body>
  <h1>📁 MD Tauhidul Islam Pranto <br> ID: 41230201217</h1>

  <div class="container">
    <table>
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
        $name = $isDir ? "<span class='folder'>[DIR]</span> <a href='$file/'>$file</a>" : "<span class='file'>[TXT]</span> <a href='$file'>$file</a>";
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
  </div>

  <footer>
    Apache/2.4.58 | PHP/8.0.30 | Server: localhost
  </footer>
</body>
</html>
