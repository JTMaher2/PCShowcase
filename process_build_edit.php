<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Process Build Edit</title>
</head>

<body>
<?php
if ($_SESSION["build_id"] != null && $_GET["new_name"] != null) {
  $db = "mysql:dbname=pcshowcase;host=localhost";
  $username = "root";
  $password = "password";

  try {
    $conn = new PDO($db, $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "UPDATE builds SET name = :new_name WHERE id = " . $_SESSION["build_id"];

    $stmt = $conn->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $stmt->execute(array(":new_name" => $_GET["new_name"]));

    $conn = null;

    echo "Build updated<br>";
  } catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
  }
}

echo "<a href='builds.php'>Back</a>";
?>
</body>

</html>
