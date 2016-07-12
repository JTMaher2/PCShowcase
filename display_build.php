<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Current Build</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
</head>
<body>
<?php
session_start();

require "header.php";

$_SESSION["build_id"] = $_GET["build_id"]; // update session var

$url = parse_url(getenv("DATABASE_URL"));

$dsn = "pgsql:host=" . $url["host"] . ";dbname=" . substr($url["path"], 1);
$username = $url["user"];
$password = $url["pass"];

echo "<div class='container'>";

try {
  // Create connection
  $conn = new PDO($dsn, $username, $password);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  display_build($conn); // display selected build, if it exists

  $conn = null;
} catch (PDOException $e) {
  echo "Error: " . $e->getMessage();
}

echo "</div>";

require "footer.php";

// display a build
function display_build($conn) {
  $sql = "SELECT name, owner FROM builds WHERE id = " . $_SESSION["build_id"];

  $stmt = $conn->prepare($sql);
  $stmt->execute();

  if ($stmt->rowCount() > 0) { // build exists, so display it
    $build = $stmt->fetch();

    echo "<h3>" . $build["name"] . "</h3>
         Created by " . $build["owner"] . "<br>";

    display_parts($build["owner"], $conn); // display parts for this build

    // if list belongs to current user, allow parts to be added
    if (isset($_SESSION["user"]) && $_SESSION["user"] == $build["owner"]) {
      echo "<br>Add new part:
            <form action='new_part.php'>";

      // ask for part information
      echo "Type: <input type='text' name='part_type'><br>
            Manufacturer: <input type='text' name='part_manufacturer'><br>
            Name: <input type='text' name='part_name'><br>
            Quantity: <input type='number' name='part_qty'><br>
            <input type='submit' value='Submit' class='btn btn-large btn-primary'>
            </form>";
    }
  } else { // build doesn't exist
    echo "Invalid build ID<br>";
  }
}

// display parts for current build
function display_parts($build_owner, $conn) {
  $sql = "SELECT id, type, manufacturer, name, qty FROM parts WHERE build_id = "
         . $_SESSION["build_id"];

  $stmt = $conn->query($sql);

  if ($stmt->rowCount() > 0) { // if this build has parts
    echo "<table border='1' style='width:100%'>
          <tr><th>Type</th><th>Manufacturer</th><th>Name</th><th>Quantity</th>
          <th>Buy</th>";

    // if current user is build creator, allow modification
    if (isset($_SESSION["user"]) && $_SESSION["user"] == $build_owner) {
      echo "<th>Modify</th>";
    }

    echo "</tr>";

    $google_shopping_url =
      "https://www.google.com/search?output=search&tbm=shop&q=";

    // output data for each part
    while ($part = $stmt->fetch()) {
      // convert part name into format that is understood by Google Shopping
      $url_part_name = str_replace(' ', '+', strtolower($part["name"]));

      echo "<tr><td>" . $part["type"] . "</td><td>" . $part["manufacturer"] .
           "</td><td>" . $part["name"] . "</td><td>" . $part["qty"] .
           "</td><td><a href='" . $google_shopping_url . $url_part_name .
           "' target='_blank'>Go</a></td>";

      // if list belongs to current user, allow parts to be edited and removed
      if (isset($_SESSION["user"]) && $_SESSION["user"] == $build_owner) {
        echo "<td>
              <div class='form-group has-feedback'>
                <form action='remove_part.php'>
                  <input type='hidden' name='part_id' value='" . $part["id"] . "'>
                  <input type='submit' value='' class='btn btn-danger form-control'>
                  <i class='glyphicon glyphicon-remove form-control-feedback'></i>
                </form>
              </div>
              <div class='form-group has-feedback'>
                <form action='edit_part.php'>
                  <input type='hidden' name='part_id' value='" . $part["id"] . "'>
                  <input type='hidden' name='name' value='" . $part["name"] . "'>
                  <input type='submit' value='' class='btn btn-warning form-control'>
                  <i class='glyphicon glyphicon-pencil form-control-feedback'></i>
                </form>
              </div>
              </td></tr>";
      }
    }

    echo "</table>";
  } else {
    echo "0 parts<br>";
  }
}
?>
</body>
</html>
