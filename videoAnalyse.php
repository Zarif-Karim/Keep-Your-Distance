<!DOCTYPE html>
<html lang="en" dir="ltr">
	<head>
		<meta charset="utf-8">
		<title>Keep your distances</title>
		<link rel="stylesheet" href="style_video.css">
		<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"/>
	</head>
	<body>


<div class='logo'>
	<div class='inner_logo'>
		<div class='logo_container'>
		 <h1><span>BLUEOSYS COMPANY</span></h1>
		</div>
		<div class="topnav" id="myTopnav">
				<a href="index.html" >Home</a>
				<a href="ImageProcessing.html">Image Processing</a>
				<a href="videoAnalyse.php" class="active">Video Processing</a>
				<a href="LiveCamera.html">Live Video</a>
				<a href="Reports_new.html">Report</a>
				<a href="javascript:void(0);" class="icon" onclick="myFunction()">
					<i class="fa fa-bars"></i>
				</a>
			</div>
	</div>
</div>




	<h1>Video processing</h1>
	<div class="w3-light-grey w3-round-xlarge" style="width:75%; height: 30%">
	<div class="w3-container w3-blue w3-round-xlarge" style="width:33%; height: 100%"></div>
	</div>
		<div class="container">
			<div class="wrapper" id="inVid">
				<div class="video">
					<video id="uploaded" controls hidden>
					</video>
				</div>
				<div class="content">
					<div class="icon"><i class="fas fa-cloud-upload-alt"></i></div>
					<div class="text">No Video Uploaded</div>
				</div>
				<div class="loading" hidden>
					<img src="loading.svg" class="l-content" height="80%">
					<div class="text">...Analysing...</div>
				</div>
					<div id="cancel-btn"><i class="fas fa-times"></i></div>
					<div class="file-name">File name here</div>
			</div>

			<div class="wrapper" id="outVid" hidden>
				<div class="video">
					<!-- <video id="processed" controls>
					</video> -->
					<object data="processed/09.04.2021 03-12-11 AM.avi" id="processed">
						<param name="src" value="processed/09.04.2021 03-12-11 AM.avi" id="pP"/>
					</object>
				</div>
					<div id="cancel-btn"><i class="fas fa-times"></i></div>
					<div class="file-name">Processed Video</div>
			</div>

				<button onclick="defaultBtnActive()" id="custom-btn">Upload Video</button>
           <input id="default-btn" type="file" name="file" hidden>
				<button onclick="submitBtnActive()" id="analysis-btn">Analyse</button>
		</div>
<script>
			const inVid = document.querySelector("#inVid");
			const outVid = document.querySelector("#outVid");
			const fileName = document.querySelector(".file-name");
			const defaultBtn = document.querySelector("#default-btn");
			const customBtn = document.querySelector("#custom-btn");
			const cancelBtn = document.querySelector("#cancel-btn i");
			const video = document.querySelector("#uploaded");
			const processed = document.querySelector("#processed");
			const param = document.querySelector("#pP");
			//const source = document.querySelector("source");
			const content = document.querySelector(".content");
			const loading = document.querySelector(".loading");
			let newfileUpload = "unset";
			let newfileDownload = "unset";
			let regExp = /[0-9a-zA-Z\^\&\'\@\{\}\[\]\,\$\=\!\-\#\(\)\.\%\+\~\_ ]+$/;

			function defaultBtnActive(){
				defaultBtn.click();
			}
			function submitBtnActive(){
				if(newfileUpload != "unset"){

					//set screen to loading svg
					video.hidden = true;
					loading.hidden = false;
					//wrapper.classList.remove("active");
					//send to cgi program
					const form = new FormData();
					form.append('file', newfileUpload);

					fetch('printPost.cgi', {
						method: 'POST',
						body: form
					})
					.then(response => response.text())
					.then(result => {
						if(result.includes("Success")){

							newfileDownload = "processed" + newfileUpload.substr(7,33);
						}
						//use response from cgi to display new video
						//remove loading svg and show video
						if(newfileDownload != "unset") {
							//processed.data = newfileDownload;
							//param.value = newfileDownload;
							loading.hidden = true;
							video.hidden = false;
							outVid.classList.add("active");
							outVid.hidden = false;

							// fetch(newfileDownload)
							// .then(res => res.blob())
							// .then(res => {
							// 	const reader = new FileReader();
							//
							//   reader.addEventListener("load", function () {
							//     // convert image file to base64 string
							//     processed.src = reader.result;
							// 		processed.load();
							// 		loading.hidden = true;
							// 		video.hidden = false;
							// 		outVid.classList.add("active");
							// 		outVid.hidden = false;
							//   }, false);
							//
							//   if (res) {
							//     reader.readAsDataURL(res);
							//   }
							// });
						}
						console.log('Success:', result);
					})
					.catch(error => {
						console.log('Error:', error);
					});
				}
			}
			defaultBtn.addEventListener("change", function(){
				const file = this.files[0];
				if(file){
					const reader = new FileReader();
					reader.onload = function(){
						const result = reader.result;
						video.src = result;
						video.hidden = false;
						content.hidden = true;
						video.load();
						inVid.classList.add("active");

						//make form and send data to php script to upload to server.
						const formData = new FormData();
						formData.append('file', file);

						fetch('fileuploaded.php', {
						  method: 'POST',
						  body: formData
						})
						.then(response => response.text())
						.then(result => {
							if(result.includes("newfile")){
								newfileUpload = result.substr(9);
							}
						  console.log('Success:', result);
						})
						.catch(error => {
						  console.log('Error:', error);
						});
					}
					cancelBtn.addEventListener("click", function(){
						video.src = "";
						video.hidden = true;
						content.hidden = false;
						video.load();
						inVid.classList.remove("active");
					})
					reader.readAsDataURL(file);
				}
				if(this.value){
					let valueStore = this.value.match(regExp);
					fileName.textContent = valueStore;
				}
			});
		</script>
		<script>
		function myFunction() {
			var x = document.getElementById("myTopnav");
			if (x.className === "topnav") {
				x.className += " responsive";
			} else {
				x.className = "topnav";
			}
		}
		</script>
	</body>
</html>
