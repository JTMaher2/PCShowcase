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
if (!isset($_SESSION["user"])) {
  echo "<form action='login.php' method='post'>
          Email: <input type='text' name='email'><br>
          Password: <input type='password' name='password'><br>
          <input type='submit' value='Login'>
        </form><br>
        <form action='login.php' method='post'>
            <input type='hidden' name='email' value='guest@example.com'>
            <input type='hidden' name='password' value='password1!'>
            <input type='submit' value='Guest Login'>
        </form><br>
        <a href='register.php'>Register</a> |
        <a href='reset_password.php'>Reset Password</a><br>";
}

require "footer.php";
?>
</body>
</html>
