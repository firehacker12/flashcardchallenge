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
    <link rel="shortcut icon" type="image/png" href="../favicon.png" />
    <script src="https://kit.fontawesome.com/4a43f383fb.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.0/css/bulma.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/p5.js/0.7.3/p5.min.js"></script>
    <link href="../login/style.css" type="text/css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Ubuntu:wght@300&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.3.0/socket.io.dev.js"></script>
    <script src="socket.js"></script>
  </head>
  <body>
    <div id="mainHome" style="//display:none;">
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
      <center><h1 class="is-size-1 has-text-weight-semibold">Welcome back, <?php echo $_SESSION['Username']; ?>!</h1></center>
      <br/><br/>
      <center>
        <p class="is-size-3">What would you like to play with your students?</p>
        <br/>
        <button class="button is-large" onclick="showPopup(2);">Quick Quiz</button>
        <button class="button is-large" onclick="showPopup(4);">Test</button>
        <br/><br/><br/><br/><br/><br/>
        <h1 class="is-size-3 has-text-weight-semibold">Coming Soon!</h1>
        <button class="button is-large" onclick="//showPopup(0);">Flashcards</button>
        <button class="button is-large" onclick="//showPopup(1);">Pictionary</button>
        <button class="button is-large" onclick="//showPopup(3);">Matching</button>
      </center>
      <div id="popupHTML">
        <div id="popup0" class="popup" style="">
          <br/>
          <center><h1 class="is-size-1">Flashcards</h1></center>
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
                <input id="roomname0" class="input" placeholder="My Room" maxlength="20" />
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
              <select class="select selectSearch" onchange="changedSearch(this);">
                <option>My Quizzes</option>
                <option>All Quizzes</option>
              </select>
            </div>
            <input class="input" id="search" placeholder="Steve Jobs, United States, Algebra, Etc" />
            <br/>
            <div style="overflow-x: scroll;height:15vh;white-space: nowrap;overflow-y: hidden;" id="previewImages"></div>
            <center>
              <a href="../createquiz"><button class="button notmoving is-medium is-warning">Create A Quiz</button></a>
              <button class="button notmoving is-medium is-danger" disabled id="startButton" onclick="roomMake('flash');">Create Room</button>
            </center>
            </center>
          </div>
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
                <input id="roomname1" class="input" placeholder="My Room" maxlength="20" />
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
              <select class="select selectSearch" onchange="changedSearch(this);">
                <option>My Quizzes</option>
                <option>All Quizzes</option>
              </select>
            </div>
            <input class="input" id="search" placeholder="Steve Jobs, United States, Algebra, Etc" />
            <br/>
            <div style="overflow-x: scroll;height:15vh;white-space: nowrap;overflow-y: hidden;" id="previewImages"></div>
            <center>
              <a href="../createquiz"><button class="button notmoving is-medium is-warning">Create A Quiz</button></a>
              <button class="button notmoving is-medium is-danger" disabled id="startButton" onclick="roomMake('pic');">Create Room</button>
            </center>
            </center>
          </div>
        </div>
        <div id="popup2" class="popup" style="">
          <br/>
          <center><h1 class="is-size-1">Quick Quiz</h1></center>
          <br/>
          <span style="margin-left: 12.5%"></span><strong class="is-size-3">Settings</strong>
          <hr/>
          <br/>
          <div style="width:75%;position:relative;left:12.5%;">
            <div class="columns">
              <div class="column">
                <p class="is-size-5">Time to Answer</p>
                <select class="select" id="quickTime">
                  <option>5 Seconds</option>
                  <option>10 Seconds</option>
                  <option>30 Seconds</option>
                  <option>45 Seconds</option>
                  <option>60 Seconds</option>
                  <option>120 Seconds</option>
                </select>
                <br/><br/>
                <p class="is-size-5">Room Name</p>
                <input id="roomname2" class="input" placeholder="My Room" maxlength="20" />
                <strong class="is-size-6 has-text-grey">*Optional</strong>
              </div>
              <div class="column">

              </div>
            </div>
            <br/>
          </div>
          <span style="margin-left: 12.5%"></span><strong class="is-size-3">Search</strong>
          <hr/>
          <div style="width:75%;position:relative;left:12.5%;">
            <div style="margin-bottom: 5px;display:flex;">
              <p class="is-size-6 has-text-weight-semibold is-inline" style="align-self: center;padding-right: 10px;">Search For Quizzes</p>
              <select class="select selectSearch" onchange="changedSearch(this);">
                <option>My Quizzes</option>
                <option>All Quizzes</option>
              </select>
            </div>
            <input class="input" id="search" placeholder="Steve Jobs, United States, Algebra, Etc" />
            <br/>
            <div style="overflow-x: scroll;height:15vh;white-space: nowrap;overflow-y: hidden;" id="previewImages"></div>
            <center>
              <a href="../createquiz"><button class="button notmoving is-medium is-warning">Create A Quiz</button></a>
              <button class="button notmoving is-medium is-danger" disabled id="startButton" onclick="roomMake('quick');">Create Room</button>
            </center>
            </center>
          </div>
        </div>
        <div id="popup3" class="popup" style="">
          <br/>
          <center><h1 class="is-size-1">Matching</h1></center>
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
                <input id="roomname3" class="input" placeholder="My Room" maxlength="20" />
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
              <select class="select selectSearch" onchange="changedSearch(this);">
                <option>My Quizzes</option>
                <option>All Quizzes</option>
              </select>
            </div>
            <input class="input" id="search" placeholder="Steve Jobs, United States, Algebra, Etc" />
            <br/>
            <div style="overflow-x: scroll;height:15vh;white-space: nowrap;overflow-y: hidden;" id="previewImages"></div>
            <center>
              <a href="../createquiz"><button class="button notmoving is-medium is-warning">Create A Quiz</button></a>
              <button class="button notmoving is-medium is-danger" disabled id="startButton" onclick="roomMake('matching');">Create Room</button>
            </center>
            </center>
          </div>
        </div>
        <div id="popup4" class="popup" style="">
          <br/>
          <center><h1 class="is-size-1">Test</h1></center>
          <br/>
          <span style="margin-left: 12.5%"></span><strong class="is-size-3">Settings</strong>
          <hr/>
          <br/>
          <div style="width:75%;position:relative;left:12.5%;">
            <div class="columns">
              <div class="column">
                <br/>
                <p class="is-size-5">Room Name</p>
                <input id="roomname4" class="input" placeholder="My Room" maxlength="20" />
                <strong class="is-size-6 has-text-grey">*Optional</strong>
                <br/>
              </div>
            </div>
            <br/>
          </div>
          <span style="margin-left: 12.5%"></span><strong class="is-size-3">Search</strong>
          <hr/>
          <div style="width:75%;position:relative;left:12.5%;">
            <div style="margin-bottom: 5px;display:flex;">
              <p class="is-size-6 has-text-weight-semibold is-inline" style="align-self: center;padding-right: 10px;">Search For Quizzes</p>
              <select class="select selectSearch" onchange="changedSearch(this);">
                <option>My Quizzes</option>
                <option>All Quizzes</option>
              </select>
            </div>
            <input class="input" id="search" placeholder="Steve Jobs, United States, Algebra, Etc" />
            <br/>
            <div style="overflow-x: scroll;height:15vh;white-space: nowrap;overflow-y: hidden;" id="previewImages"></div>
            <center>
              <a href="../createquiz"><button class="button notmoving is-medium is-warning">Create A Quiz</button></a>
              <button class="button notmoving is-medium is-danger" disabled id="startButton" onclick="roomMake('test');">Create Room</button>
            </center>
            </center>
          </div>
        </div>
      </div>
      <div id="overlay" style="display:none;" onclick="hidePopup();"></div>
      <span style="display:none;" id="teacherName"><?php echo $Username; ?></span>
    </div>
    <div id="showStudentScores" style="display:none;">
      <center><h1 class="is-size-1">Your quiz is active!</h1></center>
      <center><h1 class="is-size-3">View your students progress live!</h1></center>
      <hr/>
      <center><h1 class="is-size-2">Average Class Score</h1></center>
      <center><div>
        <progress id="correctAverage" class="averageProgress" style="position:absolute;" value="0" max="500"></progress>
        <progress id="incorrectAverage" class="averageProgress" style="--xOffset:0px;" value="0" max="500"></progress>
      </div></center>
      <br/><br/>
      <center><h1 class="is-size-2">Individual Scores</h1></center>
      <center>
        <div style="width:100%;display:flex;">
          <div class="column" id="lobbyColumn1Scores">

          </div>
          <div class="column" id="lobbyColumn2Scores">

          </div>
          <div class="column" id="lobbyColumn3Scores">

          </div>
        </div>
      </center>
      <br/><br/>
      <center><h1 class="is-size-4">Are your students finished?</h1></center>
      <center><button class="button is-warning is-medium" onclick="endRoom();">Home</button></center>
    </div>
    <div id="lobbyRoom" style="display:none;">
      <br/>
      <center>
      <h1  class="is-size-1 has-text-link">Student Waiting Room</h1>
      <h1 class="is-size-5 has-text-link" id="gameTypeStrong"></h1>
      <br>
      <h1  class="is-size-4 has-text-info" id="lobbyCodeDisplay" >Code: </h1>
      <hr style="border:1px solid black;">
      <br>
      <button class="button notmoving is-medium is-primary" onclick="startGame();" disabled style="position:absolute; right: 40px;top:60px;" id="startTheRoom" >Start Room</button>
      <div id="lobbyWaitStudentList">
        <div style="width:100%;display:flex;">
          <div class="column" id="lobbyColumn1">

          </div>
          <div class="column" id="lobbyColumn2">

          </div>
          <div class="column" id="lobbyColumn3">

          </div>
        </div>
      </div>
    </center>
      <!--button onclick="startGame();">Start Game</button-->
    </div>
    <div id="quickGameRoom" style="display:none;">
      <center>
      <div id="quickAskQuestion" style="//display:none;">
        <h1  class="is-size-1 has-text-black">Students Are Answering Questions</h1>
        <!--h1  class="is-size-4 has-text-black" id="quickQuestionsLeft" >10 / 10</h1-->
        <!--<h1 class="is-size-5 has-text-black" style="padding: 10px 20px 10px 20px;position: absolute;top:30px;left:20px;" id="quickQuizTime" >0 Seconds</h1>-->
        <hr style="border:1px solid black;">
        <h1  class="is-size-3 has-text-black" id="quickQuestionShowed" style="max-width:95%;word-wrap:break-word;" >Question 1</h1>
        <br>
        <hr style="border:1px solid grey;margin:0px;">
        <br>
        <br>
        <div style="width:100%;display:flex;">
          <div id="quickQuizQuestionAnswers" style="width:50%;display:inline-block;">
            <!--
            <h1 class="is-size-3 has-text-black" style="">Answer 1</h1>
            <progress value="0" max="1"></progress>
            <br>
            <br>
            <h1 class="is-size-3 has-text-black" style="">Answer 2</h1>
            <progress value="0" max="1"></progress>-->
          </div><!--
          --><div style="width: 50%;display:inline-block;">
            <center><h1 class="is-size-2 has-text-weight-semibold">Leaderboard</h1></center>
            <div id="leaderboardContainer">
              <!--<center><h1 id="leader" class="is-size-4 has-text-weight-light">1. asdf</h1></center>
              <center><h1 id="leader" class="is-size-4 has-text-weight-light">2. asdf</h1></center>
              <center><h1 id="leader" class="is-size-4 has-text-weight-light">3. asdf</h1></center>
              <center><h1 id="leader" class="is-size-4 has-text-weight-light">4. asdf</h1></center>
              <center><h1 id="leader" class="is-size-4 has-text-weight-light">5. asdf</h1></center>-->
            </div>
          </div>
        </div>
      </div>
      <div id="quickShowAnswer" style="display:none;">
        <button class="button notmoving is-medium is-primary" onclick="quickStartNextQuestion()" style="position:absolute; right: 40px;top:5px;" id="quickNextQuestion" >Next Question</button>
      </div>
    </center>
    </div>
  </body>
  <script>
    let teacherName = document.getElementById('teacherName').innerHTML;
    var previousSearch = [];

    function setup() {
      for (var i=0; i<5; i++) {
        previousSearch[i] = "";
      }
      retreiveData();
    }

    setInterval(() => {
      var index = undefined;
      for (var i=0; i<document.querySelectorAll("#search").length; i++) {
        if (previousSearch[i] != document.querySelectorAll("#search")[i].value) {
          index = i;
        }
      }

      if (index != undefined) {
        var dividers = [];
        for (var i=0; i<document.querySelectorAll("#previewImages")[index].childNodes.length; i++) {
          dividers.push({elem: document.querySelectorAll("#previewImages")[index].childNodes[i], name: document.querySelectorAll("#previewImages")[index].childNodes[i].childNodes[0].childNodes[0].textContent.toLowerCase(), close:0});
        }
        var search = document.querySelectorAll("#search")[index].value;
        search = search.toLowerCase();
        var searchArray = search.split("");
        for (var i=0; i<dividers.length; i++) {
          var searchQueryArray = dividers[i].name.split("");
          var count = 0;
          for (var j=0; j<searchQueryArray.length; j++) {
            if (searchArray[j] == undefined) break;
            if (searchQueryArray[j] == searchArray[j]) {
              count++;
            }
            else break;
          }
          dividers[i].close = count;
        }
        var sortedDividers = dividers.slice().sort(compareValues("close", "desc"));
        document.querySelectorAll("#previewImages")[index].innerHTML = "";
        for (var i=0; i<sortedDividers.length; i++) {
          document.querySelectorAll("#previewImages")[index].appendChild(sortedDividers[i].elem);
        }
        previousSearch[index] = document.querySelectorAll("#search")[index].value;
      }
    }, 30);

    function changedSearch(elem) {
      //var parentElement = elem.querySelectorAll("#search")[0];
      var index = undefined;
      for (var i=0; i<document.querySelectorAll(".selectSearch").length; i++) {
        if (document.querySelectorAll(".selectSearch")[i] == elem) {
          index = i;
          break;
        }
      }
      var parentElement = document.querySelectorAll("#previewImages")[index];
      parentElement.innerHTML = "";
      if (elem.value == "My Quizzes") {
        //My quizzes
        //for (var j=0; j<parentElement.length; j++) {
          for (var i=0; i<myQuizzes.length; i++) {
            var jsonSet = JSON.parse(myQuizzes[i]);
            var elem_ = document.createElement("div");
            elem_.setAttribute("style","background-color: rgb("+floor(random(0, 200))+", "+floor(random(0, 200))+", "+floor(random(0, 200))+");");
            elem_.setAttribute("class","previewImage");
            elem_.setAttribute("onclick", "selectPreview(this, this.parentElement, 0, "+i+");");
            elem_.innerHTML = "<center><p class='has-text-white is-size-4' style='margin-top: 15px;line-height: 4vh;text-shadow: 0px 0px 2px black;'>"+jsonSet[0].n+"</p></center><center><p class='has-text-white is-size-4'>By: "+jsonSet[0].author+"</p></center>";
            parentElement.appendChild(elem_);
          }
        //}
      }
      else {
        //Other quizzes
        //for (var j=0; j<parentElement.length; j++) {
          for (var i=0; i<otherQuizzes.length; i++) {
            var jsonSet = JSON.parse(otherQuizzes[i]);
            var elem_ = document.createElement("div");
            elem_.setAttribute("style","background-color: rgb("+floor(random(0, 200))+", "+floor(random(0, 200))+", "+floor(random(0, 200))+");");
            elem_.setAttribute("class","previewImage");
            elem_.setAttribute("onclick", "selectPreview(this, this.parentElement, 1, "+i+");");
            elem_.innerHTML = "<center><p class='has-text-white is-size-4' style='margin-top: 15px;line-height: 4vh;text-shadow: 0px 0px 2px black;'>"+jsonSet[0].n+"</p></center><center><p class='has-text-white is-size-4'>By: "+jsonSet[0].author+"</p></center>";
            parentElement.appendChild(elem_);
          }
        //}
      }
    }

    function compareValues(key, order='asc') {
      //let finalArr = nn.slice().sort(compareValues("fitness", "asc")).slice();
      return function(a, b) {
        if(!a.hasOwnProperty(key) || !b.hasOwnProperty(key)) {
          // property doesn't exist on either object
          return 0;
        }

        const varA = (typeof a[key] === 'string') ?
          a[key].toUpperCase() : a[key];
        const varB = (typeof b[key] === 'string') ?
          b[key].toUpperCase() : b[key];

        let comparison = 0;
        if (varA > varB) {
          comparison = 1;
        } else if (varA < varB) {
          comparison = -1;
        }
        return (
          (order == 'desc') ? (comparison * -1) : comparison
        );
      };
    }

    function retreiveData() {
      var xhttp = new XMLHttpRequest();
      xhttp.open("POST", "getinfo.php", false);
      xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
          showGames(xhttp.responseText);
        }
      }
      xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
      xhttp.send();
    }

    var myQuizzes;
    var otherQuizzes;
    var selectedQuizIndex = 0;

    function showGames(info) {
      var info_ = info.substring(0, info.length-1);
      if (info_.length > 0) {
        var infoArray = info_.split("╞");
        myQuizzes = infoArray[0].split("╪");
        otherQuizzes = infoArray[1].split("╪");
        delete myQuizzes[myQuizzes.length-1];
        myQuizzes = cleanArray(myQuizzes);
        //myQuizzes = JSON.parse(myQuizzes);

        var parentElement = document.querySelectorAll("#previewImages");

        // <img class="previewImage" style="border-left:1px solid rgba(0, 0, 0, .5);" src="https://bulma.io/images/placeholders/600x480.png" />
        for (var j=0; j<parentElement.length; j++) {
          for (var i=0; i<myQuizzes.length; i++) {
            var jsonSet = JSON.parse(myQuizzes[i]);
            var elem = document.createElement("div");
            elem.setAttribute("style","background-color: rgb("+floor(random(0, 200))+", "+floor(random(0, 200))+", "+floor(random(0, 200))+");");
            elem.setAttribute("class","previewImage");
            elem.setAttribute("onclick", "selectPreview(this, this.parentElement, 0, "+i+");");
            elem.innerHTML = "<center><p class='has-text-white is-size-4' style='margin-top: 15px;line-height: 4vh;text-shadow: 0px 0px 2px black;'>"+jsonSet[0].n+"</p></center><center><p class='has-text-white is-size-4'>By: "+jsonSet[0].author+"</p></center>";
            parentElement[j].appendChild(elem);
          }
        }
      }
    }

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

    var currentSelected = null;
    var currentType = 0;
    var currentNumber = 0;

    function selectPreview(elem, elemParent, type, number) {
      var index = undefined;
      for (var i=0; i<document.querySelectorAll("#previewImages").length; i++) {
        if (document.querySelectorAll("#previewImages")[i] == elemParent) {
          index = i;
          break;
        }
      }
      if (index != undefined) {
        elem.classList.add("active");
        if (currentSelected != null) {
          if (currentSelected.classList.contains("active"))
            if (currentSelected != elem)
              currentSelected.classList.remove("active");
        }
        document.querySelectorAll("#startButton")[index].removeAttribute("disabled");
        //var styling = currentSelected.getAttribute("style");
        currentSelected = elem;
        currentType = type;
        currentNumber = number;
        selectedQuizIndex = index;
      }
    }

    function cleanArray(arr) {
      var arr_ = [];
      for (var i=0; i<arr.length; i++) {
        if (arr[i] != undefined && arr[i] != null) {
          arr_.push(arr[i]);
        }
      }

      return arr_;
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
    progress {
      appearance: none;
      webkit-appearance: none;
      //position:absolute;
      width: 150px;
    }
    progress::-webkit-progress-bar {
      background-color: rgba(220, 220, 220, 1);
      border-radius: 10px;
    }
    progress::-webkit-progress-value {
      float: left;
      background-color: rgb(81, 224, 49);
      border-top-left-radius: var(--borderTopLeft);
      border-bottom-left-radius: var(--borderBottomLeft);
      border-top-right-radius: var(--borderTopRight);
      border-bottom-right-radius: var(--borderBottomRight);
      position:relative;
      //right:75px;
    }
    .other::-webkit-progress-value {
      background-color: rgb(247, 62, 30);
      //border-top-right-radius: 10px;
      //border-bottom-right-radius: 10px;
      //border-top-left-radius: 0px;
      //border-bottom-left-radius: 0px;
      left: var(--xOffset);
    }
    .averageProgress {
      width: 500px;
    }
    .averageProgress::-webkit-progress-value {
      left: var(--xOffset);
    }
    #incorrectAverage::-webkit-progress-value {
      background-color: rgb(247, 62, 30);
    }
    .noRound::-webkit-progress-bar {
      border-radius: 0px;
    }
    button {
      margin: 25px;
      transition: margin .25s;
    }
    html, body {
      overflow-y: hidden;
    }
    .active {
      filter: brightness(80%);
      border: 1px solid black;
    }
    .previewImage {
      width:250px;
      height: 15vh;
      margin: 0;
      padding: 0;
      display: inline-block;
      border-right:1px solid rgba(0, 0, 0, .5);
      cursor: pointer;
      transition: all .25s;
    }
    .previewImage:hover {
      filter: brightness(80%);
    }
    button:not(.notmoving):hover {
      margin: 30px;
    }
    .popup {
      overflow-y: scroll;
      background-color: white;
      width: 50%;
      height: 90vh;
      position:fixed;
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
      position:fixed;
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
