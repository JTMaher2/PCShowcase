<!DOCTYPE html>
<html lang="en">
<head>
  <title>Process Password Change</title>
  <meta charset="utf-8">
</head>

<body>
<?php
  function look_up_token($email) {
    $servername = "localhost";
    $username = "root";
    $password = "password";
    $dbname = "myDB";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT token FROM users WHERE email=$email";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
      $token = $result->fetch_assoc()["token"];
    } else {
      $token = null;
    }

    $conn->close();

    return $token;
  }

  function change_password($email, $user_password) {
    $servername = "localhost";
    $username = "root";
    $db_password = "password";
    $dbname = "myDBPDO";

    try {
      $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $db_password);
      // set the PDO error mode to exception
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      // hash and salt the password
      $user_password = password_hash($user_password, PASSWORD_DEFAULT);

      $sql = "UPDATE users SET password='$user_password' WHERE email='$email'";

      // Prepare statement
      $stmt = $conn->prepare($sql);

      // execute the query
      $stmt->execute();

      // echo a message to say the UPDATE succeeded
      echo "Password successfully changed<br>
            <a href='index.php'>Home</a>";
    } catch(PDOException $e) {
      echo $sql . "<br>" . $e->getMessage();
    }

    $conn = null;
  }

  $email = $_POST["email"];
  $token = $_POST["token"];

  $db_token = look_up_token($email);

  if ($db_token != null && $db_token == $token) {
    // check that password matches confirmed password
    $password = $_POST["password"];
    $confirm = $_POST["confirm"];

    if ($password == $confirm) {
      // check if password is correct length
      if (strlen($user_password) < 8 || strlen($user_password) > 64) {
        echo "Password must be 8-64 characters<br>";
        echo "<a href='index.php'>Home</a>";
      } else {
        // check if password contains at least 1 letter, 1 number, and 1 symbol
        if (preg_match('/[a-zA-Z]/', $user_password) && preg_match('/\d/', $user_password)
            && preg_match('/\W/', $user_password)) {
              // it's safe to change password
              change_password($email, $password);
        } else {
          echo "Password must be 8-64 characters, and must contain at least 1
                letter, 1 number, and 1 symbol<br>
                <a href='ask_for_new_password.html?email=$email&token=$token'>Try Again</a>";
        }
    } else {
      echo "Password must match confirmed password.<br>
            <a href='ask_for_new_password.html?email=$email&token=$token'>Try Again</a>";
    }
  } else {
    echo "Error: invalid token.<br><a href='index.html'>Home</a>";
  }
?>
</body>

</html>
