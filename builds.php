<?php
// Start the session
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>My PC Builds</title>
</head>
<body>

<?php
// if user is logged in
if ($_SESSION["user"] != null) {
  echo "<a href='logout.php'>Logout</a>"; // logout link

  echo "<h3>Builds</h3>";

  // get builds from DB
  $dsn = "mysql:dbname=pcshowcase;host=localhost";
  $username = "root";
  $password = "password";

  try {
    // Create connection
    $dbh = new PDO($dsn, $username, $password);
    // set the PDO error mode to exception
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT id, name
        FROM builds
        WHERE owner = :email";

    $sth = $dbh->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $sth->execute(array(":email" => $_SESSION["user"]));

    if ($sth->rowCount() > 0) {
      echo "<table border='1'><tr><th>Name</th><th>Modify</th></tr>";
      // output data of each row
      while($row = $sth->fetch()) {
        echo "<tr><td><a href='display_build.php?build_id=" . $row["id"] . "'>"
             . $row["name"] . "</a></td>
             <td><form action='remove_build.php'>
             <input type='hidden' name='build_id' value='" . $row["id"] . "'>
             <input type='submit' value='X'>
             </form>
             <form action='edit_build.php'>
             <input type='hidden' name='build_id' value='" . $row["id"] . "'>
             <input type='submit' value='Edit'>
             </form></td></tr>";
      }
      echo "</table><br>";
    } else {
      echo "You do not have any builds.<br><br>";
    }

    $conn = null;
  } catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
  }

  echo "<strong>New build:</strong>
        <form action='new_build.php'>
        Name: <input type='text' name='name'><br>
        <input type='submit' value='Create'>
        </form>";
} else {
  echo "You are not logged in.<br>
        <a href='index.php'>Home</a>";
}
?>

</body>
</html>
