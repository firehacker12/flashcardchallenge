var socket = io(":2000");
var id = null;
var teacher = false;
var gameCode = null;
var picDrawer = false;
var mySet;
var mainMusic = new Audio("../audio/main_quick3.wav");
var clickSound = new Audio("../audio/click.wav");
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
  let roomTmp = (document.getElementById('enterCode').value.trim()).toLowerCase();
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
  clickSound.play();
  socket.emit('sendQuickAnswer',ans);
  document.getElementById('quickQuizWait').setAttribute("style","");
  document.getElementById('quickQuizWaitMain').setAttribute("style","");
  document.getElementById('quickQuizWaitBoard').setAttribute("style","display:none;");
  document.getElementById('quickQuiz').setAttribute("style","display:none;");
  quickAnswerSave = ans;
  quickGameTrue = false;
  //console.log("e");
}

function quickFailed(){
  socket.emit('sendQuickAnswer',"╬");
  document.getElementById('quickQuizWait').setAttribute("style","");
  document.getElementById('quickQuizWaitMain').setAttribute("style","");
  document.getElementById('quickQuizWaitBoard').setAttribute("style","display:none;");
  document.getElementById('quickQuiz').setAttribute("style","display:none;");
  quickAnswerSave = "No Answer Selected";
  quickGameTrue = false;

}

socket.on('receiveQuestionsTest', (set) => {
  mySet = set;
  setupTest(mySet);
});

socket.on('receiveQuickLeaderboard', (board) => {
  mainMusic.pause();
  mainMusic.currentTime = 0;
  document.getElementById("leaderboardContainer").innerHTML = "";
  for(var i=0; i<board.length; i++){
    if (i > 4) break;
    var html = "<center><h1 id='leader' class='is-size-4 has-text-weight-light has-text-warning'>"+(i+1)+". "+board[i].name+" - "+parseInt(board[i].points)+"</h1></center>";
    document.getElementById("leaderboardContainer").innerHTML += html;
  }
});

socket.on('checkQuickAnswer', (cA) => {
  if(quickAnswerSave == cA){
    quickPoints += 50 + (((parseInt(gameSetting.rounds.split(' ')[0])-quickTimer)/parseInt(gameSetting.rounds.split(' ')[0]))*50);
    for(var i=0; i<document.querySelectorAll('#quickQuizPoints').length; i++){
      document.querySelectorAll('#quickQuizPoints')[i].innerHTML = floor(quickPoints)+"";
    }
    document.getElementById('quickQuizWaitCI').innerHTML = "Correct";
    document.getElementById('quickQuizWaitCI').setAttribute('class',"is-size-1");
    document.getElementById("quickQuizWaitCI").setAttribute("style","color:#84D904;");
  }
  else{
    document.getElementById('quickQuizWaitCI').innerHTML = "Incorrect";
    document.getElementById('quickQuizWaitCI').setAttribute('class',"is-size-1");
    document.getElementById("quickQuizWaitCI").setAttribute("style","color:#F2441D;");
  }
  socket.emit('sendQuickPoints',quickPoints);
  document.getElementById('quickQuizWait').setAttribute('style','');
  document.getElementById('quickQuizWaitMain').setAttribute("style","display:none;");
  document.getElementById('quickQuizWaitBoard').setAttribute("style","");
  document.getElementById('quickQuizWaitAnswer').innerHTML = cA;
  document.getElementById('quickQuizWaitYourAnswer').innerHTML = quickAnswerSave;

});

setInterval(function(){
  if(quickGameTrue){
    quickTimer += 1;
    document.getElementById('quickQuizTime').innerHTML = parseInt(gameSetting.rounds.split(' ')[0])-quickTimer;
    if(parseInt(gameSetting.rounds.split(' ')[0])-quickTimer <= 0){
      quickFailed();
    }
  }
}, 1000);

function runTimer() {

}

socket.on('wrongCode',()=> {
  document.getElementById('enterCode').value = "";
  document.getElementById('codeError').setAttribute("style","color:red;");
});

socket.on('answerQuickQuestion', (tmpSet,settings) => {
  //console.log(tmpSet.q);
  socket.emit('receivedQuickQuestion');
  document.getElementById('quickQuiz').setAttribute('style','');
  document.getElementById('quickQuestionShowed').innerHTML = tmpSet.q;
  document.getElementById('quickQuestionWaitShowed').innerHTML = tmpSet.q;
  if(tmpSet.fA.length<3){
    document.getElementById('quickQuestionHolder2').setAttribute('style','');
    document.getElementById('quickQuestionHolder4').setAttribute('style','display:none;');
    let r = random();
    var falseAnswer1 = tmpSet.fA[floor(random(0,tmpSet.fA.length))];
    if(r > 0.5){
      document.getElementById('quickButton21').innerHTML = tmpSet.cA;
      document.getElementById('quickButton21').setAttribute("onclick","sendQuickAnswer('"+tmpSet.cA+"');");
      document.getElementById('quickButton22').setAttribute("onclick","sendQuickAnswer('"+falseAnswer1+"');");
      document.getElementById('quickButton22').innerHTML = falseAnswer1;
    }
    else{
      document.getElementById('quickButton22').innerHTML = tmpSet.cA;
      document.getElementById('quickButton22').setAttribute("onclick","sendQuickAnswer('"+tmpSet.cA+"');");
      document.getElementById('quickButton21').setAttribute("onclick","sendQuickAnswer('"+falseAnswer1+"');");
      document.getElementById('quickButton21').innerHTML = falseAnswer1;
    }
  }
  else{
    document.getElementById('quickQuestionHolder2').setAttribute('style','display:none;');
    document.getElementById('quickQuestionHolder4').setAttribute('style','');
    let r = random();

    var falseAnswer1 = tmpSet.fA[floor(random(0,tmpSet.fA.length))];
    var falseAnswer2;
    var falseAnswer3;
    console.log(tmpSet.fA);
    var copyArray = shuffle(tmpSet.fA);
    console.log(copyArray);
    for(var i=0; i<copyArray.length; i++) {
      if (copyArray[i] != falseAnswer1) {
        falseAnswer2 = copyArray[i];
        break;
      }
    }
    copyArray = shuffle(tmpSet.fA);
    for(var i=0; i<copyArray.length; i++) {
      if (copyArray[i] != falseAnswer1 && copyArray[i] != falseAnswer2) {
        falseAnswer3 = copyArray[i];
        break;
      }
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

  mainMusic.play();
});

function shuffle(array) {
  var currentIndex = array.length, temporaryValue, randomIndex;

  // While there remain elements to shuffle...
  while (0 !== currentIndex) {

    // Pick a remaining element...
    randomIndex = Math.floor(Math.random() * currentIndex);
    currentIndex -= 1;

    // And swap it with the current element.
    temporaryValue = array[currentIndex];
    array[currentIndex] = array[randomIndex];
    array[randomIndex] = temporaryValue;
  }

  return array;
}

socket.on('testCode',(code) => {
  console.log(code);
});

socket.on('roomClosed', () => {
  location.reload();
});

socket.on('startGame', (room) => {
  document.getElementById("lobbyScreen").setAttribute('style','display:none;');
  //console.log(room.settings.gameType);
  gameSetting = room.settings;
  //console.log(room.teacher);
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
  document.getElementById("lobbyName").innerHTML = room.teacher;
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
