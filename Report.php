<?php
	session_start();
	require_once("php/dbconn.php");

	$uID;
	if(!isset($_SESSION['userId'])){
		//page not accessable if not logged
		die("User Information not available");
		header("Location: http://localhost/Keep-Your-Distance/homePages.html");

	} else {
		$uID = $_SESSION['userId'];
	}

	$data = $dbConn->query("SELECT id,name,size FROM videofile WHERE ofUser=".$uID.";") OR die('Query Failed: '.$dbConn->error);
	$device = $dbConn->query("SELECT id FROM device WHERE ofUser=".$uID.";") OR die('Query Failed: '.$dbConn->error);
	$sumVf = $dbConn->query("SELECT SUM(vf.size) as vSum FROM videofile as vf WHERE vf.ofUser=$uID") OR die('Query Failed: '.$dbConn->error);
	$sumDf = $dbConn->query("SELECT SUM(df.size) as dSum FROM datafile as df WHERE df.ofUser=$uID") OR die('Query Failed: '.$dbConn->error);

	$sum;
	if($sumVf->num_rows && $sumDf->num_rows){
		$sumVf = $sumVf->fetch_assoc();
		$sumDf = $sumDf->fetch_assoc();
		$sum = intval($sumVf['vSum']) + intval($sumDf['dSum']);
		//convert to MB
		$sum = intval($sum/(1024*1024));
	} else $sum = -1;

	$videoCount = $data->num_rows;
	$deviceCount = $device->num_rows;

?>

<!DOCTYPE html>
<html>
<title>Keep Your Distance Report</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
<script src="https://d3js.org/d3.v5.min.js"></script>
<link rel="stylesheet" href="https://www.w3schools.com/lib/w3-colors-camo.css">
<script src = "https://cdn.jsdelivr.net/npm/chart.js@3.2.1/dist/chart.min.js"></script>
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js" charset="utf-8"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.1/css/all.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<script src="graph.js"></script>
<script src="utils.js"></script>
<link rel="stylesheet" href="listbox.css">
<link rel="stylesheet" href="style.css">
<style>
html,body,h1,h2,h3,h4,h5 {font-family: "Raleway", sans-serif}
</style>
<body class="w3-light-grey" onload="setupNavigation();">

<!-- Top container -->
<div class="w3-bar w3-top w3-camo-black w3-large" style="z-index:4">
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
</div>


<!-- Sidebar/menu -->
<nav class="w3-sidebar w3-collapse w3-white w3-animate-left" style="z-index:3;width:300px;" id="mySidebar"><br>
  <div class="w3-container w3-row">
    <div class="w3-col s4">
      <img src="icon.png" class="w3-circle w3-margin-right" style="width:46px">
    </div>
    <div class="w3-col s8 w3-bar">
      <span>Welcome, <strong id="show_uname"><?php echo $_SESSION['userName'];?></strong></span><br>
      <a href="#" class="w3-bar-item w3-button"><i class="fa fa-envelope"></i></a>
      <a href="#" class="w3-bar-item w3-button"><i class="fa fa-user"></i></a>
    </div>
  </div>
  <hr>
  <div class="w3-container">
    <h5>Dashboard</h5>
  </div>
  <div class="w3-bar-block">
    <!-- <a href="#" class="w3-bar-item w3-button w3-padding-16 w3-hide-large w3-dark-grey w3-hover-black" onclick="w3_close()" title="close menu"><i class="fa fa-remove fa-fw"></i>  Close Menu</a>
    <a href="homePages.html" class="w3-bar-item w3-button w3-padding w3-blue"><i class="fa fa-users fa-fw"></i>  Overview</a>
    <a href="demo.php" class="w3-bar-item w3-button w3-padding"><i class="fa fa-eye fa-fw"></i>  System Demo</a>
    <a href="Report.php" class="w3-bar-item w3-button w3-padding"><i class="fa fa-history fa-fw"></i>  History</a> -->
    <div class="w3-one">
      <div class="w3-container w3-blue w3-padding-16">
        <div class="w3-left"><i class="fa fa-film w3-xxxlarge"></i></div>
        <div class="w3-right">
          <h3><?php echo $videoCount; ?></h3>
        </div>
        <div class="w3-clear"></div>
        <h4>Videos</h4>
      </div>
    </div>

    <div class="w3-one">
      <div class="w3-container w3-red w3-padding-16">
        <div class="w3-left"><i class="fa fa-camera w3-xxxlarge"></i></div>
        <div class="w3-right">
          <h3><?php echo $deviceCount; ?></h3>
        </div>
        <div class="w3-clear"></div>
        <h4>Devices</h4>
      </div>
    </div>

    <div class="w3-one">
      <div class="w3-container w3-teal w3-padding-16">
        <div class="w3-left"><i class="fa fa-database w3-xxxlarge"></i></div>
        <div class="w3-right">
          <h3><?php echo $sum; ?> MB</h3>
        </div>
        <div class="w3-clear"></div>
        <h4>Space Used</h4>
      </div>
    </div>

    <div class="w3-one">
      <div class="w3-container w3-orange w3-text-white w3-padding-16">
        <div class="w3-left"><i class="fa fa-users w3-xxxlarge"></i></div>
        <div class="w3-right">
          <h3>FREE</h3>
        </div>
        <div class="w3-clear"></div>
        <h4>User Subscription</h4>
      </div>
    </div>

  </div>
