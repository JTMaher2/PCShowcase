<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Current Build</title>
</head>
<body>
<?php
session_start();

require "header.php";

$_SESSION["build_id"] = $_GET["build_id"]; // update session var

$dsn = "mysql:dbname=pcshowcase;host=localhost";
$username = "root";
$password = "password";

try {
  // Create connection
  $conn = new PDO($dsn, $username, $password);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  display_build($conn); // display selected build, if it exists

  $conn = null;
} catch (PDOException $e) {
  echo "Error: " . $e->getMessage();
}

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
            Name: <input type='text' name='part_name'><br>
            Quantity: <input type='number' name='part_qty'><br>
            <input type='submit' value='Submit'>
            </form>";
    }
  } else { // build doesn't exist
    echo "Invalid build ID<br>";
  }
}

// display parts for current build
function display_parts($build_owner, $conn) {
  $sql = "SELECT id, type, name, qty FROM parts WHERE build_id = " .
         $_SESSION["build_id"];

  $stmt = $conn->query($sql);

  if ($stmt->rowCount() > 0) { // if this build has parts
    echo "<table border='1' style='width:100%'>
          <tr><th>Type</th><th>Name</th><th>Quantity</th><th>Buy</th>";

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

      echo "<tr><td>" . $part["type"] . "</td><td>" . $part["name"] .
           "</td><td>" . $part["qty"] . "</td><td>" .
           "<a href='$google_shopping_url" . $url_part_name .
           "' target='_blank'>Go</a></td>";

      // if list belongs to current user, allow parts to be edited and removed
      if (isset($_SESSION["user"]) && $_SESSION["user"] == $build_owner) {
        echo "<td>
              <form action='remove_part.php'>
                <input type='hidden' name='part_id' value='" . $part["id"] .
                "'>
                <input type='submit' value='X'>
              </form>
              <form action='edit_part.php'>
                <input type='hidden' name='part_id' value='" . $part["id"] .
                "'>
                <input type='hidden' name='name' value='" . $part["name"] .
                "'>
                <input type='submit' value='Edit'>
              </form>
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
