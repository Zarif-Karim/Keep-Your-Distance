<?php
	session_start();
	require_once('dbConn.php');
	$email=$emailError=$passwordError="";

	if(isset($_REQUEST['signin'])){
		$email = $dbConn->escape_string($_POST['your_email']);
		if(!$password = hash('sha256',$dbConn->escape_string($_POST['your_pass']))) $password = '';

		//echo "$email, $password";

 		if($email == '' || $password == ''){
 		echo "Please enter all fields in the correct format.<br>";
 		$sql = 'SELEC admin FROM cusmer;';
 		}
 		else
 		$sql = 'SELECT id, name, password FROM users where email = "'.$email.'";';

 		if(!$row = $dbConn->query($sql)){
 			die('Failed to retrieve record');
 		} else {
 			if($row->num_rows){
 				$row = $row->fetch_assoc();
 				//var_dump($row);
 				if($row['password']==$password){
					$_SESSION['userId'] = $row['id'];
					setcookie('userId', $_SESSION['userId'], time() + (86400 * 7), "/"); //set for a week
		 			$_SESSION['userName'] = $row['name'];
					setcookie('userName', $_SESSION['userName'], time() + (86400 * 7), "/"); //set for a week
		 			header("Location: http://".$_SERVER['HTTP_HOST']."/Keep-Your-Distance/Report.php");
 				} else {
 					$passwordError="Incorrect Password!";
					echo $passwordError;
 				}
 			} else {
 				$email = "";
 				$emailError = "Email not registered. Please sign-up in the register page.";
				echo $emailError;
 			}
 		}
 	}

	$dbConn->close();
?>
