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


<!DOCTYPE html>
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
	<!--<![endif]-->

	<head>
		
		<title>The Project | Page Services 2</title>
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
	</head>

	<!-- body classes:  -->
	<!-- "boxed": boxed layout mode e.g. <body class="boxed"> -->
	<!-- "pattern-1 ... pattern-9": background patterns for boxed layout mode e.g. <body class="boxed pattern-1"> -->
	<!-- "transparent-header": makes the header transparent and pulls the banner to top -->
	<!-- "page-loader-1 ... page-loader-6": add a page loader to the page (more info @components-page-loaders.html) -->
	<body class="no-trans   ">

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
						<li><i class="fa fa-home pr-10"></i><a href="index.php">Home</a></li>
						<li><i class="fa fa-search pr-10"></i><a href="search.php">Search</a></li>
						<li class="active">Result</li>
					</ol>
				</div>
			</div>
			<!-- breadcrumb end -->

			<!-- main-container start -->
			<!-- ================ -->
			<section class="main-container padding-bottom-clear">

				<div class="container">
					<div class="row">

						<!-- main start -->
						<!-- ================ -->
						<div class="main col-md-12">
							<!-- page-title start -->
							<!-- ================ -->
							<h1 class="page-title text-center">Our <strong>Services</strong></h1>
							<div class="separator"></div>
							<br>
							<!-- page-title end -->

