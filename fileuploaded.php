<?php
session_start();
require_once('php/dbconn.php');

function upload($fileName, $fileSize, $fileTmpName, $nameDate){
        $target_file = "uploads/" . basename($fileName);
        $uploadOk = 1;
        $fileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
        $file_save_name = "uploads/" . $nameDate . "." . $fileType;

        // Check file size
        if ($fileSize > 100*1024*1024) { //100MB
          echo "Error: Sorry, your file is too large.";
          $uploadOk = 0;
        }

        //Allow certain file formats
        if($fileType != "mp4" && $fileType != "csv") {
          echo "Error: Sorry, file not allowed.";
          $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
          echo "Error: Sorry, your file was not uploaded.";
        // if everything is ok, try to upload file
        } else {
          if (move_uploaded_file($fileTmpName, $file_save_name)) {
            echo "newfile: ". $file_save_name;
          } else {
            echo "Error: Sorry, there was an error uploading your file.";
          }
        }
}

function addToDB(&$db, $table, $conLabel, $name, $ext, $size, $con, $user){

        $id;

        $sql = "INSERT INTO $table (name, ext, size, $conLabel, ofUser) VALUES ('$name', '$ext', $size, $con, $user)";
        //echo "$table query: $sql <br>";
        if(!$row = $db->query($sql)){
			echo "Failed to insert new record in '$table' table";
                        return -1;
	} else {
                $id = $db->insert_id;
                //echo "$table ID=".$id;
        }

        return $id;

}

date_default_timezone_set("Australia/Sydney");
$target_dir = "uploads/";
$userId =  $_SESSION['userId'];
$nameDateFiles = date("d.m.Y h-i-s A");
$deviceId = $_SESSION['deviceId'];

//video file uploading
upload($_FILES["video_file"]["name"], $_FILES["video_file"]["size"], $_FILES["video_file"]["tmp_name"], $nameDateFiles);
//data file uploading
upload($_FILES["data_file"]["name"], $_FILES["data_file"]["size"], $_FILES["data_file"]["tmp_name"], $nameDateFiles);
//video file database entry
$vidId = addToDB($dbConn,'videofile','fromDevice',$nameDateFiles,'mp4',$_FILES["video_file"]["size"],$deviceId,$userId);
//data file database entry
$dataId;
if($vidId != -1)
$dataId = addToDB($dbConn,'datafile','ofVideo',$nameDateFiles,'csv',$_FILES["data_file"]["size"],$vidId,$userId);
else $dataId = -1;

if($vidId && $dataId) echo "videoId=".$vidId." dataId=".$dataId;

$dbConn->close();
?>
