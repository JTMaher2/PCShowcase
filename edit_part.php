<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Edit Part</title>
</head>

<body>
  <form action="process_part_edit.php">
    <input type="hidden" name='part_id' value="<?php echo $_GET["part_id"]; ?>">
    New Type: <input type="text" name="type"><br>
    New Name: <input type="text" name="name"><br>
    <input type="submit" value="Submit Changes">
  </form><br>
  <?php echo "<a href='display_build.php?build_id=" . $_SESSION["build_id"] .
             "'>Back</a>"; ?>
</body>

</html>
