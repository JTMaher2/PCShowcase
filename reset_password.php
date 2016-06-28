<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Reset Password</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
</head>
<body>
<?php
session_start();

require "header.php";

echo "<div class='container'><div class='center jumbotron'>";

echo "<h3>Reset Password</h3>
      <form action='send_password_reset.php' method='post'>
          Email Address: <input type='text' name='email'><br>
          <input type='submit' value='Submit' class='btn btn-large btn-primary'>
      </form>";

echo "</div></div>";

require "footer.php";
?>
</body>
</html>
