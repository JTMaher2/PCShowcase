<!DOCTYPE html>
<html lang="en">
<head>
  <title>All Builds</title>
  <meta charset="utf-8">
</head>
<body>
<?php
session_start();

require "header.php";

echo "<h3>All Builds</h3>";

$dsn = "mysql:dbname=pcshowcase;host=localhost";
$username = "root";
$password = "password";

try { // to display all builds
  $conn = new PDO($dsn, $username, $password);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $sql = "SELECT id, name FROM builds";

  foreach ($conn->query($sql) as $build) {
    echo "<a href='display_build.php?build_id=" . $build["id"] . "'>" .
         $build["name"] . "</a><br>";
  }

  $conn = null;
} catch (PDOException $e) {
  echo "Error: " . $e->getMessage();
}

require "footer.php";
?>
</body>
</html>
