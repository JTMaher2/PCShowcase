<!DOCTYPE html>
<html lang="en">
<head>
  <title>Send Password Reset Email</title>
  <meta charset="utf-8">
</head>

<body>
<?php
$address = $_POST["email"];
$subject = "PC Showcase Password Reset";

$message = "
<html>
<head>
<title>Password Reset</title>
</head>
<body>
Click <a href='localhost/process_password_reset.php?email=$address'>here</a>
to reset your PC Showcase password.<br>If you did not request for your
password to be reset, please ignore this message.
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
