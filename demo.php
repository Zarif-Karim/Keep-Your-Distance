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
                        echo "no data";
                        setcookie('focallength', '-1', time() + (86400 * -1), "/"); //set for a week
                }
	}
	else {
		setcookie('userName', '-1', time() + (86400 * -1), "/"); //set for a week
		setcookie('focallength', '-1', time() + (86400 * -1), "/"); //set for a week
	}

	// $startDate = date("Y-m-d h:m:s", strtotime("+2 days"));
	// $endDate = date("Y-m-d h:m:s",strtotime("+1 month"));
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Live Camera</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content = "ie=edge"/>
    <link rel="stylesheet" href="style.css">
    <script src="utils.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js" charset="utf-8"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.1/css/all.min.css">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <script src="https://d3js.org/d3.v5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/p5@1.3.1/lib/p5.js"></script>
    <script src="https://unpkg.com/ml5@0.6.1/dist/ml5.min.js" type="text/javascript"></script>
    <script src = "https://cdn.jsdelivr.net/npm/chart.js@3.2.1/dist/chart.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="sketch.js"></script>
    <script src="graph.js"></script>
    <style>
      h1{
        text-align: absolute;
        font-family: "Lato",sans-serif;

      }
      .vidElement{
        border: pink;
        margin-left: 10px;
      }
      </style>
  </head>
  <body onload="generateCharts();">
   <header>
      <div class="twp-video-layer"></div>
    </div>
    <div class="inner-width">
      <a href="homePages.html" class="logo"><img src="logo.png" alt=""></a>
      <i class="menu-toggle-btn fas fa-bars"></i>
     <nav class="navigation-menu">
       <p><span class="Welcome">Welcome<strong id="show_uname"></strong></span></p>
      <a href="homePages.html"><i class="fas fa-home home"></i>HOME</a>
      <a href="demo.php"><i class="fas fa-users live"></i>SYSTEM DEMO</a>
      <a href="Report.php"><i class="fas fa-headset report"></i>REPORT</a>
      	<a href="login_register.html"><i class="fa fa-sign-in"></i>LOGIN</a>
     </nav>
    </div>
  </header>

    <!-- live camera page--->
                  <h1>System Demo</h1>
                  <div id = "bar">
                  <div class="w3-light-black w3-round-xlarge bar" style="width:50%; height: 20px">
		                    <div class="w3-container w3-blue w3-round-xlarge" style="width:0%; height: 100%" id="progressBar"></div>
	                            <div id="progressLable" style="text-align: center; color: ; "> ...Loading Model... </div>
		                      </div>
                      </div>
                    <br>
                    <br>
                      <!--check box code--->
                    <div id="box3">
                      <div id="box4">
                      <div class="camera-container" style="background-color: white; width: 83%; margin-right: 10px;margin-left: 100px; border: solid 2px white;">
                        <div class="live-camera" style="margin-left: auto; margin-right: auto;">
                        <div id="canvas-container" style="width=640; height=480; margin-top: 10px; margin-left: 15px;"></div>
                        <div class="button">
                          <button id="start-btn">Start</button>
                          <!-- <button id="dwn-btn">Download & Reset</button> -->
                        </div>
                        <div class="input-text">
                        </div>
                        </div>
                        </div>
                      </div>
                     <div id="box8">
                        <div id="chartsView">
                    <div class="chart0"><canvas id = "chart0" width="auto" height="100px"></canvas></div>
                    <div class="chart1">
                            <canvas id = "chart1" width="auto" height="100px"></canvas>
                            <label for="incident">Incidents tolerance(meters):</label><br>
                            <input type="text" id="incident" name="incident" value="1.5">
                            <button id="incident_submit"> Set</button>
                    </div>
                    <div class="chart2"><canvas id = "chart2" width="auto" height="100px"></canvas></div>
                        </div>
                           <div id="calibrateBox">
                                  <div id="instruction_list">
                                            <ul>
                                              <strong style="font-size: 25px;">How to calibrate:</strong><br>
                                                      <li>Turn on camera</li>
                                                      <li>Select a known distance to be stood away from camera</li>
                                                            <a style="margin-left:55px; font-size: 18px;">Enter Distance in meters: <input type="number" placeholder="0.2" id="c_dist"></a><br>
                                                      <li>Stand entered distance away form the camera</li>
                                                      <li>Observe width from screen<br>
                                                                       (Stand straight and square facing towards the camera)<br>
                                                      <li><a>Enter Width in pixels: <input type="number" placeholder="352" id="c_width"></a><br></li>
                                                      <li>Click Calibrate <button style=" margin-left: 10px;width: 100px ;height:30px;" id="c_btn">Calibrate</button><br></li>
                                                      <li>Observe distance on screen for resonable accuracy<br>
                                                                        (Repeat steps 2 - 7 for recalibration)<br></li>
                                                     <li>Turn off camera<br></li>
                                                     <li>Select Calibration Done<br></li>
                                              <ul>

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
      </body>
</html>
