<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>New Part</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
</head>
<body>
<?php
session_start();

require "header.php";

echo "<div class='container'>";

echo "<h3>New Part</h3>";

$url = parse_url(getenv("CLEARDB_DATABASE_URL"));

$dsn = "mysql:host=" . $url["host"] . ";dbname=" . substr($url["path"], 1);
$username = $url["user"];
$password = $url["pass"];

try {
  $conn = new PDO($dsn, $username, $password);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  add_part($conn);

  $conn = null;
} catch (PDOException $e) {
  echo "<br>Error: " . $e->getMessage();
}

// link back to build
echo "<br><a href='display_build.php?build_id=" . $_SESSION["build_id"] .
     "' class='btn btn-large btn-primary'>Back</a><br>";

echo "</div>"

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
      echo "<br>You have not specified a build.<br>";
    }
  } else {
    echo "<br>You are not logged in.<br>";
  }
}
?>
</body>
</html>
