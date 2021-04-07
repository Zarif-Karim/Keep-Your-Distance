<!DOCTYPE html>
<html lang="en" dir="ltr">
	<head>
		<meta charset="utf-8">
		<title>Keep your distances</title>
		<link rel="stylesheet" href="style_video.css">
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
		<div class="container">
			<div class="wrapper">
				<div class="video">
					<video controls hidden>
						<source>
					</video>
				</div>
				<div class="content">
					<div class="icon"><i class="fas fa-cloud-upload-alt"></i></div>
					<div class="text">No Video Uploaded</div>
				</div>
					<div id="cancel-btn"><i class="fas fa-times"></i></div>
					<div class="file-name">File name here</div>
			</div>
				<button onclick="defaultBtnActive()" id="custom-btn">Upload Video</button>
        <form enctype = "multipart/form-data" action = "fileuploaded.php"
           method = "post" hidden>
           <input id="default-btn" type="file" name="file" />
           <input type = "submit" name = "submit" id="submit-btn"/>
        </form>
				<button onclick="submitBtnActive()" id="analysis-btn">Analyse</button>
		</div>
<script>
			const wrapper = document.querySelector(".wrapper");
			const fileName = document.querySelector(".file-name");
			const defaultBtn = document.querySelector("#default-btn");
			const customBtn = document.querySelector("#custom-btn");
			const submitBtn = document.querySelector("#submit-btn");
			const cancelBtn = document.querySelector("#cancel-btn i");
			const video = document.querySelector("video");
			const source = document.querySelector("source");
			const content = document.querySelector(".content");
			let regExp = /[0-9a-zA-Z\^\&\'\@\{\}\[\]\,\$\=\!\-\#\(\)\.\%\+\~\_ ]+$/;

			function defaultBtnActive(){
				defaultBtn.click();
			}
			function submitBtnActive(){
				submitBtn.click();
			}
			defaultBtn.addEventListener("change", function(){
				const file = this.files[0];
				if(file){
					const reader = new FileReader();
					reader.onload = function(){
						const result = reader.result;
						source.src = result;
						video.hidden = false;
						content.hidden = true;
						video.load();
						wrapper.classList.add("active");
					}
					cancelBtn.addEventListener("click", function(){
						source.src = "";
						video.hidden = true;
						content.hidden = false;
						video.load();
						wrapper.classList.remove("active");
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
