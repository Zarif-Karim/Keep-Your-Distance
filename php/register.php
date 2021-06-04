<?php
	session_start();
	require_once('dbConn.php');

	if(isset($_REQUEST['signup'])){
		$email = $dbConn->escape_string($_POST['email']);
		if(!$password = hash('sha256',$dbConn->escape_string($_POST['pass']))) $password = '';
		$name= $dbConn->escape_string($_POST['name']);

 		if(!$email || $password == '' || !$name)
 		{ echo "Please enter all fields in the correct format.<br>"; $sql = 'Insert into customer values (1,2,3,4)';}
 		else
 		$sql = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$password')";

 		if(!$row = $dbConn->query($sql)){
 			die('Failed to insert new record');
 		} else {
 			$_SESSION['userId'] = $dbConn->insert_id;
 			$_SESSION['userName'] = $name;
 			header("Location: http://localhost/Keep-Your-Distance/Report.html");
 		}
 	}
	else {
		echo "signup not set!";
	}

	$dbConn->close();
?>
