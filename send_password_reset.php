<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Send Password Reset Email</title>
</head>
<body>
<?php
session_start();

require "header.php";

if (isset($_SESSION["user"])) {
    // do not allow guest to reset password
    if ($_SESSION["user"] != 'guest@example.com') {
        // token is a hash of current timestamp
        $token = password_hash(date_timestamp_get(date_create()), PASSWORD_DEFAULT);

        store_token_in_db($token);

        $subject = "PC Showcase Password Reset";

        $message = "<html>
                    <head>
                      <title>Password Reset</title>
                    </head>
                    <body>
                      Click <a href='localhost/ask_for_new_password.php?email=" .
                                     $_POST["email"] . "&token=$token'>here</a>
                      to reset your PC Showcase password.<br>
                      If you did not request for your password to be reset, please
                      ignore this message.
                    </body>
                    </html>";

        $headers = "MIME-Version: 1.0\r\nContent-type:text/html;charset=UTF-8\r\nFrom:
                    <jtmaher2@gmail.com>\r\n";

        // send email
        mail($_POST["email"], $subject, $message, $headers);

        echo "Please check your inbox for instructions on how to reset your password.
              <br>";
  }
}

require "footer.php";

// store a security token in the users table
function store_token_in_db($token) {
  $url = parse_url(getenv("CLEARDB_DATABASE_URL"));

  $dsn = "mysql:host=" . $url["host"] . ";dbname=" . substr($url["path"], 1);
  $username = $url["user"];
  $password = $url["pass"];

  try { // to add token to user record
    $conn = new PDO($dsn, $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "UPDATE users SET token = :token WHERE email = :email";

    $stmt = $conn->prepare($sql,
                           array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $stmt->execute(array(":token" => $token, ":email" => $_POST["email"]));

    $conn = null;
  } catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
  }
}
?>
</body>
</html>
