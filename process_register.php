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
require "vendor/autoload.php"; # for SendGrid

create_db_if_not_exists();

// pcshowcase DB exists, so select it
$url = parse_url(getenv("CLEARDB_DATABASE_URL"));

$dsn = "mysql:host=" . $url["host"] . ";dbname=" . substr($url["path"], 1);
$username = $url["user"];
$password = $url["pass"];

try { // to add token to user record
  $conn = new PDO($dsn, $username, $password);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

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

// create PC Showcase database if it doesn't already exist
function create_db_if_not_exists() {
  // since pcshowcase might not exist, select information_schema
  $url = parse_url(getenv("CLEARDB_DATABASE_URL"));

  $dsn = "mysql:host=" . $url["host"] . ";dbname=" . substr($url["path"], 1);
  $username = $url["user"];
  $password = $url["pass"];

  try { // to see if pcshowcase DB already exists
    $conn = new PDO($dsn, $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "CREATE DATABASE IF NOT EXISTS pcshowcase";

    $created = $conn->exec($sql);

    // only make tables if the DB was just created
    if ($created) {
      $sql = "CREATE TABLE pcshowcase.users (email VARCHAR(254) PRIMARY KEY,
                                  activated TINYINT(1),
                                  password TEXT,
                                  token TEXT,
                                  num_builds INT(11) UNSIGNED)";

      $conn->exec($sql);

      $sql = "CREATE TABLE pcshowcase.builds (id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                                   name TEXT,
                                   owner VARCHAR(254))";

      $conn->exec($sql);

      $sql = "CREATE TABLE pcshowcase.parts (id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                                  build_id INT(11) UNSIGNED,
                                  type TEXT,
                                  manufacturer TEXT,
                                  name TEXT,
                                  qty INT(11) UNSIGNED)";

      $conn->exec($sql);
    }

    $conn = null;
  } catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
  }
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

  // use SendGrid to send email
  $sendgrid = new SendGrid(getenv("SENDGRID_USERNAME"),
                           getenv("SENDGRID_PASSWORD"));

  $message = new SendGrid\Email();
  $message->addTo($_POST["email"])
        ->setFrom("jtmaher2@gmail.com")
        ->setSubject("Activate Your PC Showcase Account")
        ->setText("Visit this link to activate your account:
                  http://pc-showcase.herokuapp.com/activate_account.php?email=" .
                  $_POST["email"] . "&token=$token")
        ->setHtml("<html>
                   <head>
                   <title>PC Showcase Account Activation</title>
                   </head>
                   <body>
                       Click
                       <a href='http://pc-showcase.herokuapp.com/activate_account.php?email=" .
                                $_POST["email"] . "&token=$token'>here</a>
                       to activate your account.
                   </body>
                   </html>");

  $sendgrid->send($message);
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
