var socket = io(":2000");

function roomMake(){
  socket.emit('createRoom','yes','teacher','settings');
}

function roomJoin(){
  let roomTmp = document.getElementById('enterCode').value;
  let nameTmp = document.getElementById('enterName').value;
  socket.emit('joinRoom',roomTmp,nameTmp);
}

socket.on('testCode',(code) => {
  console.log(code);
});

socket.on('joinLobby',(code) => {
  document.getElementById('joinMake').setAttribute("style","display:none");
  document.getElementById('lobbyScreen').setAttribute("style","");
});
