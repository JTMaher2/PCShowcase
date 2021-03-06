<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Process Part Edit</title>
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

  update_part($conn);

  $conn = null;
} catch (PDOException $e) {
  echo "<br>Error: " . $e->getMessage();
}

echo "</div>";

require "footer.php";

// attempt to modify a part
function update_part($conn) {
  if (isset($_SESSION["user"]) && $_SESSION["user"] == get_part_owner($conn)) {
    // determine what changes, if any, user wants to make
    if ($_GET["type"] != null) {
      $sql = "UPDATE parts SET type = :new_type WHERE id = :id";

      $stmt = $conn->prepare($sql,
                             array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
      $stmt->execute(array(":new_type" => $_GET["type"],
                           ":id" => $_GET["part_id"]));
    }

    if ($_GET["manufacturer"] != null) {
      $sql = "UPDATE parts SET manufacturer = :new_manufacturer WHERE id = :id";

      $stmt = $conn->prepare($sql,
                             array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
      $stmt->execute(array(":new_manufacturer" => $_GET["manufacturer"],
                           ":id" => $_GET["part_id"]));
    }

    if ($_GET["name"] != null) {
      $sql = "UPDATE parts SET name = :new_name WHERE id = :id";

      $stmt = $conn->prepare($sql,
                             array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
      $stmt->execute(array(":new_name" => $_GET["name"],
                           ":id" => $_GET["part_id"]));
    }

    if ($_GET["qty"] != null) {
      $sql = "UPDATE parts SET qty = :new_qty WHERE id = :id";

      $stmt = $conn->prepare($sql,
                             array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
      $stmt->execute(array(":new_qty" => $_GET["qty"],
                           ":id" => $_GET["part_id"]));
    }

    echo "Part updated<br>";
  } else {
    echo "You do not have permission to edit this part.<br><br>";
  }

  echo "<a href='display_build.php?build_id=" . $_SESSION["build_id"] .
       "' class='btn btn-large btn-primary'>Back</a><br>";
}

// get owner that corresponds to unique part ID
function get_part_owner($conn) {
  // get build ID
  $sql = "SELECT build_id FROM parts WHERE id = :part_id";

  $stmt = $conn->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
  $stmt->execute(array(":part_id" => $_GET["part_id"]));

  // get owner
  $sql = "SELECT owner FROM builds WHERE id = " . $stmt->fetch()["build_id"];

  $stmt = $conn->prepare($sql);
  $stmt->execute();

  return $stmt->fetch()["owner"];
}
?>
</body>
</html>
