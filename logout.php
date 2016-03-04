<?php
// Start the session
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Logout</title>
</head>
<body>

<?php
  $email = $_SESSION["email"];
  session_unset();
  session_destroy();

  echo "You are now logged out.<br>";
  echo "<a href='index.php'>Home</a>";
?>

</body>
</html>
