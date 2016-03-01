<?php
// Start the session
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>New Part</title>
</head>
<body>

<?php
$servername = "localhost";
$username = "root";
$password = "password";
$dbname = "pcshowcase";

$build_id = $_GET["build_id"];

try { // to add new part
  $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
  // set the PDO error mode to exception
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $user_id = $_SESSION["user_id"];
  $type = $_GET["type"];
  $name = $_GET["name"];

  $sql = "INSERT INTO parts (user_id, build_id, type, name)
  VALUES ('$user_id', '$build_id', '$type', '$name')";
  // use exec() because no results are returned
  $conn->exec($sql);
  echo "New record created successfully<br>";
  }
catch(PDOException $e)
  {
  echo $sql . "<br>" . $e->getMessage();
  }
$conn = null;

// retrieve name of build
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
$user_id = $_SESSION["user_id"];
$sql = "SELECT name FROM builds WHERE id=$build_id";
$result = $conn->query($sql);
$build_name = $result->fetch_assoc()["name"];
$conn->close();

// display link back to build
echo "<a href='display_build.php?build_id=" . $build_id .
  "&name=" . $build_name . "'>Back to Build</a>";
?>

</body>
</html>
