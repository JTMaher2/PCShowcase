<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Remove Part</title>
  <link href="vendor/twbs/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php
session_start();

require "header.php";

echo "<div class='container'>";

echo "<h3>Remove Part</h3>";

$url = parse_url(getenv("CLEARDB_DATABASE_URL"));

$dsn = "mysql:host=" . $url["host"] . ";dbname=" . substr($url["path"], 1);
$username = $url["user"];
$password = $url["pass"];

try {
  $conn = new PDO($dsn, $username, $password);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  // if the current user is the owner of the build, allow part deletion
  if (get_build_owner($conn) == $_SESSION["user"]) {
    delete_part($conn);
  } else {
    echo "You do not have permission to delete this part.<br>";
  }

  $conn = null;
} catch (PDOException $e) {
  echo "Error: " . $e->getMessage() . "<br>";
}

// link back to build
echo "<a href='display_build.php?build_id=" . $_SESSION["build_id"] .
     "' class='btn btn-large btn-primary'>Back</a><br>";

echo "</div>";

require "footer.php";

// find build owner
function get_build_owner($conn) {
  $sql = "SELECT owner FROM builds WHERE id = " . $_SESSION["build_id"];

  $stmt = $conn->query($sql);

  return $stmt->fetch()["owner"];
}

// remove part from build
function delete_part($conn) {
  $sql = "DELETE FROM parts WHERE id = :part_id";

  $stmt = $conn->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
  $stmt->execute(array(":part_id" => $_GET["part_id"]));

  if ($stmt->rowCount() > 0) {
    header("Location: display_build.php?build_id=" . $_SESSION["build_id"]);
  } else {
    echo "Invalid part ID<br>";
  }
}
?>
</body>
</html>
