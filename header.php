<?php
error_reporting(-1);
ini_set('display_errors', 'On');
set_error_handler("var_dump");

echo "<a href='index.php'>Home</a>";

if (isset($_SESSION["user"])) { // if logged in
  echo " | <a href='my_builds.php'>My Builds</a> |
        <a href='all_builds.php'>All Builds</a> |
        <a href='settings.php'>Settings</a> |
        <a href='logout.php'>Logout</a>";
}

echo "<br>";
?>
