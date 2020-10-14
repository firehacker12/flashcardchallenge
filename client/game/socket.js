var socket = io(":2000");
var id = null;
var teacher = false;
var gameCode = null;
var picDrawer = false;
var mySet;
var studentAnswers = [];
var quickPoints = 0;
var quickAnswerSave = null;
var quickTimer = 0;
var quickGameTrue = false;
var gameSetting = null;

function switchName(){
  //document.getElementById("codeE").setAttribute("style","display:none;");
  if(document.getElementById('enterName').value.trim() != ""){
    document.getElementById("nameE").setAttribute("style","display:none;");
  }
  else{
    document.getElementById('enterName').value = "";
    document.getElementById('nameError').setAttribute("style","color:red;");
  }
}

function overrideRoomJoin(customName, customCode) {
  let roomTmp = customCode;
  let nameTmp = customName;
  if(roomTmp.length == 6){
    socket.emit('joinRoom',roomTmp,nameTmp);
  }
  else{
    document.getElementById('enterCode').value = "";
    document.getElementById('codeError').setAttribute("style","color:red;");
  }
}

function roomJoin() {
  let roomTmp = document.getElementById('enterCode').value.trim();
  let nameTmp = document.getElementById('enterName').value.trim();
  if(roomTmp.length == 6){
    socket.emit('joinRoom',roomTmp,nameTmp);
  }
  else{
    document.getElementById('enterCode').value = "";
    document.getElementById('codeError').setAttribute("style","color:red;");
  }
}

function sendQuickAnswer(ans){
  socket.emit('sendQuickAnswer',ans);
  document.getElementById('quickQuizWait').setAttribute("style","");
  document.getElementById('quickQuiz').setAttribute("style","display:none;");
  quickAnswerSave = ans;
  quickGameTrue = false;
  //console.log("e");
}

function quickFailed(){
  socket.emit('sendQuickAnswer',"╬");
  quickAnswerSave = "╬";
  quickGameTrue = false;
  quickTimer = 0;
  document.getElementById('quickQuizWait').setAttribute("style","");
  document.getElementById('quickQuiz').setAttribute("style","display:none;");
}

socket.on('receiveQuestionsTest', (set) => {
  mySet = set;
  setupTest(mySet);
});

socket.on('checkQuickAnswer', (cA) => {
  if(quickAnswerSave == cA){
    quickPoints += 50 + (((parseInt(gameSetting.rounds.split(' ')[0])-quickTimer)/parseInt(gameSetting.rounds.split(' ')[0]))*50);
    for(var i=0; i<document.querySelectorAll('#quickQuizPoints').length; i++){
      document.querySelectorAll('#quickQuizPoints')[i].innerHTML = floor(quickPoints)+" Points";
    }

  }
});

setInterval(function(){ if(quickGameTrue){quickTimer += 0.01; document.getElementById('quickQuizTime').innerHTML = nf(parseInt(gameSetting.rounds.split(' ')[0])-quickTimer,1,2) + " Seconds"; if(parseInt(gameSetting.rounds.split(' ')[0])-quickTimer <= 0){quickFailed();} } }, 10);

socket.on('wrongCode',()=> {
  document.getElementById('enterCode').value = "";
  document.getElementById('codeError').setAttribute("style","color:red;");
});

