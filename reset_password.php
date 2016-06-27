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

if (isset($_SESSION["user"])) {
    // do not allow guest to reset password
    if ($_SESSION["user"] != 'guest@example.com') {
        echo "<h1>Reset Password</h1>
              <form action='send_password_reset.php' method='post'>
                Email Address: <input type='text' name='email'><br>
                <input type='submit' value='Submit'>
              </form>";
    }
}

require "footer.php";
?>
</body>
</html>
