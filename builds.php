<?php
// Start the session
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>My PC Builds</title>
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

$owner = $_SESSION["user_id"];
$sql = "SELECT id, name FROM builds WHERE owner = $owner";
$result = $conn->query($sql);

// if user is logged in
if ($owner != null) {
  echo "<a href='logout.php'>Logout</a><br><br>"; // logout link

  if ($result->num_rows > 0) {
      echo "<table border='1'><tr><th>Builds</th></tr>";
      // output data of each row
      while($row = $result->fetch_assoc()) {
          echo "<tr><td><a href='display_build.php?build_id=" . $row["id"] .
  "&name=" . $row["name"] . "'>" . $row["name"] . "</a></td>" .
  "<td><form action='remove_build.php'>" .
  "<input type='hidden' name='build_id' value='" . $row["id"] . "'>" .
  "<input type='submit' value='X'>" .
  "</form></td></tr>";
      }
      echo "</table><br>";
  } else {
    echo "You do not have any builds.<br><br>";
  }

  echo "<strong>New build:</strong>";
  echo "<form action='new_build.php'>";
  echo "Name: <input type='text' name='name'><br>";
  echo "<input type='submit' value='Create'>";
  echo "</form>";
} else {
  echo "You are not logged in.<br>";
  echo "<a href='index.php'>Home</a>";
}

$conn->close();
?>

</body>
</html>
