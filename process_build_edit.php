<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Process Build Edit</title>
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

  update_build($conn);

  $conn = null;
} catch (PDOException $e) {
  echo "Connection failed: " . $e->getMessage();
}

echo "</div>";

require "footer.php";

// attempt to update build
function update_build($conn) {
  // if user is logged in, and is build owner
  if (isset($_SESSION["user"]) && $_SESSION["user"] == get_build_owner($conn)) {
    // if user has specified a build ID and new name
    if ($_GET["build_id"] != null) {
      if ($_GET["new_name"] != null) {
        $sql = "UPDATE builds SET name = :new_name WHERE id = :build_id";

        $stmt = $conn->prepare($sql,
                               array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $stmt->execute(array(":new_name" => $_GET["new_name"],
                             ":build_id" => $_GET["build_id"]));
      }

      if ($_GET["status"] != null) {
        $sql = "UPDATE builds SET status = :new_status WHERE id = :build_id";

        $stmt = $conn->prepare($sql,
                               array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $stmt->execute(array(":new_status" => $_GET["status"],
                             ":build_id" => $_GET["build_id"]));
      }

      if ($_GET["description"] != null) {
        $sql = "UPDATE builds SET description = :new_description WHERE id = :build_id";

        $stmt = $conn->prepare($sql,
                               array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $stmt->execute(array(":new_description" => $_GET["description"],
                             ":build_id" => $_GET["build_id"]));
      }

      header("Location: my_builds.php");
    } else {
      echo "Invalid parameters<br>";
    }
  } else {
    echo "You do not have permission to edit this build.<br>";
  }
}

// get build creator
function get_build_owner($conn) {
  $sql = "SELECT owner FROM builds WHERE id = :build_id";

  $stmt = $conn->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
  $stmt->execute(array(":build_id" => $_GET["build_id"]));

  return $stmt->fetch()["owner"];
}
?>
</body>
</html>
