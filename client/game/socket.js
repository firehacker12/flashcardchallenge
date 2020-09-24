var socket = io(":2000");
var id = null;
var teacher = false;
var gameCode = null;

function roomMake(){
  let nameTmp = document.getElementById('enterName').value;
  socket.emit('createRoom','yes',nameTmp,'settings');
}

function endRoom(){
  if(teacher){
    socket.emit('endRoom',gameCode);
  }
}

function roomJoin(){
  let roomTmp = document.getElementById('enterCode').value;
  let nameTmp = document.getElementById('enterName').value;
  socket.emit('joinRoom',roomTmp,nameTmp);
}

function startGame(){

}

socket.on('testCode',(code) => {
  console.log(code);
});

socket.on('roomClosed',()=>{
  document.getElementById('joinMake').setAttribute("style","");
  document.getElementById('lobbyScreen').setAttribute("style","display:none");
  gameCode = false;
  teacher = false;
  id = null;
});

socket.on('joinLobby',(room,id_) => {
  document.getElementById('joinMake').setAttribute("style","display:none");
  document.getElementById('lobbyScreen').setAttribute("style","");
  id = id_;
  gameCode = room.id;
  if(id_ == room.teacherId){
    teacher = true;
  }
  document.getElementById('lobbyCode').innerHTML = room.id;
  document.getElementById('studentLobbyList').innerHTML = "";
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
      document.getElementById('studentLobbyList').innerHTML += "<p1>"+room.students[i].name+"</p1><br>";
    }
  }
});
