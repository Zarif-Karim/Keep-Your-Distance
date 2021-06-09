<?php
	session_start();
	session_destroy();

	//unset cookies
	setcookie('userId', '-1', time() + (86400 * -1), "/"); //set for a week
	setcookie('userName', '-1', time() + (86400 * -1), "/"); //set for a week
	setcookie('focallength', '-1', time() + (86400 * -1), "/"); //set for a week

	header("Location: http://".$_SERVER['HTTP_HOST']."/Keep-Your-Distance/homePages.html");
?>
