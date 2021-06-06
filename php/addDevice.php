<?php
	session_start();
	require_once('dbConn.php');

	if(isset($_POST['focallength'])){
		$fl = $dbConn->escape_string($_POST['focallength']);
                $deviceName = gethostname();
                $user = $_SESSION['userId'];
 		if(!$fl){
                        echo "ERROR setting focallength";
 		} else {
                        $sql = "INSERT INTO device (name, focallength, ofUser) VALUES ('$deviceName', $fl, $user)";
                        if(!$row = $dbConn->query($sql)){
         			echo "Failed to insert new record in 'device' table";
         		} else {
                                $ffd = $row->fetch_assoc();
                                echo "deviceID=".$dbConn->insert_id;."; focallength=".$ffd['focallength'].";";
                        }
                }
 	}
	else {
		echo "ERROR: no focallength provided";
	}

	$dbConn->close();
?>
