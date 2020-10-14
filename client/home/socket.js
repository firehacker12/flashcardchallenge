var socket = io(":2000");
var id = null;
var teacher = true;
var gameCode = null;
var game = null;
var quickQuestionNum = 0;
var mySet = null;
var mySettings = null;
var quickStudentAnswers = [];
var quickStudentsDone = 0;

function roomMake(gameType) {
  if (currentSelected != null) {
    let nameTmp = document.getElementById('teacherName').innerHTML + "'s Room";

    for (var i=0; i<5; i++) {
      if (document.getElementById('startButton'+i) != undefined) {
        var makeButton = document.getElementById("startButton"+i).setAttribute("class","button is-medium is-danger is-loading is-disabled");
      }
    }
    for (var i=0; i<5; i++) {
      if (document.getElementById('roomname'+i) != undefined) {
        if (document.getElementById('roomname'+i).value.length > 0) {
          nameTmp = document.getElementById('roomname'+i).value;
          break;
        }
      }
    }
    //console.log(nameTmp);
    let settingsTmp = {gameType:gameType,rounds:0};
    var gameTypeString;
    switch(gameType) {
      case "pic":
        gameTypeString = "Pictionary";
        break;
      case "matching":
        gameTypeString = "Matching Game";
        break;
      case "flash":
        gameTypeString = "Flashcards";
        break;
      case "quick":
        gameTypeString = "Quick Quizes"
        settingsTmp.rounds = document.getElementById("quickTime").value;
        break;
      case "test":
        gameTypeString = "Test";
        break;

    }
    document.getElementById("gameTypeStrong").innerHTML = gameTypeString;
    //let nameTmp = "tmp name";
    let tmpSet = [
      {q:"2+2=?",cA:"fish",fA:["4","2","3","1"]},
      {q:"yes or no",cA:"maybe",fA:["yes","no","si"]},
      {q:"funny",cA:"funny",fA:["very funny","not funny","this is a test to see how long these should be and what i should set the limit to"]}
      //{q:"",cA:"",fA:[""]}
    ];

    socket.emit('createRoom',tmpSet,nameTmp,settingsTmp);
  }
}

function startGame(){
  if (teacher) {
    if (currentType == 0){
      //My Quizzes
      mySet = JSON.parse(myQuizzes[currentNumber]);
    }
    else {
      //Other quizzes
      mySet = JSON.parse(otherQuizzes[currentNumber]);
    }
    socket.emit("startTheGame");
  }
}

function endRoom(){
  if(teacher){
    socket.emit('endRoom',gameCode);
  }
}

socket.on('roomClosed', () => {
  location.reload();
});

socket.on('startGame', (room) => {
  document.getElementById("lobbyRoom").setAttribute('style','display:none;');
  game = room.settings.gameType;

  switch(room.settings.gameType) {
    case "test":
      document.getElementById("showStudentScores").setAttribute("style", "");
      showPlayers(room);
      setupTest(mySet);
      break;
  }
  mySettings = room.settings;
  if(game == "quick"){
    quickQuestionNum = 0;
    quickQuestion();
  }
});

socket.on('studentSentQuestion', (questionNumber, answer, students, socketID) => {
  var studentID;
  var studentName;

  for (var i=0; i<students.length; i++) {
    if (students[i]) {
      if (students[i].id == socketID) {
        studentID = i;
        studentName = students[i].name;
        break;
      }
    }
  }
  if (answer == mySet[questionNumber].cA) {
    var unitUp = 1/mySet.length;
    document.getElementById(correctStudentIDToDivName[studentID]).setAttribute("value",parseInt(document.getElementById(correctStudentIDToDivName[studentID]).getAttribute("value"))+(unitUp*150));
    //document.getElementById(wrongStudentIDToDivName[studentID]).style.left = (-150)+parseInt(document.getElementById(correctStudentIDToDivName[studentID]).getAttribute("value"));
    document.getElementById(wrongStudentIDToDivName[studentID]).style.setProperty('--xOffset', parseInt(document.getElementById(wrongStudentIDToDivName[studentID]).style.getPropertyValue("--xOffset"))+(150/mySet.length));
    //for (var i=0; i<)
    //document.getElementById("")
  }
  else {
    var unitUp = 1/mySet.length;
    document.getElementById(wrongStudentIDToDivName[studentID]).setAttribute("value", parseInt(document.getElementById(wrongStudentIDToDivName[studentID]).getAttribute("value"))+(unitUp*150));
    //document.getElementById(wrongStudentIDToDivName[studentID]).style.width = parseInt(document.getElementById(correctStudentIDToDivName[studentID]).getAttribute("value"));
  }
});

socket.on('receiveQuickAnswer',(ans,studId) => {
  //console.log(ans);
  if(quickStudentAnswers[ans] != undefined){
    quickStudentAnswers[ans] += 1;
    quickStudentsDone++;
    if(quickStudentsDone >= studentCount){
      //console.log("e");
      socket.emit('quickSendcA',mySet[quickQuestionNum].cA);
    }
    //console.log("r");
  }
});

function quickQuestion(){
  socket.emit('askQuickQuestion',mySet[quickQuestionNum],mySettings);
  quickStudentAnswers = [];
  quickStudentsDone = 0;
  quickStudentAnswers[mySet[quickQuestionNum].cA] = 0;
  for(var i=0; i<mySet[quickQuestionNum].fA.length; i++){
    quickStudentAnswers[mySet[quickQuestionNum].fA[i]] = 0;
  }
}

