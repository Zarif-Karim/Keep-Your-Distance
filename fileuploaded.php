<?php
date_default_timezone_set("Australia/Sydney");
$target_dir = "uploads/";
$target_file = $target_dir . basename($_FILES["file"]["name"]);
$uploadOk = 1;
$fileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
$file_save_name = $target_dir . date("d.m.Y h-i-s A") . "." . $fileType;

// Check file size
if ($_FILES["file"]["size"] > 100*1024*1024) { //100MB
  echo "Error: Sorry, your file is too large.";
  $uploadOk = 0;
}

//Allow certain file formats
if($fileType != "mp4" &&  $fileType != "mov" && $fileType != "csv") {
  echo "Error: Sorry, file not allowed.";
  $uploadOk = 0;
}

// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
  echo "Error: Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
} else {
  if (move_uploaded_file($_FILES["file"]["tmp_name"], $file_save_name)) {
    echo "newfile: ". $file_save_name;
  } else {
    echo "Error: Sorry, there was an error uploading your file.";
  }
}
?>
