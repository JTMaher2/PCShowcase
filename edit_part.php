<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Edit Part</title>
</head>
<body>
<?php
session_start();

require "header.php";

// if user is logged in, and is the owner of this part's build
if (isset($_SESSION["user"]) && $_SESSION["user"] == get_part_owner()) {
  echo "<h3>Edit " . $_GET["name"] . "</h3>
        <form action='process_part_edit.php'>
          <input type='hidden' name='part_id' value='" . $_GET["part_id"] . "'>
          Type: <input type='text' name='type'><br>
          Name: <input type='text' name='name'><br>
          Quantity: <input type='number' name='qty'><br>
          <input type='submit' value='Submit'>
        </form><br>
        <a href='display_build.php?build_id=" . $_SESSION["build_id"] .
                   "'>Back</a><br>";
} else {
  echo "You do not have permission to edit this part.<br>";
}

// get owner that corresponds to unique part ID
function get_part_owner() {
  $dsn = "mysql:dbname=pcshowcase;host=localhost";
  $username = "root";
  $password = "password";

  try {
    $conn = new PDO($dsn, $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // get build ID
    $sql = "SELECT build_id FROM parts WHERE id = :part_id";

    $stmt = $conn->prepare($sql,
                           array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $stmt->execute(array(":part_id" => $_GET["part_id"]));

    $build_id = $stmt->fetch()["build_id"];

    // get owner
    $sql = "SELECT owner FROM builds WHERE id = $build_id";

    $stmt = $conn->prepare($sql);
    $stmt->execute();

    $owner = $stmt->fetch()["owner"];

    $conn = null;
  } catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
  }

  return $owner;
}

require "footer.php";
?>
</body>
</html>
