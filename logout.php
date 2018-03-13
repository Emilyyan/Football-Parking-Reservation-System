<?php
	session_start();

	session_destroy();
	header("Location: http://zcilok.cloudapp.net/capstone/index.php");
?>
