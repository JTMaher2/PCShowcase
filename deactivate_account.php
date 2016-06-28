<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Deactivate Account</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
</head>
<body>
<?php
session_start();

require "header.php";

echo "<div class='container'>";

if (isset($_SESSION["user"])) {
    // do not let guest deactivate account
    if ($_SESSION["user"] != "guest@example.com") {
      echo "If you would like to deactivate your account, and delete all builds
            associated with it, please type your email address:
            <br>
            <form action='process_deactivate_account.php'>
              <input type='text' name='email'><br>
              <input type='submit' value='Submit' class='btn btn-large btn-danger'>
            </form>";
    }
} else { // not logged in, so redirect home
  header("Location: index.php");
}

echo "</div>";

require "footer.php";
?>
</body>
</html>
