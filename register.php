<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Register</title>
</head>
<style>
  .tooltip {
    position: relative;
    display: inline-block;
    border-bottom: 1px dotted black;
  }

  .tooltip .tooltiptext {
    visibility: hidden;
    width: 120px;
    background-color: black;
    color: #fff;
    text-align: center;
    border-radius: 6px;
    padding: 5px 0;

    /* Position the tooltip */
    position: absolute;
    z-index: 1;
  }

  .tooltip:hover .tooltiptext {
    visibility: visible;
  }
</style>
<body>
<?php
session_start();
require "header.php";
?>

<h3>Register</h3>

<form action="process_register.php" method="post">
  Email: <input type="text" name="email"><br>

  <div class="tooltip">Password:
    <span class="tooltiptext">(must be 8-64 characters, and must contain at
                              least 1 letter, 1 number, and 1 symbol)</span>
  </div>

  <input type="password" name="password"><br>
  Confirm password: <input type="password" name="confirm_password"><br>
  <input type="submit" value="Submit">
</form><br>

<?php require "footer.php"; ?>
</body>
</html>