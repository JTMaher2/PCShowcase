<?php
// Start the session
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Login</title>
</head>
<body>

<?php
// see if account has been activated
function account_activated($email) {
  $dsn = 'mysql:dbname=pcshowcase;host=localhost';
  $user = 'root';
  $password = 'password';

  try {
    $dbh = new PDO($dsn, $user, $password);
    // set the PDO error mode to exception
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT activated
        FROM users
        WHERE email = :email";

    $sth = $dbh->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $sth->execute(array(':email' => $email));

    $activated = $sth->fetch()["activated"];
  } catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
  }

  return $activated;
}

$servername = "localhost";
$username = "root";
$password = "password";
$dbname = "pcshowcase";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$email = $_POST["email"];
$pw_sql = "SELECT password FROM users WHERE email = '$email'";
$pw_result = $conn->query($pw_sql);

$hashed_password = $pw_result->fetch_assoc()["password"];

if (account_activated($email)) {
  if ($password != null) {
    // verify password
    $submitted_password = $_POST["password"];

    if (password_verify($submitted_password, $hashed_password)) {
      $_SESSION["email"] = $email; // use user's email as session variable

      echo "Login successful<br>";
      echo "<a href='builds.php'>My Builds</a>";
    } else {
      echo "Incorrect password<br>";
      echo "<a href='index.php'>Home</a>";
    }
  } else {
    echo "No account exists for $email.<br>";
    echo "<a href='index.php'>Home</a>";
  }
} else {
  echo "You cannot log in until your account is activated.<br>";
  echo "<a href='index.php'>Home</a>";
}

$conn->close();
?>

</body>
</html>
