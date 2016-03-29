<!DOCTYPE html>
<html lang="en">
<head>
  <title>Settings</title>
  <meta charset="utf-8">
</head>
<body>
<?php
session_start();

require "header.php";

if ($_SESSION["user"]) {
  echo "<h3>Settings</h3>
        <a href='reset_password.php'>Change Password</a><br>
        <a href='change_email.php'>Change Email</a><br>
        <a href='deactivate_account.php'>Deactivate Account</a><br>";
}

require "footer.php";
?>
</body>
</html>
