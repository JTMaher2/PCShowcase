<?php
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
function look_up_build_owner($build_id) {
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

  $sql = "SELECT owner FROM builds WHERE id='$build_id'";
  $result = $conn->query($sql);

  $owner = $result->fetch_assoc()["owner"];

  $conn->close();

  return $owner;
}

function delete_part($part_id) {
  $servername = "localhost";
  $username = "root";
  $password = "password";
  $dbname = "pcshowcase";

  try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // sql to delete a record
    $sql = "DELETE FROM parts WHERE id = '$part_id'";

    $stmt = $conn->prepare($sql);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
      echo "Part deleted successfully<br>";
    } else {
      echo "Invalid part ID<br>";
    }
  } catch(PDOException $e) {
    echo $sql . "<br>" . $e->getMessage();
  }

  $conn = null;
}

// look up build owner, and compare with logged-in user
$build_id = $_GET["build_id"];
$build_owner = look_up_build_owner($build_id);

// if the current user is the owner of the build, allow part deletion
if ($_SESSION["email"] == $build_owner) {
  $part_id = $_GET["part_id"];
  delete_part($part_id);
} else {
  echo "You are not the owner of this build.<br>";
}

echo "<a href='display_build.php?build_id=" . $build_id . "'>Back to Build</a>";
?>

</body>
</html>
