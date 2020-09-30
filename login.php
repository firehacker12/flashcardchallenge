<?php
  session_start();
  include "client/classes/init.php";

  $username = $_POST['username'];
  $password = $_POST['password'];

  $query = "SELECT * FROM login";

  $response = @mysqli_query($conn, $query);

  if ($response) {
    while ($row = mysqli_fetch_array($response)) {
      if ($row['Username'] == $username || $row['Email'] == $username) {
        if (password_verify($password, $row['Password'])) {
          //Correct password
          $_SESSION['Username'] = $row['Username'];

          if (!empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS'])) {
      			$uri = 'https://';
      		} else {
      			$uri = 'http://';
      		}
      		$uri .= $_SERVER['HTTP_HOST'];
      		header("Location: ".$uri."/flashcardchallenge/client/home");
        }
        else {
          $_SESSION['loginerror'] = "Incorrect Email or Password";
          if (!empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS'])) {
      			$uri = 'https://';
      		} else {
      			$uri = 'http://';
      		}
      		$uri .= $_SERVER['HTTP_HOST'];
      		header("Location: ".$uri."/flashcardchallenge/client/login");
        }
      }
      else {
        $_SESSION['loginerror'] = "Incorrect Email/Username or Password";
        if (!empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS'])) {
          $uri = 'https://';
        } else {
          $uri = 'http://';
        }
        $uri .= $_SERVER['HTTP_HOST'];
        header("Location: ".$uri."/flashcardchallenge/client/login");
      }
    }
  }
  else {
    echo "Error occured";
  }
?>
