<?php
  session_start();
  include "client/classes/init.php";

  $username = $_POST['username'];
  $email = $_POST['email'];
  $password = $_POST['password'];

  if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
    //Valid email
    $password = password_hash($password, PASSWORD_DEFAULT);
    $SELECT = "SELECT Username From login Where Username = ? Limit 1";
  	$INSERT = "INSERT Into login (Username, Email, Password) values(?, ?, ?)";
  	$stmt = $conn->prepare($SELECT);
  	$stmt->bind_param("s", $username);
  	$stmt->execute();
  	$stmt->bind_result($username);
  	$stmt->store_result();
  	$rnum = $stmt->num_rows();

    if ($rnum == 0) {
      $stmt->close();
      $stmt = $conn->prepare($INSERT);
      $stmt->bind_param("sss", $username, $email, $password);
      $stmt->execute();
      $_SESSION['Username'] = $username;

      if (!empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS'])) {
  			$uri = 'https://';
  		} else {
  			$uri = 'http://';
  		}
  		$uri .= $_SERVER['HTTP_HOST'];
  		header("Location: ".$uri."/flashcardchallenge/client/home");
    }
    else {
      $_SESSION['loginerror'] = "Username or Email taken!";
      if (!empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS'])) {
        $uri = 'https://';
      } else {
        $uri = 'http://';
      }
      $uri .= $_SERVER['HTTP_HOST'];
      header("Location: ".$uri."/flashcardchallenge/client/register");
    }
  }
  else {
    $_SESSION['loginerror'] = "Invalid email address!";
    if (!empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS'])) {
  		$uri = 'https://';
  	} else {
  		$uri = 'http://';
  	}
  	$uri .= $_SERVER['HTTP_HOST'];
  	header("Location: ".$uri."/flashcardchallenge/client/register");
  }
?>
