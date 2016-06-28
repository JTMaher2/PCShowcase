<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Edit Build</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
</head>
<body>
<?php
session_start();

require "header.php";

echo "<div class='container'>";

// if viewer is logged in, and is build owner
if (isset($_SESSION["user"]) && $_SESSION["user"] == get_build_owner()) {
  // allow editing
  echo "<h3>Edit Build</h3>
        <form action='process_build_edit.php'>
          <input type='hidden' name='build_id' value='" . $_GET["build_id"] .
          "'>
          Name: <input type='text' name='new_name'><br>
          <input type='submit' value='Submit' class='btn btn-large btn-primary'>
        </form>";
} else {
  echo "You do not have permission to edit this build.<br>";
}

echo "</div>";

require "footer.php";

// get owner of a build
function get_build_owner() {
  $url = parse_url(getenv("CLEARDB_DATABASE_URL"));

  $dsn = "mysql:host=" . $url["host"] . ";dbname=" . substr($url["path"], 1);
  $username = $url["user"];
  $password = $url["pass"];

  try {
    $conn = new PDO($dsn, $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT owner FROM builds WHERE id = :build_id";

    $stmt = $conn->prepare($sql,
                           array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $stmt->execute(array(":build_id" => $_GET["build_id"]));

    $owner = $stmt->fetch()["owner"];

    $conn = null;
  } catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
  }

  return $owner;
}
?>
</body>
</html>