<?php
		$team = $_POST['team'];
		$date = $_POST['date'];
		
		//search by team
		if(!empty($team)){
			//get the corresponding events
			//$query_event = "SELECT Date, DATE_FORMAT(StartTime,'%h:%i %p'), TeamA, teamB, Name, Price FROM cap.Event CROSS JOIN cap.PKLot WHERE TeamA = ? or TeamB = ?";
			$query_event = "SELECT Date, DATE_FORMAT(StartTime,'%h:%i %p'), TeamA, teamB, Button_Code, Name, Price FROM Event_Buttons INNER JOIN PKLot ON Event_Buttons.PKLot_id = PKLot.P_id WHERE TeamA = ? or TeamB = ?";
			$stat_event = $link->prepare($query_event);
			$stat_event->bind_param('ss', $team, $team);
			$stat_event->execute();
		
			$stat_event->store_result();
			$stat_event->bind_result($Date, $StartTime, $TeamA, $TeamB, $Button_Code, $PKName, $PKPrice);
			$count = $stat_event->num_rows;
		}
		
		
		//search by date
		if(!empty($date)){
			//get the corresponding events
			$query_event = "SELECT Date, DATE_FORMAT(StartTime,'%h:%i %p'), TeamA, teamB, Button_Code, Name, Price FROM Event_Buttons INNER JOIN PKLot ON Event_Buttons.PKLot_id = PKLot.P_id WHERE Date = ?";
			$stat_event = $link->prepare($query_event);
			$stat_event->bind_param('s', $date);
			$stat_event->execute();
		
			$stat_event->store_result();
			$stat_event->bind_result($Date, $StartTime, $TeamA, $TeamB, $Button_Code, $PKName, $PKPrice);
			$count = $stat_event->num_rows;
		}
		
		
		//search by both team and date
		if( (!empty($team)) && (!empty($date))){
			$query_event = "SELECT Date, DATE_FORMAT(StartTime,'%h:%i %p'), TeamA, teamB, Button_Code, Name, Price FROM Event_Buttons INNER JOIN PKLot ON Event_Buttons.PKLot_id = PKLot.P_id WHERE Date = ? AND (TeamA = ? or TeamB = ?)";
			$stat_event = $link->prepare($query_event);
			$stat_event->bind_param('sss', $date,$team,$team);
			$stat_event->execute();
		
			$stat_event->store_result();
			$stat_event->bind_result($Date, $StartTime, $TeamA, $TeamB, $Button_Code, $PKName, $PKPrice);
			$count = $stat_event->num_rows;
		}
		
				
		if($count == 0){
			echo "<div class='row'>";
			echo "<p>Opps, there is no corresponding game...</p>";
			echo "<br>";
			echo "<a href='search.php' name='SearchAgain' class='btn btn-default'>Search Again</a>";
			
			echo "</div>";
		
		}	
			
		
		else if($count != 0){	
			$count_track=0;
			while($stat_event->fetch()){
				$count_track++;	
				//echo $count_track;
				//echo "<li><a>".$row."</a></li>";
				if($count_track%2 == 1){
					echo "<div class='row'>";
							
						echo "<div class='col-sm-3'>";
							echo "<div class='image-box style-2 mb-20'>";
									echo "<div class='overlay-container overlay-visible'>";
									echo "<img src='images/service-3.jpg' alt=''>";
									echo "<a href='#' class='overlay-link'><i class='fa fa-link'></i></a>";
										echo "<div class='overlay-bottom hidden-xs'>";
											echo "<div class='text'>";
											echo "<p class='lead margin-clear text-left'>Service Three</p>";
											echo "</div>";
										echo "</div>";
									echo "</div>";
							echo "</div>";
						echo "</div>";
						echo "<div class='body padding-horizontal-clear col-sm-3'>";
							//echo "<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aliquam atque ipsam nihil, adipisci rem minus? Voluptatem distinctio laborum porro aspernatur.</p>";
							echo "<b>".$TeamA."</b> VS <b>".$TeamB."</b>";
							echo "<br>";
							echo $Date;
							
							echo "@".$StartTime;
							echo "<br>";
							echo "Parking Spot #".$PKName;
							echo "<br>";
							//setlocale(LC_MONETARY, 'en_US');
							echo "<b>$".money_format('%i', $PKPrice)."</b>";
							//echo "<a name='AddCart' class='pull-right btn btn-sm btn-default-transparent btn-animated'>Add to Cart<i class='fa fa-shopping-cart'></i></a>";
							/*echo '<form target="paypal" action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="96AYSW6VNWGGA">
<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_cart_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form>
';*/
							echo $Button_Code;
							//echo "<a class='link-dark' href='page-services.html'>Read More<i class='pl-5 fa fa-angle-double-right'></i></a>";
					echo "</div>";
				}		
									
				else if($count_track%2 == 0){
				echo "<div class='col-sm-3'>";
					echo "<div class='image-box style-2 mb-20'>";
						echo "<div class='overlay-container overlay-visible'>";
						echo "<img src='images/service-3.jpg' alt=''>";
						echo "<a href='#' class='overlay-link'><i class='fa fa-link'></i></a>";
							echo "<div class='overlay-bottom hidden-xs'>";
								echo "<div class='text'>";
								echo "<p class='lead margin-clear text-left'>Service Three</p>";
								echo "</div>";
							echo "</div>";
						echo "</div>";
					echo "</div>";
				echo "</div>";
				echo "<div class='body padding-horizontal-clear col-sm-3'>";
					echo "<b>".$TeamA."</b> VS <b>".$TeamB."</b>";
							echo "<br>";
							echo $Date;
							
							echo "@".$StartTime;
							echo "<br>";
							echo "Parking Spot #".$PKName;
							echo "<br>";
							//setlocale(LC_MONETARY, 'en_US');
							echo "<b>$".money_format('%i', $PKPrice)."</b>";
							echo $Button_Code;
							//echo "<a name='AddCart' class='pull-right btn btn-sm btn-default-transparent btn-animated'>Add to Cart<i class='fa fa-shopping-cart'></i></a>";
						//echo "<a class='link-dark' href='page-services.html'>Read More<i class='pl-5 fa fa-angle-double-right'></i></a>";
				echo "</div>";	
				}	
			
				if($count_track==$count || $count_track%2==0){									
					echo "</div>";
				}
			}
						
		$stat_event->close();
		echo "<div class='separator'></div>";
		echo "<div class='row' style='padding: 15px;'>";
		echo "<a href='checkout.php' name='Checkout' class='btn btn-default-transparent'>Checkout </a>&nbsp;&nbsp;&nbsp;";
		echo "<a href='search.php' name='NewSearch' class='btn btn-default'>New Search</a>";
		
		echo "</div>";
		}
	
