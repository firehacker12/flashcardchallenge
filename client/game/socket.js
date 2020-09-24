var socket = io(":2000");
var id = null;

function roomMake(){
  let nameTmp = document.getElementById('enterName').value;
  socket.emit('createRoom','yes',nameTmp,'settings');
}

function roomJoin(){
  let roomTmp = document.getElementById('enterCode').value;
  let nameTmp = document.getElementById('enterName').value;
  socket.emit('joinRoom',roomTmp,nameTmp);
}

socket.on('testCode',(code) => {
  console.log(code);
});

socket.on('joinLobby',(room,id_) => {
  document.getElementById('joinMake').setAttribute("style","display:none");
  document.getElementById('lobbyScreen').setAttribute("style","");
  id = id_;
  document.getElementById('lobbyCode').innerHTML = room.id;
  document.getElementById('studentLobbyList').innerHTML = "";
  for(var i=0; i<room.students.length; i++){
    document.getElementById('studentLobbyList').innerHTML += "<p1>"+room.students[i].name+"</p1><br>";
  }
});

socket.on('updateLobby',(room) => {
  document.getElementById('lobbyCode').innerHTML = room.id;
  document.getElementById('studentLobbyList').innerHTML = "";
  for(var i=0; i<room.students.length; i++){
    document.getElementById('studentLobbyList').innerHTML += "<p1>"+room.students[i].name+"</p1><br>";
  }
});
