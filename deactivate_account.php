<!DOCTYPE html>
<html lang="en">
<head>
  <title>Deactivate Account</title>
  <meta charset="utf-8">
</head>
<body>
<?php
session_start();

require "header.php";

if (isset($_SESSION["user"])) {
    // do not let guest deactivate account
    if ($_SESSION["user"] != "guest@example.com") {
      echo "If you would like to deactivate your account, and delete all builds
            associated with it, please type your email address:
            <br>
            <form action='process_deactivate_account.php'>
              <input type='text' name='email'><br>
              <input type='submit' value='Submit'>
            </form>";
    }
} else { // not logged in, so redirect home
  header("Location: index.php");
}

require "footer.php";
?>
</body>
</html>
