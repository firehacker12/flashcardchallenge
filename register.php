<?php
  include "client/classes/init.php";

  $username = $_POST['username'];
  $email = $_POST['email'];
  $password = $_POST['password'];

  $password = password_hash($password, PASSWORD_DEFAULT);

  $query = "INSERT INTO login (Username, Email, Password) values('$username', '$email', '$password')";

  $response = @mysqli_query($conn, $query);

  if ($response) {
    echo "Successfully added query";
  }
  else {
    echo "Error occured";
  }
?>
