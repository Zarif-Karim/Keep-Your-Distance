<?php
	if (isset($_POST['submit'])) {
		if (isset($_POST['email']) && isset($_POST['psw']) &&
			isset($_POST['pswrepeat'])) {
			
			$email = $_POST['email'];
			$psw = $_POST['psw'];
			$pswrepeat = $_POST['pswrepeat'];
			
			$host = "localhost";
			$dbUsername = "root";
			$dbPassword = "";
			$dbName = "KYD";
			$conn = new mysqli($host, $dbUsername, $dbPassword, $dbName);
			if ($conn->connect_error) {
				die('Could not connect to the database.');
			}
			else {
				$Select = "SELECT email FROM register WHERE email = ? LIMIT 1";
				$Insert = "INSERT INTO register(email,password,confirmpassword) values(?, ?, ?)";
				$stmt = $conn->prepare($Select);
				$stmt->bind_param("s", $email);
				$stmt->execute();
				$stmt->bind_result($resultEmail);
				$stmt->store_result();
				$stmt->fetch();
				$rnum = $stmt->num_rows;
				if ($rnum == 0) {
					$stmt->close();
					$stmt = $conn->prepare($Insert);
					$stmt->bind_param("ssssii",$email, $psw, $pswrepeat, );
					if ($stmt->execute()) {
						echo "New record inserted sucessfully.";
					}
					else {
						echo $stmt->error;
					}
				}
				else {
					echo "Someone already registers using this email.";
				}
				$stmt->close();
				$conn->close();
			}
		}
		else {
			echo "All field are required.";
			die();
		}
	}
	else {
		echo "Submit button is not set";
	}
	?>