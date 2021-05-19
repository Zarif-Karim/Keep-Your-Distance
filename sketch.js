let capture;

function setup() {
  createCanvas(windowWidth, windowHeight);
  capture = createCapture(VIDEO);
  capture.size(640, 480);
  capture.hide();
}

function draw() {
  background(255);
  let w = windowWidth * 0.7;
  let h = windowHeight * 0.6;
  image(capture, windowWidth/2 - w/2, windowHeight/2 - h/2, w, h);
  //filter('INVERT');
}


function windowResized() {
  resizeCanvas(windowWidth, windowHeight);
}
