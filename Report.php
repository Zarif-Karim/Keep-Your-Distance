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

	$data = $dbConn->query("SELECT name FROM videofile WHERE ofUser=".$uID.";") OR die('Query Failed: '.$dbConn->error);
	// $startDate = date("Y-m-d h:m:s", strtotime("+2 days"));
	// $endDate = date("Y-m-d h:m:s",strtotime("+1 month"));
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
<script src="utils.js"></script>
<link rel="stylesheet" href="listbox.css">
<link rel="stylesheet" href="style.css">
<style>
html,body,h1,h2,h3,h4,h5 {font-family: "Raleway", sans-serif}
</style>
<body class="w3-light-grey" onload="setUserName();">

<!-- Top container -->
<div class="w3-bar w3-top w3-camo-black w3-large" style="z-index:4">
    <div class="inner-width">
			<a href="homePages.html" class="logo"><img src="logo.png" alt=""></a>
			<i class="menu-toggle-btn fas fa-bars"></i>
		 <nav class="navigation-menu" style="height:10px;">
			<p><span class="Welcome">Welcome <strong id="show_uname"><?php echo $_SESSION['userName'];?></strong></span></p>
			<a href="homePages.html"><i class="fas fa-home home"></i>HOME</a>
			<a href="demo.php"><i class="fas fa-users live"></i>SYSTEM DEMO</a>
			<a href="Report.php"><i class="fas fa-headset report"></i>REPORT</a>
			<a href="php/logoff.php"><i class="fa fa-sign-out" style="color:lightblue;"></i>Sign out</a>
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
    <a href="#" class="w3-bar-item w3-button w3-padding-16 w3-hide-large w3-dark-grey w3-hover-black" onclick="w3_close()" title="close menu"><i class="fa fa-remove fa-fw"></i>  Close Menu</a>
    <a href="homePages.html" class="w3-bar-item w3-button w3-padding w3-blue"><i class="fa fa-users fa-fw"></i>  Overview</a>
    <a href="demo.php" class="w3-bar-item w3-button w3-padding"><i class="fa fa-eye fa-fw"></i>  System Demo</a>
    <a href="Report.php" class="w3-bar-item w3-button w3-padding"><i class="fa fa-history fa-fw"></i>  History</a>
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

  <div class="w3-row-padding w3-margin-bottom">
    <div class="w3-quarter">
      <div class="w3-container w3-red w3-padding-16">
        <div class="w3-left"><i class="fa fa-camera w3-xxxlarge"></i></div>
        <div class="w3-right">
          <h3>52</h3>
        </div>
        <div class="w3-clear"></div>
        <h4>Video</h4>
      </div>
    </div>
    <div class="w3-quarter">
      <div class="w3-container w3-blue w3-padding-16">
        <div class="w3-left"><i class="fa fa-eye w3-xxxlarge"></i></div>
        <div class="w3-right">
          <h3>99</h3>
        </div>
        <div class="w3-clear"></div>
        <h4>Views</h4>
      </div>
    </div>
    <div class="w3-quarter">
      <div class="w3-container w3-teal w3-padding-16">
        <div class="w3-left"><i class="fa fa-share-alt w3-xxxlarge"></i></div>
        <div class="w3-right">
          <h3>23</h3>
        </div>
        <div class="w3-clear"></div>
        <h4>Shares</h4>
      </div>
    </div>
    <div class="w3-quarter">
      <div class="w3-container w3-orange w3-text-white w3-padding-16">
        <div class="w3-left"><i class="fa fa-users w3-xxxlarge"></i></div>
        <div class="w3-right">
          <h3>50</h3>
        </div>
        <div class="w3-clear"></div>
        <h4>Users</h4>
      </div>
    </div>
  </div>

  <div class="w3-panel">
    <div class="w3-row-padding" style="margin:0 -16px">
      <div class="w3-half">
        <h5>Video</h5>
        <!-- <div id="canvas-container"></div> -->
        <div class="video">
                <video id="uploaded" controls height="480" width="650">
                </video>
        </div>
        </div>
        <div class="w3-half">
        <div id="ex" style="margin-left: 50px;">
          <p>Choose your previous video.</p>
          <div class="listbox-area">
            <div class="left-area">
              <span id="ss_elem">Video List:</span>
              <ul id="ss_elem_list" tabindex="0" role="listbox" aria-labelledby="ss_elem">
		<?php
			if($data->num_rows) {
				while($vf = $data->fetch_assoc()) {
					echo '<li id="ss_elem_Np" role="option">'.$vf['name'].'</li>';
				}
			} else {
				echo '<li id="ss_elem_Np" role="option">No Videos</li>';
			}

			$dbConn->close();
		?>
                <li id="ss_elem_Pu" role="option">video.mp4</li>
                <!-- <li id="ss_elem_Am" role="option">security.mp4</li>
                <li id="ss_elem_Cm" role="option">video1.mp4</li>
                <li id="ss_elem_Bk" role="option">video2.mp4</li>
                <li id="ss_elem_Cf" role="option">video3.mp4</li>
                <li id="ss_elem_Es" role="option">video4.mp4</li>
                <li id="ss_elem_Fm" role="option">video5.mp4</li>
                <li id="ss_elem_Md" role="option">video6.mp4</li>
                <li id="ss_elem_No" role="option">video7.mp4</li> -->
              </ul>
            </div>
            <div class="button">
              <button id="start-btn" onClick="getVideo('video.mp4');">Play Video</button>
            </div>
          </div>
        </div>
      </div>
        <hr>
          <div><canvas id = "chart" width="auto" height="100px" style="background-color: white; margin-top: 10px;"></canvas></div>
          <div><canvas id = "chart1" width="auto" height="100px" style="background-color: white; margin-top: 10px;"></canvas></div>
        </div>

     <footer class="w3-container w3-padding-10 w3-light-grey" style="margin-top:10px;">
      <p style="background-color: white; text-align: center;">Thank you to visit our system</p>
      </footer>

  <hr>

                              <!-- nav function--->
