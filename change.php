<?php
	session_start();
	$link = mysqli_connect("localhost", "root", "Zhan2003", "cap");
	
	if(!$link){
		printf("Connect failed: %s\n", mysqli_connect_error());
		exit();
	}
	
	print_r($_POST);
	print_r($_SESSION);

?>

<?php

			if (isset($_POST["ForgetPassword"])) {
  				//$email = $_POST["email"];
    //echo "count is ";
				//check whether email exists
				$query_check = "SELECT COUNT(*) FROM cap.User WHERE (Email = ?)";
           		$stmt_check = $link->prepare($query_check);
                $stmt_check->bind_param("s", $_POST["email"]);
    			$stmt_check->execute();
    			
    			$stmt_check->bind_result($count);
    			//$userExists = $stmt_check->fetch();
    			$stmt_check->fetch();

    			/*
    			if ($userExists){
    				echo "Exists!";
    			}
*/
				$stmt_check->close();
				
				echo "count is ".$count;
		}
		
		
		?>

		
		
		
		
		