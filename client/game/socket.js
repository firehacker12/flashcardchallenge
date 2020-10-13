var socket = io(":2000");
var id = null;
var teacher = false;
var gameCode = null;
var picDrawer = false;
var mySet;
var studentAnswers = [];

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

function roomJoin(){
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

socket.on('receiveQuestionsTest', (set) => {
  mySet = set;
  setupTest(mySet);
});

socket.on('studentSentQuestion', (questionNumber, answer) => {

});

socket.on('wrongCode',()=> {
  document.getElementById('enterCode').value = "";
  document.getElementById('codeError').setAttribute("style","color:red;");
});

socket.on('answerQuickQuestion', (tmpSet,settings) => {
  //console.log(tmpSet.q);
  document.getElementById('quickQuiz').setAttribute('style','');

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
