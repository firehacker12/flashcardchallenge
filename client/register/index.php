<?php
  session_start();
  if (isset($_SESSION['loginerror'])) {
    if ($_SESSION['loginerror'] == "Incorrect Email or Password") {
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
  </head>
  <body>
    <div>
      <!--<div style="position:absolute;top:5em;left:0;">
        <img src="../img/classroomimage.jpg" style="width:100%;object-fit:cover;object-position:0 75;height:75vh;z-index:-1;filter: brightness(20%);" />
      </div>
      <div style="position:absolute;top:5em;left:0;width:100%;">
        <h1 class="textShadow has-text-centered has-text-white has-text-weight-semibold" style="z-index: 1;font-size: 8em;">Quizzard</h1>
        <h2 class="textShadow has-text-centered has-text-white has-text-weight-semibold" style="z-index: 1;font-size: 4em;padding-bottom:50px;">Study tool of the future</h2>
        <hr/>
        <div class="columns">
          <div class="column has-text-centered">
            <h1 class="is-size-1 has-text-white textShadow">Educational!</h1>
          </div>
          <div class="column has-text-centered">
            <h1 class="is-size-1 has-text-white textShadow">Fun!</h1>
          </div>
          <div class="column has-text-centered">
            <h1 class="is-size-1 has-text-white textShadow">Competitive!</h1>
          </div>
        </div>
      </div>-->
      <form action="../../register.php" method="POST">
        <input placeholder="Username" type="text" name="username" maxlength="100" />
        <input placeholder="Email" type="text" name="email" maxlength="100" />
        <input placeholder="Password" type="password" name="password" />
        <input type="submit" placeholder="Register" />
      </form>
      <p>or <a href="../login">login here</a></p>
      <?php echo "<p style='color:red;'>$loginError</p>"; ?>
    </div>
  </body>
  <script>
    var burger = document.getElementById("burger");
    var nav = document.getElementById("navbar");
    burger.addEventListener('click', () => {
      burger.classList.toggle('is-active');
      nav.classList.toggle('is-active');
    });
  </script>
</html>
