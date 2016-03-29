<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Ask for New Password</title>
</head>
<body>
<?php
session_start();

require "header.php";

echo "<h3>Change Password</h3>
      <form action='process_password_change.php' method='post'>
        <input type='hidden' name='email' value=" . $_GET["email"]  . ">
        <input type='hidden' name='token' value=" . $_GET["token"]  . ">
        New password: <input type='password' name='password'><br>
        Confirm: <input type='password' name='confirm'><br>
        <input type='submit' value='Submit'>
      </form>";

require "footer.php";
?>
</body>
</html>
