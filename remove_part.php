<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Remove Part</title>
</head>
<body>
<?php
session_start();

require "header.php";

echo "<h3>Remove Part</h3>";

$dsn = "mysql:dbname=pcshowcase;host=localhost";
$username = "root";
$password = "password";

try {
  $conn = new PDO($dsn, $username, $password);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $build_id = get_build($conn);

  // if the current user is the owner of the build, allow part deletion
  if (get_build_owner($build_id, $conn) == $_SESSION["user"]) {
    delete_part($conn);

    echo "<a href='display_build.php?build_id=$build_id'>Back</a><br>";
  } else {
    echo "You do not have permission to delete this part.<br>";
  }

  $conn = null;
} catch (PDOException $e) {
  echo "Error: " . $e->getMessage();
}

require "footer.php";

// find build that part belongs to
function get_build($conn) {
  $sql = "SELECT build_id FROM parts WHERE id = :part_id";

  $stmt = $conn->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
  $stmt->execute(array(":part_id" => $_GET["part_id"]));

  return $stmt->fetch()["build_id"];
}

// find build owner
function get_build_owner($build_id, $conn) {
  $sql = "SELECT owner FROM builds WHERE id = $build_id";

  $stmt = $conn->query($sql);

  return $stmt->fetch()["owner"];
}

// remove part from build
function delete_part($conn) {
  $sql = "DELETE FROM parts WHERE id = :part_id";

  $stmt = $conn->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
  $stmt->execute(array(":part_id" => $_GET["part_id"]));

  if ($stmt->rowCount() > 0) {
    echo "Part deleted successfully<br>";
  } else {
    echo "Invalid part ID<br>";
  }
}
?>
</body>
</html>
