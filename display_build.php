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
$name = $_GET["name"];
echo "<h3>$name:</h3>";

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

$user_id = $_SESSION["user_id"];
$build_id = $_GET["build_id"];
$sql = "SELECT id, type, name FROM parts WHERE user_id = '$user_id' AND build_id = '$build_id'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  echo "<table border='1' style='width:100%'>";
  echo "<tr><th>Type</th><th>Name</th><th>Buy</th><th>Remove</th></tr>";

  // output data of each row
  while($row = $result->fetch_assoc()) {
    $url_name = str_replace(' ', '+', strtolower($row["name"]));

    echo "<tr><td>" . $row["type"] . "</td><td>" . $row["name"] . "</td><td>" .
"<a href='https://www.google.com/search?output=search&tbm=shop&q=" . $url_name
. "' target='_blank'>Go</a></td>" .
"<td><form action='remove_part.php'>" .
"<input type='hidden' name='part_id' value='" . $row["id"] . "'>" . // forward part id
"<input type='hidden' name='build_id' value='$build_id'>" .
"<input type='submit' value='X'></form></td></tr>";
  }

  echo "</table>";
} else {
  echo "0 parts<br>";
}
$conn->close();

echo "<br>Add new part: ";
echo "<form action='new_part.php'>";
echo "<input type='hidden' name='build_id' value='$build_id'>"; // forward build_id on to next page
echo "Type: <input type='text' name='type'><br>";
echo "Name: <input type='text' name='name'><br>";
echo "<input type='submit' value='Add'>";
echo "</form><br>";

echo "<a href='builds.php'>Back to Overview</a>";
?>

</body>
</html>
