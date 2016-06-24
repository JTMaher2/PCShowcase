<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>My PC Builds</title>
</head>
<body>
<?php
session_start();

require "header.php";

if (isset($_SESSION["user"])) {
  echo "<h3>Builds</h3>";

  $url = parse_url(getenv("CLEARDB_DATABASE_URL"));

  $dsn = "mysql:host=" . $url["host"] . ";dbname=" . substr($url["path"], 1);
  $username = $url["user"];
  $password = $url["pass"];

  try { // to get builds from DB
    $conn = new PDO($dsn, $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT id, name FROM builds WHERE owner = :user";

    $stmt = $conn->prepare($sql,
                           array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $stmt->execute(array(":user" => $_SESSION["user"]));

    if ($stmt->rowCount() > 0) {
      echo "<table border='1'><tr><th>Name</th><th>Modify</th></tr>";

      // display builds
      while($build = $stmt->fetch()) {
        echo "<tr><td><a href='display_build.php?build_id=" . $build["id"] .
             "'>" . $build["name"] . "</a></td>
              <td>
                <form action='remove_build.php'>
                  <input type='hidden' name='build_id' value='" . $build["id"]
                  . "'>
                  <input type='submit' value='X'>
                </form>
                <form action='edit_build.php'>
                  <input type='hidden' name='build_id' value='" . $build["id"]
                  . "'>
                  <input type='submit' value='Edit'>
                </form></td></tr>";
      }

      echo "</table><br>";
    } else {
      echo "You do not have any builds.<br><br>";
    }

    $conn = null;
  } catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    die();
  }

  echo "<strong>New build:</strong>
        <form action='new_build.php'>
          Name: <input type='text' name='name'><br>
          <input type='submit' value='Create'>
        </form><br>";
} else {
  echo "You are not logged in.<br>";
}

require "footer.php";
?>
</body>
</html>
