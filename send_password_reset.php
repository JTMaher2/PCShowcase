<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Send Password Reset Email</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
</head>
<body>
<?php
session_start();

require "header.php";

echo "<div class='container'>";

// do not allow guest to reset password
if ($_POST["email"] != 'guest@example.com') {
    // token is a hash of current timestamp
    $token = password_hash(date_timestamp_get(date_create()), PASSWORD_DEFAULT);

    store_token_in_db($token);

    $sendgrid = new SendGrid(getenv("SENDGRID_USERNAME"),
                               getenv("SENDGRID_PASSWORD"));

    $message = new SendGrid\Email();

    $message->addTo($_POST["email"])
            ->setFrom("jtmaher2@gmail.com")
            ->setSubject("PC Showcase Password Reset")
            ->setText("Click
                      http://pc-showcase.herokuapp.com/ask_for_new_password.php?email="
                      . $_POST["email"] . "&token=" . $token . " to reset your"
                      . " PC Showcase password. If you did not request for your"
                      " password to be reset, please ignore this message.")
            ->setHtml("<html>
                       <head>
                         <title>Password Reset</title>
                       </head>
                       <body>
                         Click <a href='http://pc-showcase.herokuapp.com/ask_for_new_password.php?email=" .
                                        $_POST["email"] . "&token=$token'>here</a>
                         to reset your PC Showcase password.<br>
                         If you did not request for your password to be reset,
                          please ignore this message.
                      </body>
                      </html>");

    // send email
    $sendgrid->send($message);

    echo "Please check your inbox for instructions on how to reset your password.<br>";
}

echo "</div>";

require "footer.php";

// store a security token in the users table
function store_token_in_db($token) {
  $url = parse_url(getenv("DATABASE_URL"));

  $dsn = "pgsql:host=" . $url["host"] . ";dbname=" . substr($url["path"], 1);
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
