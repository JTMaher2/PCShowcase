<?php
echo "<a href='index.php'>Home</a>";

if ($_SESSION["user"]) { // if logged in
  echo " | <a href='my_builds.php'>My Builds</a> |
        <a href='all_builds.php'>All Builds</a> |
        <a href='settings.php'>Settings</a> |
        <a href='logout.php'>Logout</a>";
}

echo "<br>";
?>
