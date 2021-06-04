<?php
$dbConn = new mysqli('localhost', 'root', '', 'kyd');
if($dbConn->connect_error) {
    die("Failed to connect to the database: " . $dbConn->connect_error);
}
?>
