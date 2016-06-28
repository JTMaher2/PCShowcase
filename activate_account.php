<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Activate Account</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
</head>
<body>
<?php
session_start();

require "header.php";

$url = parse_url(getenv("CLEARDB_DATABASE_URL"));

$dsn = "mysql:host=" . $url["host"] . ";dbname=" . substr($url["path"], 1);
$username = $url["user"];
$password = $url["pass"];

try { // to activate user's account
  $conn = new PDO($dsn, $username, $password);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  echo "<div class='container'>";

  // if the provided token is the same as the token in the database
  if ($_GET["token"] == get_stored_token($conn)) {
    activate_account($conn); // it's safe to activate
  } else {
    echo "Invalid token<br>";
  }

  $conn = null;
} catch (PDOException $e) {
  echo "Error: " . $e->getMessage();
}

echo "</div>";

require "footer.php";

// retrieve token for specified user
function get_stored_token($conn) {
  $sql = "SELECT token FROM users WHERE email = :email";

  $stmt = $conn->prepare($sql,
                         array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
  $stmt->execute(array(":email" => $_GET["email"]));

  return $stmt->fetch()["token"];
}

// activate a user's account
function activate_account($conn) {
  $sql = "UPDATE users SET activated = 1, token = '' WHERE email = :email";

  $stmt = $conn->prepare($sql,
                         array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
  $stmt->execute(array(":email" => $_GET["email"]));

  echo "Account successfully activated<br>";
}
?>
</body>
</html>
