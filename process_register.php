<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Process Register</title>
</head>
<body>
<?php
session_start();

require "header.php";

$dsn = "mysql:dbname=pcshowcase;host=localhost";
$username = "root";
$password = "password";

try { // to add token to user record
  $conn = new PDO($dsn, $username, $password);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  // if the database does not already exist, create it
  if (!db_exists($conn)) {
    create_db($conn);
  }

  // do not proceed if email is already registered
  if (is_registered($_POST["email"], $conn)) {
    echo "That email address is already in use.<br>";
  } else {
    // check for valid password
    if (valid_password()) {
      register_user($conn); // safe to add new record to database
    } else {
      echo "Password must .<br>
            <a href='register.html'>Back</a>";
    }
  }

  $conn = null;
} catch(PDOException $e) {
  echo "Error: " . $e->getMessage();
}

require "footer.php";

// does the PC Showcase database already exist?
function db_exists($conn) {
  $sql = "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE
          SCHEMA_NAME = 'pcshowcase'";

  $stmt = $conn->prepare($sql);

  return $stmt->execute(); // 1 if exists, 0 otherwise
}

// create PC Showcase database
function create_db($conn) {
  $sql = "CREATE DATABASE pcshowcase";

  $conn->exec($sql);

  $sql = "CREATE TABLE users (email VARCHAR(254) PRIMARY KEY,
                              activated TINYINT(1),
                              password TEXT,
                              token TEXT,
                              num_builds INT(11) UNSIGNED)";

  $conn->exec($sql);

  $sql = "CREATE TABLE builds (id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY_KEY,
                               name TEXT,
                               owner VARCHAR(254))";

  $conn->exec($sql);

  $sql = "CREATE TABLE parts (id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY_KEY,
                              build_id INT(11) UNSIGNED,
                              type TEXT,
                              name TEXT)";

  $conn->exec($sql);
}

// check if submitted password is valid
function valid_password() {
  if (strcmp($_POST["password"], $_POST["confirm_password"]) == 0) {
    if (strlen($_POST["password"]) >= 8 && strlen($_POST["password"]) <= 64) {
      if (preg_match('/[a-zA-Z]/', $_POST["password"]) &&
          preg_match('/\d/', $_POST["password"]) &&
          preg_match('/\W/', $_POST["password"])) {
        return true;
      } else {
        echo "Password must contain at least 1 letter, 1 number, and 1
              symbol<br>";
      }
    } else {
      echo "Password must be between 8 and 64 characters";
    }
  } else {
    echo "Password and confirmed password do not match";
  }

  return false;
}

// store a security token in the users table
function store_token_in_db($token, $conn) {
  $sql = "UPDATE users SET token = :token WHERE email = :email";

  $stmt = $conn->prepare($sql,
                         array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
  $stmt->execute(array(":token" => $token, ":email" => $_POST["email"]));
}

// send activation instructions to user's email address
function send_activation_email($conn) {
  // token is hash of current timestamp
  $token = password_hash(date_timestamp_get(date_create()), PASSWORD_DEFAULT);

  store_token_in_db($token, $conn);

  $subject = "Activate Your PC Showcase Account";

  $message = "<html>
              <head>
                <title>PC Showcase Account Activation</title>
              </head>
              <body>
                Click <a href='localhost/activate_account.php?email=" .
                               $_POST["email"] . "&token=$token'>here</a> to
                activate your account.
              </body>
              </html>";

  $headers = "MIME-Version: 1.0\r\nContent-type:text/html;charset=UTF-8\r\nFrom:
              <jtmaher2@gmail.com>\r\n";

  mail($_POST["email"], $subject, $message, $headers); // send
}

// put user in database
function register_user($conn) {
  // hash and salt the password
  $hashed_password = password_hash($_POST["password"], PASSWORD_DEFAULT);

  $sql = "INSERT INTO users (email, password) VALUES (:email, :password)";

  $stmt = $conn->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
  $stmt->execute(array(":email" => $_POST["email"],
                       ":password" => $hashed_password));

  send_activation_email($conn);

  echo "Please check your inbox for an email that contains instructions on
        how to activate your account.<br>";
}

// check if email address is already registered
function is_registered($email, $conn) {
  $sql = "SELECT count(1) FROM users WHERE email = :email";

  $stmt = $conn->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
  $stmt->execute(array(":email" => $_POST["email"]));

  // 1 if already registered, 0 if not already registered
  return $stmt->fetch()["count(1)"];
}
?>
</body>
</html>
