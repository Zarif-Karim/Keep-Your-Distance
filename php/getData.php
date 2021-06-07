<?php
	session_start();
	require_once('dbConn.php');

	if(isset($_POST['ofVideo'])){
		$vId = $dbConn->escape_string($_POST['ofVideo']);
                $user = $_SESSION['userId'];

                $sql = "SELECT name FROM datafile WHERE ofVideo=$vId AND ofUser=$user;";
                if(!$row = $dbConn->query($sql)){
 			echo "Failed to get data record in 'datafile' table";
 		} else {
                        $dfd = $row->fetch_assoc();
                        echo $dfd['name'];
                }

 	}
	else {
		echo "ERROR: no videoID provided";
	}

	$dbConn->close();
?>
