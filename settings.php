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

if (isset($_SESSION["user"])) {
  // do not allow guest account to be modified
  if ($_SESSION["user"] != 'guest@example.com') {
    echo "<h3>Settings</h3>
          <a href='reset_password.php'>Change Password</a><br>
          <a href='change_email.php'>Change Email</a><br>
          <a href='deactivate_account.php'>Deactivate Account</a><br>";
  }
}

require "footer.php";
?>
</body>
</html>
