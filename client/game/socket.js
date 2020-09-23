var socket = io(":2000");

function roomMake(){
  socket.emit('createRoom',{'a'},'yes','teacher',{'settings'});
}

function roomJoin(){
  let roomTmp = document.getElementById('enterCode').value;
  let nameTmp = document.getElementById('enterName').value;
  socket.emit('joinRoom',code,name);
}
