var socket = io(":2000");
var id = null;
var teacher = false;
var gameCode = null;



function roomMake(){
  let nameTmp = document.getElementById('enterName').value;
  let tmpSet = [
    {q:"2+2=?",cA:"fish",fA:["4","2","3","1"]},
    {q:"yes or no",cA:"maybe",fA:["yes","no","si"]},
    {q:"funny",cA:"funny",fA:["very funny","not funny","this is a test to see how long these should be and what i should set the limit to"]}
    //{q:"",cA:"",fA:[""]}
  ];
  let settingsTmp = {gameType:"matching",rounds:5};

  socket.emit('createRoom',tmpSet,nameTmp,settingsTmp);
}

function endRoom(){
  if(teacher){
    socket.emit('endRoom',gameCode);
  }
}

socket.on('joinLobby',(room,id_) => {
  //document.getElementById('joinMake').setAttribute("style","display:none");
  //document.getElementById('lobbyScreen').setAttribute("style","");
  id = id_;
  gameCode = room.id;
  teacher = true;

  //document.getElementById('lobbyCode').innerHTML = room.id;
  //document.getElementById('studentLobbyList').innerHTML = "";
  gameType = room.settings.gameType;

  /*for(var i=0; i<room.students.length; i++){
    if(room.students[i]){
      document.getElementById('studentLobbyList').innerHTML += "<p1>"+room.students[i].name+"</p1><br>";
    }
  }*/
});


socket.on('updateLobby',(room) => {
  //document.getElementById('lobbyCode').innerHTML = room.id;
  //document.getElementById('studentLobbyList').innerHTML = "";
  for(var i=0; i<room.students.length; i++){
    if(room.students[i]){
      //document.getElementById('studentLobbyList').innerHTML += "<p1>"+room.students[i].name+"</p1><br>";
    }
  }
});
