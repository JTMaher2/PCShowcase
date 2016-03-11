<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Remove Part</title>
</head>

<body>
<?php
// find build that part belongs to
function get_build() {
  $db = "mysql:dbname=pcshowcase;host=localhost";
  $username = "root";
  $password = "password";

  $conn = new PDO($db, $username, $password);
  // set the PDO error mode to exception
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  // get build ID
  $sql = "SELECT build_id FROM parts WHERE id = :part_id";

  $stmt = $conn->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
  $stmt->execute(array(":part_id" => $_GET["part_id"]));

  return $stmt->fetch()["build_id"];
}

// find build owner
function get_build_owner($build_id) {
  $db = "mysql:dbname=pcshowcase;host=localhost";
  $username = "root";
  $password = "password";

  $conn = new PDO($db, $username, $password);
  // set the PDO error mode to exception
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  // get owner
  $sql = "SELECT owner FROM builds WHERE id = '$build_id'";

  $stmt = $conn->query($sql);

  $owner = $stmt->fetch()["owner"];

  $conn = null;

  return $owner;
}

// remove part from build
function delete_part() {
  $db = "mysql:dbname=pcshowcase;host=localhost";
  $username = "root";
  $password = "password";

  try {
    $conn = new PDO($db, $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // sql to delete a record
    $sql = "DELETE FROM parts WHERE id = :part_id";

    $stmt = $conn->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $stmt->execute(array(":part_id" => $_GET["part_id"]));

    if ($stmt->rowCount() > 0) {
      echo "Part deleted successfully<br>";
    } else {
      echo "Invalid part ID<br>";
    }
  } catch(PDOException $e) {
    echo $sql . "<br>" . $e->getMessage();
  }

  $conn = null;
}

$build_id = get_build();

// if the current user is the owner of the build, allow part deletion
if (get_build_owner($build_id) == $_SESSION["user"]) {
  delete_part();

  echo "<a href='display_build.php?build_id=$build_id'>Back to Build</a>";
} else {
  echo "You do not have permission to delete this part.<br>
        <a href='index.php'>Home</a>";
}
?>
</body>

</html>