?>

					
						</div>
						<!-- main end -->

					</div>
				</div>
			</section>
			<!-- main-container end -->
		
			<!-- section start -->
			<!-- ================ -->
			
<!--
			<section class="video-background-banner pv-40 dark-translucent-bg">
				<div class="container">
					<div class="call-to-action text-center">
						<div class="row">
							<div class="col-sm-8 col-sm-offset-2">
								<h2 class="title">Subscribe Now</h2>
								<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Repellendus error pariatur deserunt laudantium nam, mollitia quas nihil inventore, quibusdam?</p>
								<div class="separator"></div>
								<form class="form-inline margin-clear">
									<div class="form-group has-feedback">
										<label class="sr-only" for="subscribe3">Email address</label>
										<input type="email" class="form-control" id="subscribe3" placeholder="Enter email" name="subscribe3" required="">
										<i class="fa fa-envelope form-control-feedback"></i>
									</div>
									<button type="submit" class="btn btn-gray-transparent btn-animated margin-clear">Submit <i class="fa fa-send"></i></button>
								</form>
							</div>
						</div>
					</div>
				</div>
			</section>
-->
			<!-- section end -->

<!--
			<section class="clearfix pv-30">
				<div class="container">
					<div class="row">
						<div class="col-md-12">
							<h3 class="text-center">The <strong>Process</strong> We Follow</h3>
							<div class="separator"></div>
							
							<div class="process">
								
								<ul class="nav nav-pills" role="tablist">
									<li class="active"><a href="#pill-pr-1" role="tab" data-toggle="tab" title="Step 1"><i class="fa fa-dot-circle-o pr-5"></i> Step 1</a></li>
									<li><a href="#pill-pr-2" role="tab" data-toggle="tab" title="Step 2"><i class="fa fa-dot-circle-o pr-5"></i> Step 2</a></li>
									<li><a href="#pill-pr-3" role="tab" data-toggle="tab" title="Step 3"><i class="fa fa-dot-circle-o pr-5"></i> Step 3</a></li>
								</ul>
								
								<div class="tab-content clear-style">
									<div class="tab-pane active" id="pill-pr-1">
										<div class="row">
											<div class="col-md-6">
												<h4 class="title">Consectetur adipisicing elit</h4>
												<p>Ipsum dolor sit amet, consectetur adipisicing elit. Sit, labore iste! Pariatur tempore, dicta voluptatibus quis blanditiis voluptates in. Molestiae asperiores sed, pariatur nesciunt saepe. Culpa ipsam ut enim reiciendis!</p>
												<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ea sit enim sint deleniti saepe esse nisi nesciunt fuga eaque dicta tenetur, cupiditate illo, consequuntur unde dolores quis dolore rem ex asperiores error. Labore saepe beatae harum quod fuga ipsam! Iusto earum iste similique, quam esse rerum, quae atque inventore consequuntur voluptatum amet deserunt mollitia? Tempore fugit, cumque dolor eaque doloremque iusto nostrum excepturi unde! Similique ipsum fugit eius laboriosam nihil quos, quia et! Earum iure, sapiente. Molestiae unde earum fugiat voluptate incidunt.</p>
												<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Dolores repellendus autem corporis obcaecati, laboriosam ipsam ea, alias saepe libero ab consequuntur.</p>
											</div>
											<div class="col-md-6">
												<div class="embed-responsive embed-responsive-16by9">
													<iframe class="embed-responsive-item" src="//player.vimeo.com/video/29198414?byline=0&amp;portrait=0"></iframe>
													<p><a href="http://vimeo.com/29198414">Introducing Vimeo Music Store</a> from <a href="http://vimeo.com/staff">Vimeo Staff</a> on <a href="https://vimeo.com">Vimeo</a>.</p>
												</div>
											</div>
										</div>
									</div>
									<div class="tab-pane" id="pill-pr-2">
										<div class="row">
											<div class="col-md-6">
												<img src="images/section-image-3.jpg" alt="">
											</div>
											<div class="col-md-6">
												<h3 class="title">Lorem ipsum dolor sit amet</h3>
												<p>Ipsum dolor sit amet, consectetur adipisicing elit. Sit, labore iste! Pariatur tempore, dicta voluptatibus quis blanditiis voluptates in. Molestiae asperiores sed, pariatur nesciunt saepe. Culpa ipsam ut enim reiciendis!</p>
												<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ea sit enim sint deleniti saepe esse nisi nesciunt fuga eaque dicta tenetur, cupiditate illo, consequuntur unde dolores quis dolore rem ex asperiores error. Labore saepe beatae harum quod fuga ipsam! Iusto earum iste similique, quam esse rerum, quae atque inventore consequuntur voluptatum amet deserunt mollitia? Tempore fugit, cumque dolor eaque doloremque iusto nostrum excepturi unde! Similique ipsum fugit eius laboriosam nihil quos, quia et! Earum iure, sapiente. Molestiae unde earum fugiat voluptate incidunt.</p>
											</div>
										</div>
									</div>
									<div class="tab-pane" id="pill-pr-3">
										<p>Consectetur adipisicing elit. Sit, labore iste! Pariatur tempore, dicta voluptatibus quis blanditiis voluptates in. Molestiae asperiores sed, pariatur nesciunt saepe. Culpa ipsam ut enim reiciendis!</p>
										<p>Ipsum dolor sit amet, consectetur adipisicing elit. Debitis eaque nihil obcaecati minus perferendis officiis cumque nesciunt quae dolorem ad tenetur voluptate blanditiis labore libero id veritatis similique facere, fugiat soluta omnis minima? Iusto optio hic a reiciendis, inventore numquam impedit quaerat deserunt, voluptatum illum in magni, aspernatur corporis mollitia nam? Ipsum, cumque facilis recusandae commodi dolorum sequi quis possimus, aut, ab incidunt dolorem perferendis rerum tenetur. Labore exercitationem magni perspiciatis laborum, aliquam officia molestias obcaecati, voluptatum quidem eum iure aut, officiis dicta cupiditate ab, sit aliquid. Corporis ducimus ab commodi officia? Inventore suscipit saepe dolorem vitae aperiam autem, iusto id, enim assumenda modi a voluptate aliquam molestiae quidem sapiente, obcaecati recusandae. Magni repellat dignissimos sint iste dolorem maxime accusantium quas repellendus quam ipsa, sapiente, beatae enim ab ex. Optio eveniet necessitatibus, tempora dolor eaque saepe, ipsam vero at officiis cumque, maxime consequuntur in ea, voluptate quae nesciunt hic delectus.</p>
									</div>
								</div>
							</div>
							
							<div class="pricing-tables stripped object-non-visible" data-animation-effect="fadeInUpSmall"  data-effect-delay="100">
								<div class="row grid-space-0">
									
									<div class="col-sm-3 plan stripped">
										<div class="header dark-bg">
											<h3>Basic</h3>
											<div class="price"><span>Free</span></div>
										</div>
										<ul>
											<li>1 User</li>
											<li>
												<a href="#" class="pt-popover" data-toggle="popover" data-placement="right" data-content="Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum." title="" data-original-title="15GB Storage" data-trigger="hover">1GB Storage</a>
											</li>
											<li>
												<a href="#" class="pt-popover" data-toggle="popover" data-placement="right" data-content="Vivamus sagittis lacus vel augue laoreet rutrum faucibus. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum." title="" data-original-title="15 Email Acounts">1 Email Acounts</a>
											</li>
											<li>Subdomains</li>
											<li>Security</li>
											<li>Bandwidth</li>
											<li>Databases</li>
											<li><a class="btn btn-dark btn-animated">Subscribe <i class="fa fa-user"></i></a></li>
										</ul>
									</div>
									

									
									<div class="col-sm-3 plan stripped">
										<div class="header dark-bg">
											<h3>Premium</h3>
											<div class="price"><span>$19.99</span>/m.</div>
										</div>
										<ul>
											<li>80 Users</li>
											<li>Unlimited Disk Space</li>
											<li>
												<a href="#" class="pt-popover" data-toggle="popover" data-placement="right" data-content="Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum." title="" data-original-title="Unlimited Subdomains">Unlimited Email Acounts</a>
											</li>        
											<li>Subdomains</li>
											<li>Security</li>
											<li>500 Visitors per month</li>
											<li>1 Database</li>
											<li><a class="btn btn-dark btn-animated">Add to cart <i class="fa fa-shopping-cart"></i></a></li>
										</ul>
									</div>
									
									
									<div class="col-sm-3 plan stripped best-value">
										<div class="header default-bg">
											<h3>Pro</h3>
											<div class="price"><span>$24.99</span>/m.</div>
										</div>
										<ul>
											<li>Unlimited Users</li>
											<li>Unlimited Disk Space</li>
											<li>Unlimited Email Acounts</li>
											<li>Unlimited Subdomains</li>
											<li>
												<a href="#" class="pt-popover" data-toggle="popover" data-placement="right" data-content="Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum." title="" data-original-title="Enchaned Security">Enchaned Security</a>
											</li>
											<li>Unlimited Bandwidth</li>
											<li>Unlimited Databases</li>
											<li><a class="btn btn-default btn-animated">Add to cart <i class="fa fa-shopping-cart"></i></a></li>
										</ul>
									</div>
									
									<div class="col-sm-3 plan">
										<div class="header dark-bg">
											<h3>Ultimate</h3>
											<div class="price"><span>$45.99</span>/m.</div>
										</div>
										<ul>
											<li>Unlimited Users</li>
											<li>Unlimited Disk Space</li>
											<li>Unlimited Email Acounts</li>
											<li>Unlimited Subdomains</li>
											<li>
												<a href="#" class="pt-popover" data-toggle="popover" data-placement="right" data-content="Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum." title="" data-original-title="Enchaned Security">Enchaned Security</a>
											</li>
											<li>Unlimited Bandwidth</li>
											<li>Unlimited Databases</li>
											<li><a class="btn btn-dark btn-animated">Add to cart <i class="fa fa-shopping-cart"></i></a></li>
										</ul>
									</div>
									

								</div>
							</div>
							
							
						</div>
					</div>
				</div>
			</section>
			-->
			<!-- section end -->
		
			<!-- footer top start -->
			<!-- ================ -->
			
