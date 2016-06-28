<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Process Email Change</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
</head>
<body>
<?php
session_start();

require "header.php";

echo "<div class='container'>";

if (isset($_SESSION["user"])) {
    // do not allow guest to change email
    if ($_SESSION["user"] != 'guest@example.com') {
        // if new username is different from old username
        if ($_GET["new_username"] != $_SESSION["user"]) {
          $url = parse_url(getenv("CLEARDB_DATABASE_URL"));

          $dsn = "mysql:host=" . $url["host"] . ";dbname=" . substr($url["path"], 1);
          $username = $url["user"];
          $password = $url["pass"];

          try {
            $conn = new PDO($dsn, $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = "UPDATE users SET name = :new_name WHERE name = :old_name";

            $stmt = $conn->prepare($sql,
                                   array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
            $stmt->execute(array(":new_name" => $_GET["new_username"],
                                 ":old_name" => $_SESSION["user"]));

            $conn = null;

            echo "Username changed<br>";
          } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
          }
        } else {
          echo "Please enter a different username than your current one.<br>
                <a href='change_username.php' class='btn btn-large btn-primary'>Back</a>";
        }
    }
}

echo "</div>";

require "footer.php";
?>
</body>
</html>
