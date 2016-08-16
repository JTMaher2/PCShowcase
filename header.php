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
echo "<a href='https://github.com/JTMaher2/PCShowcase'>
        <img style='position: absolute; top: 0; right: 0; border: 0;'
             src='https://camo.githubusercontent.com/365986a132ccd6a44c23a9169022c0b5c890c387/68747470733a2f2f73332e616d617a6f6e6177732e636f6d2f6769746875622f726962626f6e732f666f726b6d655f72696768745f7265645f6161303030302e706e67'
             alt='Fork me on GitHub'
             data-canonical-src='https://s3.amazonaws.com/github/ribbons/forkme_right_red_aa0000.png'>
      </a>";
echo "</ul></nav></div></header><br><br><br>";
?>
