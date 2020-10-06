<?php
  session_start();

  if (isset($_SESSION['Username'])) {
    $Username = $_SESSION['Username'];
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
        <center><strong class="is-size-3">Settings</strong></center>
      </div>
      <div id="popup1" class="popup" style="">
        <br/>
        <center><h1 class="is-size-1">Pictionary</h1></center>
        <br/>
        <span style="margin-left: 12.5%"></span><strong class="is-size-3">Settings</strong>
        <hr/>
        <br/>
        <div style="width:75%;position:relative;left:12.5%;">
          <div class="columns">
            <div class="column">
              <p class="is-size-5">Time to draw</p>
              <select class="select">
                <option>30 seconds</option>
                <option>60 seconds</option>
                <option>90 seconds</option>
                <option>120 seconds</option>
              </select>
              <br/><br/>
              <p class="is-size-5">Room Name</p>
              <input id="roomname1" class="input" placeholder="My Room" />
              <strong class="is-size-6 has-text-grey">*Optional</strong>
            </div>
            <div class="column">
              <p class="is-size-5">Rounds</p>
              <select class="select">
                <option>1</option>
                <option>3</option>
                <option>5</option>
                <option>Custom</option>
              </select>
            </div>
          </div>
          <br/>
        </div>
        <span style="margin-left: 12.5%"></span><strong class="is-size-3">Search</strong>
        <hr/>
        <div style="width:75%;position:relative;left:12.5%;">
          <div style="margin-bottom: 5px;display:flex;">
            <p class="is-size-6 has-text-weight-semibold is-inline" style="align-self: center;padding-right: 10px;">Search For Quizzes</p>
            <select class="select">
              <option>My Quizzes</option>
              <option>My Favorite Quizzes</option>
              <option>Top Quizzes</option>
            </select>
          </div>
          <input class="input" placeholder="Steve Jobs, United States, Algebra, Etc" />
          <br/><br/>
          <div style="display:flex;overflow-x: scroll;">
            <img class="previewImage" style="border-left:1px solid rgba(0, 0, 0, .5);" src="https://bulma.io/images/placeholders/600x480.png" /><!--
            --><img class="previewImage" src="https://bulma.io/images/placeholders/128x128.png" /><!--
            --><img class="previewImage" src="https://bulma.io/images/placeholders/128x128.png" /><!--
            --><img class="previewImage" src="https://bulma.io/images/placeholders/128x128.png" /><!--
            --><img class="previewImage" src="https://bulma.io/images/placeholders/128x128.png" /><!--
            --><img class="previewImage" src="https://bulma.io/images/placeholders/128x128.png" /><!--
        --></div>
          <center>
            <a href="../createquiz"><button class="button notmoving is-medium is-warning">Create A Quiz</button></a>
            <button class="button notmoving is-medium is-danger" id="startButton1" onclick="roomMake();">Create Room</button>
          </center>
          </center>
        </div>
      </div>
      <div id="popup2" class="popup" style="">
        <br/>
        <center><h1 class="is-size-1">Quick Quiz</h1></center>
      </div>
      <div id="popup3" class="popup" style="">
        <br/>
        <center><h1 class="is-size-1">Matching</h1></center>
      </div>
      <div id="popup4" class="popup" style="">
        <br/>
        <center><h1 class="is-size-1">Test</h1></center>
      </div>
    </div>
    <div id="overlay" style="display:none;" onclick="hidePopup();"></div>
    <span style="display:none;" id="teacherName"><?php echo $Username; ?></span>
  </body>
  <script>
    let teacherName = document.getElementById('teacherName').innerHTML;
    for (var i=0; i<5; i++) {
      if(document.getElementById('roomname'+i) != undefined){
        document.getElementById("roomname"+i).setAttribute("placeholder",teacherName + "'s Room");
      }
    }

    var activeIndex = 0;
    function showPopup(index) {
      activeIndex = index;
      document.getElementById("popup"+index).setAttribute("style","animation: showPopup .35s;animation-fill-mode: forwards;animation-timing-function: ease-out;");
      document.getElementById("overlay").setAttribute("style","animation: showPopupBody .35s;animation-fill-mode: forwards;animation-timing-function: ease-out;");
    }

    function hidePopup() {
      document.getElementById("popup"+activeIndex).setAttribute("style","animation: hidePopup .35s;animation-fill-mode: forwards;animation-timing-function: ease-out;");
      document.getElementById("overlay").setAttribute("style","animation: hidePopupBody .35s;animation-fill-mode: forwards;animation-timing-function: ease-out;");
      setTimeout(() => {
        document.getElementById("popup"+activeIndex).setAttribute("style","display:none;");
        document.getElementById("overlay").setAttribute("style","display: none;");
      }, 500);
    }

    var burger = document.getElementById("burger");
    var nav = document.getElementById("navbar");
    burger.addEventListener('click', () => {
      burger.classList.toggle('is-active');
      nav.classList.toggle('is-active');
    });
  </script>
  <style>
    button {
      margin: 25px;
      transition: margin .25s;
    }
    html, body {
      overflow-y: hidden;
    }
    .previewImage {
      width:calc(20% - 0px);
      border-right:1px solid rgba(0, 0, 0, .5);
    }
    button:not(.notmoving):hover {
      margin: 30px;
    }
    .popup {
      overflow-y: scroll;
      background-color: white;
      width: 50%;
      height: 90vh;
      position:absolute;
      top:-100vh;
      left:25%;
      z-index: 3;
      border-radius: 10px;
    }
    @keyframes showPopup {
      from {
        top:-100vh;
      }
      to {
        top:5vh;
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
        top:5vh;
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
