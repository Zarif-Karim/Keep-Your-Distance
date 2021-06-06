let cnv;
let capture;
let detector;
let WIDTH = 640;
let HEIGHT = 480;
let cameraBtn;
let progressBar;
let progressLable;

//Media Recorder
let mediaRecorder; //reference to media recorder constructor
let recordedBlobs; //store the bytes recorded by webcam
let errorMsgElement;

let cST = 'Start';
let stream = false;

let incident_tolerance;
let incident_input;
let incident_submit;

let data_table;

//data containers
let frameData = [];
//let overallData = [];
let data_frameNo = [];
let data_numObjDetected = [];
let data_incidents = [];

let data_distances = [];
let f0t1 = [];

let frameNo = 0;

let calibrationBtn;
let calibrationMode = false;
let chartView;
let calibrateBox;

let FOCAL_LENGTH_IN_PIXELS;
let averagewidth = 0.45;

function preload() {
	detector = ml5.objectDetector('cocossd',{},modeloaded);

	//calibration
	FOCAL_LENGTH_IN_PIXELS = parseFloat(getCookie('focallength')); //get from db
	if(!FOCAL_LENGTH_IN_PIXELS) {
		calibrationMode = true;
	}

	chartView = select("#chartsView");
	calibrateBox = select("#calibrateBox");
	if(calibrationMode) {
		chartView.hide();
		calibrateBox.show();
	} else {
		chartView.show();
		calibrateBox.hide();
	}
}

function setup() {
        cnv = createCanvas(WIDTH, HEIGHT);
        cnv.parent("#canvas-container");

	errorMsgElement = select("span#errorMsg");
	progressBar = select("#progressBar");
	progressLable = select("#progressLable");

        cameraBtn = select("#start-btn");
	cameraBtn.hide();
        if(cameraBtn)  cameraBtn.mouseClicked(() => {
		if(cST == 'Start') {
			capture = createCapture(VIDEO, ()=>{
	                        capture.size(WIDTH, HEIGHT);
	                        capture.hide();
	                        stream = true;
				cST = 'Stop';
				cameraBtn.html(cST);
	                        loop();

				if(!calibrationMode) {
					setProgressBar(50, "Stop Video");
	        			// var canvas = document.querySelector('canvas');
					window.stream = canvas.captureStream(30);
					startRecording();
				} else {
					setProgressBar(50, "Follow Calibration Steps");
				}
	                });
		} else if (cST == 'Stop'){
			capture.remove();
	                stream = false;
			cST = 'Start';
			cameraBtn.html(cST);
	                noLoop();
			redraw();

			if(!calibrationMode) {
				setProgressBar(75, "Download Files");
	      			stopRecording();
				sleep(1000).then(() => {
					download_reset();
					cST = 'Refresh';
					cameraBtn.html(cST);
				});
			} else {
				cST = 'Calibration Done';
				cameraBtn.html(cST);
				setProgressBar(75, "Press Calibration Done or Recalibrate if required");

			}
		}
		else if(cST == 'Refresh') {
			window.location.reload();
		}
		else {
			setProgressBar(100, "Calibration Done, Please Wait...");
			sleep(1000);
			cST = 'Start';
			cameraBtn.html(cST);
			chartView.show();
			calibrateBox.hide();
			calibrationMode = false;
			setProgressBar(25, "Start Video");
		}
        });

	incident_tolerance = 1.5;
	incident_input = select("#incident");
	incident_submit = select("#incident_submit");
	if(incident_input && incident_submit) incident_submit.mouseClicked(()=> {
		incident_tolerance = incident_input.value();
	});

	data_table = new p5.Table();

	data_table.addColumn('Frame_Number');
	data_table.addColumn('Objects_Detected');
	data_table.addColumn('Incidents_Occured');

	calibrationBtn = select("#c_btn");
	if (calibrationBtn) {
		calibrationBtn.mouseClicked(() => {
			var distInput = select("#c_dist");
			var widthInput = select("#c_width");
			FOCAL_LENGTH_IN_PIXELS =
					(parseFloat(distInput.elt.value) *
					parseFloat(widthInput.elt.value)) /
					averagewidth;
			setCookie("focallength", FOCAL_LENGTH_IN_PIXELS, 365);
		});
	}
}

function draw() {
        background(0);

	if(stream)
        {
                image(capture, 0, 0, WIDTH, HEIGHT);
                if(calibrationMode) detector.detect(capture, calibrate_device);
		else detector.detect(capture, video_data);
        }
}

function windowResized() {
	resizeCanvas(WIDTH, HEIGHT);
}

function modeloaded() {
	console.log("Model Loaded");
	setProgressBar(25, "Model Loaded");
	setTimeout(()=>{
		setProgressBar(25, "Start Video");
		cameraBtn.show();
	}, 1500);
}

function sleep(ms) {
       return new Promise(resolve => setTimeout(resolve, ms));
}

