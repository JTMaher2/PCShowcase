<!DOCTYPE html>
<html lang="en">
<head>
  <title>Process Password Change</title>
  <meta charset="utf-8">
</head>
<body>
<?php
session_start();

require "header.php";

if (isset($_SESSION["user"])) {
    // do not allow guest to change password
    if ($_SESSION["user"] != 'guest@example.com') {
        $url = parse_url(getenv("CLEARDB_DATABASE_URL"));

        $dsn = "mysql:host=" . $url["host"] . ";dbname=" . substr($url["path"], 1);
        $username = $url["user"];
        $password = $url["pass"];

        try {
          $conn = new PDO($dsn, $username, $password);
          $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

          validate_password($conn);

          $conn = null;
        } catch (PDOException $e) {
          echo "Error: " . $e->getMessage();
        }
    }
}

require "footer.php";

// verify submitted token and password
function validate_password($conn) {
  $stored_token = get_token($conn);

  if ($stored_token != null && $stored_token == $_POST["token"]) {
    // check that password matches confirmed password
    if ($_POST["password"] == $_POST["confirm"]) {
      // check if password is correct length
      if (strlen($_POST["password"]) < 8 || strlen($_POST["password"]) > 64) {
        echo "Password must be 8-64 characters<br>";
        echo "<a href='index.php'>Home</a>";
      } else {
        // check if password contains at least 1 letter, 1 number, and 1 symbol
        if (preg_match('/[a-zA-Z]/', $_POST["password"])
            && preg_match('/\d/', $_POST["password"])
            && preg_match('/\W/', $_POST["password"])) {
          // it's safe to change password
          change_password($conn);
        } else {
          echo "Password must be 8-64 characters, and must contain at least 1
                letter, 1 number, and 1 symbol<br>
                <a href='ask_for_new_password.html?email=" . $_POST["email"] .
                "&token=" . $_POST["token"] . "'>Back</a>";
        }
      }
    } else {
      echo "Password must match confirmed password.<br>
      <a href='ask_for_new_password.html?email=" . $_POST["email"] .
      "&token=" . $_POST["token"] . "'>Back</a>";
    }
  } else {
    echo "Error: invalid token<br>";
  }
}

// retrieve user's security token
function get_token($conn) {
  $sql = "SELECT token FROM users WHERE email = :email";

  $stmt = $conn->prepare($sql,
                         array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
  $stmt->execute(array(":email" => $_POST["email"]));

  return $stmt->fetch()["token"];
}

// change user's password
function change_password($conn) {
  // hash and salt the password
  $hashed_password = password_hash($_POST["password"], PASSWORD_DEFAULT);

  $sql = "UPDATE users SET password = :password, token = '' WHERE email =
          :email";

  $stmt = $conn->prepare($sql,
                         array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
  $stmt->execute(array(":password" => $hashed_password,
                       ":email" => $_POST["email"]));

  echo "Password successfully changed<br>";
}
?>
</body>
</html>
