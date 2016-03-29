<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Index</title>
</head>
<body>
<?php
session_start();

require "header.php";

echo "<h1>Welcome to PC Showcase</h1>
      <h3>Use this site to create custom desktop PCs.</h3>";

// if no one is logged in, display login form
if (!$_SESSION["user"]) {
  echo "<form action='login.php' method='post'>
          Email: <input type='text' name='email'><br>
          Password: <input type='password' name='password'><br>
          <input type='submit' value='Login'>
        </form><br>
        <a href='register.php'>Register</a> |
        <a href='reset_password.html'>Reset Password</a><br>";
}

require "footer.php";
?>
</body>
</html>
