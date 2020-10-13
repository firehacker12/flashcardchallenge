var socket = io(":2000");
var id = null;
var teacher = true;
var gameCode = null;
var game = null;
var quickQuestionNum = 0;
var mySet = null;
var mySettings = null;

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

  console.log(mySet);
  switch(room.settings.gameType) {
    case "test":
      setupTest(mySet);
      break;
  }
  mySettings = room.settings;
  if(game == "quick"){
    quickQuestionNum = 0;
    quickQuestion();
  }
});

function setupTest(set) {
  socket.emit('sendQuestionsTest',set);
}

function quickQuestion(){
  socket.emit('askQuickQuestion',mySet[quickQuestionNum],mySettings);
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


socket.on('updateLobby',(room) => {
  var columnNum = 1;
  //document.getElementById('lobbyCode').innerHTML = room.id;
  //document.getElementById('lobbyWaitStudentList').innerHTML = "";
  document.getElementById("startTheRoom").removeAttribute("disabled");
  console.log(room.students.length);
  if (room.students.length == 0) document.getElementById("startTheRoom").setAttribute("disabled", "true");
  document.getElementById('lobbyColumn1').innerHTML = "";
  document.getElementById('lobbyColumn2').innerHTML = "";
  document.getElementById('lobbyColumn3').innerHTML = "";
  for(var i=0; i<room.students.length; i++){
    if(room.students[i]){
      //document.getElementById('lobbyWaitStudentList').innerHTML += "<p1> "+room.students[i].name+" </p1>";
      if(columnNum == 1){
        columnNum++;
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
    }
  }
});
