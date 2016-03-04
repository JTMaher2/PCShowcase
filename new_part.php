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

$build_id = $_SESSION["build_id"]; // retrieve build ID from session vars

try { // to add new part
  $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
  // set the PDO error mode to exception
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $part_type = $_GET["part_type"];
  $part_name = $_GET["part_name"];

  $sql = "INSERT INTO parts (build_id, type, name)
  VALUES ('$build_id', '$part_type', '$part_name')";
  // use exec() because no results are returned
  $conn->exec($sql);
  echo "New part added successfully<br>";
} catch(PDOException $e) {
  echo $sql . "<br>" . $e->getMessage();
}

$conn = null;

// display link back to build
echo "<a href='display_build.php?build_id=" . $build_id . "&build_name=" . $build_name . "'>Back to Build</a>";
?>

</body>
</html>
