let cnv;
let capture;
let detector;
let WIDTH = 640;
let HEIGHT = 480;
let cameraBtnStart;
let cameraBtnStop;
//PImage img;

function preload() {
  detector = ml5.objectDetector('cocossd',{},modeloaded);
}

function setup() {
  //createCanvas(windowWidth, windowHeight);
  cnv = createCanvas(WIDTH, HEIGHT);
  cnv.parent("#canvas-container");
  capture = createCapture(VIDEO);
  capture.size(WIDTH, HEIGHT);
  capture.hide();
  cameraBtnStart = select("#start-btn");
  cameraBtnStop = select("#stop-btn");
}

function draw() {
  background(255);
  //let w = windowWidth * 0.7;
  //let h = windowHeight * 0.6;
  if(cameraBtnStart)  cameraBtnStart.mouseClicked(() => {
          capture.play();

          detector.detect(capture, (err, results) => {
            if(err) console.log(err);
            console.log(results);
            for(let i = 0; i < results.length; i++) {
                let obj = results[i];
                stroke(0,255,0);
                strokeWeight(4);
                noFill();
                rect(obj.x, obj.y, obj.width, obj.height);

                //from normalized
                let x = obj.normalized.x * WIDTH;
                let y = obj.normalized.y * HEIGHT;
                let w = obj.normalized.width * WIDTH;
                let h = obj.normalized.height * HEIGHT;
                stroke(255,0,0);
                rect(x, y, w, h);

                //console.log(capture.width)
            }

          });
  });
  if(cameraBtnStop) cameraBtnStop.mouseClicked(() =>{
                capture.pause();
  });
  //image(capture, windowWidth/2 - w/2, windowHeight/2 - h/2, w, h);
  image(capture, 0, 0, capture.width, capture.height);
  //filter('INVERT');
}

function windowResized() {
  resizeCanvas(windowWidth, windowHeight);
}

function modeloaded() {
  console.log("Model Loaded");
}
