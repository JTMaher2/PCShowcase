<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Activate Account</title>
</head>
<body>
<?php
session_start();

require "header.php";

$url = parse_url(getenv("CLEARDB_DATABASE_URL"));

$server = $url["host"];
$username = $url["user"];
$password = $url["pass"];
$db = substr($url["path"], 1);

try { // to activate user's account
  $conn = new PDO($server, $username, $password, $db);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

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
