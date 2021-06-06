<?php
	session_start();
	session_destroy();

	header("Location: http://".$_SERVER['HTTP_HOST']."/Keep-Your-Distance/homePages.html");
?>
