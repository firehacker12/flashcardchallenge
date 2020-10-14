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
    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script src="https://kit.fontawesome.com/4a43f383fb.js" crossorigin="anonymous"></script>
    <link rel="shortcut icon" type="image/png" href="../favicon.png" />
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
      <input class="input" type="text" id="setName" placeholder="Unnamed Set" style="margin-left: 8%;width:25%;" maxlength="16" />
      <hr style="background-color:rgba(0, 0, 0, .5);"/>
      <div id="questions">
        <!-- Question 1 -->
        <div id="question">
          <div id='questionHide' class="questionHide" onclick="toggleQuestion(0);" style=''>
            <center><h1 class="is-size-2 has-text-weight-semibold has-text-info"><span id="questionName">Unnamed Question</span> <i id="caret" class="fas fa-caret-down"></i></center></h1>
          </div>
          <div id="questionInfo">
            <br/>
            <center><input class="input" id="setQuestionName" value="" style="width: 75%;text-align: center;" placeholder="Ask your question here..." /></center>
            <br/><br/>
            <div id="columns" style="width:100%;display:flex;">
              <div style="border-right: 2px solid rgba(0, 0, 0, .5);width:calc(50% - 2px);margin:0;display:inline-block;">
                <center><h1 class="is-size-3 has-text-success">Correct Answer <i class="fas fa-check has-text-success"></i></h1></center>
                <br/>
                <center>
                  <textarea id="correctAnswer" style="width:50%;resize: none;text-align: center;" type="text" class="input" placeholder="Correct Answer" maxlength="100"></textarea>
                </center>
              </div><!--
              --><div style="width:calc(50% - 2px);margin:0;display:inline-block;">
                <center><h1 class="is-size-3 has-text-danger">Incorrect Answers <i class="fas fa-times has-text-danger"></i></h1></center>
                <br/>
                <center><div id="answerParent"><textarea id="incorrectAnswer" style="width:50%;resize: none;text-align: center;" type="text" class="input" placeholder="Incorrect Answer" maxlength="100"></textarea><br><br></div></center>
                <center><button id="addAnswerIncorrect" class="button is-large customButton is-link is-outlined" onclick="addAnswer(0);">Add Answer <i class="fas fa-plus" style="margin-left: 10px;margin-top: 2px;"></i></button></center>
              </div>
            </div>
          </div>
        </div>
      </div>
      <br/><br/>
    </div>
    <center><hr style="background-color: black;width:50%;"/></center>
    <div id="dialogue" class='popup' style="display:none;">
      <div id="submitFormDiv" style="display:none;">
        <br/>
        <center><h1 class="is-size-1">Submit Form</h1></center>
        <br/><br/><br/><br/><br/><br/>
        <center><p class="is-size-5">Are you sure you are ready to submit?</p></center>
        <br/>
        <center><p id="setNameForm" class="is-size-3"><strong></strong>Unnamed Set</p></center>
        <br/>
        <center>
          <button id="exitSubmitForm" class="button is-large customButton is-danger" onclick="hideDialogue();" style="margin-right: 7.5px;min-width: 300px;">No, take me back!</button>
          <div id="submitFormParent" style="display:inline;">
            <button id="submitForm" class="button is-large customButton is-success" onclick="submitForm();" style="margin-left: 7.5px;min-width: 300px;">Yes, submit it!</button>
          </div>
        </center>
      </div>
      <div id="removeQuestionForm" style="display:none;">
        <br/>
        <center><h1 class="is-size-1"><strong class="has-text-weight-bold">Remove Question</strong></h1></center>
        <br/>
        <center><p class="is-size-3">Are you sure you want to remove this question?</p></center>
        <center><p class="is-size-6">You can't undo this process</p></center>
        <br/>
        <center><p class="is-size-4"><strong id="dialogueQuestion"></strong></p></center>
        <br/><br/>
        <center>
          <button id="exitQuestionDialogue" class="button is-large customButton is-danger" onclick="hideDialogue();" style="margin-right: 7.5px;min-width: 300px;">No, I don't</button>
          <button id="deleteQuestionButton" class="button is-large customButton is-success" onclick="removeQuestion();" style="margin-left: 7.5px;min-width: 300px;">Yes, delete it</button>
        </center>
      </div>
    </div>
    <div id="overlay" onclick="hideDialogue();" style="display:none;"></div>
    <center><button id="addQuestion" class="button is-large customButton is-info is-outlined" onclick="addQuestion();">Add Question <i class="fas fa-plus" style="margin-left: 10px;margin-top: 2px;"></i></button></center>
    <br/>
    <center><button id="finishSet" class="button is-large customButton is-warning" onclick="prepareSubmitForm();">Finish set <i class="fas fa-check-square" style="margin-left: 10px;margin-top: 2px;"></i></button></center>
    <br/><br/>
  </body>
  <script>
    var questionToRemove = 0;

    function jsonFormat(letter) {
      var finalLetter;
      switch(letter) {
        case '"':
          finalLetter = '\"';
          break;
        case "'":
          finalLetter = "\'";
          break;
        case "\\":
          finalLetter = '\\';
          break;
        default:
          finalLetter = letter;
          break;
      }
      return finalLetter;
    }

    function splitInput(word) {
      var splitName = word.split("");
      var finalName = "";
      for (var i=0; i<splitName.length; i++) {
        finalName += jsonFormat(splitName[i]);
      }

      return finalName;
    }

    function finishSetJSON(){

      var tmpSet = []; //[ {n:"",q:"",cA:"",fA:["",""] } ]
      var finalSetName;
      var finalQuestionName;
      var finalCorrectAnswer;
      if(document.getElementById("setName").value.trim() != ""){///////////////
        finalSetName = splitInput(document.getElementById("setName").value.trim());
        for(var i=0; i<document.querySelectorAll("#question").length; i++) {
          if(document.querySelectorAll("#setQuestionName")[i].value.trim() != ""){///////////
            document.querySelectorAll("#setQuestionName")[i].setAttribute("style","width: 75%;text-align: center;");
            finalQuestionName = splitInput(document.querySelectorAll("#setQuestionName")[i].value.trim());
            //console.log(document.querySelectorAll("#setQuestionName")[i].value);
            if(document.querySelectorAll("#correctAnswer")[i].value.trim() != ""){///////////////////
              finalCorrectAnswer = splitInput(document.querySelectorAll("#correctAnswer")[i].value.trim());
              //console.log((document.querySelectorAll("#correctAnswer")[i].value).trim());
              if(document.querySelectorAll("#answerParent")[i].childNodes[0].value.trim() != ""){//////////////////
                //console.log(document.querySelectorAll("#answerParent")[i].childNodes[0].value);
                var fArray = [];
                for(var f=0; f<document.querySelectorAll("#answerParent")[i].childNodes.length; f+=3){
                  //console.log(f);
                  if(document.querySelectorAll("#answerParent")[i].childNodes[f].value.trim() != ""){//////////////////
                    var finalFalseAnswer = splitInput(document.querySelectorAll("#answerParent")[i].childNodes[f].value.trim());
                    fArray.push(finalFalseAnswer);
                  }
                }
                var myUsername = "<?php echo $Username; ?>";
                tmpSet.push({author:myUsername,n:finalSetName,q:finalQuestionName,cA:finalCorrectAnswer,fA:fArray});
              }
              else{// First false Answer
                document.getElementById("exitSubmitForm").setAttribute("class","button is-large customButton is-danger");
                document.getElementById("submitForm").setAttribute("class","button is-large customButton is-success");
                hideDialogue();
                showQuestion(i);
                document.querySelectorAll("#answerParent")[i].childNodes[0].setAttribute("style","width:50%;resize: none;text-align: center;border: 1px solid red;");
                document.querySelectorAll("#answerParent")[i].childNodes[0].setAttribute("value","");
                document.querySelectorAll("#answerParent")[i].childNodes[0].setAttribute("placeholder",  "Please fill out this field!");
                document.querySelectorAll("#answerParent")[i].childNodes[0].focus();

                break;
              }
            }
            else{// Question Answer
              document.getElementById("exitSubmitForm").setAttribute("class","button is-large customButton is-danger");
              document.getElementById("submitForm").setAttribute("class","button is-large customButton is-success");
              hideDialogue();
              showQuestion(i);
              document.querySelectorAll("#correctAnswer")[i].setAttribute("style","width:50%;resize: none;text-align: center;border: 1px solid red;");
              document.querySelectorAll("#correctAnswer")[i].setAttribute("value","");
              document.querySelectorAll("#correctAnswer")[i].setAttribute("placeholder","Please fill out this field!");
              document.querySelectorAll("#correctAnswer")[i].focus();

              break;
            }
          }
          else{// Question Name
            document.getElementById("exitSubmitForm").setAttribute("class","button is-large customButton is-danger");
            document.getElementById("submitForm").setAttribute("class","button is-large customButton is-success");
            hideDialogue();
            showQuestion(i);
            document.querySelectorAll("#setQuestionName")[i].setAttribute("style","width: 75%;text-align: center;border: 1px solid red;");
            document.querySelectorAll("#setQuestionName")[i].setAttribute("value","");
            document.querySelectorAll("#setQuestionName")[i].setAttribute("placeholder","Please fill out this field!");
            document.querySelectorAll("#setQuestionName")[i].focus();

            break;
          }
        }
      }
      else{
        document.getElementById("exitSubmitForm").setAttribute("class","button is-large customButton is-danger");
        document.getElementById("submitForm").setAttribute("class","button is-large customButton is-success");
        hideDialogue();
        document.getElementById("setName").setAttribute("style","margin-left: 8%;width:25%;border: 1px solid red;");
        document.getElementById("setName").setAttribute("value","");
        document.getElementById("setName").setAttribute("placeholder","Please fill out this field!");
        document.getElementById("setName").focus();

      }

      console.log(JSON.stringify(tmpSet));

      if(tmpSet.length > 0) {
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
          if (this.readyState == 3 && this.status == 200) {
            console.log(xhttp.responseText);
            //good
            if (xhttp.responseText == "Success") {
              //Success
              document.getElementById("exitSubmitForm").setAttribute("style","display: none;");
              document.getElementById("submitForm").setAttribute("class","button is-large customButton is-success");
              document.getElementById("setNameForm").innerHTML += "<br/><br/>Submitted!";
              document.getElementById("submitFormParent").innerHTML = "<a href='../home'><button id='submitForm' class='button is-large customButton is-success' style='margin-left: 7.5px;min-width: 300px;'>Home</button></a>";
              document.getElementById("overlay").setAttribute("onclick","");
            }
            else if (xhttp.responseText == "Error") {
              document.getElementById("exitSubmitForm").setAttribute("class","button is-large customButton is-danger");
              document.getElementById("submitForm").setAttribute("class","button is-large customButton is-success");
              document.getElementById("setNameForm").innerHTML += "<br/><br/>An error occured. Please try again later!";
              document.getElementById("exitSubmitForm").innerHTML = "Go back";
              document.getElementById("submitForm").innerHTML = "Try Again";
            }
          }
        }
        xhttp.open("POST", "sendquiz.php", true);
        xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhttp.send("QuizJSON="+JSON.stringify(tmpSet)+"&QuizName="+document.querySelectorAll("#setName")[0].value);
      }
    }

    function prepareSubmitForm() {
      document.getElementById("submitFormDiv").setAttribute("style","");
      document.getElementById("removeQuestionForm").setAttribute("style","display:none;");
      if (document.getElementById("setName").value.length < 1) {
        document.getElementById("setNameForm").innerHTML = "Unnamed Set";
      }
      else {
        document.getElementById("setNameForm").innerHTML = document.getElementById("setName").value;
      }
      showDialogue();
    }
    function submitForm() {
      document.getElementById("exitSubmitForm").setAttribute("class","button is-large customButton is-danger is-loading");
      document.getElementById("submitForm").setAttribute("class","button is-large customButton is-success is-loading");
      finishSetJSON();
    }
    function addAnswer(index) {
      var element = document.createElement("textarea");
      element.setAttribute("id","incorrectAnswer");
      element.setAttribute("style","width:50%;resize: none;text-align: center;");
      element.setAttribute("type","text");
      element.setAttribute("class","input");
      element.setAttribute("maxlength","100");
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
      var questionHTML = "<hr style='margin:0;padding:0;background-color:white;'/><div id='questionHide' class='questionHide' onclick='toggleQuestion("+(questionLength-1)+");' style=''><center><h1 class='is-size-2 has-text-weight-semibold has-text-info'><span id='questionName'>Unnamed Question</span> <i id='caret' class='fas fa-caret-down'></i></center></h1></div><div id='questionInfo'><br/><center><input id='setQuestionName' value=''  class='input' style='width: 75%;text-align: center;' placeholder='Ask your question here...' /></center><br/><br/><div id='columns' style='width:100%;display:flex;'><div style='border-right: 2px solid rgba(0, 0, 0, .5);width:calc(50% - 2px);margin:0;display:inline-block;'><center><h1 class='is-size-3 has-text-success'>Correct Answer <i class='fas fa-check has-text-success'></i></h1></center><br/><center><textarea id='correctAnswer' style='width:50%;resize: none;text-align: center;' type='text' class='input' maxlength='100' placeholder='Correct Answer'></textarea></center></div><!----><div style='width:calc(50% - 2px);margin:0;display:inline-block;'><center><h1 class='is-size-3 has-text-danger'>Incorrect Answers <i class='fas fa-times has-text-danger'></i></h1></center><br/><center><div id='answerParent'><textarea id='incorrectAnswer' style='width:50%;resize: none;text-align: center;' type='text' class='input' maxlength='100' placeholder='Incorrect Answer'></textarea><br/><br/></div></center><center><button id='addAnswerIncorrect' class='button is-large customButton is-link is-outlined' onclick='addAnswer("+(questionLength-1)+");'>Add Answer <i class='fas fa-plus' style='margin-left: 10px;margin-top: 2px;'></i></button></center></div></div><br/><center><button id='removeQuestionButton' class='button is-large is-danger' onclick='promptRemoveQuestion("+questionLength+");questionToRemove="+questionLength+"'>Remove Question <i class='fas fa-trash-alt' style='margin-left: 10px;margin-top: 2px;'></i></button><br/>";
      questionNode.innerHTML = questionHTML;
      //document.getElementById("questions").innerHTML += questionHTML;
      var hr = document.createElement("hr");
      hr.setAttribute("style","margin:0;padding:0;");
      //document.getElementById("questions").appendChild(hr);
      document.getElementById("questions").appendChild(questionNode);

      hideQuestion(questionLength-2);
    }

    function showDialogue() {
      document.getElementById("overlay").setAttribute("style","animation: showPopupOverlay .35s;animation-fill-mode: forwards;");
      document.getElementById("dialogue").setAttribute("style","animation: showPopup .35s;animation-fill-mode: forwards;");
    }

    function hideDialogue() {
      document.getElementById("overlay").setAttribute("style","animation: hidePopupOverlay .35s;animation-fill-mode: forwards;");
      document.getElementById("dialogue").setAttribute("style","animation: hidePopup .35s;animation-fill-mode: forwards;");
      setTimeout(() => {
        document.getElementById("overlay").setAttribute("style","display:none;");
      }, 350);
    }

    function promptRemoveQuestion(index) {
      document.getElementById("removeQuestionForm").setAttribute("style","");
      document.getElementById("submitFormDiv").setAttribute("style","display:none;");
      document.getElementById("dialogueQuestion").innerHTML = document.querySelectorAll("#questionName")[index-1].childNodes[0].textContent;
      showDialogue();
    }

    function removeQuestion(index) {
      document.querySelectorAll("#question")[questionToRemove-1].remove();
      var sortedChildren = [];
      for (var i=0; i<document.getElementById("questions").childNodes.length; i++) {
        if (document.getElementById("questions").childNodes[i].nodeType == 1) sortedChildren.push(document.getElementById("questions").childNodes[i]);
      }
      for (var i=questionToRemove-1; i<sortedChildren.length; i++) {
        sortedChildren[i].childNodes[1].setAttribute("onclick","toggleQuestion("+i+");");
        document.querySelectorAll("#addAnswerIncorrect")[i].setAttribute("onclick","addAnswer("+i+");");
        document.querySelectorAll("#removeQuestionButton")[i-1].setAttribute("onclick","promptRemoveQuestion("+(i+1)+");questionToRemove="+(i+1));


      }
      hideDialogue();
    }

    function showQuestion(index) {
      document.querySelectorAll("#questionInfo")[index].setAttribute("style","");
      //document.querySelectorAll("#questionHide")[index].setAttribute("style","display:none;");
      document.querySelectorAll("#caret")[index].setAttribute("class", "fas fa-caret-down");
    }

    function hideQuestion(index) {
      document.querySelectorAll("#questionInfo")[index].setAttribute("style","display:none;");
      //document.querySelectorAll("#questionHide")[index].setAttribute("style","");
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
        if (document.querySelectorAll("#setQuestionName")[i].value == "") {
          document.querySelectorAll("#questionName")[i].innerHTML = "Unnamed Question";
        }
        else {
          document.querySelectorAll("#questionName")[i].innerHTML = document.querySelectorAll("#setQuestionName")[i].value;
        }
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
