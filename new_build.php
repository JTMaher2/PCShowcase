<?php
// Start the session
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>New Build</title>
</head>
<body>

<?php
// basic DB credentials
$servername = "localhost";
$username = "root";
$password = "password";
$dbname = "pcshowcase";

// find # of builds this user has made
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION["user_id"];
$sql = "SELECT num_builds FROM users WHERE id=$user_id";
$result = $conn->query($sql);
$num_builds = $result->fetch_assoc()["num_builds"];
$conn->close();

try { // to increment # of builds
  $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
  // set the PDO error mode to exception
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  // update user's num_builds based on the retrieved value
  $sql = "UPDATE users SET num_builds=" . ($num_builds + 1) . " WHERE id=$user_id";
  $stmt = $conn->prepare($sql);
  $stmt->execute();
}
catch(PDOException $e) {
  echo $sql . "<br>" . $e->getMessage();
}

try { // to add new record to builds table
  $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
  // set the PDO error mode to exception
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $name = $_GET["name"];

  $sql = "INSERT INTO builds (name, owner) VALUES (:name, :user_id)";

  // use parameters to prevent SQL injection
  $stmt = $conn->prepare($sql);
  $stmt->bindParam(':name', $name);
  $stmt->bindParam(':user_id', $user_id);
  $stmt->execute();

  echo "New build created successfully<br>";

  $build_id = $conn->lastInsertId(); // get this build's ID

  echo "<a href='display_build.php?build_id=" . $build_id .
  "&name=" . $name . "'>Go to Build</a>";
}
catch(PDOException $e) {
  echo $e->getMessage();
}

$conn = null; // close DB connection
?>

</body>
</html>
