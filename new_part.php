<?php
// Start the session
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>New Part</title>
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

$db = "mysql:host=localhost;dbname=pcshowcase";
$username = "root";
$password = "password";

// if user is logged in
if ($_SESSION["user"] != null) {
  // if user has specified a build
  if ($_GET["build_id"] != null) {
    // if user is build owner
    if ($_SESSION["user"] == get_build_owner()) {
      try { // to add new part
        $conn = new PDO($db, $username, $password);
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $part_type = $_GET["part_type"];
        $part_name = $_GET["part_name"];

        $sql = "INSERT INTO parts (build_id, type, name)
        VALUES ('$build_id', '$part_type', '$part_name')";
        // use exec() because no results are returned
        $conn->exec($sql);
        echo "New part added successfully<br>";
      } catch(PDOException $e) {
        echo $sql . "<br>" . $e->getMessage();
      }

      $conn = null;
    } else {
      echo "You are not the owner of this build.<br>";
    }

    // link back to build
    echo "<a href='display_build.php?build_id=" . $_GET["build_id"] . "'>Back to Build</a>";
  } else {
    echo "You have not specified a build.<br>
          <a href='builds.php'>My Builds</a>";
  }
} else {
  echo "You are not logged in.<br>
        <a href='index.php'>Home</a>";
}
?>


</body>
</html>
