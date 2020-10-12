<?php
  session_start();

  include "../classes/init.php";

  if (isset($_SESSION['Username'])) {
    $Author = $_SESSION['Username'];
    $query = "SELECT * FROM quizzes WHERE Author='$Author'";

    $response = @mysqli_query($conn, $query);

    if ($response) {
      while ($row = mysqli_fetch_array($response)) {
        echo $row['QuizDataJSON'] . "╪"; //6872
      }
    }
    else {
      echo "Error";
    }

    echo "╞"; //╞ 6854

    $query = "SELECT * FROM quizzes";

    $response = @mysqli_query($conn, $query);

    if ($response) {
      while ($row = mysqli_fetch_array($response)) {
        echo $row['QuizDataJSON'] . "╪";
      }
    }
    else {
      echo "Error";
    }
  }
  else {
    if (!empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS'])) {
      $uri = 'https://';
    } else {
      $uri = 'http://';
    }
    $uri .= $_SERVER['HTTP_HOST'];
    header("Location: ".$uri."/flashcardchallenge/client/login");
  }
?>
