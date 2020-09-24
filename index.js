var express = require('express');
var app = express();
var serv = require('http').Server(app);
var uid = require('uid');

app.use('/', express.static('client'));

var portnumber = 2000;

serv.listen(portnumber);
console.log("Server started on port " + portnumber);

var SOCKET_LIST = {};
var STUDENT_LIST = {};
var ROOM_LIST = {};

var Student = function(id){
	var self = {
		id:id
	}
	return self;
}

var Room = function(setId,teacherName, teacherId,settings){
  var id = uid(6);
  var self = {
    id:id,
    teacher:teacherName,
		teacherId:teacherId,
		settings:settings,
		students:[]
  }

  return self;
}

var StudentNumber = 0;

var io = require('socket.io')(serv,{});
io.sockets.on('connection', function(socket){
	socket.id = StudentNumber;
  StudentNumber++;
	console.log("someone connected");
	SOCKET_LIST[socket.id] = socket;
	var student = Student(socket.id);
	STUDENT_LIST[socket.id] = student;

  socket.on('createRoom',(setId,teacherName,settings) => {
		var tmpRoom = new Room(setId,teacherName,socket.id,settings);
		ROOM_LIST[tmpRoom.id] = tmpRoom;
		socket.emit('joinLobby',ROOM_LIST[tmpRoom.id],socket.id);
  });

  socket.on('joinRoom',(code,name)=> {
		if(ROOM_LIST[code].id == code){
			ROOM_LIST[code].students.push({name:name,id:socket.id});
			socket.emit("joinLobby",ROOM_LIST[code],socket.id);
			SOCKET_LIST[ROOM_LIST[code].teacherId].emit('updateLobby',ROOM_LIST[code]);
			for(var i=0; i<ROOM_LIST[code].students.length; i++){
				if(socket.id != ROOM_LIST[code].students[i].id){
					SOCKET_LIST[ROOM_LIST[code].students[i].id].emit('updateLobby',ROOM_LIST[code]);
				}
			}
		}
  });

	socket.on('endRoom', (code) => {
		if(ROOM_LIST[code]){
			if(ROOM_LIST[code].teacherId == socket.id){
				for(var i=0; i<ROOM_LIST[code].students.length; i++){
					SOCKET_LIST[ROOM_LIST[code].students[i].id].emit('roomClosed');
				}
				socket.emit('roomClosed');
			}
		}
	});

	socket.on('disconnect',function() {

	});
});