var correctStudentIDToDivName = [];
var wrongStudentIDToDivName = [];

function showPlayers(currentRoom) {
  studentCount = 0;
  document.getElementById('lobbyColumn1Scores').innerHTML = "";
  document.getElementById('lobbyColumn2Scores').innerHTML = "";
  document.getElementById('lobbyColumn3Scores').innerHTML = "";
  var columnNum = 1;
  for(var i=0; i<currentRoom.students.length; i++){
    if(currentRoom.students[i]){
      //document.getElementById('lobbyWaitStudentList').innerHTML += "<p1> "+currentRoom.students[i].name+" </p1>";
      if(columnNum == 1){
        columnNum++;
        //<div style='position:relative;right:75px;'><progress id='correctProgress"+studentCount+"' value='0' max='150'></progress><progress class='other' style='left:0;width:0px;' id='wrongProgress"+studentCount+"' value='0' max='150'></progress></div>
        document.getElementById('lobbyColumn1Scores').innerHTML += "<h1 class='is-size-4 has-text-weight-semibold has-text-dark'>"+currentRoom.students[i].name+"</h1><div style='position:relative;//right:75px;'><progress id='correctProgress"+studentCount+"' style='position:absolute;' value='0' max='150'></progress><progress class='other' style='--xOffset: 0;' id='wrongProgress"+studentCount+"' value='0' max='150'></progress></div><br>";
      }
      else if(columnNum == 2){
        columnNum++;
        document.getElementById('lobbyColumn2Scores').innerHTML += "<h1 class='is-size-4 has-text-weight-semibold has-text-dark'>"+currentRoom.students[i].name+"</h1><div style='position:relative;//right:75px;'><progress id='correctProgress"+studentCount+"' style='position:absolute;' value='0' max='150'></progress><progress class='other' style='--xOffset: 0;' id='wrongProgress"+studentCount+"' value='0' max='150'></progress></div><br>";
      }
      else{
        columnNum = 1;
        document.getElementById('lobbyColumn3Scores').innerHTML += "<h1 class='is-size-4 has-text-weight-semibold has-text-dark'>"+currentRoom.students[i].name+"</h1><div style='position:relative;//right:75px;'><progress id='correctProgress"+studentCount+"' style='position:absolute;' value='0' max='150'></progress><progress class='other' style='--xOffset: 0;' id='wrongProgress"+studentCount+"' value='0' max='150'></progress></div><br>";
      }
      correctStudentIDToDivName[i] = "correctProgress"+studentCount;
      wrongStudentIDToDivName[i] = "wrongProgress"+studentCount;
      studentCount++;
    }
  }
}

function setupTest(set) {
  socket.emit('sendQuestionsTest',set);
}

socket.on('joinLobby',(room,id_) => {
  document.getElementById('mainHome').setAttribute("style","display:none");
  document.getElementById('lobbyRoom').setAttribute("style","");
  //console.log("E");
  id = id_;
  gameCode = room.id;
  teacher = true;

  document.getElementById('lobbyCodeDisplay').innerHTML = "Code: "+room.id;
  //document.getElementById('lobbyWaitStudentList').innerHTML = "";
  gameType = room.settings.gameType;

  /*for(var i=0; i<room.students.length; i++){
    if(room.students[i]){
      document.getElementById('studentLobbyList').innerHTML += "<p1>"+room.students[i].name+"</p1><br>";
    }
  }*/
});

var studentCount = 0;
socket.on('updateLobby',(room) => {
  var columnNum = 1;
  //document.getElementById('lobbyCode').innerHTML = room.id;
  //document.getElementById('lobbyWaitStudentList').innerHTML = "";
  document.getElementById("startTheRoom").removeAttribute("disabled");
  if (room.students.length == 0) document.getElementById("startTheRoom").setAttribute("disabled", "true");
  document.getElementById('lobbyColumn1').innerHTML = "";
  document.getElementById('lobbyColumn2').innerHTML = "";
  document.getElementById('lobbyColumn3').innerHTML = "";
  studentCount = 0;
  for(var i=0; i<room.students.length; i++){
    if(room.students[i]){
      //document.getElementById('lobbyWaitStudentList').innerHTML += "<p1> "+room.students[i].name+" </p1>";
      if(columnNum == 1) {
        columnNum++;
        //<div style='position:relative;right:75px;'><progress id='correctProgress"+studentCount+"' value='0' max='150'></progress><progress class='other' style='left:0;width:0px;' id='wrongProgress"+studentCount+"' value='0' max='150'></progress></div>
        document.getElementById('lobbyColumn1').innerHTML += "<h1 class='is-size-4 has-text-weight-semibold has-text-dark'>"+room.students[i].name+"</h1><br>";
      }
      else if(columnNum == 2){
        columnNum++;
        document.getElementById('lobbyColumn2').innerHTML += "<h1 class='is-size-4 has-text-weight-semibold has-text-dark'>"+room.students[i].name+"</h1><br>";
      }
      else{
        columnNum = 1;
        document.getElementById('lobbyColumn3').innerHTML += "<h1 class='is-size-4 has-text-weight-semibold has-text-dark'>"+room.students[i].name+"</h1><br>";
      }
      studentCount++;
    }
  }
});
