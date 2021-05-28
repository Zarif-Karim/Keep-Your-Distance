let cnv;
let capture;
let detector;
let WIDTH = 640;
let HEIGHT = 480;
let cameraBtnStart;
let cST = 'Start';
let stream = false;

let incident_tolerance;
let incident_input;
let incident_submit;


//data containers
let frameData = [];
//let overallData = [];
let data_frameNo = [];
let data_numObjDetected = [];
let data_incidents = [];

let data_distances = [];
let f0t1 = [];

let frameNo = 0;

let FOCAL_LENGTH_IN_PIXELS = 651.222;
let averagewidth = 0.45;
//PImage img;

function preload() {
	detector = ml5.objectDetector('cocossd',{},modeloaded);
}

function setup() {
        //createCanvas(windowWidth, windowHeight);
        cnv = createCanvas(WIDTH, HEIGHT);
        cnv.parent("#canvas-container");
        cameraBtnStart = select("#start-btn");
        if(cameraBtnStart)  cameraBtnStart.mouseClicked(() => {
		if(cST == 'Start') {
			capture = createCapture(VIDEO, ()=>{
	                        capture.size(WIDTH, HEIGHT);
	                        capture.hide();
	                        stream = true;
				cST = 'Stop';
				cameraBtnStart.html(cST);
	                        update();
	                        loop();
	                });
		} else {
			capture.remove();
	                stream = false;
			cST = 'Start';
			cameraBtnStart.html(cST);
	                noLoop();
			redraw();
		}
        });

	incident_tolerance = 1.5;
	incident_input = select("#incident");
	incident_submit = select("#incident_submit");
	if(incident_input && incident_submit) incident_submit.mouseClicked(()=> {
		incident_tolerance = incident_input.value();
	});
}

function draw() {
        background(0);

	if(stream)
        {
                image(capture, 0, 0, WIDTH, HEIGHT);
                detector.detect(capture, (err, results) => {
	                if(err) console.log(err);
	                else {
	                        frameData = [];
	                	for(let i=0; i < results.length; i++) {
	                        	if(results[i].label == "person")
	                        	{
	                                	frameData.push(results[i]);
	                        	}
	        		}

	                        for(let i = 0; i < frameData.length; i++) {
	                        	let obj = frameData[i];
	                                stroke(0,255,0);
	                                strokeWeight(4);
	                                noFill();
	                                rect(obj.x, obj.y, obj.width, obj.height);
	                                let distance = (FOCAL_LENGTH_IN_PIXELS * averagewidth)/obj.width;
	                                fill(255);
	                                textSize(32);
	                                text(i + ": " + nf(distance,0,2), obj.x + 10, obj.y + obj.height - 10);
	                                //console.log(capture.width)
	                        }

	                        data_frameNo.push(++frameNo);
	                        data_numObjDetected.push(frameData.length);
				let dist = [];
				let incidents = 0;
				for(let i = 0; i < frameData.length; i++){
				        for(let j = i+1; j < frameData.length; j++){
				                dist.push(calculate_distance(frameData[i], frameData[j], [255,0,255]));
						if(dist[i] <= incident_tolerance) incidents++;
						if(i==0 && j==1) f0t1.push(dist[0]);
				        }
				}
				/*if(dist.length > 0)*/
				data_distances.push(dist);
				data_incidents.push(incidents);
	                }
                });
        }
}

function pythagaros(vd, hd)
{
        let d = vd*vd + hd*hd;
        d = Math.sqrt(d);
        return d;
}

//calculates the distance between two objects in meters
function calculate_distance(obj1, obj2) {
	//the camera centre is assumed to be directly alligned with the centre point of the frame
	const centre = [WIDTH/2,HEIGHT/2];

	//obj1 = frameData[objIndex1];
        let obj1Centre = [obj1.x + obj1.width/2,obj1.y + obj1.height/2];
        console.log("obj1Center:" + obj1Centre);
	//cout << format("\nobj1Centre: (%d,%d,%.2f)\n", obj1Centre.x, obj1Centre.y, dfc[objIndex1].first);

	//obj2 = frameData[objIndex2];
        let obj2Centre = [obj2.x + obj2.width / 2,obj2.y + obj2.height / 2];
        console.log("obj2Center:" + obj2Centre);
	//cout << format("obj2Centre: (%d,%d,%.2f)\n", obj2Centre.x, obj2Centre.y, dfc[objIndex2].first);


	let horizontal_dist = centre[0] - obj1Centre[0];
	let vertical_dist = centre[1] - obj1Centre[1];
	let distToCentre_1 = pythagaros(vertical_dist, horizontal_dist);

	let dfcp_1 = pythagaros(distToCentre_1, FOCAL_LENGTH_IN_PIXELS);
        let dfc_1 = (FOCAL_LENGTH_IN_PIXELS * averagewidth)/obj1.width;
	let pixelToCentimeretRatio_1 = dfc_1 / dfcp_1;

	let x_1 = horizontal_dist * pixelToCentimeretRatio_1;
	let y_1 = vertical_dist * pixelToCentimeretRatio_1;
	let z_1 = dfc_1;

  	horizontal_dist = centre[0] - obj2Centre[0];
	vertical_dist = centre[1] - obj2Centre[1];
	let distToCentre_2 = pythagaros(vertical_dist, horizontal_dist);

	let dfcp_2 = pythagaros(distToCentre_2, FOCAL_LENGTH_IN_PIXELS);
        let dfc_2 = (FOCAL_LENGTH_IN_PIXELS * averagewidth)/obj2.width;
	let pixelToCentimeretRatio_2 = dfc_2 / dfcp_2;
	/* real-world coordinates relative to picture frame center */
	let x_2 = horizontal_dist * pixelToCentimeretRatio_2;
	let y_2 = vertical_dist * pixelToCentimeretRatio_2;
	let z_2 = dfc_2;

	let distance = Math.sqrt((x_2 - x_1) * (x_2 - x_1) + (y_2 - y_1) * (y_2 - y_1) + (z_2 - z_1) * (z_2 - z_1));

	if (distance < 3.0) {
                stroke(255);
                strokeWeight(3);
		line(obj1Centre[0],obj1Centre[1], obj2Centre[0],obj2Centre[1]);
		if(distance <= incident_tolerance) fill(255,0,0);
		else fill(0,255,0);
                textSize(32);
		let x = Math.min(obj1Centre[0],obj2Centre[0]);
		x = x + (Math.max(obj1Centre[0],obj2Centre[0]) - x)/2;
		let y = Math.min(obj1Centre[1],obj2Centre[1])
		y = y + (Math.max(obj1Centre[1],obj2Centre[1]) - y)/2;
                text(nf(distance,0,2), x, y);
	}

	return distance;
}

function windowResized() {
	resizeCanvas(WIDTH, HEIGHT);
}

function modeloaded() {
	console.log("Model Loaded");
}
