<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Change Email</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
</head>
<body>
<?php
session_start();

require "header.php";

echo "<div class='container'>";

if (isset($_SESSION["user"])) {
    // do not let guest change email
    if ($_SESSION["user"] != "guest@example.com") {
      echo "<h3>Change Email</h3>
            <form action='process_email_change.php'>
              New Email: <input type='text' name='new_email'><br>
              <input type='submit' value='Submit' class='btn btn-large btn-primary'>
            </form>";
    }
}

echo "</div>";

require "footer.php";
?>
</body>
</html>
