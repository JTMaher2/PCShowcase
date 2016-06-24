<!DOCTYPE html>
<html lang="en">
<head>
  <title>Deactivate Account</title>
  <meta charset="utf-8">
</head>
<body>
<?php
session_start();

require "header.php";

// if user is logged in, and has submitted an email address
if (isset($_SESSION["user"]) && isset($_GET["email"])) {
  // if the submitted email is same as user's email
  if ($_GET["email"] == $_SESSION["user"]) {
    $url = parse_url(getenv("CLEARDB_DATABASE_URL"));

    $dsn = "mysql:host=" . $url["host"] . ";dbname=" . substr($url["path"], 1);
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
          <a href='deactivate_account.php'>Back</a>";
  }
}

require "footer.php";
?>
</body>
</html>
