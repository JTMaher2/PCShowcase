<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Index</title>
</head>
<body>
<?php
session_start();

echo "<h1>Welcome to PC Showcase</h1>
      <h3>Use this site to create custom desktop PCs.</h3>";

// if someone is logged in, link to builds page
if ($_SESSION["user"] != null) {
  echo "<a href='builds.php'>My Builds</a>";
} else { // otherwise, display login form
  echo "<form action='login.php' method='post'>
          Email: <input type='text' name='email'><br>
          Password: <input type='password' name='password'><br>
          <input type='submit' value='Login'>
        </form><br>
        <a href='register.html'>Register</a> |
        <a href='reset_password.html'>Reset Password</a>";
}
?>
</body>
</html>
