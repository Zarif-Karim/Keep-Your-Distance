<?php
	session_start();
	require_once("php/dbconn.php");

	$uID;
    $data;
    $focallength;
	if(isset($_SESSION['userId'])){
		$uID = $_SESSION['userId'];
                $deviceName = gethostname();
                $data = $dbConn->query("SELECT id,focallength FROM device WHERE ofUser=$uID AND name='$deviceName';") OR die('Query Failed: '.$dbConn->error);

                if($data->num_rows) {
                        if($data->num_rows > 1) die("FATAL ERROR : MULTIPLE ENTRY FOR SAME DEVICE");
                        $vf = $data->fetch_assoc(); // should only return 1 result
                        $focallength = $vf['focallength'];
                        if(!$focallength) {
                                setcookie('focallength', '-1', time() + (86400 * -1), "/"); //set for a week
                                die("ERROR SETTING FOCALLENGTH");
                        } else {
				$_SESSION['deviceId'] = $vf['id'];
                                setcookie('focallength', $focallength, time() + (86400 * 7), "/"); //set for a week
                        }
                } else {
                        //echo "no data";
                        setcookie('focallength', '-1', time() + (86400 * -1), "/"); //set for a week
                }
	}
	else {
		setcookie('userName', '-1', time() + (86400 * -1), "/"); //set for a week
		setcookie('focallength', '-1', time() + (86400 * -1), "/"); //set for a week
	}
?>


<!DOCTYPE html>
<html lang="en" dir="ltr">
	<head>
		<meta charset="utf-8">
		<title>Live Camera</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta http-equiv="X-UA-Compatible" content = "ie=edge"/>
		<link rel="stylesheet" href="style.css">
		<link rel="stylesheet" href="calibrateStyle.css">
		<script src="utils.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js" charset="utf-8"></script>
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.1/css/all.min.css">
		<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
		<!-- <script src="https://d3js.org/d3.v5.min.js"></script> -->
		<script src="https://cdn.jsdelivr.net/npm/p5@1.3.1/lib/p5.js"></script>
		<script src="https://unpkg.com/ml5@latest/dist/ml5.min.js"></script>
		<script src = "https://cdn.jsdelivr.net/npm/chart.js@3.2.1/dist/chart.min.js"></script>
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
		<script src="graph.js"></script>
		<!-- <style>
			h1{
				text-align: absolute;
				font-family: "Lato",sans-serif;
			}

			.vidElement{
				border: pink;
				margin-left: 10px;
			}
		</style> -->
	</head>
	<body onload="generateChartsDemo(); setupNavigation();">
		<header>
			<div class="inner-width">
				<a href="homePages.html" class="logo"><img src="logo.png" alt=""></a>
				<i class="menu-toggle-btn fas fa-bars"></i>
				<nav class="navigation-menu" style="height:10px;">
					<p><span class="Welcome login">Welcome <strong id="show_uname"><?php echo $_SESSION['userName'];?></strong></span></p>
					<a href="homePages.html"><i class="fas fa-home home"></i>HOME</a>
					<a href="demo.php"><i class="fas fa-users live"></i>SYSTEM DEMO</a>
					<a href="Report.php" class="login"><i class="fas fa-headset report"></i>DASHBOARD</a>
					<a href="login_register.html" class="logout"><i class="fa fa-sign-in"></i>SIGN IN</a>
					<a href="php/logoff.php" class="login"><i class="fa fa-sign-out" style="color:lightblue;"></i>SIGN OUT</a>
				</nav>
			</div>
		</header>

		<!-- live camera page--->
    	<div class="w3-row">
			<div class="w3-container w3-light-grey">
				<h1 class="w3-panel w3-text-black">System Demo</h1>
			</div>
		</div>
        <div id="bar" class="w3-row">
			<div class="w3-panel w3-light-grey">
			        <div class="w3-light-black w3-round-xlarge bar" style="width:50%; height: 20px">
				        <div class="w3-container w3-blue w3-round-xlarge" style="width:0%; height: 100%" id="progressBar"></div>
				</div>
				<div class="w3-text-indigo" id="progressLable" style="text-align: center; font-size: x-large; font-weight: bold;"> ...Loading Model... </div>
			</div>
		</div>
		<div class="w3-row">
            <div class="w3-half w3-light-grey">
				<div class="w3-container w3-light-grey w3-center">
					<div class="w3-card-4">
						<div class="w3-panel w3-padding">
							<div id="canvas-container">
								<!-- videoCanvas inserted dynamically here-->
							</div>
		                    <div class="button">
		                      <button id="start-btn">Start</button>
		                    </div>
						</div>
					</div>
				</div>
			</div>
			<div class="w3-half w3-light-grey">
				<div class="w3-container" id="chartsView">
					<div class="w3-row-padding w3-light-grey">
						<div class="w3-panel w3-card-4 w3-white">
							<!-- chart1 -->
							<div class="chart0">
								<canvas id="chart0" width="auto" height="100px">
								</canvas>
							</div>
						</div>
					</div>
					<div class="w3-row-padding w3-light-grey">
						<div class="w3-panel w3-card-4 w3-white">
							<!-- chart2 -->
							<div class="chart1">
				                                <canvas id = "chart1" width="auto" height="100px"></canvas>
				                                <label for="incident">Incidents tolerance(meters):</label><br>
				                                <input type="text" id="incident" name="incident" value="1.5">
				                                <button id="incident_submit"> Set</button>
				                        </div>
						</div>
					</div>
					<div class="w3-row-padding w3-light-grey">
						<div class="w3-panel w3-card-4 w3-white">
							<!-- chart3 -->
							<div class="chart2">
								<canvas id = "chart2" width="auto" height="100px">
								</canvas>
							</div>
						</div>
					</div>
				</div>
				<div class="w3-container w3-light-grey" id="calibrateBox" hidden>
					<div id="instruction_list">
						<ul class="w3-ul w3-card-4">
							<h2 class="w3-padding-large">How to calibrate:</h2>
							<li>Turn on camera</li>
							<li>Select a known distance to be stood away from camera:
							<li>
								<div class="w3-half">
								   <label>Enter Distance in meters: </label>
							    </div>
								<div class="w3-half">
								   <input type="number" placeholder="0.2" class="w3-input w3-border w3-round-large" id="c_dist">
								</div>
							</li>
							<li>Stand entered distance away form the camera</li>
							<li>Observe width from screen<br>
								(Stand straight and square facing towards the camera)<br>
							</li>
							<li>
								<div class="w3-half">
								   <label>Enter Width in pixels: </label>
							    </div>
								<div class="w3-half">
								   <input class="w3-input w3-border w3-round-large" type="number" placeholder="352" id="c_width">
								</div>
							</li>
							<li>
								<div class="w3-half">
								   <label>Click: </label>
							    </div>
								<div class="w3-half">
								   <button class="w3-btn w3-blue w3-block w3-round-xxlarge">Calibrate</button>
								</div>
							</li>
							<li>Observe distance on screen for resonable accuracy<br>
								(Repeat steps 2 - 7 for recalibration)<br>
							</li>
							<li>Turn off camera</li>
							<li>Select Calibration Done
								<?php if(isset($uID)) echo "to save device"; ?>
							</li>
						<ul>
					</div>
				</div>
			</div>
		</div>
		<!-- nav function--->
		<script type="text/javascript">
			$(".menu-toggle-btn").click(function(){
				$(this).toggleClass("fa-times");
				$(".navigation-menu").toggleClass("active");
			});
		</script>
		<div>
			<span id="errorMsg"></span>
		</div>
		<script src="sketch.js"></script>
	</body>
</html>