</nav>


<!-- Overlay effect when opening sidebar on small screens -->
<div class="w3-overlay w3-hide-large w3-animate-opacity" onclick="w3_close()" style="cursor:pointer" title="close side menu" id="myOverlay"></div>

<!-- !PAGE CONTENT! -->
<div class="w3-main" style="margin-left:300px;margin-top:43px;">

  <!-- Header -->
  <div class="w3-container" style="padding-top:22px">
    <h5><b><i class="fa fa-dashboard"></i> My Dashboard</b></h5>
  </div>

  <!-- <div class="w3-row-padding w3-margin-bottom">


  </div> -->

  <div class="w3-panel">
    <div class="w3-row-padding" style="margin:0 -16px">
      <div class="w3-half">
        <h2>Video<span id="status">: load video from list</span></h2>
        <!-- <div id="canvas-container"></div> -->
        <div class="video">
                <video id="uploaded" controls height="480" width="650">
                </video>
        </div>
        </div>
        <div class="w3-half">
        <div id="ex" style="margin-left: 50px;">
          <h5>Choose your previous video.</h5>
          <div class="listbox-area">
            <div class="left-area">
              <span id="ss_elem">Video List:</span>
              <ul id="ss_elem_list" tabindex="0" role="listbox" aria-labelledby="ss_elem">
		<?php
			if($data->num_rows) {
				while($vf = $data->fetch_assoc()) {
					//echo '<li id="ss_elem_'.$no.'" role="option">'.$vf['name'].'</li>';
					echo '<li id="videofile_'.$vf['id'].'" role="option">'.$vf['name'].'</li>';
				}
			} else {
				echo '<li id="ss_elem_Np" role="option">No Videos</li>';
			}

			$dbConn->close();
		?>
                <!-- <li id="ss_elem_Pu" role="option">video.mp4</li> -->
              </ul>
            </div>

          </div>
		<div class="button">
			<button id="start-btn" onClick="getVideo();"> Load Video </button>
		</div>
        </div>
      </div>
        <hr>
	<div>
	<div id="chartContainer">
  	</div>
	</div>
        </div>

     <footer class="w3-container w3-padding-10 w3-light-grey" style="margin-top:10px;">
      <p style="background-color: white; text-align: center;">Thank you to visit our system</p>
      </footer>

  <hr>
</div>

                              <!-- nav function--->
<script type="text/javascript">
  $(".menu-toggle-btn").click(function(){
    $(this).toggleClass("fa-times");
    $(".navigation-menu").toggleClass("active");
  });
</script>

<script>
// Get the Sidebar
var mySidebar = document.getElementById("mySidebar");

// Get the DIV with overlay effect
var overlayBg = document.getElementById("myOverlay");

// Toggle between showing and hiding the sidebar, and add overlay effect
function w3_open() {
  if (mySidebar.style.display === 'block') {
    mySidebar.style.display = 'none';
    overlayBg.style.display = "none";
  } else {
    mySidebar.style.display = 'block';
    overlayBg.style.display = "block";
  }
}

// Close the sidebar with the close button
function w3_close() {
  mySidebar.style.display = "none";
  overlayBg.style.display = "none";
}
</script>

<script>
var selection = -1;


// Add a "checked" symbol when clicking on a list item
var myNodelist = document.getElementsByTagName("LI");
var list = document.querySelector('ul');
list.addEventListener('click', function(ev) {
	//unselect all
	for (var i = 0; i < myNodelist.length; i++) {
		myNodelist[i].classList.remove('checked');
	}
	//select clicked
	if (ev.target.tagName === 'LI') {
		if(ev.target.innerHTML !== 'No Videos') {
			ev.target.classList.toggle('checked');
			selection = ev.target.id;
		}
	}
}, false);

</script>
</body>
</html>
