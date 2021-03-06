<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Deactivate Account</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
</head>
<body>
<?php
session_start();

require "header.php";

echo "<div class='container'>";

// if user is logged in, and has submitted an email address
if (isset($_SESSION["user"]) && isset($_GET["email"])) {
  // if the submitted email is same as user's email, and the user is not guest
  if ($_GET["email"] == $_SESSION["user"] && $_SESSION["user"] != "guest@example.com") {
    $url = parse_url(getenv("DATABASE_URL"));

    $dsn = "pgsql:host=" . $url["host"] . ";dbname=" . substr($url["path"], 1);
    $username = $url["user"];
    $password = $url["pass"];

    try { // to delete user from DB
      $conn = new PDO($dsn, $username, $password);
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      // get all builds that user has made
      $sql = "SELECT id FROM builds WHERE owner = :user";

      $stmt = $conn->prepare($sql,
                             array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
      $stmt->execute(array(":user" => $_SESSION["user"]));

      // delete each of these builds, and the parts that go along with them
      while ($build = $stmt->fetch()) {
        $conn->exec("DELETE FROM parts WHERE build_id = " . $build["id"]);
        $conn->exec("DELETE FROM builds WHERE id = " . $build["id"]);
      }

      // delete user
      $sql = "DELETE FROM users WHERE email = :user";

      $stmt = $conn->prepare($sql,
                             array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
      $stmt->execute(array(":user" => $_SESSION["user"]));

      $conn = null;
    } catch (PDOException $e) {
      echo "Error: " . $e->getMessage();
    }

    // logout
    session_unset();
    session_destroy();
    header("Location: index.php");
  } else {
    echo "Submitted email address does not match<br>
          <a href='deactivate_account.php' class='btn btn-large btn-primary'>Back</a>";
  }
}

echo "</div>";

require "footer.php";
?>
</body>
</html>
