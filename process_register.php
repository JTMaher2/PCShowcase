<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Process Register</title>
</head>
<body>

<?php
$servername = "localhost";
$username = "root";
$password = "password";
$dbname = "pcshowcase";

// check if email is already in database
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$email = $_POST["email"];
$sql = "SELECT count(1) FROM users WHERE email = $email";
$email_taken = $conn->query($sql);

$conn->close();

// do not proceed if email is already registered
if ($email_taken == 1) {
  echo "That email address is already in use.<br>";
  echo "<a href='index.php'>Home</a>";
} else { // check for valid password
  $user_password = $_POST["password"];
  $confirm_user_password = $_POST["confirm_password"];

  // check if password matches confirm_password
  if (strcmp($user_password, $confirm_user_password) == 0) {
    // check if password is correct length
    if (strlen($user_password) < 8 || strlen($user_password) > 64) {
      echo "Password must be 8-64 characters<br>";
      echo "<a href='index.php'>Home</a>";
    } else {
      // check if password contains at least 1 letter, 1 number, and 1 symbol
      if (preg_match('/[a-zA-Z]/', $user_password) && preg_match('/\d/', $user_password)
          && preg_match('/\W/', $user_password)) {
            // safe to add new record to database
            register_user($servername, $username, $password, $dbname, $email, $user_password);
      } else {
        echo "Password must contain at least 1 letter, at least 1 number, and least 1 symbol<br>";
        echo "<a href='register.html'>Back</a>";
      }
    }
  } else { // password does not match confirm_password
    echo "Password and confirm password do not match<br>";
    echo "<a href='register.html'>Back</a>";
  }
}

function register_user($servername, $username, $password, $dbname, $email, $user_password) {
  try {
      $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
      // set the PDO error mode to exception
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      // hash and salt the password
      $hashed_user_password = password_hash($user_password, PASSWORD_DEFAULT);

      $sql = "INSERT INTO users (email, password)
              VALUES ('$email', '$hashed_user_password')";
      $conn->exec($sql); // use exec() because no results are returned
      echo "$email successfully registered<br>";
      echo "<a href='index.php'>Home</a>";
  }
  catch(PDOException $e) {
      echo $sql . "<br>" . $e->getMessage();
  }

  $conn = null;
}
?>

</body>
</html>
