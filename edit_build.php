<?php
session_start();
$_SESSION["build_id"] = $_GET["build_id"];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Edit Build</title>
</head>

<body>
<h3>Edit Build</h3>
<form action="process_build_edit.php">
  Name: <input type="text" name="new_name"><br>
  <input type="submit" value="Submit">
</form>
</body>

</html>
