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
		id:id,
		room:null
	}
	return self;
}

var Room = function(setId,teacherName, teacherId,settings){
  var id = uid(6);
  var self = {
    id:id,
		setId:setId,
    teacher:teacherName,
		teacherId:teacherId,
		settings:settings,
		students:[],
		gameStarted:false
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
		STUDENT_LIST[socket.id].room = tmpRoom.id;
  });

  socket.on('joinRoom',(code,name)=> {
		if(ROOM_LIST[code]){
			if(ROOM_LIST[code].id == code){
				if(ROOM_LIST[code].gameStarted == false){
					ROOM_LIST[code].students.push({name:name,id:socket.id});
					socket.emit("joinLobby",ROOM_LIST[code],socket.id);
					STUDENT_LIST[socket.id].room = ROOM_LIST[code].id;
					SOCKET_LIST[ROOM_LIST[code].teacherId].emit('updateLobby',ROOM_LIST[code]);
					for(var i=0; i<ROOM_LIST[code].students.length; i++){
						if(ROOM_LIST[code].students[i]){
							if(socket.id != ROOM_LIST[code].students[i].id){
								SOCKET_LIST[ROOM_LIST[code].students[i].id].emit('updateLobby',ROOM_LIST[code]);
							}
						}
					}
				}
			}
		}
		else{
			socket.emit('wrongCode');
		}
  });

	socket.on('startTheGame', (info) => {
		if(ROOM_LIST[STUDENT_LIST[socket.id].room]){
			if(ROOM_LIST[STUDENT_LIST[socket.id].room].teacherId == socket.id){
				for(var i=0; i<ROOM_LIST[STUDENT_LIST[socket.id].room].students.length; i++){
					if(ROOM_LIST[STUDENT_LIST[socket.id].room].students[i]){
						SOCKET_LIST[ROOM_LIST[STUDENT_LIST[socket.id].room].students[i].id].emit('startGame',ROOM_LIST[STUDENT_LIST[socket.id].room]);

				  }
				}
				socket.emit('startGame',ROOM_LIST[STUDENT_LIST[socket.id].room]);
				ROOM_LIST[STUDENT_LIST[socket.id].room].gameStarted = true;
			}
		}
	});

	socket.on('sendQuestionsTest', (set) => {
		if(STUDENT_LIST[socket.id]){
			if(ROOM_LIST[STUDENT_LIST[socket.id].room]){
				var room = STUDENT_LIST[socket.id].room;
				for(var i=0; i<ROOM_LIST[room].students.length; i++){
					if(ROOM_LIST[room].students){
						SOCKET_LIST[ROOM_LIST[room].students[i].id].emit('receiveQuestionsTest',set);
					}
				}
			}
		}
	});

	socket.on('sendQuickAnswer', (ans) =>{
		if(STUDENT_LIST[socket.id]){
			if(ROOM_LIST[STUDENT_LIST[socket.id].room]){
				console.log("e");
				SOCKET_LIST[ROOM_LIST[STUDENT_LIST[socket.id].room].teacherId].emit('receiveQuickAnswer',ans,socket.id);
			}
		}
	});

	socket.on('quickSendcA', (cA) => {
		if(STUDENT_LIST[socket.id]){
			if(ROOM_LIST[STUDENT_LIST[socket.id].room]){
				var room = STUDENT_LIST[socket.id].room;
				for(var i=0; i<ROOM_LIST[room].students.length; i++){
					if(ROOM_LIST[room].students){
						SOCKET_LIST[ROOM_LIST[room].students[i].id].emit('checkQuickAnswer',cA);
					}
				}
			}
		}
	});

	socket.on('answerToQuestion', (questionNumber, answer) => {
		if(STUDENT_LIST[socket.id]){
			if (ROOM_LIST[STUDENT_LIST[socket.id].room].teacherId) {
				SOCKET_LIST[ROOM_LIST[STUDENT_LIST[socket.id].room].teacherId].emit('studentSentQuestion', questionNumber, answer, ROOM_LIST[STUDENT_LIST[socket.id].room].students, socket.id);
			}
		}
	});

	socket.on('askQuickQuestion',(setQuestion,settings) => {
		if(STUDENT_LIST[socket.id]){
			if(ROOM_LIST[STUDENT_LIST[socket.id].room]){
				var room = STUDENT_LIST[socket.id].room;
				for(var i=0; i<ROOM_LIST[room].students.length; i++){
					if(ROOM_LIST[room].students){
						SOCKET_LIST[ROOM_LIST[room].students[i].id].emit('answerQuickQuestion',setQuestion,settings);
					}
				}
			}
		}
	});

	socket.on('endRoom', (code) => {
		if(ROOM_LIST[code]){
			if(ROOM_LIST[code].teacherId == socket.id){
				for(var i=0; i<ROOM_LIST[code].students.length; i++){
					if(ROOM_LIST[code].students[i]){
						SOCKET_LIST[ROOM_LIST[code].students[i].id].emit('roomClosed');
						STUDENT_LIST[ROOM_LIST[code].students[i].id].room = null;
				  }
				}
				socket.emit('roomClosed');
				STUDENT_LIST[socket.id].room = null;
				delete ROOM_LIST[code];
				ROOM_LIST = cleanArray(ROOM_LIST);
			}
		}
	});

	socket.on('disconnect',function() {
		if(STUDENT_LIST[socket.id].room != null){
			if(ROOM_LIST[STUDENT_LIST[socket.id].room]){
				var room = STUDENT_LIST[socket.id].room;
				if(socket.id == ROOM_LIST[room].teacherId){
					console.log("yes");
					for(var i=0; i<ROOM_LIST[room].students.length; i++){
						if(ROOM_LIST[room].students[i]){
							SOCKET_LIST[ROOM_LIST[room].students[i].id].emit('roomClosed');
							STUDENT_LIST[ROOM_LIST[room].students[i].id].room = null;
					  }
					}
					socket.emit('roomClosed');
					STUDENT_LIST[socket.id].room = null;
					delete ROOM_LIST[room];
					ROOM_LIST = cleanArray(ROOM_LIST);
				}

				else{
					for(var i=0; i<ROOM_LIST[room].students.length; i++){
						if (exists(ROOM_LIST[room].students[i])) {
							if(ROOM_LIST[room].students[i].id == socket.id){
							 	delete ROOM_LIST[room].students[i];
								ROOM_LIST[room].students = cleanArray(ROOM_LIST[room].students);
								for(var i=0; i<ROOM_LIST[room].students.length; i++){
									if(ROOM_LIST[room].students[i]){
										SOCKET_LIST[ROOM_LIST[room].students[i].id].emit('updateLobby',ROOM_LIST[room]);
									}
								}
								SOCKET_LIST[ROOM_LIST[room].teacherId].emit('updateLobby',ROOM_LIST[room])
								break;
							}
						}
					}
				}
			}
		}
	});
});

function exists(obj) {
	return (obj != null && obj != undefined);
}

function cleanArray(arr) {
	var arr_ = [];
	for (var i=0; i<arr.length; i++) {
		if (arr[i]) {
			arr_.push(arr[i]);
		}
	}
	return arr_;
}
