<?php
	session_start();
	$link = mysqli_connect("localhost", "root", "Zhan2003", "cap");
	
	if(!$link){
		printf("Connect failed: %s\n", mysqli_connect_error());
		exit();
	}

?>

												
<?php
	if(isset($_POST['login'])){
			$link = mysqli_connect("localhost", "root", "Zhan2003", "cap");
	
			if(!$link){
				printf("Connect failed: %s\n", mysqli_connect_error());
				exit();
			}
			
			$e = $_POST['SignInEmail'];
        	$p = $_POST['SignInPassword'];

			$_SESSION['Email']=$e;
	
			$query_login = "SELECT password_hash, salt FROM cap.Authentication WHERE (Email = ?)";
			
			$stat_login = $link->prepare($query_login);
			$stat_login->bind_param('s', $e);
			$stat_login->execute();
			
			$stat_login->bind_result($login_hash, $login_salt);
	
			$stat_login->fetch();
			
			//pg_prepare($conn, "lookup", $query);
			//$result = pg_execute($conn, "lookup", array($u));

			//$row = pg_fetch_assoc($result);
			$localhash = sha1( $login_salt . $p);
			$stat_login->close();
			//login part
			if($localhash == $login_hash){
				$query_user = "SELECT FirstName, LastName, Phone_Num FROM cap.User WHERE (Email = ?)";
				$stat_user = $link->prepare($query_user);
				$stat_user->bind_param('s', $e);
				$stat_user->execute();
			
				$stat_user->bind_result($firstname, $lastname, $phonenumber);
				$stat_user->fetch();
				
				$_SESSION['FirstName'] = $firstname;
				$_SESSION['LastName'] = $lastname;
				$_SESSION['PhoneNumber'] = $phonenumber;
				
				$stat_user->close();
	  			header('Location: http://zcilok.cloudapp.net/capstone/index.php');  
			}

			else if($localhash != $login_hash){
	    		echo ("<SCRIPT LANGUAGE='JavaScript'>
   					 window.alert('The password is wrong or the account does not exist!')
   					 window.location.href='http://zcilok.cloudapp.net/capstone/index.php';
    				</SCRIPT>");
        	}
			//$stat_login->close();
			
	}

?>

<?php
//update user profile, needs to be put in the front
										if(isset($_POST['save'])){
										
											//profile 
											$FirstName = $_POST['FirstName'];
											$LastName = $_POST['LastName'];
											$PhoneNumber = $_POST['PhoneNumber'];										
											$Email = $_POST['Email'];
											
											//for password change
											$OldPassword = $_POST['OldPassword'];
											$NewPassword = $_POST['NewPassword'];
											$ReNewPassword = $_POST['ReNewPassword'];
											
											//update user database
											$query_update = "UPDATE cap.User SET FirstName = ?, LastName = ?, Phone_Num = ? WHERE (Email = ?)";
											$stat_update = $link->prepare($query_update);
											$stat_update->bind_param('ssss', $FirstName, $LastName, $PhoneNumber, $Email);
											$stat_update->execute();
											$stat_update->close();
											
											//update session value
											$_SESSION['FirstName'] = $FirstName;
											$_SESSION['LastName'] = $LastName;
											$_SESSION['PhoneNumber'] = $PhoneNumber;
											
											//get old pwhash from database
											$query_get_oldpassword = "SELECT password_hash, salt FROM cap.Authentication WHERE (Email = ?)";
											$stat_pwgrab = $link->prepare($query_get_oldpassword);
											$stat_pwgrab->bind_param('s', $_SESSION['Email']);
											$stat_pwgrab->execute();
	
											$stat_pwgrab->bind_result($old_hash1, $old_salt);
											$stat_pwgrab->fetch();
											
											
											//compare these two hash, check whether input old password matches password in database
											$old_hash2 = sha1( $old_salt . $OldPassword);
											$stat_pwgrab->close();
											
											/*shoud use js
											if($old_hash1 != $old_hash2){
													say "The current password you entered is wrong."
											}
											
											if(*$NewPassword != $ReNewPassword){
													say "The passwords you entered do not match. Please fix to continue."
											}
											*/
											
											if($old_hash1 == $old_hash2){
												mt_srand();
												$newsalt = sha1(mt_rand());
												$pwhash = sha1( $newsalt . $NewPassword);
											
												$query_password_update = "UPDATE cap.Authentication SET password_hash = ?, salt = ? WHERE (Email = ?)";
												$stat_pwupdate = $link->prepare($query_password_update);
												$stat_pwupdate->bind_param('sss', $pwhash, $newsalt, $_SESSION['Email']);
												$stat_pwupdate->execute();
												$stat_pwupdate->close();
												
											}									
										}
										
?>

<!DOCTYPE html>
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
	<!--<![endif]-->

	<head>
		<title>Reserve It | Dashboard</title>
	<meta charset="utf-8">
	<meta name="description" content="The Project a Bootstrap-based, Responsive HTML5 Template">
	<meta name="author" content="htmlcoder.me">

	<!-- Mobile Meta -->
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<!-- Favicon -->
	<link rel="shortcut icon" href="images/favicon.ico">

	<!-- Web Fonts -->
	<link href='http://fonts.googleapis.com/css?family=Roboto:400,300,300italic,400italic,500,500italic,700,700italic' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Raleway:700,400,300' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Pacifico' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=PT+Serif' rel='stylesheet' type='text/css'>

	<!-- Bootstrap core CSS -->
	<link href="bootstrap/css/bootstrap.css" rel="stylesheet">

	<!-- Font Awesome CSS -->
	<link href="fonts/font-awesome/css/font-awesome.css" rel="stylesheet">

	<!-- Fontello CSS -->
	<link href="fonts/fontello/css/fontello.css" rel="stylesheet">

	<!-- Plugins -->
	<link href="plugins/magnific-popup/magnific-popup.css" rel="stylesheet">
	<link href="plugins/rs-plugin/css/settings.css" rel="stylesheet">
	<link href="css/animations.css" rel="stylesheet">
	<link href="plugins/owl-carousel/owl.carousel.css" rel="stylesheet">
	<link href="plugins/owl-carousel/owl.transitions.css" rel="stylesheet">
	<link href="plugins/hover/hover-min.css" rel="stylesheet">
	
	<!-- the project core CSS file -->
	<link href="css/style.css" rel="stylesheet" >

	<!-- Color Scheme (In order to change the color scheme, replace the blue.css with the color scheme that you prefer)-->
	<link href="css/skins/light_blue.css" rel="stylesheet">

	<!-- Custom css --> 
	<link href="css/custom.css" rel="stylesheet">
	<link href="css/jquery-ui.min.css" rel="stylesheet">
	<link href="css/datepicker.css" rel="stylesheet">
	
	<!-- Jquery -->
	<script type="text/javascript" src="plugins/jquery.min.js"></script>
	<!-- Jquery UI -->
	<script type="text/javascript" src="plugins/jquery-ui.min.js"></script>
	<script type="text/javascript" src="plugins/bootstrap-datepicker.js"></script>
	<!-- Bootstrap Validator
	<script type="text/javascript" src="plugins/validator.js"></script> -->
		<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/t/dt/dt-1.10.11/datatables.min.css"/>
		<script type="text/javascript" src="https://cdn.datatables.net/t/dt/dt-1.10.11/datatables.min.js"></script>
		
		<script>
		$(document).ready(function() {
    		$('#example').DataTable( {
       		"order": [[ 3, "desc" ]]
    	} );
		} );
		</script>
		<style>
			.collapse-style-2 .panel {
				border: none!important;
				background-color: #FAFAFA;
			}
			.panel-body{
				padding: 10px;
			}
			
		</style>	
	</head>

	<!-- body classes:  -->
	<!-- "boxed": boxed layout mode e.g. <body class="boxed"> -->
	<!-- "pattern-1 ... pattern-9": background patterns for boxed layout mode e.g. <body class="boxed pattern-1"> -->
	<!-- "transparent-header": makes the header transparent and pulls the banner to top -->
	<!-- "page-loader-1 ... page-loader-6": add a page loader to the page (more info @components-page-loaders.html) -->
	<body class="no-trans">

		<!-- scrollToTop -->
		<!-- ================ -->
		<div class="scrollToTop circle"><i class="icon-up-open-big"></i></div>
		
		<!-- page wrapper start -->
		<!-- ================ -->
		<div class="page-wrapper">
		
			<!-- header-container start -->
			<div class="header-container">

				<div class="header-top dark ">
					<div class="container">
						<div class="row">
							<div class="col-xs-3 col-sm-6 col-md-9">

							</div>
							<div class="col-xs-9 col-sm-6 col-md-3">

								<!-- header-top-second start -->
								<!-- ================ -->
								<div id="header-top-second"  class="clearfix">

									<!-- header top dropdowns start -->
									<!-- ================ -->
									<div class="header-top-dropdown text-right">
										<div class="btn-group" id="log">
											<a href="page-signup.php" class="btn btn-default btn-sm" id="sign-up-btn"><i class="fa fa-user pr-10"></i> Sign Up</a>
										</div>
										<div class="btn-group dropdown">
											<button type="button" class="btn dropdown-toggle btn-default btn-sm" data-toggle="dropdown"  id="log-in-btn"><i class="fa fa-lock pr-10"></i> Login</button>
											<ul class="dropdown-menu dropdown-menu-right dropdown-animation">
												<li>
												
													<script>
													<?php
													if(isset($_SESSION['FirstName'])){
														$user = $_SESSION['FirstName'];
														
														echo "$('#log-in-btn').remove();";
														echo "$('#sign-up-btn').remove();";
																						
														echo "var account=\"<a href='dashboard.php' class='btn btn-default btn-sm'>".$user."'s Account</a>\";";
														echo "$('div#log').append(account);";
														echo "var logout=\"<a href='logout.php' class='btn btn-default btn-sm'>Logout</a>\"; ";
														echo "$('div#log').append(logout);";
	
													}
	
													?>
													</script>
													<form action="http://zcilok.cloudapp.net/capstone/index.php" method="POST" class="login-form margin-clear">
														<div class='form-group has-feedback'>
														<label class='control-label'>Email</label>
														<input name="SignInEmail" type="email" class="form-control" placeholder="">
															<i class="fa fa-user form-control-feedback"></i>
														</div>
														<div class="form-group has-feedback">
															<label class="control-label">Password</label>
															<input name="SignInPassword" type="password" class="form-control" placeholder="">
															<i class="fa fa-lock form-control-feedback"></i>
														</div>
														<button name="login" type="submit" class="btn btn-gray btn-sm">Log In</button>
														<ul>
															<li><a href="forget-password.php">Forgot your password?</a></li>
														</ul>

													</form>
												</li>
											</ul>
										</div>
									</div>
									<!--  header top dropdowns end -->
								</div>
								<!-- header-top-second end -->
							</div>
						</div>
					</div>
				</div>
				<!-- header-top end -->
				
				<!-- header start -->
				<!-- classes:  -->
				<!-- "fixed": enables fixed navigation mode (sticky menu) e.g. class="header fixed clearfix" -->
				<!-- "dark": dark version of header e.g. class="header dark clearfix" -->
				<!-- "full-width": mandatory class for the full-width menu layout -->
				<!-- "centered": mandatory class for the centered logo layout -->
				<!-- ================ --> 
				<header class="header  fixed   clearfix">
					
					<div class="container">
						<div class="row">
							<div class="col-md-3">
								<!-- header-left start -->
								<!-- ================ -->
								<div class="header-left clearfix">

									<!-- logo -->
									<div id="logo" class="logo">
										<a href="index.php"><img id="logo_img" src="images/logo_light_blue.png" width="150px" alt="The Project"></a>
									</div>

									<!-- name-and-slogan -->
									<div class="site-slogan">
										    <p>Reserve It -- Book your parking spot now!</p>
									</div>
									
								</div>
								<!-- header-left end -->

							</div>
							<div class="col-md-9">
					
								<!-- header-right start -->
								<!-- ================ -->
								<div class="header-right clearfix">
									
								<!-- main-navigation start -->
								<!-- classes: -->
								<!-- "onclick": Makes the dropdowns open on click, this the default bootstrap behavior e.g. class="main-navigation onclick" -->
								<!-- "animated": Enables animations on dropdowns opening e.g. class="main-navigation animated" -->
								<!-- "with-dropdown-buttons": Mandatory class that adds extra space, to the main navigation, for the search and cart dropdowns -->
								<!-- ================ -->
								<div class="main-navigation  animated with-dropdown-buttons">

									<!-- navbar start -->
									<!-- ================ -->
									<nav class="navbar navbar-default" role="navigation">
										<div class="container-fluid">

											<!-- Toggle get grouped for better mobile display -->
											<div class="navbar-header">
												<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-collapse-1">
													<span class="sr-only">Toggle navigation</span>
													<span class="icon-bar"></span>
													<span class="icon-bar"></span>
													<span class="icon-bar"></span>
												</button>
												
											</div>

											<!-- Collect the nav links, forms, and other content for toggling -->
											<div class="collapse navbar-collapse" id="navbar-collapse-1">
												<!-- main-menu -->
												<ul class="nav navbar-nav ">

													<!-- mega-menu start -->													
													<li class="mega-menu">
														<a href="search.php">Game Search</a>
													</li>
													<!-- mega-menu end -->

													<li>
														<a href="#">Parking Map</a>
													</li>

													<li class="">
														<a href="portfolio-grid-2-3-col.html" >About us</a>
													</li>
													<li class="active">
														<a href="index-shop.html">Support</a>
													</li>
													
												</ul>
												<!-- main-menu end -->
												
												<!-- header dropdown buttons -->
												<div class="header-dropdown-buttons hidden-xs ">
													<div class="btn-group dropdown">
														<button type="button" class="btn dropdown-toggle" data-toggle="dropdown"><i class="icon-search"></i></button>
														<ul class="dropdown-menu dropdown-menu-right dropdown-animation">
															<li>
																<form role="search" class="search-box margin-clear">
																	<div class="form-group has-feedback">
																		<input type="text" class="form-control" placeholder="Search">
																		<i class="icon-search form-control-feedback"></i>
																	</div>
																</form>
															</li>
														</ul>
													</div>

												</div>
												<!-- header dropdown buttons end-->
												
											</div>

										</div>
									</nav>
									<!-- navbar end -->

								</div>
								<!-- main-navigation end -->	
								</div>
								<!-- header-right end -->
					
							</div>
						</div>
					</div>
					
				</header>			
				<!-- header end -->
				<!-- ================ -->
		</div>			

			<!-- header-container end -->
		
			<!-- breadcrumb start -->
			<!-- ================ -->
			<div class="breadcrumb-container">
				<div class="container">
					<ol class="breadcrumb">
						<li><i class="fa fa-home pr-10"></i><a href="index.html">Home</a></li>
						<li class="active">Dashboard</li>
					</ol>
				</div>
			</div>
			<!-- breadcrumb end -->

			<!-- main-container start -->
			<!-- ================ -->
			<section class="main-container">

				<div class="container">
					<div class="row">

						<!-- main start -->
						<!-- ================ -->
						<div class="main col-md-12">

							<!-- page-title start -->
							<!-- ================ -->
							<h1 class="page-title">Dashboard</h1>
							<div class="separator-2"></div>
							<!-- page-title end -->
							<!--<p class="lead">Lorem ipsum dolor sit amet, consectetur adipisicing elit Illo quaerat <br> commodi excepturi dignissimos!</p>
							-->

							<!-- user dashboard tabs start -->
							<!-- ================ -->
							<div class="main col-md-11">
								
								<div class="vertical">
									<!-- Nav tabs -->
									<ul class="nav nav-tabs" role="tablist">
										<li class="active"><a href="#vtab1" role="tab" data-toggle="tab" aria-expanded="false"><i class="fa fa-magic pr-10"></i> User Profile</a></li>
										<li class=""><a href="#vtab2" role="tab" data-toggle="tab" aria-expanded="false"><i class="fa fa-life-saver pr-10"></i> Order History</a></li>
										<!--<li class=""><a href="#vtab3" role="tab" data-toggle="tab" aria-expanded="true"><i class="fa fa-expand pr-10"></i> Navigation</a></li>-->

									</ul>
									<!-- Tab panes -->
									
																				
									<!-- Tab contents -->
									<div class="tab-content">
										<!-- Panel Start -->
										<!-- =============== -->
										<div class="tab-pane fade active in" id="vtab1">
											<form action="dashboard.php" method="POST" data-toggle="validator">
											
									
											<h3 class="title"><i class="icon-user"></i> User Profile</h3>
											<div class="row">
											<!-- Left Column Start -->
											<!-- ================= -->
											<div class="col-md-6" name="column-left">
											
												<!--email part-->
												<div class="row">
													<div class="col-md-10">
														<div class="form-group has-feedback">
														<label class="control-label">Email</label>
														<input type="email" name="Email" value="<?php echo $_SESSION['Email']; ?>" readonly class="form-control ">
														<i class="fa fa-envelope-o form-control-feedback"></i>

														</div>
													</div>
												</div>
											
												<!--first name part-->		
												<div class="row">
													<div class="col-md-10">	
														<div class="form-group has-feedback">
														<label class="control-label">First Name</label>
														<input type="text" name="FirstName" value="<?php echo $_SESSION['FirstName']; ?>" class="form-control">
														<i class="fa fa-user form-control-feedback"></i>
														</div>
													</div>
												</div>
												
												<!--last name part-->
												<div class="row">
													<div class="col-md-10">	
														<div class="form-group has-feedback">
														<label class="control-label">Last Name</label>
														<input type="text" name="LastName" value="<?php echo $_SESSION['LastName']; ?>" class="form-control">
														<i class="fa fa-user form-control-feedback"></i>
														</div>
													</div>
												</div>
											
												<!--phone part-->
												<div class="row">
													<div class="col-md-10">	
														<div class="form-group has-feedback">
														<label class="control-label">Phone</label>
														<input type="text" name="PhoneNumber" value="<?php echo $_SESSION['PhoneNumber']; ?>" class="form-control">
														<i class="fa fa-mobile-phone form-control-feedback"></i>
														</div>
													</div>
												</div>
											</div>
											<!-- Left Column Ends -->
											
											<!-- Right Column Start -->
											<!-- ================== -->
											<div class="col-md-6" name="column-right">
												<!--password panel start-->
												<!-- ==================== -->
												<div class="panel-group collapse-style-2" id="accordion-2">
													<div class="panel panel-default">
									<div class="panel-heading">
										<h4 class="panel-title">
											<a data-toggle="collapse" data-parent="#accordion-2" href="#collapseThree-2" class="collapsed" aria-expanded="false">
												<i class="fa fa-key pr-10"></i>Change Password?
											</a>
										</h4>
									</div>
									<div id="collapseThree-2" class="panel-collapse collapse " aria-expanded="false" style="height: 0px;">
										<div class="panel-body">
											<!-- Current PW -->
											<div class="row">
												<div class="col-md-10">
								<div class="form-group has-feedback">
									<label class="control-label">Current Password</label>
									<input type="password" name="OldPassword" class="form-control">
									<i class="fa fa-lock form-control-feedback"></i>
								</div>
							</div>
											</div>
											<!-- New PW -->
											<div class="row">
												<div class="col-md-10">
								<div class="form-group has-feedback">
									<label class="control-label">New Password</label>
									<input type="password" name="NewPassword" id="inputPW" class="form-control">
									<i class="fa fa-lock form-control-feedback"></i>
								</div>
							</div>
											</div>
											<!-- Confirm PW -->
											<div class="row">
												<div class="col-md-10">
								<div class="form-group has-feedback">
									<label class="control-label">Confirm Password</label>
									<input type="password" name="ReNewPassword" class="form-control" data-match="#inputPW" data-match-error="Whoops, these don't match">
									<i class="fa fa-lock form-control-feedback"></i>
									<div class="help-block with-errors"></div>
								</div>
							</div>
											</div>
										</div>
									</div>
								</div>
								</div>	
											</div>
											<!-- Right Column Ends -->
											
											</div>
											<p style="color:#FAFAFA">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Facere hic qui non placeat ad explicabo dignissimos amet iusto veniam!</p>
											<button name="save" type="submit" class="btn btn-default">Update</button>
											</form>
										</div>
										<!-- Panel ends -->
										
										<!--Order History start-->
										<div class="tab-pane fade" id="vtab2">
											<h3 class="title"><i class="icon-clipboard-1"></i> Order History</h3>
											

											<table id="example" class="display" cellspacing="0" width="100%">
												<thead>
           											<tr>
                										<th>Order Number</th>
                										<th>Status</th>
                										<th>Order Date</th>
                										<th>Detail</th>
            										</tr>
        										</thead>
											
												<tbody>	
                								<?php
                										//get number of results
                										$query_count = "SELECT COUNT(*) FROM cap.Reservation WHERE User_Email = ?";
                										$stmt_count = $link->prepare($query_count);
                										$stmt_count->bind_param("s", $_SESSION['Email']);
                										$stmt_count->execute();
                										$stmt_count->bind_result($count);
                										while($stmt_count->fetch()){
                							
                										}
                										$stmt_count->close();
                										
                										
                										//get orders
           												$query_order = "SELECT * FROM cap.Reservation WHERE User_Email = ?";
           												$stmt_order = $link->prepare($query_order);
                										$stmt_order->bind_param("s", $_SESSION['Email']);
                										$stmt_order->execute();
                										$stmt_order->bind_result($Order_Num, $Order_Date, $email, $b_id, $status);
                									
           												$order_array = array();//store order number
           												
           											 	while($stmt_order->fetch()){
           											 		
           											 		echo "<tr>";
           											 		echo "<td>";
           													echo $Order_Num;	
           													array_push($order_array,$Order_Num);
           													echo "</td>";
           													
           													echo "<td>";
           													echo $status;
           													echo "</td>";
           													
           													echo "<td>";
           													echo $Order_Date;
           													echo "</td>";
           													
           													echo "<td>";
           													echo "<a class='btn btn-default-transparent btn-animated' data-toggle='modal' data-target='.bs-example-modal-lg#".$Order_Num."'>View details
</i><i class='fa fa-eye'></i></a>";	
           								
           													echo "</td>";
           													
           													echo "</tr>";
           													
           												}
           												
           												$stmt_order->close();

                								//start order detail part
                								$DateArray = array();
                								$TeamAArray = array();
                								$TeamBArray =  array();
                								$PKLot_idArray = array();
                								
                								$PK_NameArray = array();
           						 				$PK_PriceArray = array();
           	
                								
                							for($i=0;$i<$count;$i++){
           						 				$query_detail = "SELECT Date, DATE_FORMAT(StartTime,'%h:%i %p'), TeamA, TeamB, PKLot_id FROM cap.Reservation INNER JOIN cap.Event_Buttons ON Event_Buttons.B_id = Reservation.Button_id WHERE Order_Num = ?";
           						 				
           						 				$stmt_detail = $link->prepare($query_detail);			
												$stmt_detail->bind_param("s", $order_array[$i]);
												$stmt_detail->execute();
												$stmt_detail->bind_result($Date, $StartTime, $TeamA, $TeamB, $PKLot_id);
 												$stmt_detail->fetch();
 												
 												array_push($DateArray,$Date);
 												array_push($TeamAArray,$TeamA);
 												array_push($TeamBArray,$TeamB);
 												array_push($PKLot_idArray,$PKLot_id);
           						 				$stmt_detail->close();
           						 				

												$query_PKLot = "SELECT Name, Price FROM cap.PKLot WHERE P_id = ?";
												$stmt_PKLot = $link->prepare($query_PKLot);
												$stmt_PKLot->bind_param("d", $PKLot_id);
												$stmt_PKLot->execute();
												$stmt_PKLot->bind_result($PK_Name, $PK_Price);
												$stmt_PKLot->fetch();
												array_push($PK_NameArray,$PK_Name);
												array_push($PK_PriceArray,$PK_Price);
												$stmt_PKLot->close();
											}
												
           						 ?>
           						 					
           		
            									</tbody>
   										 </table>		
									
							<?php
							for($i=0;$i<$count;$i++){
							
							echo "<div class='modal fade bs-example-modal-lg' tabindex='-1' role='dialog' aria-labelledby='myLargeModalLabel' aria-hidden='true' id=".$order_array[$i].">";
								echo "<div class='modal-dialog modal-lg'>";
									echo "<div class='modal-content'>";
										echo "<div class='modal-header'>";
										echo "<button type='button' class='close' data-dismiss='modal'><span aria-hidden='true'>&times;</span><span class='sr-only'>Close</span></button>";
										echo "<h4 class='modal-title' id='myLargeModalLabel'>Order details</h4>";
										echo "</div>";
														
										echo "<div class='modal-body'>";
											echo "<div class='row'>";
													echo "<div class='col-md-6'>";
														echo "<iframe class='mfp-iframe' src='https://maps.google.com/maps?q=Faurot+Field+at+Memorial+Stadium&amp;hl=en&amp;t=v&amp;output=embed' frameborder='0' style='width: 500px;height: 500px;'></iframe>";
													echo "</div>";
													
													echo "<div class='col-md-6' style='padding-right:50px;'>";
														echo "<h2>".$TeamAArray[$i]." vs ".$TeamBArray[$i]."</h2>";
														echo "<p class='lead'>";
														echo "Game Date:&nbsp;&nbsp;&nbsp;".$DateArray[$i]."</br>";
														echo "Parking Spot:&nbsp;&nbsp;&nbsp;".$PK_NameArray[$i]."</br>";
														echo "</p>";
														echo "<h3 style='text-align: right;'>Subtotal: $".$PK_PriceArray[$i]."</h3>";
															
														echo "<div class='alert alert-info' role='alert'>Please use the QR code provided below to confirm at your arrival:</div>";
														echo "<div class='large gray-bg object-non-visible animated object-visible pulse' data-animation-effect='pulse' data-effect-delay='1000'>";
														echo "<img id='myImg' src='https://api.qrserver.com/v1/create-qr-code/?data=http://zcilok.cloudapp.net/ci/index.php/test/index/4HJ5566804158562C'   style='    height: 200px;margin-left: auto;margin-right: auto;'>";
														echo "</div>";
														
													echo "</div>";//end class col-md-6
											echo "</div>";//end class = 'row'
										echo "</div>";//end class - body
									echo "</div>";
								echo "</div>";
							echo "</div>";
							}
							?>
								

											
											
											<p style="color:#FAFAFA">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Facere hic qui non placeat ad explicabo dignissimos amet iusto veniam!</p>
											<a href="page-services.html" class="btn btn-default">Read more</a>
											
										</div>
									
									</div>	
									</div>
									<!-- Tab contents end -->

								</div>
							
							</div>
							<!-- user dashboard tabs end -->

						</div>
						<!-- main end -->

					</div>
				</div>
			</section>
			<!-- main-container end -->
			
			<!-- footer top start -->
			<!-- ================ -->
			<div class="dark-bg  default-hovered footer-top animated-text">
				<div class="container">
					<div class="row">
						<div class="col-md-12">
							<div class="call-to-action text-center">
								<div class="row">
									<div class="col-sm-8">
										<h2>Powerful Booking System</h2>
										<h2>Waste no more time</h2>
									</div>
									<div class="col-sm-4">
										<p class="mt-10"><a href="#" class="btn btn-animated btn-lg btn-gray-transparent ">Reserve It<i class="fa fa-cart-arrow-down pl-20"></i></a></p>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- footer top end -->
			
			<!-- footer start (Add "dark" class to #footer in order to enable dark footer) -->
			<!-- ================ -->
			<footer id="footer" class="clearfix ">

				<!-- .footer start -->
				<!-- ================ -->
				<div class="footer">
					<div class="container">
						<div class="footer-inner">
							<div class="row">
								<div class="col-md-3">
									<div class="footer-content">
										<div class="logo-footer"><img id="logo-footer" src="images/logo_light_blue.png" alt=""></div>
										<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Necessitatibus illo vel dolorum soluta consectetur doloribus sit. Delectus non tenetur odit dicta vitae debitis suscipit doloribus. Ipsa, aut voluptas quaerat... <a href="page-about.html">Learn More<i class="fa fa-long-arrow-right pl-5"></i></a></p>
										<div class="separator-2"></div>
										<nav>
											<ul class="nav nav-pills nav-stacked">
												<li><a target="_blank" href="http://htmlcoder.me/support">Support</a></li>
												<li><a href="#">Privacy</a></li>
												<li><a href="#">Terms</a></li>
												<li><a href="page-about.html">About</a></li>
											</ul>
										</nav>
									</div>
								</div>
								<div class="col-md-3">
									<div class="footer-content">
										<h2 class="title">Latest From Blog</h2>
										<div class="separator-2"></div>
										<div class="media margin-clear">
											<div class="media-left">
												<div class="overlay-container">
													<img class="media-object" src="images/blog-thumb-1.jpg" alt="blog-thumb">
													<a href="blog-post.html" class="overlay-link small"><i class="fa fa-link"></i></a>
												</div>
											</div>
											<div class="media-body">
												<h6 class="media-heading"><a href="blog-post.html">Lorem ipsum dolor sit amet...</a></h6>
												<p class="small margin-clear"><i class="fa fa-calendar pr-10"></i>Mar 23, 2015</p>
											</div>
											<hr>
										</div>
										<div class="media margin-clear">
											<div class="media-left">
												<div class="overlay-container">
													<img class="media-object" src="images/blog-thumb-2.jpg" alt="blog-thumb">
													<a href="blog-post.html" class="overlay-link small"><i class="fa fa-link"></i></a>
												</div>
											</div>
											<div class="media-body">
												<h6 class="media-heading"><a href="blog-post.html">Lorem ipsum dolor sit amet...</a></h6>
												<p class="small margin-clear"><i class="fa fa-calendar pr-10"></i>Mar 22, 2015</p>
											</div>
											<hr>
										</div>
										<div class="media margin-clear">
											<div class="media-left">
												<div class="overlay-container">
													<img class="media-object" src="images/blog-thumb-3.jpg" alt="blog-thumb">
													<a href="blog-post.html" class="overlay-link small"><i class="fa fa-link"></i></a>
												</div>
											</div>
											<div class="media-body">
												<h6 class="media-heading"><a href="blog-post.html">Lorem ipsum dolor sit amet...</a></h6>
												<p class="small margin-clear"><i class="fa fa-calendar pr-10"></i>Mar 21, 2015</p>
											</div>
											<hr>
										</div>
										<div class="media margin-clear">
											<div class="media-left">
												<div class="overlay-container">
													<img class="media-object" src="images/blog-thumb-4.jpg" alt="blog-thumb">
													<a href="blog-post.html" class="overlay-link small"><i class="fa fa-link"></i></a>
												</div>
											</div>
											<div class="media-body">
												<h6 class="media-heading"><a href="blog-post.html">Lorem ipsum dolor sit amet...</a></h6>
												<p class="small margin-clear"><i class="fa fa-calendar pr-10"></i>Mar 21, 2015</p>
											</div>
										</div>
										<div class="text-right space-top">
											<a href="blog-large-image-right-sidebar.html" class="link-dark"><i class="fa fa-plus-circle pl-5 pr-5"></i>More</a>	
										</div>
									</div>
								</div>
								<div class="col-md-3">
									<div class="footer-content">
										<h2 class="title">Portfolio Gallery</h2>
										<div class="separator-2"></div>
										<div class="row grid-space-10">
											<div class="col-xs-4 col-md-6">
												<div class="overlay-container mb-10">
													<img src="images/gallery-1.jpg" alt="">
													<a href="portfolio-item.html" class="overlay-link small">
														<i class="fa fa-link"></i>
													</a>
												</div>
											</div>
											<div class="col-xs-4 col-md-6">
												<div class="overlay-container mb-10">
													<img src="images/gallery-2.jpg" alt="">
													<a href="portfolio-item.html" class="overlay-link small">
														<i class="fa fa-link"></i>
													</a>
												</div>
											</div>
											<div class="col-xs-4 col-md-6">
												<div class="overlay-container mb-10">
													<img src="images/gallery-3.jpg" alt="">
													<a href="portfolio-item.html" class="overlay-link small">
														<i class="fa fa-link"></i>
													</a>
												</div>
											</div>
											<div class="col-xs-4 col-md-6">
												<div class="overlay-container mb-10">
													<img src="images/gallery-4.jpg" alt="">
													<a href="portfolio-item.html" class="overlay-link small">
														<i class="fa fa-link"></i>
													</a>
												</div>
											</div>
											<div class="col-xs-4 col-md-6">
												<div class="overlay-container mb-10">
													<img src="images/gallery-5.jpg" alt="">
													<a href="portfolio-item.html" class="overlay-link small">
														<i class="fa fa-link"></i>
													</a>
												</div>
											</div>
											<div class="col-xs-4 col-md-6">
												<div class="overlay-container mb-10">
													<img src="images/gallery-6.jpg" alt="">
													<a href="portfolio-item.html" class="overlay-link small">
														<i class="fa fa-link"></i>
													</a>
												</div>
											</div>
										</div>
										<div class="text-right space-top">
											<a href="portfolio-grid-2-3-col.html" class="link-dark"><i class="fa fa-plus-circle pl-5 pr-5"></i>More</a>	
										</div>
									</div>
								</div>
								<div class="col-md-3">
									<div class="footer-content">
										<h2 class="title">Find Us</h2>
										<div class="separator-2"></div>
										<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Accusantium odio voluptatem necessitatibus illo vel dolorum soluta.</p>
										<ul class="social-links circle animated-effect-1">
											<li class="facebook"><a target="_blank" href="http://www.facebook.com"><i class="fa fa-facebook"></i></a></li>
											<li class="twitter"><a target="_blank" href="http://www.twitter.com"><i class="fa fa-twitter"></i></a></li>
											<li class="googleplus"><a target="_blank" href="http://plus.google.com"><i class="fa fa-google-plus"></i></a></li>
											<li class="linkedin"><a target="_blank" href="http://www.linkedin.com"><i class="fa fa-linkedin"></i></a></li>
											<li class="xing"><a target="_blank" href="http://www.xing.com"><i class="fa fa-xing"></i></a></li>
										</ul>
										<div class="separator-2"></div>
										<ul class="list-icons">
											<li><i class="fa fa-map-marker pr-10 text-default"></i> One infinity loop, 54100</li>
											<li><i class="fa fa-phone pr-10 text-default"></i> +00 1234567890</li>
											<li><a href="mailto:info@theproject.com"><i class="fa fa-envelope-o pr-10"></i>info@theproject.com</a></li>
										</ul>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- .footer end -->

				<!-- .subfooter start -->
				<!-- ================ -->
				<div class="subfooter">
					<div class="container">
						<div class="subfooter-inner">
							<div class="row">
								<div class="col-md-12">
									<p class="text-center">Copyright © 2015 The Project by <a target="_blank" href="http://htmlcoder.me">HtmlCoder</a>. All Rights Reserved</p>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- .subfooter end -->

			</footer>
			<!-- footer end -->
			
		</div>
		<!-- page-wrapper end -->

		<!-- JavaScript files placed at the end of the document so the pages load faster -->
		<!-- ================================================== -->
		<!-- Jquery and Bootstap core js files -->
		<!--<script type="text/javascript" src="plugins/jquery.min.js"></script>-->
		<script type="text/javascript" src="bootstrap/js/bootstrap.min.js"></script>

		<!-- Modernizr javascript -->
		<script type="text/javascript" src="plugins/modernizr.js"></script>

		<!-- Isotope javascript -->
		<script type="text/javascript" src="plugins/isotope/isotope.pkgd.min.js"></script>
		
		<!-- Magnific Popup javascript -->
		<script type="text/javascript" src="plugins/magnific-popup/jquery.magnific-popup.min.js"></script>
		
		<!-- Appear javascript -->
		<script type="text/javascript" src="plugins/waypoints/jquery.waypoints.min.js"></script>

		<!-- Count To javascript -->
		<script type="text/javascript" src="plugins/jquery.countTo.js"></script>
		
		<!-- Parallax javascript -->
		<script src="plugins/jquery.parallax-1.1.3.js"></script>

		<!-- Contact form -->
		<script src="plugins/jquery.validate.js"></script>

		<!-- Owl carousel javascript -->
		<script type="text/javascript" src="plugins/owl-carousel/owl.carousel.js"></script>
		
		<!-- SmoothScroll javascript -->
		<script type="text/javascript" src="plugins/jquery.browser.js"></script>
		<script type="text/javascript" src="plugins/SmoothScroll.js"></script>

		<!-- Initialization of Plugins -->
		<script type="text/javascript" src="js/template.js"></script>

		<!-- Custom Scripts -->
		<script type="text/javascript" src="js/custom.js"></script>
	</body>
</html>
