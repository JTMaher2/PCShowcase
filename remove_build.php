<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Remove Build</title>
</head>

<body>
<?php
// get # of builds that currently exist for this user
function select_num_builds($email) {
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

  $sql = "SELECT num_builds FROM users WHERE email = '$email'";
  $result = $conn->query($sql);

  $num_builds = $result->fetch_assoc()["num_builds"];

  $conn->close();

  return $num_builds;
}

$servername = "localhost";
$username = "root";
$password = "password";
$dbname = "pcshowcase";

try {
  $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
  // set the PDO error mode to exception
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $build_id = $_GET["build_id"];

  // delete build record
  $sql = "DELETE FROM builds WHERE id = '$build_id'";
  $conn->exec($sql); // use exec() because no results are returned

  // delete parts that correspond to the deleted build
  $sql = "DELETE FROM parts WHERE build_id = '$build_id'";
  $conn->exec($sql);

  // decrement this user's num_builds
  $email = $_SESSION["email"];
  $num_builds = select_num_builds($email);
  $sql = "UPDATE users SET num_builds=" . ($num_builds - 1) . " WHERE email='$email'";
  $stmt = $conn->prepare($sql);
  $stmt->execute();

  echo "Build deleted successfully<br>";
  echo "<a href='builds.php'>My Builds</a>";
} catch(PDOException $e) {
  echo $sql . "<br>" . $e->getMessage();
}

$conn = null;
?>
</body>

</html>
