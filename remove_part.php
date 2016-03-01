<?php
// Start the session
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Remove Part</title>
</head>
<body>

<?php
$servername = "localhost";
$username = "root";
$password = "password";
$dbname = "pcshowcase";

try {
  $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
  // set the PDO error mode to exception
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $part_id = $_GET["part_id"];

  // sql to delete a record
  $sql = "DELETE FROM parts WHERE id = '$part_id'";

  // use exec() because no results are returned
  $conn->exec($sql);
  echo "Part deleted successfully<br>";

  $build_id = $_GET["build_id"];

  echo "<a href='display_build.php?build_id=" . $build_id . "'>Back to Build</a>";
  }
catch(PDOException $e)
  {
  echo $sql . "<br>" . $e->getMessage();
  }

$conn = null;
?>

</body>
</html>
