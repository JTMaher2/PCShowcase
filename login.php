<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Login</title>
</head>
<body>
<?php
session_start();

require "header.php";

// attempt user login
$url = parse_url(getenv("CLEARDB_DATABASE_URL"));

$server = $url["host"];
$username = $url["user"];
$password = $url["pass"];
$db = substr($url["path"], 1);

try {
  $conn = new PDO($server, $username, $password, $db);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $sql = "SELECT password FROM users WHERE email = :email";

  // retrieve user's hashed password from DB
  $stmt = $conn->prepare($sql,
                         array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
  $stmt->execute(array(":email" => $_POST["email"]));

  $stored_password = $stmt->fetch()["password"];

  if (account_activated($conn)) {
    // verify that submitted password is same as stored password
    if (password_verify($_POST["password"], $stored_password)) {
      $_SESSION["user"] = $_POST["email"]; // use user's email as ID

      header("Location: my_builds.php");
    } else {
      echo "Incorrect password<br>";
    }
  } else {
    echo "The account for " . $_POST["email"] . " does not exist or has not
          been activated.<br>";
  }

  $conn = null;
} catch (PDOException $e) {
  echo "Error: " . $e->getMessage();
}

require "footer.php";

// see if account has been activated
function account_activated($conn) {
  $sql = "SELECT activated FROM users WHERE email = :email";

  $stmt = $conn->prepare($sql,
                         array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
  $stmt->execute(array(":email" => $_POST["email"]));

  return $stmt->fetch()["activated"];
}
?>
</body>
</html>
