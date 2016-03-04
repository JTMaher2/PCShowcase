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

if ($password != null) {
  // verify password
  $submitted_password = $_POST["password"];

  if (password_verify($submitted_password, $hashed_password)) {
    $_SESSION["email"] = $email; // use user's email as session variable

    echo "You are now logged in.<br>";
    echo "<a href='builds.php'>View your builds.</a>";
  } else {
    echo "Incorrect password<br>";
    echo "<a href='index.php'>Back</a>";
  }
} else {
  echo "No account exists for $email.<br>";
  echo "<a href='index.php'>Home</a>";
}

$conn->close();
?>

</body>
</html>
