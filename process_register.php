<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Process Register</title>
</head>
<body>

<?php
// store a security token in the users table
function store_token_in_db($token, $email) {
  $servername = "localhost";
  $username = "root";
  $password = "password";
  $dbname = "pcshowcase";

  try { // to add token to user record
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "UPDATE users SET token='$token' WHERE email='$email'";

    $stmt = $conn->prepare($sql);
    $stmt->execute();
  } catch(PDOException $e) {
    echo $sql . "<br>" . $e->getMessage();
  }

  $conn = null;
}

function send_activation_email($email) {
  // Generate a security token that can be used to verify the user's identity
  $date = date_create();
  $timestamp = date_timestamp_get($date);
  // token is a hash of current timestamp
  $token = password_hash($timestamp, PASSWORD_DEFAULT);

  store_token_in_db($token, $email);

  $subject = "Activate Your PC Showcase Account";

  $message = "
  <html>
  <head>
  <title>PC Showcase Account Activation</title>
  </head>
  <body>
  Click <a href='localhost/activate_account.php?email=" . $email . "&token=" .
  $token . "'>here</a> to activate your account.
  </body>
  </html>
  ";

  $headers = "MIME-Version: 1.0" . "\r\n";
  $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
  $headers .= 'From: <jtmaher2@gmail.com>' . "\r\n";

  // send email
  mail($address,$subject,$message,$headers);
}

function register_user($email, $user_password) {
  $servername = "localhost";
  $username = "root";
  $db_password = "password";
  $dbname = "pcshowcase";

  try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username,
                    $db_password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // hash and salt the password
    $hashed_user_password = password_hash($user_password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (email, password)
            VALUES ('$email', '$hashed_user_password')";
    $conn->exec($sql); // use exec() because no results are returned

    send_activation_email($email);

    echo "Please check your inbox for an email that contains instructions on
          how to activate your account.<br><a href='index.php'>Home</a>";
  }
  catch(PDOException $e) {
    echo $sql . "<br>" . $e->getMessage();
  }

  $conn = null;
}

// check if email address is already registered
function is_registered($email) {
  $servername = "localhost";
  $username = "root";
  $password = "password";
  $dbname = "pcshowcase";

  try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $sql = "SELECT count(1) FROM users WHERE email = '$email'";
    $stmt = $conn->query($sql);
    $result = $stmt->fetch()["count(1)"];
    $conn = null;
  } catch(PDOException $e) {
    echo $e->getMessage();
  }

  return $result; // 1 if already registered, 0 if not already registered
}

// check if submitted password is valid
function valid_password($password, $confirm_password) {
  $valid = false;

  // check if password matches confirmed password
  if (strcmp($password, $confirm_password) == 0) {
    // check if password is correct length
    if (strlen($password) >= 8 && strlen($password) <= 64) {
      // check if password contains at least 1 letter, 1 number, and 1 symbol
      if (preg_match('/[a-zA-Z]/', $password) &&
          preg_match('/\d/', $password) &&
          preg_match('/\W/', $password)) {
        $valid = true;
      } else {
        echo "Password must contain at least 1 letter, at least 1 number, and
              at least 1 symbol<br>";
      }
    } else {
      echo "Password must be 8-64 characters<br>";
    }
  } else { // password does not match confirm_password
    echo "Password and confirm password do not match<br>";
  }

  return $valid;
}

$email = $_POST["email"];

// do not proceed if email is already registered
if (is_registered($email)) {
  echo "That email address is already in use.<br>";
  echo "<a href='index.php'>Home</a>";
} else {
  // check for valid password
  $password = $_POST["password"];
  $confirm_password = $_POST["confirm_password"];

  if (valid_password($password, $confirm_password)) {
    register_user($email, $password); // safe to add new record to database
  } else {
    echo "<a href='register.html'>Back</a>";
  }
}
?>

</body>
</html>
