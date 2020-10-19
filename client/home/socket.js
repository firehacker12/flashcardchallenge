var socket = io(":2000");
var id = null;
var teacher = true;
var gameCode = null;
var game = null;
var quickQuestionNum = 0;
var mySet = null;
var gameStarted = false;
var mySettings = null;
var quickStudentAnswers = [];
var quickStudentsDone = 0;
var quickAnswersIn = 0;
var quickStudentPoints = [];
var quickStudentPointsCount = 0;

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
        gameTypeString = "Quick Quizzes"
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
  gameStarted = true;
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
  var correctStudent;
  var incorrectStudent;
  correctStudent = document.getElementById(correctStudentIDToDivName[studentID]);
  incorrectStudent = document.getElementById(wrongStudentIDToDivName[studentID]);
  var averageCorrect = document.getElementById("correctAverage");
  var averageIncorrect = document.getElementById("incorrectAverage");

  if (answer == mySet[questionNumber].cA) {
    var unitUp = 1/mySet.length;
    incorrectStudent = document.getElementById(wrongStudentIDToDivName[studentID]);
    console.log(((unitUp*500)/students.length));
    correctStudent.setAttribute("value",parseFloat(correctStudent.getAttribute("value"))+(unitUp*150));
    averageCorrect.setAttribute("value",parseFloat(averageCorrect.getAttribute("value"))+((unitUp*500)/students.length));
    //incorrectStudent.style.left = (-150)+parseInt(correctStudent.getAttribute("value"));
    console.log((150/mySet.length), (((unitUp*500)/students.length)));
    incorrectStudent.style.setProperty('--xOffset', parseFloat(incorrectStudent.style.getPropertyValue("--xOffset"))+(150/mySet.length));
    averageIncorrect.style.setProperty('--xOffset', parseFloat(averageIncorrect.style.getPropertyValue("--xOffset"))+(((unitUp*500)/students.length)));

    //for (var i=0; i<)
    //document.getElementById("")
  }
  else {
    var unitUp = 1/mySet.length;
    incorrectStudent.setAttribute("value", parseFloat(incorrectStudent.getAttribute("value"))+(unitUp*150));
    averageIncorrect.setAttribute("value", parseFloat(averageIncorrect.getAttribute("value"))+((unitUp*500)/students.length));
    //incorrectStudent.style.width = parseInt(correctStudent.getAttribute("value"));
  }

  if (round(parseFloat(correctStudent.getAttribute("value"))) == round(parseFloat(correctStudent.getAttribute("max")))) {
    correctStudent.style.setProperty("--borderTopRight", "10px");
    correctStudent.style.setProperty("--borderBottomRight", "10px");
    correctStudent.style.setProperty("--borderTopLeft","10px");
    correctStudent.style.setProperty("--borderBottomLeft", "10px");
  }
  else {
    correctStudent.style.setProperty("--borderTopRight", "0px");
    correctStudent.style.setProperty("--borderBottomRight", "0px");
    correctStudent.style.setProperty("--borderTopLeft","10px");
    correctStudent.style.setProperty("--borderBottomLeft", "10px");
  }
  if (round(parseFloat(correctStudent.getAttribute("value"))) == 0 && round(parseFloat(incorrectStudent.getAttribute("value"))) > 0) {
    incorrectStudent.style.setProperty("--borderTopRight", "10px");
    incorrectStudent.style.setProperty("--borderTopLeft", "10px");
    incorrectStudent.style.setProperty("--borderBottomRight", "10px");
    incorrectStudent.style.setProperty("--borderBottomLeft", "10px");
  }
  else {
    incorrectStudent.style.setProperty("--borderTopRight", "10px");
    incorrectStudent.style.setProperty("--borderTopLeft", "0px");
    incorrectStudent.style.setProperty("--borderBottomRight", "10px");
    incorrectStudent.style.setProperty("--borderBottomLeft", "0px");
  }
  if (round(parseFloat(averageCorrect.getAttribute("value"))) == round(parseFloat(averageCorrect.getAttribute("max")))) {
    averageCorrect.style.setProperty("--borderTopRight", "10px");
    averageCorrect.style.setProperty("--borderBottomRight", "10px");
    averageCorrect.style.setProperty("--borderTopLeft","10px");
    averageCorrect.style.setProperty("--borderBottomLeft", "10px");
  }
  else {
    averageCorrect.style.setProperty("--borderTopRight", "0px");
    averageCorrect.style.setProperty("--borderBottomRight", "0px");
    averageCorrect.style.setProperty("--borderTopLeft","10px");
    averageCorrect.style.setProperty("--borderBottomLeft", "10px");
  }
  if (round(parseFloat(averageCorrect.getAttribute("value"))) == 0 && round(parseFloat(averageIncorrect.getAttribute("value"))) > 0) {
    averageIncorrect.style.setProperty("--borderTopRight", "10px");
    averageIncorrect.style.setProperty("--borderTopLeft", "10px");
    averageIncorrect.style.setProperty("--borderBottomRight", "10px");
    averageIncorrect.style.setProperty("--borderBottomLeft", "10px");
  }
  else {
    averageIncorrect.style.setProperty("--borderTopRight", "10px");
    averageIncorrect.style.setProperty("--borderTopLeft", "0px");
    averageIncorrect.style.setProperty("--borderBottomRight", "10px");
    averageIncorrect.style.setProperty("--borderBottomLeft", "0px");
  }
});

