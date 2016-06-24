<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>New Part</title>
</head>
<body>
<?php
session_start();

require "header.php";

echo "<h3>New Part</h3>";

$url = parse_url(getenv("CLEARDB_DATABASE_URL"));

$server = $url["host"];
$username = $url["user"];
$password = $url["pass"];
$db = substr($url["path"], 1);

try {
  $conn = new PDO($server, $username, $password, $db);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  add_part($conn);

  $conn = null;
} catch (PDOException $e) {
  echo "Error: " . $e->getMessage();
}

// link back to build
echo "<a href='display_build.php?build_id=" . $_SESSION["build_id"] .
     "'>Back</a><br>";

require "footer.php";

// try to add part to build
function add_part($conn) {
  // if user is logged in
  if (isset($_SESSION["user"])) {
    // if user has specified a build
    if (isset($_SESSION["build_id"])) {
      $sql = "INSERT INTO parts (build_id, type, manufacturer, name, qty)
              VALUES (" . $_SESSION["build_id"] . ", :type, :manufacturer,
              :name, :qty)";

      $stmt = $conn->prepare($sql,
                             array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
      $stmt->execute(array(":type" => $_GET["part_type"],
                           ":manufacturer" => $_GET["part_manufacturer"],
                           ":name" => $_GET["part_name"],
                           ":qty" => $_GET["part_qty"]));

      header("Location: display_build.php?build_id=" . $_SESSION["build_id"]);
    } else {
      echo "You have not specified a build.<br>";
    }
  } else {
    echo "You are not logged in.<br>";
  }
}
?>
</body>
</html>
