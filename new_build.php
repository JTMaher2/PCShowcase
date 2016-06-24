<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>New Build</title>
</head>
<body>
<?php
session_start();

require "header.php";

echo "<h3>New Build</h3>";

$url = parse_url(getenv("CLEARDB_DATABASE_URL"));

$server = $url["host"];
$username = $url["user"];
$password = $url["pass"];
$db = substr($url["path"], 1);

try { // to make new build and increment user's # of builds
  $conn = new PDO($server, $username, $password, $db);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  new_build($conn);
  increment_num_builds($conn);

  $conn = null;
} catch (PDOException $e) {
  echo "Error: " . $e->getMessage();
}

require "footer.php";

// add new record to builds table
function new_build($conn) {
  $sql = "INSERT INTO builds (name, owner) VALUES (:name, :email)";

  // make new build
  $stmt = $conn->prepare($sql,
                         array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
  $stmt->execute(array(":name" => $_GET["name"],
                       ":email" => $_SESSION["user"]));

  $build_id = $conn->lastInsertId(); // get this build's ID

  header("Location: display_build.php?build_id=" . $build_id); // redirect
}

// increment # of builds that belong to user
function increment_num_builds($conn) {
  $sql = "UPDATE users SET num_builds = " . (get_num_builds($conn) + 1) .
         " WHERE email = :email";

  $stmt = $conn->prepare($sql,
                         array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
  $stmt->execute(array(":email" => $_SESSION["user"]));
}

// get # of builds that belong to user
function get_num_builds($conn) {
  $sql = "SELECT num_builds FROM users WHERE email = :email";

  $stmt = $conn->prepare($sql,
                         array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
  $stmt->execute(array(":email" => $_SESSION["user"]));

  $num_builds = $stmt->fetch()["num_builds"];

  return $num_builds;
}
?>
</body>
</html>
