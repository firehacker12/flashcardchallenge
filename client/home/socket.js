var socket = io(":2000");
var id = null;
var teacher = false;
var gameCode = null;

function roomMake(gameType) {
  if (currentSelected != null) {
    let nameTmp = document.getElementById('teacherName').innerHTML + "'s Room";

    for (var i=0; i<5; i++) {
      if (document.getElementById('startButton'+i) != undefined) {
        var makeButton = document.getElementById("startButton"+i).setAttribute("class","button is-medium is-danger is-loading is-disabled");
      }
    }
    for (var i=0; i<5; i++) {
      if (document.getElementById('roomname'+i) != undefined) {
        if (document.getElementById('roomname'+i).value.length > 0) {
          nameTmp = document.getElementById('roomname'+i).value;
          break;
        }
      }
    }
    console.log(nameTmp);
    //let nameTmp = "tmp name";
    let tmpSet = [
      {q:"2+2=?",cA:"fish",fA:["4","2","3","1"]},
      {q:"yes or no",cA:"maybe",fA:["yes","no","si"]},
      {q:"funny",cA:"funny",fA:["very funny","not funny","this is a test to see how long these should be and what i should set the limit to"]}
      //{q:"",cA:"",fA:[""]}
    ];
    let settingsTmp = {gameType:gameType,rounds:5};

    socket.emit('createRoom',tmpSet,nameTmp,settingsTmp);
  }
}

function startGame(){
  socket.emit("startTheGame");
}

function endRoom(){
  if(teacher){
    socket.emit('endRoom',gameCode);
  }
}

socket.on('startGame', (room) => {
  document.getElementById("lobbyRoom").setAttribute('style','display:none;');
});

socket.on('joinLobby',(room,id_) => {
  document.getElementById('mainHome').setAttribute("style","display:none");
  document.getElementById('lobbyRoom').setAttribute("style","");
  //console.log("E");
  id = id_;
  gameCode = room.id;
  teacher = true;

  document.getElementById('lobbyCodeDisplay').innerHTML = "Code: "+room.id;
  //document.getElementById('lobbyWaitStudentList').innerHTML = "";
  gameType = room.settings.gameType;

  /*for(var i=0; i<room.students.length; i++){
    if(room.students[i]){
      document.getElementById('studentLobbyList').innerHTML += "<p1>"+room.students[i].name+"</p1><br>";
    }
  }*/
});


socket.on('updateLobby',(room) => {
  var columnNum = 1;
  //document.getElementById('lobbyCode').innerHTML = room.id;
  //document.getElementById('lobbyWaitStudentList').innerHTML = "";
  document.getElementById("startTheRoom").removeAttribute("disabled");
  document.getElementById('lobbyColumn1').innerHTML = "";
  document.getElementById('lobbyColumn2').innerHTML = "";
  document.getElementById('lobbyColumn3').innerHTML = "";
  for(var i=0; i<room.students.length; i++){
    if(room.students[i]){
      //document.getElementById('lobbyWaitStudentList').innerHTML += "<p1> "+room.students[i].name+" </p1>";
      if(columnNum == 1){
        columnNum++;
        document.getElementById('lobbyColumn1').innerHTML += "<h1 class='is-size-4 has-text-weight-semibold has-text-dark'>"+room.students[i].name+"</h1><br>";
      }
      else if(columnNum == 2){
        columnNum++;
        document.getElementById('lobbyColumn2').innerHTML += "<h1 class='is-size-4 has-text-weight-semibold has-text-dark'>"+room.students[i].name+"</h1><br>";
      }
      else{
        columnNum = 1;
        document.getElementById('lobbyColumn3').innerHTML += "<h1 class='is-size-4 has-text-weight-semibold has-text-dark'>"+room.students[i].name+"</h1><br>";
      }
    }
  }
});
