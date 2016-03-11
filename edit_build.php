<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Edit Build</title>
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

// if viewer is logged in, and is build owner
if ($_SESSION["user"] != null) {
  if ($_SESSION["user"] == get_build_owner()) {
    // allow editing
    echo "<a href='logout.php'>Logout</a>
          <h3>Edit Build</h3>
          <form action='process_build_edit.php'>
            Name: <input type='text' name='new_name'><br>
            <input type='submit' value='Submit'>
          </form>";
  } else {
    echo "You do not have permission to edit this build.<br>
          <a href='builds.php'>My Builds</a>";
  }
} else {
  echo "You are not logged in.<br>
        <a href='index.php'>Home</a>";
}
?>
</body>

</html>
