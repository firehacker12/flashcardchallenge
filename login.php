<?php
  session_start();
  include "client/classes/init.php";

  $username = $_POST['username'];
  $password = $_POST['password'];

  $query = "SELECT * FROM login";

  $response = @mysqli_query($conn, $query);

  echo $username;

  if ($response) {
    while ($row = mysqli_fetch_array($response)) {
      if ($row['Username'] == $username || $row['Email'] == $username) {
        if (password_verify($password, $row['Password'])) {
          //Correct password
          $_SESSION['Username'] = $row['Username'];
        }
        else {
          echo "no, that's not right";
        }
      }
    }
  }
  else {
    echo "Error occured";
  }
?>
