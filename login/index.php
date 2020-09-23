<?php

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
    <nav class="navbar has-shadow" role="navigation" aria-label="main navigation">
      <div class="navbar-brand">
        <img src="../img/logo.png" style="margin-left:15px;margin-top:5px;width:64px;height:64px;" >

        <a role="button" id="burger" class="navbar-burger burger" aria-label="menu" aria-expanded="false" data-target="navbar">
          <span aria-hidden="true"></span>
          <span aria-hidden="true"></span>
          <span aria-hidden="true"></span>
        </a>
      </div>

      <div id="navbar" class="navbar-menu" style="margin-left: 25px;">
        <div class="navbar-start">
          <a class="navbar-item" href="../home">
            Home
          </a>

          <a class="navbar-item" href="../aboutus">
            About Us
          </a>

          <div class="navbar-item has-dropdown is-hoverable">
            <a class="navbar-link">
              More
            </a>

            <div class="navbar-dropdown">
              <a class="navbar-item">
                About
              </a>
              <a class="navbar-item">
                Jobs
              </a>
              <a class="navbar-item">
                Contact
              </a>
              <hr class="navbar-divider">
              <a class="navbar-item">
                Report an issue
              </a>
            </div>
          </div>
        </div>

        <div class="navbar-end">
          <div class="navbar-item">
            <div class="buttons">
              <a class="button is-primary">
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
      <div style="position:absolute;top:0;left:0;">
        <img src="../img/classroomimage.jpg" style="width:100%;object-fit:cover;object-position:0 75;height:75vh;filter: brightness(20%);z-index:-1;" />
      </div>
      <div style="position:absolute;top:0;left:0;">
        <h1 class="has-text-centered" style="z-index: 1;">Quizzard</h1>
        <h2>Study tool of the future</h2>
      </div>
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
