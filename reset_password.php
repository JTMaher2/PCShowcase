<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Reset Password</title>
  <link href="vendor/twbs/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php
session_start();

require "header.php";

echo "<div class='container'>";

echo "<h1>Reset Password</h1>
      <form action='send_password_reset.php' method='post'>
          Email Address: <input type='text' name='email'><br>
          <input type='submit' value='Submit' class='btn btn-large btn-primary'>
      </form>";

echo "</div>";

require "footer.php";
?>
</body>
</html>
