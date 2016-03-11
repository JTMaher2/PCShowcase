<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Edit Part</title>
</head>

<body>
<?php
// get owner that corresponds to unique part ID
function get_part_owner() {
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

  $build_id = $stmt->fetch()["build_id"];

  // get owner
  $sql = "SELECT owner FROM builds WHERE id = $build_id";

  $stmt = $conn->prepare($sql);
  $stmt->execute();

  $owner = $stmt->fetch()["owner"];

  $conn = null;

  return $owner;
}

// if user is logged in, and is the owner of this part's build
if ($_SESSION["user"] != null && $_SESSION["user"] == get_part_owner()) {
  echo "<form action='process_part_edit.php'>
          <input type='hidden' name='part_id' value='" . $_GET['part_id'] . "'>
          New Type: <input type='text' name='type'><br>
          New Name: <input type='text' name='name'><br>
          <input type='submit' value='Submit Changes'>
        </form><br>
        <a href='display_build.php?build_id=" . $_SESSION["build_id"] .
                   "'>Back</a>";
} else {
  echo "You do not have permission to edit this part.<br>
        <a href='index.php'>Home</a>";
}
?>
</body>

</html>