socket.on('receiveQuickAnswer',(ans,studId) => {
  //console.log(ans);
  //console.log(ans);
  if(quickStudentAnswers[ans] != undefined || ans == "â•¬"){
    //console.log("r");
    if(ans == "â•¬") {
      //quickStudentAnswers[ans] += 1;
      quickStudentsDone++;
      //quickAnswersIn++;
      document.getElementById('quickQuizQuestionAnswers').innerHTML = "<h1 class='is-size-3' style='color:white;'>"+mySet[quickQuestionNum].cA+"</h1><progress class='noRound' value="+quickStudentAnswers[mySet[quickQuestionNum].cA]+" max="+quickAnswersIn+"></progress><h1 class='in-size-3' style='color:#F28705;'>"+quickStudentAnswers[mySet[quickQuestionNum].cA]+"</h1><br>";
      for(var i=0; i<mySet[quickQuestionNum].fA.length; i++){
        document.getElementById('quickQuizQuestionAnswers').innerHTML += "<h1 class='is-size-3' style='color:white;'>"+mySet[quickQuestionNum].fA[i]+"</h1><progress class='noRound' value="+quickStudentAnswers[mySet[quickQuestionNum].fA[i]]+" max="+quickAnswersIn+"></progress><h1 class='in-size-3' style='color:#F28705;'>"+quickStudentAnswers[mySet[quickQuestionNum].fA[i]]+"</h1><br>";
      }
      if(quickStudentsDone >= studentCount){
        //console.log("e");


        socket.emit('quickSendcA',mySet[quickQuestionNum].cA);
        document.getElementById('quickShowAnswer').setAttribute('style','');
      }
    }
    else{
      quickStudentAnswers[ans] += 1;
      quickStudentsDone++;
      quickAnswersIn++;
      document.getElementById('quickQuizQuestionAnswers').innerHTML = "<h1 class='is-size-3' style='color:white;'>"+mySet[quickQuestionNum].cA+"</h1><progress class='noRound' value="+quickStudentAnswers[mySet[quickQuestionNum].cA]+" max="+quickAnswersIn+"></progress><h1 class='in-size-3' style='color:#F28705;'>"+quickStudentAnswers[mySet[quickQuestionNum].cA]+"</h1><br>";
      for(var i=0; i<mySet[quickQuestionNum].fA.length; i++){
        document.getElementById('quickQuizQuestionAnswers').innerHTML += "<h1 class='is-size-3' style='color:white;'>"+mySet[quickQuestionNum].fA[i]+"</h1><progress class='noRound' value="+quickStudentAnswers[mySet[quickQuestionNum].fA[i]]+" max="+quickAnswersIn+"></progress><h1 class='in-size-3' style='color:#F28705;'>"+quickStudentAnswers[mySet[quickQuestionNum].fA[i]]+"</h1><br>";
      }
      if(quickStudentsDone >= studentCount){
        //console.log("e");

        socket.emit('quickSendcA',mySet[quickQuestionNum].cA);
        document.getElementById('quickShowAnswer').setAttribute('style','');
        if(quickQuestionNum == mySet.length-1){
          document.getElementById('quickNextQuestion').innerHTML = "Finish";
        }
      }
      //console.log("r");
    }
  }
});

