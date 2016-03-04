<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Send Password Reset Email</title>
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

// Generate a security token that can be used to verify the user's identity
$date = date_create();
$timestamp = date_timestamp_get($date);
// token is a hash of current timestamp
$token = password_hash($timestamp, PASSWORD_DEFAULT);

$address = $_POST["email"];
store_token_in_db($token, $address);

$subject = "PC Showcase Password Reset";

$message = "
<html>
<head>
<title>Password Reset</title>
</head>
<body>
Click <a href='localhost/ask_for_new_password.php?email=" . $address .
"&token=" . $token . "'>here</a> to reset your PC Showcase password.<br>
If you did not request for your password to be reset, please ignore this
message.
</body>
</html>
";

$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
$headers .= 'From: <jtmaher2@gmail.com>' . "\r\n";

// send email
mail($address,$subject,$message,$headers);

echo "Please check your inbox for instructions on how to reset your password.<br>" .
"<a href='index.php'>Home</a>";
?>
</body>

</html>
