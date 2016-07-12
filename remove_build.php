<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Remove Build</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
</head>
<body>
<?php
session_start();

require "header.php";

$url = parse_url(getenv("DATABASE_URL"));

$dsn = "pgsql:host=" . $url["host"] . ";dbname=" . substr($url["path"], 1);
$username = $url["user"];
$password = $url["pass"];

echo "<div class='container'>";

try {
  $conn = new PDO($dsn, $username, $password);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  // if the current user is the build owner, allow deletion
  if ($_SESSION["user"] == get_owner($conn)) {
    delete_build($conn);
  } else {
    echo "You do not have permission to delete this build.<br>
          <a href='index.php' class='btn btn-large btn-primary'>Home</a>";
  }

  $conn = null;
} catch (PDOException $e) {
  echo "Error: " . $e->getMessage();
}

echo "</div>";

require "footer.php";

// get owner of build
function get_owner($conn) {
  $sql = "SELECT owner FROM builds WHERE id = :id";

  $stmt = $conn->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
  $stmt->execute(array(":id" => $_GET["build_id"]));

  return $stmt->fetch()["owner"];
}

// get # of builds that currently exist for this user
function get_num_builds($conn) {
  $sql = "SELECT num_builds FROM users WHERE email = :email";

  $stmt = $conn->prepare($sql,
                         array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
  $stmt->execute(array(":email" => $_SESSION["user"]));

  return $stmt->fetch()["num_builds"];
}

// delete a build
function delete_build($conn) {
  // delete build record
  $sql = "DELETE FROM builds WHERE id = :id";

  $stmt = $conn->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
  $stmt->execute(array(":id" => $_GET["build_id"]));

  // delete parts that correspond to the deleted build
  $sql = "DELETE FROM parts WHERE build_id = :id";

  $stmt = $conn->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
  $stmt->execute(array(":id" => $_GET["build_id"]));

  // decrement this user's num_builds
  $sql = "UPDATE users SET num_builds = " . (get_num_builds($conn) - 1) .
         " WHERE email = :email";

  $stmt = $conn->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
  $stmt->execute(array(":email" => $_SESSION["user"]));

  echo "Build deleted successfully<br>";
}
?>
</body>
</html>
