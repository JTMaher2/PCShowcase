<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Activate Account</title>
</head>

<body>
<?php
// retrieve token for specific user
function fetch_token($email) {
  $dsn = 'mysql:dbname=pcshowcase;host=localhost';
  $user = 'root';
  $password = 'password';

  try {
    $dbh = new PDO($dsn, $user, $password);
    // set the PDO error mode to exception
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = 'SELECT token
        FROM users
        WHERE email = :email';

    $sth = $dbh->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $sth->execute(array(':email' => $_GET['email']));

    $token = $sth->fetch()["token"]; // token was found
  } catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
  }

  return $token;
}

// activate a user's account
function activate_account($email) {
  $dsn = 'mysql:dbname=pcshowcase;host=localhost';
  $user = 'root';
  $password = 'password';

  try {
    $dbh = new PDO($dsn, $user, $password);
    // set the PDO error mode to exception
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "UPDATE users
        SET activated = 1, token = ''
        WHERE email = '$email'";

    $sth = $conn->prepare($sql);
    $sth->execute();

    echo "Account activated!<br>";
  } catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage() . "<br>";
  }
}

$token = $_GET['token'];
$db_token = fetch_token($_GET['email']);

if ($token == $db_token) {
  activate_account($email);
} else {
  echo "Invalid token<br>";
}

echo "<a href='index.php'>Home</a>";
?>
</body>

</html>
