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
  $user_id = $_SESSION["user_id"];

  if ($user_id != null) { // if user is logged in
    session_unset();
    session_destroy();
    
    echo "You are now logged out.<br>";
    echo "<a href='index.php'>Home</a>";
  }
?>

</body>
</html>
