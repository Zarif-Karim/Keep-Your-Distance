<html>
<head><title>File uploaded</title></head>
<body bgcolor="cyan" color="yellow">
<center>
<h3>File uploaded !! <h3>
<hr>
<?php
$target_dir = "uploads/";
$target_file = $target_dir . basename($_FILES["file"]["name"]);
$uploadOk = 1;
$fileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
$file_save_name = $target_dir . date("d.m.Y h-i-s A") . "." . $fileType;

// Check if image file is a actual image or fake image
// if(isset($_POST["submit"])) {
//   $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
//   if($check !== false) {
//     echo "File is an image - " . $check["mime"] . ".";
//     $uploadOk = 1;
//   } else {
//     echo "File is not an image.";
//     $uploadOk = 0;
//   }
// }

// Check if file already exists
if (file_exists($target_file)) {
  echo "Sorry, file already exists.";
  $uploadOk = 0;
}

// Check file size
if ($_FILES["file"]["size"] > 50*1024*1024) { //50MB
  echo "Sorry, your file is too large.";
  $uploadOk = 0;
}

// Allow certain file formats
// if($fileType === "jpg" || $fileType === "png" || $fileType === "jpeg"
// || $fileType === "gif" ) {
//   echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
//   $uploadOk = 0;
// } else
if ($fileType != "mp4" &&  $fileType != "mov" && $fileType != "avi" ) {
   echo "Sorry, only mp4, mov & avi files are allowed for video.";
   $uploadOk = 0;
 }

// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
  echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
} else {
  if (move_uploaded_file($_FILES["file"]["tmp_name"], $file_save_name)) {
    echo "The file ". htmlspecialchars( basename( $_FILES["file"]["name"])). " has been uploaded.";
  } else {
    echo "Sorry, there was an error uploading your file.";
  }
}
?>

<form action = "printPost.cgi" method = "post">
   <input type="text" value="<?php echo $file_save_name ?>" name="userfilename"/>
   <input type = "submit" name = "submit"/>
</form>
</center>
</body>
</html>
