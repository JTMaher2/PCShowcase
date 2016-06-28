<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Settings</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
</head>
<body>
<?php
session_start();

require "header.php";

echo "<div class='container'><div class='center jumbotron'>";

if (isset($_SESSION["user"])) {
  // do not allow guest account to be modified
  if ($_SESSION["user"] != 'guest@example.com') {
    echo "<h3>Settings</h3>
          <a href='reset_password.php' class='btn btn-large btn-primary'>Change Password</a><br>
          <a href='change_email.php' class='btn btn-large btn-primary'>Change Email</a><br>
          <a href='deactivate_account.php' class='btn btn-large btn-danger'>Deactivate Account</a><br>";
  }
}

echo "</div></div>";

require "footer.php";
?>
</body>
</html>
