var currentSet = [];/*[
  {q:"2+2=?",cA:"fish",fA:["4","2","3","1"]},
  {q:"yes or no",cA:"maybe",fA:["yes","no","si"]},
  {q:"funny",cA:"funny",fA:["very funny","not funny","this is a test to see how long these should be and what i should set the limit to"]}
  //{q:"",cA:"",fA:[""]}
];*/
var tiles = [];
var dragging = null;
var offset = null;
var timeTaken = 0.00;
var going = false;
var preGoing = false;
var gameType = null;
var drawing = [];
var drawColor = {r:0,g:0,b:0};
var drawUsing = "pen";
var drawSize = 10;

function setup() {
  createCanvas(800, 800).parent('gameCavas');
  textSize(20);
  //tiles.push(new Tile(100,100,"very funny",0,1));
  //for(var i=0; i<3; i++){
    //spawnTiles(currentSet[i]);
  //}

}

function draw() {
  if(gameType == "matching"){
    if(going){
      background(220);
      var doneCheck = true;
      for(var i=0; i<tiles.length; i++){
        if(tiles[i]){
          tiles[i].show();
          doneCheck = false;
        }
      }
      if(doneCheck){
        console.log(nf(timeTaken,1,2));
        going = false;
      }
    }
  }
  if(gameType == "pic"){
    if(going){
      //background(220);

    }
  }
  if(going != preGoing){
    preGoing = going;
    if(gameType == "pic"){
      drawing.push(new DrawButtons(50,height-100,50,{r:0,g:0,b:0},"c"));
      drawing.push(new DrawButtons(100,height-100,50,{r:255,g:0,b:0},"c"));
      drawing.push(new DrawButtons(150,height-100,50,{r:0,g:255,b:0},"c"));
      drawing.push(new DrawButtons(200,height-100,50,{r:0,g:0,b:255},"c"));
      drawReset();
    }
  }
}

setInterval(function(){ if(gameType == "matching"){if(going){ timeTaken+=0.01; } }}, 10);

function drawReset(){
  background(220);
  fill(0);
  strokeWeight(3);
  stroke(0);
  line(0,height-(height/5),width,height-(height/5));
  for(var i=0; i<drawing.length; i++){
    drawing[i].show();
  }
}

function spawnTiles(tmpSet){
  var randx = random(1,width-(textWidth(tmpSet.q)));
  var randy = random(1,height-(30));
  tiles.push(new Tile(randx,randy,tmpSet.q,tiles.length,tiles.length+1));

  randx = random(1,width-(textWidth(tmpSet.cA)));
  randy = random(1,height-(30));
  tiles.push(new Tile(randx,randy,tmpSet.cA,tiles.length,tiles.length-1));
}

function mousePressed(){
  if(gameType == "matching"){
    if(going){
      if(0 < mouseX && mouseX < width && 0 < mouseY && mouseY < height){
        for(var i=0; i<tiles.length; i++){
          if(tiles[i]){
            if(tiles[i].x < mouseX && mouseX < tiles[i].x+tiles[i].width && tiles[i].y < mouseY && mouseY < tiles[i].y+tiles[i].height){
              dragging = i;
              offset = createVector(mouseX-tiles[i].x,mouseY-tiles[i].y);
            }
          }
        }
      }
    }
  }
  if(gameType == "pic"){
    if(going){
      if(mouseX > 0 && mouseX < width && mouseY > 0 && mouseY < height-(height/5)){
        if(drawUsing == "pen"){
          fill(drawColor.r,drawColor.g,drawColor.b);
          stroke(drawColor.r,drawColor.g,drawColor.b);
          ellipse(mouseX,mouseY,drawSize);

        }
      }
      for(var i=0; i<drawing.length; i++){
        drawing[i].click();
      }
    }
  }
}

function mouseDragged(){
  if(gameType == "matching"){
    if(going){
      if(dragging != null){
        if(tiles[dragging]){
          if(0 < mouseX && mouseX < width && 0 < mouseY && mouseY < height){
            tiles[dragging].x = mouseX-offset.x;
            tiles[dragging].y = mouseY-offset.y;
          }
        }
      }
    }
  }
  if(gameType == "pic"){
    if(going){
      if(drawUsing == "pen"){
        if(mouseX > 0 && mouseX < width && mouseY > 0 && mouseY < height-(height/5)){
          fill(drawColor.r,drawColor.g,drawColor.b);
          stroke(drawColor.r,drawColor.g,drawColor.b);
          ellipse(mouseX,mouseY,drawSize);
        }
      }
    }
  }
}

function mouseReleased(){
  if(gameType == "matching"){
    if(going){
      if(dragging != null){
        if(tiles[dragging]){
          tiles[dragging].matchCheck();
          dragging = null;
        }
      }
    }
  }
}



class DrawButtons{
  constructor(x,y,s,color,does){
    this.x = x;
    this.y = y;
    this.size = s;
    this.color = color;
    this.does = does;

  }

  click(){
    if(dist(this.x,this.y,mouseX,mouseY) < this.size/2){
      //console.log("e");
      if(this.does == "c"){
        drawColor = this.color;
      }
    }
  }

  show(){
    fill(this.color.r,this.color.g,this.color.b);
    ellipse(this.x,this.y,this.size);
  }

}

class Tile{
  constructor(x,y,text,id,matchId){
    this.x = x;
    this.y = y;
    this.width = textWidth(text)+10;
    this.height = 30;
    this.startX = x;
    this.startY = y;
    this.text = text;
    this.id = id;
    this.matchId = matchId;

  }

  matchCheck(){
    if (this.x < tiles[this.matchId].x + tiles[this.matchId].width &&
      this.x + this.width > tiles[this.matchId].x &&
      this.y < tiles[this.matchId].y + tiles[this.matchId].height &&
      this.y + this.height > tiles[this.matchId].y) {
        //console.log("e");
        delete tiles[this.matchId];
        delete tiles[this.id];
    }

  }

  show(){
    fill(180);
    rect(this.x,this.y,this.width,this.height);
    fill(255);
    text(this.text,this.x+5,this.y+(this.height/2)+5);

  }


}
