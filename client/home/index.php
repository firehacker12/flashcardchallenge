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
    <script defer src="https://use.fontawesome.com/releases/v5.14.0/js/all.js"></script>
    <script src="https://kit.fontawesome.com/4a43f383fb.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.0/css/bulma.min.css">
    <link href="../login/style.css" type="text/css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Ubuntu:wght@300&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.3.0/socket.io.dev.js"></script>
    <script src="socket.js"></script>
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
      </div>
    </nav>
    <br/><br/>
    <center><h1 class="is-size-1 has-text-weight-semibold">Welcome back, <?php echo $_SESSION['Username']; ?>!</h1></center>
    <br/><br/>
    <center>
      <p class="is-size-3">What would you like to play with your students?</p>
      <br/>
      <button class="button is-large" onclick="showPopup(0);">Flashcards</button>
      <button class="button is-large" onclick="showPopup(1);">Pictionary</button>
      <button class="button is-large" onclick="showPopup(2);">Quick Quiz</button>
      <button class="button is-large" onclick="showPopup(3);">Matching</button>
      <button class="button is-large" onclick="showPopup(4);">Test</button>
    </center>
    <div id="popupHTML">
      <div id="popup0" class="popup" style="">
        <br/>
        <center><h1 class="is-size-1">Flashcards</h1></center>
      </div>
    </div>
    <div id="overlay" style="display:none;" onclick="hidePopup();"></div>
  </body>
  <script>
    var activeIndex = 0;
    function showPopup(index) {
      activeIndex = index;
      document.getElementById("popup"+index).setAttribute("style","animation: showPopup .5s;animation-fill-mode: forwards;animation-timing-function: ease-out;");
      document.getElementById("overlay").setAttribute("style","animation: showPopupBody .5s;animation-fill-mode: forwards;animation-timing-function: ease-out;");
    }

    function hidePopup() {
      document.getElementById("popup"+activeIndex).setAttribute("style","animation: hidePopup .5s;animation-fill-mode: forwards;animation-timing-function: ease-out;");
      document.getElementById("overlay").setAttribute("style","animation: hidePopupBody .5s;animation-fill-mode: forwards;animation-timing-function: ease-out;");
      setInterval(() => {
        document.getElementById("popup"+activeIndex).setAttribute("style","");
        document.getElementById("overlay").setAttribute("style","display: none;");
      }, 2000);
    }
  </script>
  <style>
    button {
      margin: 25px;
      transition: margin .25s;
    }
    body {
      overflow: none;
    }
    button:hover {
      margin: 30px;
    }
    .popup {
      background-color: white;
      width: 50%;
      height: 70vh;
      position:absolute;
      top:-100vh;
      left:25%;
      z-index: 3;
    }
    @keyframes showPopup {
      from {
        top:-100vh;
      }
      to {
        top:15vh;
      }
    }

    .navbar {
      z-index: 1;
    }

    #overlay {
      z-index: 2;
      width: 100%;
      height: 110vh;
      position:absolute;
      top:-5em;
      left:0;
    }

    @keyframes showPopupBody {
      from {
        background-color: rgba(0,0,0,0);
      }
      to {
        background-color: rgba(0,0,0,.9);
      }
    }

    @keyframes hidePopup {
      from {
        top:15vh;
      }
      to {
        top:-100vh;
      }
    }

    @keyframes hidePopupBody {
      from {
        background-color: rgba(0,0,0,.9);
      }
      to {
        background-color: rgba(0,0,0,0);
      }
    }
  </style>
</html>
