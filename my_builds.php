<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>My PC Builds</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
</head>
<body>
<?php
session_start();

require "header.php";

echo "<div class='container'>";

if (isset($_SESSION["user"])) {
  echo "<h3>Builds</h3>";

  $url = parse_url(getenv("DATABASE_URL"));

  $dsn = "pgsql:host=" . $url["host"] . ";dbname=" . substr($url["path"], 1);
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
                <div class='form-group has-feedback'>
                  <form action='remove_build.php'>
                    <input type='hidden' name='build_id' value='" . $build["id"] . "'>
                    <input type='submit' value='' class='btn btn-danger form-control'>
                    <i class='glyphicon glyphicon-remove form-control-feedback'></i>
                  </form>
                </div>
                <div class='form-group has-feedback'>
                  <form action='edit_build.php'>
                    <input type='hidden' name='build_id' value='" . $build["id"] . "'>
                    <input type='submit' value='' class='btn btn-warning form-control'>
                    <i class='glyphicon glyphicon-pencil form-control-feedback'></i>
                  </form></div></td></tr>";
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
          <input type='submit' value='Create' class='btn btn-large btn-success'>
        </form><br>";
} else {
  echo "You are not logged in.<br>";
}

echo "</div>";

require "footer.php";
?>
</body>
</html>