socket.on('answerQuickQuestion', (tmpSet,settings) => {
  //console.log(tmpSet.q);
  document.getElementById('quickQuiz').setAttribute('style','');
  document.getElementById('quickQuestionShowed').innerHTML = tmpSet.q;
  if(tmpSet.fA.length<3){
    document.getElementById('quickQuestionHolder2').setAttribute('style','');
    let r = random();
    if(r > 0.5){
      document.getElementById('quickButton21').innerHTML = tmpSet.cA;
      document.getElementById('quickButton22').innerHTML = tmpSet.fA[floor(random(0,tmpSet.fA.length))];
    }
    else{
      document.getElementById('quickButton22').innerHTML = tmpSet.cA;
      document.getElementById('quickButton21').innerHTML = tmpSet.fA[floor(random(0,tmpSet.fA.length))];
    }
  }
  else{
    document.getElementById('quickQuestionHolder4').setAttribute('style','');
    let r = random();

    var falseAnswer1 = tmpSet.fA[floor(random(0,tmpSet.fA.length))];
    var falseAnswer2 = tmpSet.fA[floor(random(0,tmpSet.fA.length))]
    while (falseAnswer2 == falseAnswer1) {
      falseAnswer2 = tmpSet.fA[floor(random(0,tmpSet.fA.length))];
    }
    var falseAnswer3 = tmpSet.fA[floor(random(0,tmpSet.fA.length))];
    while (falseAnswer2 == falseAnswer3 || falseAnswer1 == falseAnswer3) {
      falseAnswer3 = tmpSet.fA[floor(random(0,tmpSet.fA.length))];
    }
    if(r < 0.25){
      document.getElementById('quickButton41').innerHTML = tmpSet.cA;
      document.getElementById('quickButton41').setAttribute("onclick","sendQuickAnswer('"+tmpSet.cA+"');");
      document.getElementById('quickButton42').innerHTML = falseAnswer1;
      document.getElementById('quickButton42').setAttribute("onclick","sendQuickAnswer('"+falseAnswer1+"');");
      document.getElementById('quickButton43').innerHTML = falseAnswer2;
      document.getElementById('quickButton43').setAttribute("onclick","sendQuickAnswer('"+falseAnswer2+"');");
      document.getElementById('quickButton44').innerHTML = falseAnswer3;
      document.getElementById('quickButton44').setAttribute("onclick","sendQuickAnswer('"+falseAnswer3+"');");
    }
    else if(r < 0.5){
      document.getElementById('quickButton42').innerHTML = tmpSet.cA;
      document.getElementById('quickButton42').setAttribute("onclick","sendQuickAnswer('"+tmpSet.cA+"');");
      document.getElementById('quickButton41').innerHTML = falseAnswer1;
      document.getElementById('quickButton41').setAttribute("onclick","sendQuickAnswer('"+falseAnswer1+"');");
      document.getElementById('quickButton43').innerHTML = falseAnswer2;
      document.getElementById('quickButton43').setAttribute("onclick","sendQuickAnswer('"+falseAnswer2+"');");
      document.getElementById('quickButton44').innerHTML = falseAnswer3;
      document.getElementById('quickButton44').setAttribute("onclick","sendQuickAnswer('"+falseAnswer3+"');");
    }
    else if(r < 0.75){
      document.getElementById('quickButton43').innerHTML = tmpSet.cA;
      document.getElementById('quickButton43').setAttribute("onclick","sendQuickAnswer('"+tmpSet.cA+"');");
      document.getElementById('quickButton42').innerHTML = falseAnswer1;
      document.getElementById('quickButton42').setAttribute("onclick","sendQuickAnswer('"+falseAnswer1+"');");
      document.getElementById('quickButton41').innerHTML = falseAnswer2;
      document.getElementById('quickButton41').setAttribute("onclick","sendQuickAnswer('"+falseAnswer2+"');");
      document.getElementById('quickButton44').innerHTML = falseAnswer3;
      document.getElementById('quickButton44').setAttribute("onclick","sendQuickAnswer('"+falseAnswer3+"');");
    }
    else{
      document.getElementById('quickButton44').innerHTML = tmpSet.cA;
      document.getElementById('quickButton44').setAttribute("onclick","sendQuickAnswer('"+tmpSet.cA+"');");
      document.getElementById('quickButton42').innerHTML = falseAnswer1;
      document.getElementById('quickButton42').setAttribute("onclick","sendQuickAnswer('"+falseAnswer1+"');");
      document.getElementById('quickButton43').innerHTML = falseAnswer2;
      document.getElementById('quickButton43').setAttribute("onclick","sendQuickAnswer('"+falseAnswer2+"');");
      document.getElementById('quickButton41').innerHTML = falseAnswer3;
      document.getElementById('quickButton41').setAttribute("onclick","sendQuickAnswer('"+falseAnswer3+"');");
    }

  }
  quickGameTrue = true;
  quickTimer = 0;
  quickAnswerSave = null;

});

socket.on('testCode',(code) => {
  console.log(code);
});

socket.on('roomClosed', () => {
  location.reload();
});

socket.on('startGame', (room) => {
  document.getElementById("lobbyScreen").setAttribute('style','display:none;');
  console.log(room.settings.gameType);
  gameSetting = room.settings;
  switch(room.settings.gameType) {
    case "pic":
      document.getElementById('gameCavas').setAttribute('style',"");
      if(room.students[0].id == id){
        picDrawer = true;
        gameType = room.settings.gameType;
        going = true;
      }
      break;
    case "test":
      //console.log(mySet);
      //setupTest(mySet);
      break;
  }
});

socket.on('joinLobby',(room,id_) => {
  document.getElementById('joinMake').setAttribute("style","display:none");
  document.getElementById('lobbyScreen').setAttribute("style","");
  id = id_;
  gameCode = room.id;
  //if(id_ == room.teacherId){
    //teacher = true;
  //}
  document.getElementById('lobbyCode').innerHTML = room.id;
  document.getElementById('studentLobbyList').innerHTML = "";
  gameType = room.settings.gameType;
  for(var i=0; i<room.students.length; i++){
    if(room.students[i]){
      document.getElementById('studentLobbyList').innerHTML += "<p1>"+room.students[i].name+"</p1><br>";
    }
  }
});

socket.on('updateLobby',(room) => {
  document.getElementById('lobbyCode').innerHTML = room.id;
  document.getElementById('studentLobbyList').innerHTML = "";
  for(var i=0; i<room.students.length; i++){
    if(room.students[i]){
      document.getElementById('studentLobbyList').innerHTML += "<p1>"+room.students[i].name+"</p1><br><br>";
    }
  }
});
