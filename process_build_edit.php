<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Process Build Edit</title>
</head>

<body>
<?php
// get owner of a build
function get_build_owner() {
  $db = "mysql:dbname=pcshowcase;host=localhost";
  $username = "root";
  $password = "password";

  $conn = new PDO($db, $username, $password);
  // set the PDO error mode to exception
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $sql = "SELECT owner FROM builds WHERE id = :build_id";

  $stmt = $conn->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
  $stmt->execute(array(":build_id" => $_GET["build_id"]));

  $owner = $stmt->fetch()["owner"];

  $conn = null;

  return $owner;
}

// if user is logged in, and is build owner
if ($_SESSION["user"] != null && $_SESSION["user"] == get_build_owner()) {
  // if user has specified a build ID and new name
  if ($_GET["build_id"] != null && $_GET["new_name"] != null) {
    $db = "mysql:dbname=pcshowcase;host=localhost";
    $username = "root";
    $password = "password";

    try {
      $conn = new PDO($db, $username, $password);
      // set the PDO error mode to exception
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      $sql = "UPDATE builds SET name = :new_name WHERE id = :build_id";

      $stmt = $conn->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
      $stmt->execute(array(":new_name" => $_GET["new_name"], ":build_id" => $_GET["build_id"]));

      $conn = null;

      echo "Build updated<br>";
    } catch (PDOException $e) {
      echo "Connection failed: " . $e->getMessage();
    }
  } else {
    echo "Invalid parameters<br>";
  }
} else {
  echo "You do not have permission to edit this build.<br>";
}

echo "<a href='builds.php'>Back</a>";
?>
</body>

</html>
