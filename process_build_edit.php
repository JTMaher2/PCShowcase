<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Process Build Edit</title>
</head>
<body>
<?php
session_start();

require "header.php";

$dsn = "mysql:dbname=pcshowcase;host=localhost";
$username = "root";
$password = "password";

try {
  $conn = new PDO($dsn, $username, $password);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  update_build($conn);

  $conn = null;
} catch (PDOException $e) {
  echo "Connection failed: " . $e->getMessage();
}

require "footer.php";

// attempt to update build
function update_build($conn) {
  // if user is logged in, and is build owner
  if (isset($_SESSION["user"]) && $_SESSION["user"] == get_build_owner($conn)) {
    // if user has specified a build ID and new name
    if ($_GET["build_id"] != null && $_GET["new_name"] != null) {
      $sql = "UPDATE builds SET name = :new_name WHERE id = :build_id";

      $stmt = $conn->prepare($sql,
                             array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
      $stmt->execute(array(":new_name" => $_GET["new_name"],
                           ":build_id" => $_GET["build_id"]));

      echo "Build updated<br>";
    } else {
      echo "Invalid parameters<br>";
    }
  } else {
    echo "You do not have permission to edit this build.<br>";
  }

  echo "<a href='my_builds.php'>Back</a>";
}

// get build creator
function get_build_owner($conn) {
  $sql = "SELECT owner FROM builds WHERE id = :build_id";

  $stmt = $conn->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
  $stmt->execute(array(":build_id" => $_GET["build_id"]));

  return $stmt->fetch()["owner"];
}
?>
</body>
</html>
