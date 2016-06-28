<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>All Builds</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
</head>
<body>
<?php
session_start();

require "header.php";

echo "<div class='container'><div class='center jumbotron'>";

echo "<h3>All Builds</h3>";

$url = parse_url(getenv("CLEARDB_DATABASE_URL"));

$dsn = "mysql:host=" . $url["host"] . ";dbname=" . substr($url["path"], 1);
$username = $url["user"];
$password = $url["pass"];

try { // to display all builds
  $conn = new PDO($dsn, $username, $password);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $sql = "SELECT id, name FROM builds";

  foreach ($conn->query($sql) as $build) {
    echo "<a href='display_build.php?build_id=" . $build["id"] . "'>" .
         $build["name"] . "</a><br>";
  }

  $conn = null;
} catch (PDOException $e) {
  echo "Error: " . $e->getMessage();
}

echo "</div></div>";

require "footer.php";
?>
</body>
</html>
