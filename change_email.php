<!DOCTYPE html>
<html lang="en">
<head>
  <title>Change Email</title>
  <meta charset="utf-8">
</head>
<body>
<?php
session_start();

require "header.php";

if (isset($_SESSION["user"])) {
  echo "<h3>Change Email</h3>
        <form action='process_email_change.php'>
          New Email: <input type='text' name='new_email'><br>
          <input type='submit' value='Submit'>
        </form>";
}

require "footer.php";
?>
</body>
</html>
