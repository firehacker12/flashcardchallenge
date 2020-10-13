<?php
  session_start();
  include "../classes/init.php";

  if (isset($_SESSION['Username'])) {
    $QuizJSON = $_POST['QuizJSON'];
    $QuizName = $_POST['QuizName'];
    $Author = $_SESSION['Username'];

    //echo $QuizJSON . " " . $QuizName . " " . $Author;

    $query = "INSERT INTO quizzes (Name, QuizDataJSON, Author) VALUES ('$QuizName', '$QuizJSON', '$Author')";

    $response = @mysqli_query($conn, $query);

    if ($response) {
      echo "Success";
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
