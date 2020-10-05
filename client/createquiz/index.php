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
            Home
          </a>
          <a class="navbar-item" href="../home">
            About Us
          </a>
        </div>
      </div>
    </nav>
    <br/><br/>
    <div style="">
      <br/>
      <center><h1 class="is-size-1">Make A Set</h1></center>
      <br/>
      <br/>
      <strong style="margin-left: 8%;" class="is-size-3 is-block has-text-dark">Set name</strong>
      <input class="input" type="text" id="setName" placeholder="Unnamed Set" style="margin-left: 8%;width:25%;" maxlength="40" />
      <hr style="background-color:rgba(0, 0, 0, .5);"/>
      <!-- Question 1 -->
      <div>
        <center><h1 class="is-size-2 has-text-weight-semibold has-text-info">Question 1</center></h1>
        <br/>
        <center><input class="input" style="width: 75%;text-align: center;" placeholder="Ask your question here..." /></center>
        <br/><br/>
        <div id="columns" style="width:100%;display:flex;">
          <div style="border-right: 2px solid rgba(0, 0, 0, .5);width:calc(50% - 2px);margin:0;display:inline-block;">
            <center><h1 class="is-size-3 has-text-success">Correct Answer <i class="fas fa-check has-text-success"></i></h1></center>
            <br/>
            <center>
              <textarea id="correctAnswer" style="width:50%;resize: none;text-align: center;" type="text" class="input" maxlength="50">Correct Answer</textarea>
            </center>
          </div><!--
          --><div style="width:calc(50% - 2px);margin:0;display:inline-block;">
            <center><h1 class="is-size-3 has-text-danger">Incorrect Answers <i class="fas fa-times has-text-danger"></i></h1></center>
            <br/>
            <center><div id="answerParent">
              <textarea id="incorrectAnswer" style="width:50%;resize: none;text-align: center;" type="text" class="input" maxlength="50">Incorrect Answer</textarea>
              <br/><br/>
            </div></center>
            <center><button id="addAnswerCorrect" class="button is-large customButton is-link is-outlined" onclick="addAnswer(0);">Add Answer <i class="fas fa-plus" style="margin-left: 10px;margin-top: 2px;"></i></button></center>
          </div>
        </div>
      </div>
    </div>
  </body>
  <script>
    function addAnswer(index) {
      var element = document.createElement("textarea");
      element.setAttribute("id","incorrectAnswer");
      element.setAttribute("style","width:50%;resize: none;text-align: center;");
      element.setAttribute("type","text");
      element.setAttribute("class","input");
      element.setAttribute("maxlength","50");
      element.setAttribute("placeholder","Incorrect Answer");
      document.querySelectorAll("#answerParent")[index].appendChild(element);
      var breaks = document.createElement("br");
      var breaks2 = document.createElement("br");
      document.querySelectorAll("#answerParent")[index].appendChild(breaks);
      document.querySelectorAll("#answerParent")[index].appendChild(breaks2);
    }
  </script>
  <style>
    .customButton {
      border-style: dashed;
    }

    .customButtonOther {
      border-style: solid;
    }
  </style>
</html>
