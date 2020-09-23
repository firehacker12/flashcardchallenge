var express = require('express');
var app = express();
var serv = require('http').Server(app);
//import uid from 'uid';

app.use('/', express.static('client'));

var portnumber = 2000;

serv.listen(portnumber);
console.log("Server started on port " + portnumber);

var SOCKET_LIST = {};
var STUDENT_LIST = {};
var CLASS_LIST = {};

var Student = function(id){
	var self = {
		id:id
	}
	return self;
}

var Room = function(classCode,setId,teacherName,settings){
  var id = uid(6);
  var self = {
    id:id,
    teacher:teacherName,
    classCode:classCode
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
	//console.log(uid(6));

  socket.on('createRoom',(classCode,setId,teacherName,settings) => {

  });

  socket.on('joinRoom',(code)=> {

  });

	socket.on('disconnect',function() {

	});
});
