<!DOCTYPE html>
<html lang="en">
<head>
  <title>Process Email Change</title>
  <meta charset="utf-8">
</head>
<body>
<?php
session_start();

require "header.php";

// if new username is different from old username
if ($_GET["new_username"] != $_SESSION["user"]) {
  $dsn = "mysql:dbname=pcshowcase;host=localhost";
  $username = "root";
  $password = "password";

  try {
    $conn = new PDO($dsn, $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "UPDATE users SET name = :new_name WHERE name = :old_name";

    $stmt = $conn->prepare($sql,
                           array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $stmt->execute(array(":new_name" => $_GET["new_username"],
                         ":old_name" => $_SESSION["user"]));

    $conn = null;

    echo "Username changed<br>";
  } catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
  }
} else {
  echo "Please enter a different username than your current one.<br>
        <a href='change_username.php'>Back</a>";
}

require "footer.php";
?>
</body>
</html>
