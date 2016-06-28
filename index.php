<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Index</title>
  <link href="vendor/twbs/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php
session_start();

require "header.php";

echo "<div class='container'>
        <div class='center jumbotron'>
            <h1>Welcome to PC Showcase</h1>
            <h3>Use this site to create custom desktop PCs.</h3>";

// if no one is logged in, display login form
if (!isset($_SESSION["user"])) {
  echo "<form action='login.php' method='post'>
          Email: <input type='text' name='email'><br>
          Password: <input type='password' name='password'><br>
          <input type='submit' value='Login' class='btn btn-large btn-primary'>
        </form><br>
        <form action='login.php' method='post'>
            <input type='hidden' name='email' value='guest@example.com'>
            <input type='hidden' name='password' value='password1!'>
            <input type='submit' value='Guest Login' class='btn btn-large btn-primary'>
        </form><br>
        <a href='register.php' class='btn btn-large btn-primary'>Register</a>
        <a href='reset_password.php' class='btn btn-large btn-primary'>Reset Password</a>";
}

echo "</div></div>";

require "footer.php";
?>
</body>
</html>