<!--
			<div class="dark-bg  default-hovered footer-top animated-text">
				<div class="container">
					<div class="row">
						<div class="col-md-12">
							<div class="call-to-action text-center">
								<div class="row">
									<div class="col-sm-8">
										<h2>Powerful Bootstrap Template</h2>
										<h2>Waste no more time</h2>
									</div>
									<div class="col-sm-4">
										<p class="mt-10"><a href="#" class="btn btn-animated btn-lg btn-gray-transparent ">Purchase<i class="fa fa-cart-arrow-down pl-20"></i></a></p>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
-->
			<!-- footer top end -->
			
			<!-- footer start (Add "dark" class to #footer in order to enable dark footer) -->
			<!-- ================ -->
			<footer id="footer" class="clearfix ">

				<!-- .footer start -->
				<!-- ================ -->
				
	<!--
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
				
-->
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
		<script type="text/javascript" src="plugins/jquery.min.js"></script>
		<script type="text/javascript" src="bootstrap/js/bootstrap.min.js"></script>

		<!-- Modernizr javascript -->
		<script type="text/javascript" src="plugins/modernizr.js"></script>

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

		<!-- Background Video -->
		<script src="plugins/vide/jquery.vide.js"></script>

		<!-- Owl carousel javascript -->
		<script type="text/javascript" src="plugins/owl-carousel/owl.carousel.js"></script>
		
		<!-- SmoothScroll javascript -->
		<script type="text/javascript" src="plugins/jquery.browser.js"></script>
		<script type="text/javascript" src="plugins/SmoothScroll.js"></script>

		<!-- Initialization of Plugins -->
		<script type="text/javascript" src="js/template.js"></script>

		<!-- Custom Scripts -->
		<script type="text/javascript" src="js/custom.js"></script>
		 <script>
		 $( ".paypal_form" ).submit(function( event ) {
        $(this).find('input[name="custom"]').val("<?=$email?>");
        console.dir($(this).find('input[name="custom"]').val());
  
		//event.preventDefault();
  
});
		 </script>
	</body>
</html>
