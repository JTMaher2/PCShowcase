<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>New Part</title>
</head>
<body>
<?php
session_start();

require "header.php";

echo "<h3>New Part</h3>";

$dsn = "mysql:host=localhost;dbname=pcshowcase";
$username = "root";
$password = "password";

try {
  $conn = new PDO($dsn, $username, $password);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  add_part($conn);

  $conn = null;
} catch (PDOException $e) {
  echo "Error: " . $e->getMessage();
}

require "footer.php";

// try to add part to build
function add_part($conn) {
  // if user is logged in
  if (isset($_SESSION["user"])) {
    // if user has specified a build
    if (isset($_SESSION["build_id"])) {
      // if user is build owner, safe to add new part
      if ($_SESSION["user"] == get_build_owner($conn)) {
        $sql = "INSERT INTO parts (build_id, type, name, qty)
                VALUES (" . $_SESSION["build_id"] . ", :type, :name, :qty)";

        $stmt = $conn->prepare($sql,
                               array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $stmt->execute(array(":type" => $_GET["part_type"],
                             ":name" => $_GET["part_name"],
                             ":qty" => $_GET["part_qty"]));

        echo "New part added successfully<br>";
      } else {
        echo "You are not the owner of this build.<br>";
      }

      // link back to build
      echo "<a href='display_build.php?build_id=" . $_SESSION["build_id"] .
           "'>Back</a><br>";
    } else {
      echo "You have not specified a build.<br>";
    }
  } else {
    echo "You are not logged in.<br>";
  }
}

// get owner of a build
function get_build_owner($conn) {
  $sql = "SELECT owner FROM builds WHERE id = :build_id";

  $stmt = $conn->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
  $stmt->execute(array(":build_id" => $_SESSION["build_id"]));

  return $stmt->fetch()["owner"];
}
?>
</body>
</html>
