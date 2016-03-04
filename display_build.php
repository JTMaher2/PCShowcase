<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Current Build</title>
</head>
<body>

<?php
$servername = "localhost";
$username = "root";
$password = "password";
$dbname = "pcshowcase";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$build_id = $_GET["build_id"];
$sql = "SELECT name, owner FROM builds WHERE id = $build_id";
$result = $conn->query($sql);

if ($result->num_rows > 0) { // build exists, so display it
  $build_details = $result->fetch_assoc();
  $build_name = $build_details["name"];
  $owner = $build_details["owner"];

  echo "<h3>$build_name:</h3>";

  // select parts for this build
  $sql = "SELECT id, type, name FROM parts WHERE build_id = $build_id";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) { // if this build has parts
    echo "<table border='1' style='width:100%'>
          <tr><th>Type</th><th>Name</th><th>Buy</th>";
    // if current user is owner, allow part deletion
    if ($owner == $_SESSION["email"]) {
      echo "<th>Remove</th>";
    }
    echo "</tr>";

    // output data of each row
    while($row = $result->fetch_assoc()) {
      $url_name = str_replace(' ', '+', strtolower($row["name"]));

      echo "<tr><td>" . $row["type"] . "</td><td>" . $row["name"] . "</td><td>" .
           "<a href='https://www.google.com/search?output=search&tbm=shop&q=" .
           $url_name . "' target='_blank'>Go</a></td>";
      // if list belongs to current user, allow parts to be removed
      if ($owner == $_SESSION["email"]) {
        echo "<td><form action='remove_part.php'>" .
             // part to delete
             "<input type='hidden' name='part_id' value='" . $row["id"] . "'>" .
             // build is identified by its ID and name
             "<input type='hidden' name='build_id' value='$build_id'>" .
             "<input type='submit' value='X'></form></td></tr>";
      }
    }

    echo "</table>";
  } else {
    echo "0 parts<br>";
  }

  // if list belongs to current user, allow parts to be added
  if ($owner == $_SESSION["email"]) {
    echo "<br>Add new part:
          <form action='new_part.php'>";

    // build is identified by its ID and name
    $_SESSION["build_id"] = $build_id;

    // ask for part information
    echo "Type: <input type='text' name='part_type'><br>
          Name: <input type='text' name='part_name'><br>
          <input type='submit' value='Add'>
          </form><br>";
  }
} else { // build doesn't exist
  echo "Invalid build ID<br>";
}

$conn->close();

echo "<br><a href='builds.php'>Back to Overview</a>";
?>

</body>
</html>