<script type="text/javascript">
  $(".menu-toggle-btn").click(function(){
    $(this).toggleClass("fa-times");
    $(".navigation-menu").toggleClass("active");
  });
</script>
<!---graph 1---->

<script>
/*graph 1*/
const xlabels = [];
const yDist = [];
chartIt();
async function chartIt() {
  await getData();
const ctx = document.getElementById('chart').getContext('2d');
const myChart = new Chart(ctx, {
  type: 'line',
  data: {
    labels: xlabels,
    datasets: [
    {
      label: 'Distance(m) in the cumulative time(s)',
      data: yDist,
      backgroundColor: "rgba(120, 190, 132, 1)",
      borderColor: "rgba(120, 190, 132, 1)",
      borderCapStyle:'butt',
      borderDash:[],
      borderDashOffset: 0.0,
      borderJoinStyle:'miter',
      pointBorderColor: "rgba(120, 190, 132, 1)",
      pointBackgroundColor: "rgba(120, 190, 132, 1)",
      pointBorderWidth:0.1,
      pointRadius: 0,
      pointHoverRadius:5,
      pointHoverBackgroundColor: "rgbd(50,50,50,1)",
      pointHoverBorderColor: "rgbd(50,50,50,1)",
      pointHoverBorderWidth:2,
      pointHitRadius:10,
      lineTension: 0.4,
    }]
  },
  options: {
        responsive: true,
        plugins: {
          title: {
            display: true,
            text: "Graph with distance(m) in the cumulative time(s)"
          }
        },
      scales: {
        //x axis detail
            x: {
              display: true,
              title: {
                display: true,
                text: "Cumulative time (s)",
              }
            },
          //y axis detail
            y:{
              display: true,
                title: {
                  display: true,
                  text:"Distance (m)",
              },
              ticks: {
                min:0,
                // Include a dollar sign in the ticks
                callback: function(value, index, values) {
                  return value;
                }
                },
                // grid: {
                //   display: false
                // }
            }
      }
      },

});
}
  getData();
  async function getData(){
    const response = await fetch('live-camera.csv');
    const data = await response.text();
    const table = data.split('\n').slice(1);
    table.forEach(row =>
    {
      const columns = row.split(',');
      const Cultime = columns[2];
      xlabels.push(Cultime);
      const distance = columns[4];
       yDist.push(parseFloat(distance));
      console.log(Cultime, distance);
    });

}
</script>

<!----->
<script>
  /*graph 2*/
  const xAxis = [];
  const yAxis = [];
  chartIt();
  async function chartIt() {
    await getData();
  const ctx = document.getElementById('chart1').getContext('2d');
  const myChart1 = new Chart(ctx, {
    type: 'line',
    data: {
      labels: xAxis,
      datasets: [
      {
        label: 'Distance(m) in the cumulative time(s)',
        data: yAxis,
        backgroundColor: "rgba(255, 99, 132, 1)",
        borderColor: "rgba(255, 99, 132, 1)",
        borderCapStyle:'butt',
        borderDash:[],
        borderDashOffset: 0.0,
        borderJoinStyle:'miter',
        pointBorderColor: "rgba(255, 99, 132, 1)",
        pointBackgroundColor: "rgba(255, 99, 132, 1)",
        pointBorderWidth:0.1,
        pointRadius: 0,
        pointHoverRadius:5,
        pointHoverBackgroundColor: "rgbd(50,50,50,1)",
        pointHoverBorderColor: "rgbd(50,50,50,1)",
        pointHoverBorderWidth:2,
        pointHitRadius:10,
        lineTension: 0.4,
      }]
    },
    options: {
          responsive: true,
          plugins: {
            title: {
              display: true,
              text: "Graph with distance(m) in the processing time(s)"
            }
          },
        scales: {
          //x axis detail
              x: {
                display: true,
                title: {
                  display: true,
                  text: "Processing time (s)",
                }
              },
            //y axis detail
              y:{
                display: true,
                  title: {
                    display: true,
                    text:"Distance (m)",
                },
                ticks: {
                  min:0,
                  // Include a dollar sign in the ticks
                  callback: function(value, index, values) {
                    return value;
                  }
                  },
                  // grid: {
                  //   display: false
                  // }
              }
        }
        },

  });
  }
    getData();
    async function getData(){
      const response = await fetch('live-camera.csv');
      const data = await response.text();
      const table = data.split('\n').slice(1);
      table.forEach(row =>
      {
        const columns = row.split(',');
        const Cultime = columns[1];
        xAxis.push(Cultime);
        const distance = columns[4];
         yAxis.push(parseFloat(distance));
        console.log(Cultime, distance);
      });

  }

</script>
</div>

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
// Create a "close" button and append it to each list item
var myNodelist = document.getElementsByTagName("LI");
var i;
for (i = 0; i < myNodelist.length; i++) {
  var span = document.createElement("SPAN");
  var txt = document.createTextNode("\u00D7");
  span.className = "close";
  span.appendChild(txt);
  myNodelist[i].appendChild(span);
}

// Click on a close button to hide the current list item
var close = document.getElementsByClassName("close");
var i;
for (i = 0; i < close.length; i++) {
  close[i].onclick = function() {
    var div = this.parentElement;
    div.style.display = "none";
  }
}

// Add a "checked" symbol when clicking on a list item
var list = document.querySelector('ul');
list.addEventListener('click', function(ev) {
  if (ev.target.tagName === 'LI') {
    ev.target.classList.toggle('checked');
  }
}, false);



</script>
</body>
</html>
