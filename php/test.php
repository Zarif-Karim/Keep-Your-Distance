<?php
	session_start();
	require_once("dbconn.php");

	$uID;
        $data;
	// if(!isset($_SESSION['userId'])){
	// 	//page not accessable if not logged
        //
        //         $cookie_name = "focallength";
        //         $cookie_value = 651.111;
        //         setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/");
	// } else {
        //
	// }
        $uID = 2;
        $deviceName = gethostname();
        $data = $dbConn->query("SELECT focallength FROM device WHERE ofUser=$uID AND name='$deviceName';") OR die('Query Failed: '.$dbConn->error);

        if($data->num_rows) {
                echo $data->num_rows,"<br>";
                while($vf = $data->fetch_assoc()) {
                        echo $vf['focallength'],"<br>";
                }
        } else {
                echo "no data";
        }

	// $startDate = date("Y-m-d h:m:s", strtotime("+2 days"));
	// $endDate = date("Y-m-d h:m:s",strtotime("+1 month"));
?>
