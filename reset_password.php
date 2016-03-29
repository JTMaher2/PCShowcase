<!DOCTYPE html>
<html lang="en">
<head>
  <title>Reset Password</title>
  <meta charset="utf-8">
</head>
<body>
<?php
session_start();

require "header.php";

if ($_SESSION["user"] != null) {
  echo "<h1>Reset Password</h1>
        <form action='send_password_reset.php' method='post'>
          Email Address: <input type='text' name='email'><br>
          <input type='submit' value='Submit'>
        </form>";
}

require "footer.php";
?>
</body>
</html>