socket.on('receiveQuickPoints', (points,id,students) => {
  quickStudentPointsCount++;
  for(var i=0; i<students.length; i++){
    if(students != undefined){
      if(students[i].id == id){
        quickStudentPoints.push({name:students[i].name,points:points});
      }
    }
  }
  if(quickStudentPointsCount >= students.length){
    quickStudentPoints = quickStudentPoints.slice().sort(compareValues("points", "desc"));
    document.getElementById("leaderboardContainer").innerHTML = "";
    for(var i=0; i<quickStudentPoints.length; i++){
      if (i > 4) break;
      var html = "<center><h1 id='leader' class='is-size-4 has-text-weight-light has-text-warning'>"+(i+1)+". "+quickStudentPoints[i].name+" - "+parseInt(quickStudentPoints[i].points)+"</h1></center>";
      document.getElementById("leaderboardContainer").innerHTML += html;
    }
    socket.emit('sendQuickLeaderboard',quickStudentPoints);
  }
});

function quickStartNextQuestion(){
  quickQuestionNum++;
  if(quickQuestionNum >= mySet.length){
    endQuickQuiz();
  }
  else{
    quickQuestion();
  }
}

function endQuickQuiz(){
  endRoom();
}

function quickQuestion(){
  socket.emit('askQuickQuestion',mySet[quickQuestionNum],mySettings);
  quickStudentAnswers = [];
  quickStudentPoints = [];
  quickStudentsDone = 0;
  quickStudentPointsCount = 0;
  quickAnswersIn = 0;
  quickStudentAnswers[mySet[quickQuestionNum].cA] = 0;
  document.getElementById('quickGameRoom').setAttribute('style','');
  document.getElementById('quickShowAnswer').setAttribute('style','display:none;');
  document.getElementById('quickQuestionShowed').innerHTML = mySet[quickQuestionNum].q;
  document.getElementById('quickQuizQuestionAnswers').innerHTML = "<h1 class='is-size-3' style='color:white;'>"+mySet[quickQuestionNum].cA+"</h1><progress style='color:#F28705;' class='noRound' value='0' max='1'></progress><br><br>";
  for(var i=0; i<mySet[quickQuestionNum].fA.length; i++){
    quickStudentAnswers[mySet[quickQuestionNum].fA[i]] = 0;
    document.getElementById('quickQuizQuestionAnswers').innerHTML += "<h1 class='is-size-3' style='color:white;'>"+mySet[quickQuestionNum].fA[i]+"</h1><progress style='color:#F28705;' class='noRound' value='0' max='1'></progress><br><br>";
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
        document.getElementById('lobbyColumn1Scores').innerHTML += "<h1 class='is-size-4 has-text-weight-semibold has-text-warning'>"+currentRoom.students[i].name+"</h1><div style='position:relative;//right:75px;'><progress id='correctProgress"+studentCount+"' style='position:absolute;' value='0' max='150'></progress><progress class='other' style='--xOffset: 0;' id='wrongProgress"+studentCount+"' value='0' max='150'></progress></div><br>";
      }
      else if(columnNum == 2){
        columnNum++;
        document.getElementById('lobbyColumn2Scores').innerHTML += "<h1 class='is-size-4 has-text-weight-semibold has-text-warning'>"+currentRoom.students[i].name+"</h1><div style='position:relative;//right:75px;'><progress id='correctProgress"+studentCount+"' style='position:absolute;' value='0' max='150'></progress><progress class='other' style='--xOffset: 0;' id='wrongProgress"+studentCount+"' value='0' max='150'></progress></div><br>";
      }
      else{
        columnNum = 1;
        document.getElementById('lobbyColumn3Scores').innerHTML += "<h1 class='is-size-4 has-text-weight-semibold has-text-warning'>"+currentRoom.students[i].name+"</h1><div style='position:relative;//right:75px;'><progress id='correctProgress"+studentCount+"' style='position:absolute;' value='0' max='150'></progress><progress class='other' style='--xOffset: 0;' id='wrongProgress"+studentCount+"' value='0' max='150'></progress></div><br>";
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

socket.on('studentConnectionIssues', (student) => {
  var elem = document.createElement("h1");
  elem.setAttribute("style","color:red;");
  elem.setAttribute("class","is-size-3");
  elem.setAttribute("id","connectionIssue"+student.id);
  elem.innerHTML = student.name + " is having connection issues...<br/>";
  document.getElementById("studentConnectionIssuesDiv").appendChild(elem);
  setTimeout((documentID) => {
    document.getElementById(documentID).remove();
  }, 2500, "connectionIssue"+student.id);
});

socket.on('joinLobby',(room,id_) => {
  document.getElementById('mainHome').setAttribute("style","display:none");
  document.getElementById('lobbyRoom').setAttribute("style","");
  //console.log("E");
  id = id_;
  gameCode = room.id;
  teacher = true;

  document.getElementById('lobbyCodeDisplay').innerHTML = "Code: "+room.id.toUpperCase();
  //document.getElementById('lobbyWaitStudentList').innerHTML = "";
  gameType = room.settings.gameType;

  /*for(var i=0; i<room.students.length; i++){
    if(room.students[i]){
      document.getElementById('studentLobbyList').innerHTML += "<p1>"+room.students[i].name+"</p1><br>";
    }
  }*/
});

function kickStudent(id){
  socket.emit("kickStudent",id);
}

var studentCount = 0;
socket.on('updateLobby',(room, previousStudents, socketID) => {
  var columnNum = 1;
  //document.getElementById('lobbyCode').innerHTML = room.id;
  //document.getElementById('lobbyWaitStudentList').innerHTML = "";
  document.getElementById("startTheRoom").removeAttribute("disabled");
  document.getElementById("studentCountTeacher").innerHTML = room.students.length + " Students";
  if (room.students.length == 0) document.getElementById("startTheRoom").setAttribute("disabled", "true");
  document.getElementById('lobbyColumn1').innerHTML = "";
  document.getElementById('lobbyColumn2').innerHTML = "";
  document.getElementById('lobbyColumn3').innerHTML = "";
  studentCount = 0;
  var studentID;
  var studentName;
  var correctStudent;
  var incorrectStudent;

  if (previousStudents != null && socketID != null) {
    for (var i=0; i<previousStudents.length; i++) {
      if (previousStudents[i].id == socketID) {
        studentID = i;
        studentName = previousStudents[i].name;
        correctStudent = document.getElementById(correctStudentIDToDivName[studentID]);
        incorrectStudent = document.getElementById(wrongStudentIDToDivName[studentID]);
        var info = document.createElement("div");
        info.innerHTML = "<p class='is-size-6 has-text-grey-light'>Disconnected</p>";
        if (correctStudent != undefined && correctStudent != null) {
          correctStudent.parentElement.appendChild(info);
        }
      }
    }
  }

  for(var i=0; i<room.students.length; i++){
    if(room.students[i]){
      //document.getElementById('lobbyWaitStudentList').innerHTML += "<p1> "+room.students[i].name+" </p1>";
      if(columnNum == 1) {
        columnNum++;
        //<div style='position:relative;right:75px;'><progress id='correctProgress"+studentCount+"' value='0' max='150'></progress><progress class='other' style='left:0;width:0px;' id='wrongProgress"+studentCount+"' value='0' max='150'></progress></div>
        document.getElementById('lobbyColumn1').innerHTML += "<h1 class='is-size-4 canKick has-text-weight-semibold has-text-warning' onclick='kickStudent("+room.students[i].id+")'>"+room.students[i].name+"</h1><br>";
      }
      else if(columnNum == 2){
        columnNum++;
        document.getElementById('lobbyColumn2').innerHTML += "<h1 class='is-size-4 canKick has-text-weight-semibold has-text-warning' onclick='kickStudent("+room.students[i].id+")'>"+room.students[i].name+"</h1><br>";
      }
      else{
        columnNum = 1;
        document.getElementById('lobbyColumn3').innerHTML += "<h1 class='is-size-4 canKick has-text-weight-semibold has-text-warning' onclick='kickStudent("+room.students[i].id+")'>"+room.students[i].name+"</h1><br>";
      }
      studentCount++;
    }
  }
  if(gameStarted){
    if(quickStudentsDone >= studentCount && mySettings.gameType == "quick"){
      socket.emit('quickSendcA',mySet[quickQuestionNum].cA);
      document.getElementById('quickShowAnswer').setAttribute('style','');
    }
    if(0 >= studentCount && mySettings.gameType == "quick"){
      alert("All Students Have Left");
    }
  }
});
