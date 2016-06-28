<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Register</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
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
<div class="container">
<h3>Register</h3>

<form action="process_register.php" method="post">
  Email: <input type="text" name="email"><br>

  <div class="tooltip">Password:
    <span class="tooltiptext">(must be 8-64 characters, and must contain at
                              least 1 letter, 1 number, and 1 symbol)</span>
  </div>

  <input type="password" name="password"><br>
  Confirm password: <input type="password" name="confirm_password"><br>
  <input type="submit" value="Submit" class="btn btn-large btn-primary">
</form><br>

</div>
<?php require "footer.php"; ?>
</body>
</html>
