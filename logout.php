<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Logout</title>
</head>
<body>
<?php
session_start();
session_unset();
session_destroy();
header("Location: index.php");
?>
</body>
</html>
