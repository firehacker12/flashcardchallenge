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
          <a class="navbar-item" href="../home">
            Home
          </a>
          <a class="navbar-item" href="../aboutus">
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
      <div id="questions">
        <!-- Question 1 -->
        <div id="question">
          <div id='questionHide' class="questionHide" onclick="toggleQuestion(0);" style=''>
            <center><h1 id="questionName" class="is-size-2 has-text-weight-semibold has-text-info">Question 1 <i id="caret" class="fas fa-caret-down"></i></center></h1>
          </div>
          <div id="questionInfo">
            <br/>
            <center><input class="input" id="setQuestionName" value="Question 1" style="width: 75%;text-align: center;" placeholder="Ask your question here..." /></center>
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
      </div>
      <br/><br/>
    </div>
    <center><hr style="background-color: black;width:50%;"/></center>
    <div id="submitForm" style="display:none;" class="popup">
      <br/>
      <center><h1 class="is-size-1">Submit Form</h1></center>
      <br/><br/><br/><br/><br/><br/>
      <center><p class="is-size-5">Are you sure you are ready to submit?</p></center>
      <br/>
      <center><p id="setNameForm" class="is-size-3"><strong></strong>Unnamed Set</p></center>
      <br/>
      <center>
        <button id="addQuestion" class="button is-large customButton is-danger" onclick="exitSubmitForm();" style="margin-right: 7.5px;min-width: 300px;">No, take me back!</button>
        <button id="addQuestion" class="button is-large customButton is-success" onclick="submitForm();" style="margin-left: 7.5px;min-width: 300px;">Yes, submit it!</button>
      </center>
    </div>
    <div id="overlay" onclick="exitSubmitForm();" style="display:none;"></div>
    <center><button id="addQuestion" class="button is-large customButton is-info is-outlined" onclick="addQuestion();">Add Question <i class="fas fa-plus" style="margin-left: 10px;margin-top: 2px;"></i></button></center>
    <br/>
    <center><button id="addQuestion" class="button is-large customButton is-warning" onclick="prepareSubmitForm();">Finish set <i class="fas fa-check-square" style="margin-left: 10px;margin-top: 2px;"></i></button></center>
    <br/><br/>
  </body>
  <script>
    function prepareSubmitForm() {
      document.getElementById("submitForm").setAttribute("style","animation: showPopup .35s;animation-fill-mode: forwards;");
      document.getElementById("overlay").setAttribute("style","animation: showPopupOverlay .35s;animation-fill-mode: forwards;");
      if (document.getElementById("setName").value.length < 1) {
        document.getElementById("setNameForm").innerHTML = "Unnamed Set";
      }
      else {
        document.getElementById("setNameForm").innerHTML = document.getElementById("setName").value;
      }
    }
    function exitSubmitForm() {
      document.getElementById("submitForm").setAttribute("style","animation: hidePopup .35s;animation-fill-mode: forwards;");
      document.getElementById("overlay").setAttribute("style","animation: hidePopupOverlay .35s;animation-fill-mode: forwards;");
      setTimeout(() => {
        document.getElementById("overlay").setAttribute("style","display:none;");
      }, 350);
    }
    function submitForm() {

    }
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

    function addQuestion() {
      var questionLength = document.querySelectorAll("#question").length+1;
      var questionNode = document.createElement("div");
      questionNode.setAttribute("id","question");
      var questionHTML = "<div id='questionHide' class='questionHide' onclick='toggleQuestion("+(questionLength-1)+");' style=''><center><h1 id='questionName' class='is-size-2 has-text-weight-semibold has-text-info'>Question "+(questionLength)+"<i id='caret' class='fas fa-caret-down'></i></center></h1></div><div id='questionInfo'><br/><center><input id='setQuestionName' value='Question "+(questionLength)+"'  class='input' style='width: 75%;text-align: center;' placeholder='Ask your question here...' /></center><br/><br/><div id='columns' style='width:100%;display:flex;'><div style='border-right: 2px solid rgba(0, 0, 0, .5);width:calc(50% - 2px);margin:0;display:inline-block;'><center><h1 class='is-size-3 has-text-success'>Correct Answer <i class='fas fa-check has-text-success'></i></h1></center><br/><center><textarea id='correctAnswer' style='width:50%;resize: none;text-align: center;' type='text' class='input' maxlength='50'>Correct Answer</textarea></center></div><!----><div style='width:calc(50% - 2px);margin:0;display:inline-block;'><center><h1 class='is-size-3 has-text-danger'>Incorrect Answers <i class='fas fa-times has-text-danger'></i></h1></center><br/><center><div id='answerParent'><textarea id='incorrectAnswer' style='width:50%;resize: none;text-align: center;' type='text' class='input' maxlength='50'>Incorrect Answer</textarea><br/><br/></div></center><center><button id='addAnswerCorrect' class='button is-large customButton is-link is-outlined' onclick='addAnswer("+(questionLength-1)+");'>Add Answer <i class='fas fa-plus' style='margin-left: 10px;margin-top: 2px;'></i></button></center></div></div><br/><center><button id='addAnswerCorrect' class='button is-large is-danger' onclick='removeQuestion("+questionLength+");'>Remove Question <i class='fas fa-trash-alt' style='margin-left: 10px;margin-top: 2px;'></i></button></center><br/></div>";
      questionNode.innerHTML = questionHTML;
      //document.getElementById("questions").innerHTML += questionHTML;
      var hr = document.createElement("hr");
      hr.setAttribute("style","margin:0;padding:0;");
      document.getElementById("questions").appendChild(hr);
      document.getElementById("questions").appendChild(questionNode);

      hideQuestion(questionLength-2);
    }

    function showQuestion(index) {
      document.querySelectorAll("#questionInfo")[index].setAttribute("style","");
      //document.querySelectorAll("#questionHide")[index].setAttribute("style","display:none;");
      document.querySelectorAll("#caret")[index].setAttribute("class", "fas fa-caret-down");
    }

    function hideQuestion(index) {
      document.querySelectorAll("#questionInfo")[index].setAttribute("style","display:none;");
      document.querySelectorAll("#questionHide")[index].setAttribute("style","");
      document.querySelectorAll("#caret")[index].setAttribute("class", "fas fa-caret-down rotated");
    }

    function toggleQuestion(index) {
      if (document.querySelectorAll("#questionInfo")[index].getAttribute("style") == "display:none;") {
        showQuestion(index);
      }
      else {
        hideQuestion(index);
      }
    }

    setInterval(() => {
      //Question name update
      for (var i=0; i<document.querySelectorAll("#setQuestionName").length; i++) {
        //document.querySelectorAll("#questionName")[i].childNodes[0].textContent = document.querySelectorAll("#setName")[i].value;
        document.querySelectorAll("#questionName")[i].childNodes[0].textContent = document.querySelectorAll("#setQuestionName")[i].value + " ";
        //console.log(document.querySelectorAll("#setQuestionName")[i].value);
      }
    }, 100);

    var burger = document.getElementById("burger");
    var nav = document.getElementById("navbar");
    burger.addEventListener('click', () => {
      burger.classList.toggle('is-active');
      nav.classList.toggle('is-active');
    });
  </script>
  <style>
    .popup {
      width: 60%;
      min-height: 75vh;
      position:fixed;
      background-color: white;
      top:5vh;
      left:20%;
      z-index: 2;
    }
    .navbar {
      z-index: 0;
    }
    #overlay {
      position:fixed;
      top:0;
      left:0;
      z-index: 1;
      background-color: rgba(0, 0, 0, 0);
      width: 100%;
      height: 100vh;
    }
    @keyframes showPopup {
      from {
        transform: scale(0);
      }

      to {
        transform: scale(1);
      }
    }
    @keyframes showPopupOverlay {
      from {
        background-color: rgba(0, 0, 0, 0);
      }
      to {
        background-color: rgba(0, 0, 0, .8);
      }
    }
    @keyframes hidePopup {
      from {
        transform: scale(1);
      }

      to {
        transform: scale(0);
      }
    }
    @keyframes hidePopupOverlay {
      from {
        background-color: rgba(0, 0, 0, .8);
      }
      to {
        background-color: rgba(0, 0, 0, 0);
      }
    }
    .customButton {
      border-style: dashed;
    }

    * {
      user-select: none;
    }

    .rotated {
      transform: rotate(-90deg);
    }

    .questionHide {
      background-color: rgba(0, 0, 0, .9);
      transition: all .2s;
    }

    .questionHide:hover {
      background-color: rgba(0, 0, 0, .7);
      cursor: pointer;
    }

    .customButtonOther {
      border-style: solid;
    }
  </style>
</html>
