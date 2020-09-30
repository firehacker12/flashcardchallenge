<?php
  session_start();
  if (isset($_SESSION['loginerror'])) {
    if ($_SESSION['loginerror'] == "Username or Email taken!") {
      $_SESSION['loginerror'] = "";
      $loginError = "";
    }
    else {
      $loginError = $_SESSION['loginerror'];
    }
  }
  else {
    $_SESSION['loginerror'] = "";
    $loginError = "";
  }
?>

<html>
  <head>
    <title>Quizzard</title>
    <script defer src="https://use.fontawesome.com/releases/v5.14.0/js/all.js"></script>
    <script src="https://kit.fontawesome.com/4a43f383fb.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.0/css/bulma.min.css">
    <link href="style.css" type="text/css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Ubuntu:wght@300&display=swap" rel="stylesheet">
  </head>
  <body>
    <nav class="navbar" role="navigation" aria-label="main navigation">
      <div class="navbar-brand">
        <img src="../img/logo.png" style="margin-left:15px;margin-top:5px;width:128px;height:64px;" >

        <a role="button" id="burger" class="navbar-burger burger" aria-label="menu" aria-expanded="false" data-target="navbar">
          <span aria-hidden="true"></span>
          <span aria-hidden="true"></span>
          <span aria-hidden="true"></span>
        </a>
      </div>

      <div id="navbar" class="navbar-menu" style="margin-left: 25px;">
        <div class="navbar-start">
          <a class="navbar-item" href="../aboutus">
            About Us
          </a>
        </div>

        <div class="navbar-end">
          <div class="navbar-item">
            <div class="buttons">
              <a class="button is-primary" style="background-color: rgb(104,64,146);">
                <strong>Sign up</strong>
              </a>
              <a class="button is-light">
                Log in
              </a>
            </div>
          </div>
        </div>
      </div>
    </nav>
    <div>
      <div style="background-color: white;width:40%;position:absolute;left:30%;top:20vh;border-radius: 10px;">
        <br/>
        <center><h1 class="is-size-2">Login to Quizzard</h1></center>
        <br/>
        <form action="../../login.php" method="POST">
          <div style="position:relative;left:10%;">
            <p class="is-size-5">Username or Email</p>
            <span style="position:absolute;font-size: 25px;padding: 6.5px;"><i class="fas fa-user"></i></span><input type="text" required name="username" maxlength="100" style="width:80%;font-size:1.5em;border-radius: 5px;border-style:solid;" />
          </div>
          <br/>
          <div style="position:relative;left:10%;">
            <p class="is-size-5">Password</p>
            <span style="position:absolute;font-size: 25px;padding: 6.5px;"><i class="fas fa-lock"></i></span><input type="password" required name="password" style="width:80%;font-size:1.5em;border-radius: 5px;border-style:solid;" />
          </div>
          <br/><br/>
          <center><input type="submit" placeholder="Login" style="padding: 10px 20px 10px 20px;background-color: rgb(104,64,146);border-radius: 20px;border-style: none;text-align:center;color:white;font-size: 1em;" /></center>
        </form>
        <center><p>Don't have an account? <a href="../register">Register here</a></p></center>
        <center><?php if ($loginError != "") {echo "<p style='color:red;position:relative;top:50px;'>$loginError</p>";} else {echo "<br/>";}?></center>
      </div>
      <br/>
    </div>
  </body>
  <style>
  body {
    background-color: rgb(230, 230, 230);
  }

  h1, h2, h3, h4, h5, h6 {
    font-family: 'Ubuntu', sans-serif;
  }

  input:not([type="submit"]) {
    padding-left: 40px;
    transition: all .2s;
  }
  input:not([type="submit"]):hover {
    border-color: black;
  }

  input[type="submit"] {
    cursor: pointer;
    min-width: 125px;
    transition: all .5s;
  }

  input[type="submit"]:hover {
    min-width: 160px;
  }
  </style>
  <script>
    var burger = document.getElementById("burger");
    var nav = document.getElementById("navbar");
    burger.addEventListener('click', () => {
      burger.classList.toggle('is-active');
      nav.classList.toggle('is-active');
    });
  </script>
</html>
