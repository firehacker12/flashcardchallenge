<?php
  session_start();

  if (isset($_SESSION['Username'])) {

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

<html>
  <head>
    <title>Home</title>
  </head>
  <body>
    <h1>Welcome back, <?php echo $_SESSION['Username']; ?>!</h1>
  </body>
</html>