function uploadFiles() {
	//set progress bars
	setProgressBar(85, "Files Being Uploaded....");

	//data csv
	let toWrite = [];
	let td = data_table.getArray();
	for(let i = 0; i< td.length; i++) { //rows
		let rd = "";
		for(let j = 0; j < td[i].length; j++) { //columns
			rd += td[i][j] + ",";
		}
		rd += "\n";
		toWrite.push(rd);
	}
	let data_file = new File(toWrite, "file.csv", {type: "text/csv"});

	//video mp4
	const blob = new Blob(recordedBlobs, { type: 'video/mp4' });
	let video_file =  new File([blob], "vid.mp4",{type: "video/mp4"});

	if((toWrite.length > 0 && data_file) && video_file) {
	      //make form and send data to php script to upload to server.
	      const formData = new FormData();
	      formData.append('data_file', data_file);
	      formData.append('video_file', video_file);

	      fetch('fileuploaded.php', {
		method: 'POST',
		body: formData
	      })
	      .then(response => response.text())
	      .then(result => {
		console.log('Success:', result);
		setProgressBar(100, "Uploading Finished: Refresh Page");
	      })
	      .catch(error => {
		console.log('Error:', error);
		setProgressBar(100, "Uploading Failed: ",error);
	      });
	}
}

function download_reset(){
	setProgressBar(85, "Files Being Uploaded....");
       let filename = getName("data/");
       console.log(filename);
       try {
	       saveDataTable();
	       setProgressBar(90, "Video Data Uploaded....");
       } catch (error) {
	       console.error(error);
       }

       try {
	       downloadRecording();
	       setProgressBar(95, "Video File Uploaded....");
       } catch (error) {
	       console.error(error);
       }

       //add mechanism to  refresh chart datasets

	//Refreshing page for now....
	setProgressBar(100, "Uploading Finished: Refresh Page");
       // let timer = 5;
       // let id = setInterval(()=>{
	//        	setProgressBar(100, "Finished -> Refreshing Page in "+timer);
	// 	timer--;
	// 	if (timer == 0) {
	// 		clearInterval(id);
	// 		window.location.reload();
	// 	}
       // }, 1000);
}

function saveDataTable() {
	//let test = ["0,1,2,3", "1,2,3,4", "2,3,4,5"];

}

function getName(path) {
       let name = path + day() + "." + month() + "." + year()
		       + " "  + hour() + "-" + minute() + "-" + second();
       return name;
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

function setProgressBar(percentage, label) {
       progressBar.style("width:"+percentage+"%; height: 100%");
       progressLable.html(label);
}
// ********/

function handleDataAvailable(event) {
       console.log('handleDataAvailable', event);
       if (event.data && event.data.size > 0) {
	       recordedBlobs.push(event.data);
       }
}

function startRecording() {
       recordedBlobs = [];
       let options = { mimeType: 'video/webm;codecs=vp9,opus' };
       try {
	       mediaRecorder = new MediaRecorder(window.stream, options);
       } catch (e) {
	       console.error('Exception while creating MediaRecorder:', e);
	       errorMsgElement.innerHTML = `Exception while creating MediaRecorder: ${JSON.stringify(e)}`;
	       return;
       }

       console.log('Created MediaRecorder', mediaRecorder, 'with options', options);
       mediaRecorder.onstop = (event) => {
	       console.log('Stopped: ', event);
	       console.log('Recorded Blobs: ', recordedBlobs);
       };
       mediaRecorder.ondataavailable = handleDataAvailable;
       mediaRecorder.start();
       console.log('MediaRecorder started', mediaRecorder);
}

function stopRecording() {
       mediaRecorder.stop();
}

function downloadRecording() {
       const blob = new Blob(recordedBlobs, { type: 'video/mp4' });

	let file =  new File([blob], "vid.mp4",{type: "video/mp4"});
       //const file = this.files[0];
       if(file){
	       //make form and send data to php script to upload to server.
	       const formData = new FormData();
	       formData.append('file', file);

	       fetch('fileuploaded.php', {
		 method: 'POST',
		 body: formData
	       })
	       .then(response => response.text())
	       .then(result => {
		 console.log('Success:', result);
	       })
	       .catch(error => {
		 console.log('Error:', error);
	       });
       }
}

function video_data(err, results) {
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

		let newRow = data_table.addRow();
		newRow.setNum('Frame_Number', frameNo);
		newRow.setNum('Objects_Detected', frameData.length);
		newRow.setNum('Incidents_Occured', incidents);

		//update graphs
		update();
	}
}

function calibrate_device(err, results) {
	if(err) console.log(err);
	else {
		for(let i = 0; i < results.length; i++) {
			let obj = results[i];
			stroke(0,255,0);
			strokeWeight(4);
			noFill();
			rect(obj.x, obj.y, obj.width, obj.height);
			fill(255);
			textSize(32);
			text("width: " + nf(obj.width,0,2), obj.x + 10, obj.y + obj.height - 10);
			if(FOCAL_LENGTH_IN_PIXELS) {
				let distance = (FOCAL_LENGTH_IN_PIXELS * averagewidth)/obj.width;
				text("distance: " + nf(distance,0,2), obj.x + 10, obj.y + obj.height - 30);
			}
			//console.log(capture.width)
		}
	}
}
