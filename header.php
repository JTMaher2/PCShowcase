<?php
error_reporting(-1);
ini_set('display_errors', 'On');
set_error_handler("var_dump");

echo "<header class='navbar navbar-fixed-top navbar-inverse'>
        <div class='container'>
          <nav>
            <ul class='nav navbar-nav navbar-right'>
              <li><a href='index.php'>Home</a></li>";

if (isset($_SESSION["user"])) { // if logged in
  echo "<li><a href='my_builds.php'>My Builds</a></li>
        <li><a href='all_builds.php'>All Builds</a></li>
        <li><a href='settings.php'>Settings</a></li>
        <li><a href='logout.php'>Logout</a></li>";
}

echo "</ul></nav></div></header>";
?>
