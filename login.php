<?php
  include "client/classes/init.php";

  $username = $_POST['username'];
  $password = $_POST['password'];

  $password = password_hash($password, PASSWORD_DEFAULT);

  $query = "SELECT * FROM login WHERE Username='$username'";

  $response = @mysqli_query($conn, $query);

  if ($response) {
    while ($row = mysqli_fetch_array($response)) {
      if ($row['Username'] == $username || $row['Email'] == $username) {
        echo "Found username";
      }
    }
  }
  else {
    echo "Error occured";
  }
?>
