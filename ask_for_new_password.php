<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Ask for New Password</title>
  <link href="vendor/twbs/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php
session_start();

require "header.php";

echo "<div class='container'>";

echo "<h3>Change Password</h3>
      <form action='process_password_change.php' method='post'>
        <input type='hidden' name='email' value=" . $_GET["email"]  . ">
        <input type='hidden' name='token' value=" . $_GET["token"]  . ">
        New password: <input type='password' name='password'><br>
        Confirm: <input type='password' name='confirm'><br>
        <input type='submit' value='Submit' class='btn btn-large btn-primary'>
      </form>";

echo "</div>";

require "footer.php";
?>
</body>
</html>
