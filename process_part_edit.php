<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Process Part Edit</title>
</head>

<body>
<?php
  // determine what changes, if any, user wants to make
  if ($_GET["type"] != null && $_GET["name"] != null) {
    $update_type = true;
    $update_name = true;
    $sql = "UPDATE parts SET type = :new_type, name = :new_name WHERE id = :id";
  } else if ($_GET["type"] != null) {
    $update_type = true;
    $sql = "UPDATE parts SET type = :new_type WHERE id = :id";
  } else if ($_GET["name"] != null) {
    $update_name = true;
    $sql = "UPDATE parts SET name = :new_name WHERE id = :id";
  }

  // if user wants to make changes
  if ($update_type || $update_name) {
    $db = "mysql:dbname=pcshowcase;host=localhost";
    $username = "root";
    $password = "password";

    try {
      $conn = new PDO($db, $username, $password);
      // set the PDO error mode to exception
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      $stmt = $conn->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));

      if ($update_type && $update_name) {
        $stmt->execute(array(":new_type" => $_GET["type"],
                             ":new_name" => $_GET["name"],
                             ":id" => $_GET["part_id"]));
      } else if ($update_type) {
        $stmt->execute(array(":new_type" => $_GET["type"],
                             ":id" => $_GET["part_id"]));
      } else if ($update_name) {
        $stmt->execute(array(":new_name" => $_GET["name"],
                             ":id" => $_GET["part_id"]));
      }

      $conn = null;

      echo "Part updated<br>";
    } catch (PDOException $e) {
      echo "Connection failed: " . $e->getMessage();
    }
  }

  echo "<a href='display_build.php?build_id=" . $_SESSION["build_id"] . "'>Back</a>";
?>
</body>

</html>
