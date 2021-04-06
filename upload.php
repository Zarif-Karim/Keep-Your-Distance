<html>
 <head>
<title>Upload File</title>
</head>
 <body bgcolor="pink" color="yellow">
 <center>
 <h3>File uploaded !! <h3>
 <hr>
<?php
$target_dir = "uploads/".$target_file;
$target_file = $target_dir . basename($_FILES["file"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
	$check = getimagesize($_FILES["file"]["tmp_name"]);
	if($check !== false) {
		echo "File is an image - " . $check["mime"] . ".";
		$uploadOk = 1;
	} else {
		echo "File is not an image.";
		$uploadOk = 0;
	}
}

// Check if file already exists
if (file_exists($target_file)) {
	echo "Sorry, file already exists.";
	$uploadOk = 0;
}

// Check file size
if ($_FILES["file"]["size"] > 1000000) {
	echo "Sorry, your file is too large.";
	$uploadOk = 0;
}

// Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
&& $imageFileType != "gif" ) {
	echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
	$uploadOk = 0;
}

// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 1) {
	echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
} else {
	if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
		echo "The file ". htmlspecialchars( basename( $_FILES["file"]["name"])). " has been uploaded.";
	} else {
		echo "Sorry, there was an error uploading your file.";
	}
}
?>
     
	 <input type="text" value="<?php echo $target_file ?>" name="userfilename"/>
	 <input type = "submit" name = "submit"/>
 
 </center>
 </body>
 </html>
