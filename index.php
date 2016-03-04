<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Index</title>
</head>

<body>
<?php
$user = $_SESSION["email"];

// if someone is logged in, link to builds page
if ($user != null) {
  echo "<a href='builds.php'>My Builds</a>";
} else {
  echo "<h1>Welcome to PC Showcase</h1>";
  echo "<h3>Use this site to create custom desktop PCs.</h3>";
  echo "<form action='login.php' method='post'>";
  echo "Email:<br>";
  echo "<input type='text' name='email'><br>";
  echo "Password:<br>";
  echo "<input type='password' name='password'><br>";
  echo "<input type='submit' value='Login'>";
  echo "</form><br>";
  echo "<a href='register.html'>Register</a> | <a href='reset_password.html'>Reset Password</a>";
}
?>
</body>

</html>
