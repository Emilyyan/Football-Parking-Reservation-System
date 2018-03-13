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
	<title>Reserve It</title>
	
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
	<body class="no-trans">

		<!-- scrollToTop -->
		<!-- ================ -->
		<div class="scrollToTop circle"><i class="icon-up-open-big"></i></div>
		
		<!-- page wrapper start -->
		<!-- ================ -->
		<div class="page-wrapper">
		
		<!-- header-container start -->
		<!-- ================ -->
		
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
			
			<!-- banner start -->
			<!-- ================ -->
			<div class="dark-bg banner pv-40">
				<div class="container clearfix">

					<!-- slideshow start -->
					<!-- ================ -->
					<div class="slideshow">
						
						<!-- slider revolution start -->
						<!-- ================ -->
						<div class="slider-banner-container">
							<div class="slider-banner-boxedwidth">
								<ul class="slides">
									<!-- slide 1 start -->
									<!-- ================ -->
									<li data-transition="random" data-slotamount="7" data-masterspeed="500" data-saveperformance="on" data-title="">
									
									<!-- main image -->
									<img src="images/title_slide_1.jpg" alt="slidebg1" data-bgposition="center top"  data-bgrepeat="no-repeat" data-bgfit="cover">
									
									<!-- Transparent Background -->
									<div class="tp-caption dark-translucent-bg"
										data-x="center"
										data-y="bottom"
										data-speed="600"
										data-start="0">
									</div>

									<!-- LAYER NR. 1 -->
									<div class="tp-caption sfb fadeout large_white"
										data-x="left"
										data-y="80"
										data-speed="500"
										data-start="1000"
										data-easing="easeOutQuad">Still <span class="text-default">worry about</span> your parking?
									</div>	

									<!-- LAYER NR. 2 -->
									<div class="tp-caption sfb fadeout large_white tp-resizeme hidden-xs"
										data-x="left"
										data-y="200"
										data-speed="500"
										data-start="1300"
										data-easing="easeOutQuad"><div class="separator-2 light"></div>
									</div>	

									<!-- LAYER NR. 3 -->
									<div class="tp-caption sfb fadeout medium_white hidden-xs"
										data-x="left"
										data-y="220"
										data-speed="500"
										data-start="1300"
										data-easing="easeOutQuad"
										data-endspeed="600">Get your spots reserved now.
									</div>

									<!-- LAYER NR. 4 -->
									<div class="tp-caption sfb fadeout small_white text-center hidden-xs"
										data-x="left"
										data-y="300"
										data-speed="500"
										data-start="1600"
										data-easing="easeOutQuad"
										data-endspeed="600"><a href="#" class="btn btn-default btn-animated">Learn More <i class="fa fa-arrow-right"></i></a>
									</div>

									</li>
									<!-- slide 1 end -->

									<!-- slide 2 start -->
									<!-- ================ -->
									<li data-transition="random" data-slotamount="7" data-masterspeed="500" data-saveperformance="on">
									
									<!-- main image -->
									<img src="images/title_slide_2.jpg" alt="slidebg1" data-bgposition="center top"  data-bgrepeat="no-repeat" data-bgfit="cover">
									
									<!-- Transparent Background -->
									<div class="tp-caption dark-translucent-bg"
										data-x="center"
										data-y="bottom"
										data-speed="600"
										data-start="0">
									</div>

									<!-- LAYER NR. 1 -->
									<div class="tp-caption sfb fadeout text-right large_white"
										data-x="right"
										data-y="80"
										data-speed="500"
										data-start="1000"
										data-easing="easeOutQuad"><span class="text-default">New</span> Arrivals<br> Unlimited Variations and Layouts
									</div>	

									<!-- LAYER NR. 2 -->
									<div class="tp-caption sfb fadeout large_white tp-resizeme hidden-xs"
										data-x="right"
										data-y="200"
										data-speed="500"
										data-start="1300"
										data-easing="easeOutQuad"><div class="separator-3 light"></div>
									</div>	

									<!-- LAYER NR. 3 -->
									<div class="tp-caption sfb fadeout medium_white text-right hidden-xs"
										data-x="right"
										data-y="220"
										data-speed="500"
										data-start="1300"
										data-easing="easeOutQuad"
										data-endspeed="600">Lorem ipsum dolor sit amet, consectetur adipisicing elit. <br> Nesciunt, maiores, aliquid.
									</div>

									<!-- LAYER NR. 4 -->
									<div class="tp-caption sfb fadeout small_white text-right text-center hidden-xs"
										data-x="right"
										data-y="300"
										data-speed="500"
										data-start="1600"
										data-easing="easeOutQuad"
										data-endspeed="600"><a href="#" class="btn btn-default btn-animated">Check Now <i class="fa fa-arrow-right"></i></a>
									</div>
									</li>
									<!-- slide 2 end -->
								</ul>
								<div class="tp-bannertimer"></div>
							</div>
						</div>
						<!-- slider revolution end -->

					</div>
					<!-- slideshow end -->

				</div>
			</div>
			<!-- banner end -->
			
			<div id="page-start"></div>

			<!-- section start -->
			<!-- ================ -->
			<section class="section light-gray-bg clearfix">
				<div class="container">
					<div class="row">
						<!-- main start -->
						<!-- ================ -->
						<div class="col-md-8">
							<!-- pills start -->
							<!-- ================ -->
							<!-- Nav tabs -->
							<ul class="nav nav-pills" role="tablist">
								<li class="active"><a href="#pill-1" role="tab" data-toggle="tab" title="Latest Arrivals"><i class="icon-star"></i> Latest Arrivals</a></li>
								<li><a href="#pill-2" role="tab" data-toggle="tab" title="Featured"><i class="icon-heart"></i> Featured</a></li>
								<li><a href="#pill-3" role="tab" data-toggle="tab" title="Top Sellers"><i class=" icon-up-1"></i> Top Sellers</a></li>
							</ul>
							<!-- Tab panes -->
							<div class="tab-content clear-style">
								<div class="tab-pane active" id="pill-1">
									<div class="listing-item mb-20">
										<div class="row grid-space-0">
											<div class="col-sm-6 col-md-4 col-lg-3">
												<div class="overlay-container">
													<img src="images/product-1.jpg" alt="">
													<a class="overlay-link" href="shop-product.html"><i class="fa fa-plus"></i></a>
													<span class="badge">30% OFF</span>
												</div>
											</div>
											<div class="col-sm-6 col-md-8 col-lg-9">
												<div class="body">
													<h3 class="margin-clear"><a href="shop-product.html">Consectetur adipisicing elit</a></h3>
													<p>
														<i class="fa fa-star text-default"></i>
														<i class="fa fa-star text-default"></i>
														<i class="fa fa-star text-default"></i>
														<i class="fa fa-star text-default"></i>
														<i class="fa fa-star"></i>
														<a href="#" class="btn-sm-link"><i class="icon-heart pr-5"></i>Add to Wishlist</a>
														<a href="#" class="btn-sm-link"><i class="icon-link pr-5"></i>View Details</a>
													</p>
													<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quas inventore modi. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Minus, cum repellat nisi quaerat mollitia reiciendis totam repellendus dicta id dolorem voluptate debitis molestias molestiae asperiores, odit magni vitae placeat optio.</p>
													<div class="elements-list clearfix">
														<span class="price"><del>$199.00</del> $150.00</span>
														<a href="#" class="pull-right btn btn-sm btn-default-transparent btn-animated">Add To Cart<i class="fa fa-shopping-cart"></i></a>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="listing-item mb-20">
										<div class="row grid-space-0">
											<div class="col-sm-6 col-md-4 col-lg-3">
												<div class="overlay-container">
													<img src="images/product-2.jpg" alt="">
													<a class="overlay-link" href="shop-product.html"><i class="fa fa-plus"></i></a>
												</div>
											</div>
											<div class="col-sm-6 col-md-8 col-lg-9">
												<div class="body">
													<h3 class="margin-clear"><a href="shop-product.html">Lorem ipsum dolor sit amet</a></h3>
													<p>
														<i class="fa fa-star text-default"></i>
														<i class="fa fa-star text-default"></i>
														<i class="fa fa-star text-default"></i>
														<i class="fa fa-star text-default"></i>
														<i class="fa fa-star text-default"></i>
														<a href="#" class="btn-sm-link"><i class="icon-heart pr-5"></i>Add to Wishlist</a>
														<a href="#" class="btn-sm-link"><i class="icon-link pr-5"></i>View Details</a>
													</p>
													<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quas inventore modi.</p>
													<ul class="list-icons">
														<li><i class="icon-check"></i> Lorem ipsum dolor sit amet</li>
														<li><i class="icon-check"></i> Consectetur adipisicing elit</li>
														<li><i class="icon-check"></i> Totam doloribus mollitia ipsum</li>
													</ul>
													<div class="elements-list clearfix">
														<span class="price">$160.00</span>
														<a href="#" class="pull-right btn btn-sm btn-default-transparent btn-animated">Add To Cart<i class="fa fa-shopping-cart"></i></a>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="listing-item mb-20">
										<div class="row grid-space-0">
											<div class="col-sm-6 col-md-4 col-lg-3">
												<div class="overlay-container">
													<img src="images/product-3.jpg" alt="">
													<a class="overlay-link" href="shop-product.html"><i class="fa fa-plus"></i></a>
													<span class="badge">40% OFF</span>
												</div>
											</div>
											<div class="col-sm-6 col-md-8 col-lg-9">
												<div class="body">
													<h3 class="margin-clear"><a href="shop-product.html">Facilis eos nobis quas asperiores amet</a></h3>
													<p>
														<i class="fa fa-star text-default"></i>
														<i class="fa fa-star text-default"></i>
														<i class="fa fa-star text-default"></i>
														<i class="fa fa-star text-default"></i>
														<i class="fa fa-star"></i>
														<a href="#" class="btn-sm-link"><i class="icon-heart pr-5"></i>Add to Wishlist</a>
														<a href="#" class="btn-sm-link"><i class="icon-link pr-5"></i>View Details</a>
													</p>
													<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quas inventore modi. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Eveniet aliquid eius nostrum sint molestias ad. Reprehenderit molestias vitae aperiam possimus nostrum tempora doloremque sunt, deserunt, at, dolore similique maxime a!</p>
													<div class="elements-list clearfix">
														<span class="price"><del>$199.00</del> $150.00</span>
														<a href="#" class="pull-right btn btn-sm btn-default-transparent btn-animated">Add To Cart<i class="fa fa-shopping-cart"></i></a>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="listing-item mb-20">
										<div class="row grid-space-0">
											<div class="col-sm-6 col-md-4 col-lg-3">
												<div class="overlay-container">
													<img src="images/product-4.jpg" alt="">
													<a class="overlay-link" href="shop-product.html"><i class="fa fa-plus"></i></a>
													<span class="badge">50% OFF</span>
												</div>
											</div>
											<div class="col-sm-6 col-md-8 col-lg-9">
												<div class="body">
													<h3 class="margin-clear"><a href="shop-product.html">Dolorem quam delectus eos nostrum</a></h3>
													<p>
														<i class="fa fa-star text-default"></i>
														<i class="fa fa-star text-default"></i>
														<i class="fa fa-star text-default"></i>
														<i class="fa fa-star text-default"></i>
														<i class="fa fa-star"></i>
														<a href="#" class="btn-sm-link"><i class="icon-heart pr-5"></i>Add to Wishlist</a>
														<a href="#" class="btn-sm-link"><i class="icon-link pr-5"></i>View Details</a>
													</p>
													<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quas inventore modi. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ducimus tempora harum, debitis asperiores ad alias ab Laborum dolore possimus necessitatibus ad. Quis rerum aspernatur commodi. Blanditiis ex recusandae perspiciatis totam.</p>
													<div class="elements-list clearfix">
														<span class="price"><del>$199.00</del> $100.00</span>
														<a href="#" class="pull-right btn btn-sm btn-default-transparent btn-animated">Add To Cart<i class="fa fa-shopping-cart"></i></a>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="listing-item mb-20">
										<div class="row grid-space-0">
											<div class="col-sm-6 col-md-4 col-lg-3">
												<div class="overlay-container">
													<img src="images/product-5.jpg" alt="">
													<a class="overlay-link" href="shop-product.html"><i class="fa fa-plus"></i></a>
												</div>
											</div>
											<div class="col-sm-6 col-md-8 col-lg-9">
												<div class="body">
													<h3 class="margin-clear"><a href="shop-product.html">Corporis, rem assumenda officia</a></h3>
													<p>
														<i class="fa fa-star text-default"></i>
														<i class="fa fa-star text-default"></i>
														<i class="fa fa-star text-default"></i>
														<i class="fa fa-star"></i>
														<i class="fa fa-star"></i>
														<a href="#" class="btn-sm-link"><i class="icon-heart pr-5"></i>Add to Wishlist</a>
														<a href="#" class="btn-sm-link"><i class="icon-link pr-5"></i>View Details</a>
													</p>
													<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quas inventore modi. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Eveniet soluta hic totam adipisci cumque veniam, placeat tempora possimus suscipit optio in magni, molestiae consectetur at quidem omnis, commodi perferendis accusamus</p>
													<div class="elements-list clearfix">
														<span class="price">$80.00</span>
														<a href="#" class="pull-right btn btn-sm btn-default-transparent btn-animated">Add To Cart<i class="fa fa-shopping-cart"></i></a>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="listing-item mb-20">
										<div class="row grid-space-0">
											<div class="col-sm-6 col-md-4 col-lg-3">
												<div class="overlay-container">
													<img src="images/product-6.jpg" alt="">
													<a class="overlay-link" href="shop-product.html"><i class="fa fa-plus"></i></a>
													<span class="badge">35% OFF</span>
												</div>
											</div>
											<div class="col-sm-6 col-md-8 col-lg-9">
												<div class="body">
													<h3 class="margin-clear"><a href="shop-product.html">Perferendis necessitatibus dolorum delectus</a></h3>
													<p>
														<i class="fa fa-star text-default"></i>
														<i class="fa fa-star text-default"></i>
														<i class="fa fa-star text-default"></i>
														<i class="fa fa-star text-default"></i>
														<i class="fa fa-star text-default"></i>
														<a href="#" class="btn-sm-link"><i class="icon-heart pr-5"></i>Add to Wishlist</a>
														<a href="#" class="btn-sm-link"><i class="icon-link pr-5"></i>View Details</a>
													</p>
													<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quas inventore modi. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ullam officiis distinctio vero ea quaerat sed quidem magnam, expedita voluptatum eius similique facere veritatis vitae molestiae commodi obcaecati, nemo fuga pariatur.</p>
													<div class="elements-list clearfix">
														<span class="price"><del>$50.00</del> $32.50</span>
														<a href="#" class="pull-right btn btn-sm btn-default-transparent btn-animated">Add To Cart<i class="fa fa-shopping-cart"></i></a>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="listing-item mb-20">
										<div class="row grid-space-0">
											<div class="col-sm-6 col-md-4 col-lg-3">
												<div class="overlay-container">
													<img src="images/product-7.jpg" alt="">
													<a class="overlay-link" href="shop-product.html"><i class="fa fa-plus"></i></a>
													<span class="badge">30% OFF</span>
												</div>
											</div>
											<div class="col-sm-6 col-md-8 col-lg-9">
												<div class="body">
													<h3 class="margin-clear"><a href="shop-product.html">Fugiat nemo enim officiis repellendus</a></h3>
													<p>
														<i class="fa fa-star text-default"></i>
														<i class="fa fa-star text-default"></i>
														<i class="fa fa-star text-default"></i>
														<i class="fa fa-star text-default"></i>
														<i class="fa fa-star"></i>
														<a href="#" class="btn-sm-link"><i class="icon-heart pr-5"></i>Add to Wishlist</a>
														<a href="#" class="btn-sm-link"><i class="icon-link pr-5"></i>View Details</a>
													</p>
													<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quas inventore modi. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Blanditiis nobis necessitatibus, nam ipsa tempore repellendus iusto ducimus nulla corporis dignissimos quam ullam accusantium nostrum ex quibusdam qui, optio numquam suscipit.</p>
													<div class="elements-list clearfix">
														<span class="price"><del>$300.00</del> $210.00</span>
														<a href="#" class="pull-right btn btn-sm btn-default-transparent btn-animated">Add To Cart<i class="fa fa-shopping-cart"></i></a>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="listing-item mb-20">
										<div class="row grid-space-0">
											<div class="col-sm-6 col-md-4 col-lg-3">
												<div class="overlay-container">
													<img src="images/product-8.jpg" alt="">
													<a class="overlay-link" href="shop-product.html"><i class="fa fa-plus"></i></a>
													<span class="badge">10% OFF</span>
												</div>
											</div>
											<div class="col-sm-6 col-md-8 col-lg-9">
												<div class="body">
													<h3 class="margin-clear"><a href="shop-product.html">Est ipsa quod sit dolorum alias suscipit</a></h3>
													<p>
														<i class="fa fa-star text-default"></i>
														<i class="fa fa-star text-default"></i>
														<i class="fa fa-star text-default"></i>
														<i class="fa fa-star"></i>
														<i class="fa fa-star"></i>
														<a href="#" class="btn-sm-link"><i class="icon-heart pr-5"></i>Add to Wishlist</a>
														<a href="#" class="btn-sm-link"><i class="icon-link pr-5"></i>View Details</a>
													</p>
													<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quas inventore modi. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Non nemo, delectus nihil, sequi voluptas ratione tempora odit dolores inventore hic sit reprehenderit dolor aliquam commodi earum numquam soluta praesentium! Cum.</p>
													<div class="elements-list clearfix">
														<span class="price"><del>$199.00</del> $180.00</span>
														<a href="#" class="pull-right btn btn-sm btn-default-transparent btn-animated">Add To Cart<i class="fa fa-shopping-cart"></i></a>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="tab-pane" id="pill-2">
									<div class="listing-item mb-20">
										<div class="row grid-space-0">
											<div class="col-sm-6 col-md-4 col-lg-3">
												<div class="overlay-container">
													<img src="images/product-2.jpg" alt="">
													<a class="overlay-link" href="shop-product.html"><i class="fa fa-plus"></i></a>
													<span class="badge">20% OFF</span>
												</div>
											</div>
											<div class="col-sm-6 col-md-8 col-lg-9">
												<div class="body">
													<h3 class="margin-clear"><a href="shop-product.html">Lorem ipsum dolor sit amet</a></h3>
													<p>
														<i class="fa fa-star text-default"></i>
														<i class="fa fa-star text-default"></i>
														<i class="fa fa-star text-default"></i>
														<i class="fa fa-star text-default"></i>
														<i class="fa fa-star text-default"></i>
														<a href="#" class="btn-sm-link"><i class="icon-heart pr-5"></i>Add to Wishlist</a>
														<a href="#" class="btn-sm-link"><i class="icon-link pr-5"></i>View Details</a>
													</p>
													<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quas inventore modi. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Accusantium inventore eum, laboriosam, maxime illo odit dolores ipsam, odio, voluptatibus rerum sunt voluptate illum eligendi nihil alias ullam aspernatur numquam qui!</p>
													<div class="elements-list clearfix">
														<span class="price"><del>$199.00</del> $160.00</span>
														<a href="#" class="pull-right btn btn-sm btn-default-transparent btn-animated">Add To Cart<i class="fa fa-shopping-cart"></i></a>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="listing-item mb-20">
										<div class="row grid-space-0">
											<div class="col-sm-6 col-md-4 col-lg-3">
												<div class="overlay-container">
													<img src="images/product-1.jpg" alt="">
													<a class="overlay-link" href="shop-product.html"><i class="fa fa-plus"></i></a>
													<span class="badge">30% OFF</span>
												</div>
											</div>
											<div class="col-sm-6 col-md-8 col-lg-9">
												<div class="body">
													<h3 class="margin-clear"><a href="shop-product.html">Consectetur adipisicing elit</a></h3>
													<p>
														<i class="fa fa-star text-default"></i>
														<i class="fa fa-star text-default"></i>
														<i class="fa fa-star text-default"></i>
														<i class="fa fa-star text-default"></i>
														<i class="fa fa-star"></i>
														<a href="#" class="btn-sm-link"><i class="icon-heart pr-5"></i>Add to Wishlist</a>
														<a href="#" class="btn-sm-link"><i class="icon-link pr-5"></i>View Details</a>
													</p>
													<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quas inventore modi. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quibusdam doloribus facere nobis illum alias inventore impedit quam cum! Similique tempore dolorem accusantium sequi quam obcaecati repellendus harum, et earum veniam!</p>
													<div class="elements-list clearfix">
														<span class="price"><del>$199.00</del> $150.00</span>
														<a href="#" class="pull-right btn btn-sm btn-default-transparent btn-animated">Add To Cart<i class="fa fa-shopping-cart"></i></a>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="listing-item mb-20">
										<div class="row grid-space-0">
											<div class="col-sm-6 col-md-4 col-lg-3">
												<div class="overlay-container">
													<img src="images/product-4.jpg" alt="">
													<a class="overlay-link" href="shop-product.html"><i class="fa fa-plus"></i></a>
													<span class="badge">50% OFF</span>
												</div>
											</div>
											<div class="col-sm-6 col-md-8 col-lg-9">
												<div class="body">
													<h3 class="margin-clear"><a href="shop-product.html">Dolorem quam delectus eos nostrum</a></h3>
													<p>
														<i class="fa fa-star text-default"></i>
														<i class="fa fa-star text-default"></i>
														<i class="fa fa-star text-default"></i>
														<i class="fa fa-star text-default"></i>
														<i class="fa fa-star"></i>
														<a href="#" class="btn-sm-link"><i class="icon-heart pr-5"></i>Add to Wishlist</a>
														<a href="#" class="btn-sm-link"><i class="icon-link pr-5"></i>View Details</a>
													</p>
													<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quas inventore modi.</p>
													<div class="elements-list clearfix">
														<span class="price"><del>$199.00</del> $100.00</span>
														<a href="#" class="pull-right btn btn-sm btn-default-transparent btn-animated">Add To Cart<i class="fa fa-shopping-cart"></i></a>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="listing-item mb-20">
										<div class="row grid-space-0">
											<div class="col-sm-6 col-md-4 col-lg-3">
												<div class="overlay-container">
													<img src="images/product-3.jpg" alt="">
													<a class="overlay-link" href="shop-product.html"><i class="fa fa-plus"></i></a>
													<span class="badge">40% OFF</span>
												</div>
											</div>
											<div class="col-sm-6 col-md-8 col-lg-9">
												<div class="body">
													<h3 class="margin-clear"><a href="shop-product.html">Facilis eos nobis quas asperiores amet</a></h3>
													<p>
														<i class="fa fa-star text-default"></i>
														<i class="fa fa-star text-default"></i>
														<i class="fa fa-star text-default"></i>
														<i class="fa fa-star text-default"></i>
														<i class="fa fa-star"></i>
														<a href="#" class="btn-sm-link"><i class="icon-heart pr-5"></i>Add to Wishlist</a>
														<a href="#" class="btn-sm-link"><i class="icon-link pr-5"></i>View Details</a>
													</p>
													<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quas inventore modi.</p>
													<div class="elements-list clearfix">
														<span class="price"><del>$199.00</del> $150.00</span>
														<a href="#" class="pull-right btn btn-sm btn-default-transparent btn-animated">Add To Cart<i class="fa fa-shopping-cart"></i></a>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="listing-item mb-20">
										<div class="row grid-space-0">
											<div class="col-sm-6 col-md-4 col-lg-3">
												<div class="overlay-container">
													<img src="images/product-7.jpg" alt="">
													<a class="overlay-link" href="shop-product.html"><i class="fa fa-plus"></i></a>
													<span class="badge">30% OFF</span>
												</div>
											</div>
											<div class="col-sm-6 col-md-8 col-lg-9">
												<div class="body">
													<h3 class="margin-clear"><a href="shop-product.html">Fugiat nemo enim officiis repellendus</a></h3>
													<p>
														<i class="fa fa-star text-default"></i>
														<i class="fa fa-star text-default"></i>
														<i class="fa fa-star text-default"></i>
														<i class="fa fa-star text-default"></i>
														<i class="fa fa-star"></i>
														<a href="#" class="btn-sm-link"><i class="icon-heart pr-5"></i>Add to Wishlist</a>
														<a href="#" class="btn-sm-link"><i class="icon-link pr-5"></i>View Details</a>
													</p>
													<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quas inventore modi.</p>
													<div class="elements-list clearfix">
														<span class="price"><del>$300.00</del> $210.00</span>
														<a href="#" class="pull-right btn btn-sm btn-default-transparent btn-animated">Add To Cart<i class="fa fa-shopping-cart"></i></a>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="listing-item mb-20">
										<div class="row grid-space-0">
											<div class="col-sm-6 col-md-4 col-lg-3">
												<div class="overlay-container">
													<img src="images/product-5.jpg" alt="">
													<a class="overlay-link" href="shop-product.html"><i class="fa fa-plus"></i></a>
													<span class="badge">20% OFF</span>
												</div>
											</div>
											<div class="col-sm-6 col-md-8 col-lg-9">
												<div class="body">
													<h3 class="margin-clear"><a href="shop-product.html">Corporis, rem assumenda officia</a></h3>
													<p>
														<i class="fa fa-star text-default"></i>
														<i class="fa fa-star text-default"></i>
														<i class="fa fa-star text-default"></i>
														<i class="fa fa-star"></i>
														<i class="fa fa-star"></i>
														<a href="#" class="btn-sm-link"><i class="icon-heart pr-5"></i>Add to Wishlist</a>
														<a href="#" class="btn-sm-link"><i class="icon-link pr-5"></i>View Details</a>
													</p>
													<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quas inventore modi.</p>
													<div class="elements-list clearfix">
														<span class="price"><del>$99.00</del> $80.00</span>
														<a href="#" class="pull-right btn btn-sm btn-default-transparent btn-animated">Add To Cart<i class="fa fa-shopping-cart"></i></a>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="listing-item mb-20">
										<div class="row grid-space-0">
											<div class="col-sm-6 col-md-4 col-lg-3">
												<div class="overlay-container">
													<img src="images/product-6.jpg" alt="">
													<a class="overlay-link" href="shop-product.html"><i class="fa fa-plus"></i></a>
													<span class="badge">35% OFF</span>
												</div>
											</div>
											<div class="col-sm-6 col-md-8 col-lg-9">
												<div class="body">
													<h3 class="margin-clear"><a href="shop-product.html">Perferendis necessitatibus dolorum delectus</a></h3>
													<p>
														<i class="fa fa-star text-default"></i>
														<i class="fa fa-star text-default"></i>
														<i class="fa fa-star text-default"></i>
														<i class="fa fa-star text-default"></i>
														<i class="fa fa-star text-default"></i>
														<a href="#" class="btn-sm-link"><i class="icon-heart pr-5"></i>Add to Wishlist</a>
														<a href="#" class="btn-sm-link"><i class="icon-link pr-5"></i>View Details</a>
													</p>
													<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quas inventore modi.</p>
													<div class="elements-list clearfix">
														<span class="price"><del>$50.00</del> $32.50</span>
														<a href="#" class="pull-right btn btn-sm btn-default-transparent btn-animated">Add To Cart<i class="fa fa-shopping-cart"></i></a>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="listing-item mb-20">
										<div class="row grid-space-0">
											<div class="col-sm-6 col-md-4 col-lg-3">
												<div class="overlay-container">
													<img src="images/product-8.jpg" alt="">
													<a class="overlay-link" href="shop-product.html"><i class="fa fa-plus"></i></a>
													<span class="badge">10% OFF</span>
												</div>
											</div>
											<div class="col-sm-6 col-md-8 col-lg-9">
												<div class="body">
													<h3 class="margin-clear"><a href="shop-product.html">Est ipsa quod sit dolorum alias suscipit</a></h3>
													<p>
														<i class="fa fa-star text-default"></i>
														<i class="fa fa-star text-default"></i>
														<i class="fa fa-star text-default"></i>
														<i class="fa fa-star"></i>
														<i class="fa fa-star"></i>
														<a href="#" class="btn-sm-link"><i class="icon-heart pr-5"></i>Add to Wishlist</a>
														<a href="#" class="btn-sm-link"><i class="icon-link pr-5"></i>View Details</a>
													</p>
													<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quas inventore modi.</p>
													<div class="elements-list clearfix">
														<span class="price"><del>$199.00</del> $180.00</span>
														<a href="#" class="pull-right btn btn-sm btn-default-transparent btn-animated">Add To Cart<i class="fa fa-shopping-cart"></i></a>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="tab-pane" id="pill-3">
									<div class="listing-item mb-20">
										<div class="row grid-space-0">
											<div class="col-sm-6 col-md-4 col-lg-3">
												<div class="overlay-container">
													<img src="images/product-4.jpg" alt="">
													<a class="overlay-link" href="shop-product.html"><i class="fa fa-plus"></i></a>
													<span class="badge">50% OFF</span>
												</div>
											</div>
											<div class="col-sm-6 col-md-8 col-lg-9">
												<div class="body">
													<h3 class="margin-clear"><a href="shop-product.html">Dolorem quam delectus eos nostrum</a></h3>
													<p>
														<i class="fa fa-star text-default"></i>
														<i class="fa fa-star text-default"></i>
														<i class="fa fa-star text-default"></i>
														<i class="fa fa-star text-default"></i>
														<i class="fa fa-star"></i>
														<a href="#" class="btn-sm-link"><i class="icon-heart pr-5"></i>Add to Wishlist</a>
														<a href="#" class="btn-sm-link"><i class="icon-link pr-5"></i>View Details</a>
													</p>
													<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quas inventore modi.</p>
													<div class="elements-list clearfix">
														<span class="price"><del>$199.00</del> $100.00</span>
														<a href="#" class="pull-right btn btn-sm btn-default-transparent btn-animated">Add To Cart<i class="fa fa-shopping-cart"></i></a>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="listing-item mb-20">
										<div class="row grid-space-0">
											<div class="col-sm-6 col-md-4 col-lg-3">
												<div class="overlay-container">
													<img src="images/product-3.jpg" alt="">
													<a class="overlay-link" href="shop-product.html"><i class="fa fa-plus"></i></a>
													<span class="badge">40% OFF</span>
												</div>
											</div>
											<div class="col-sm-6 col-md-8 col-lg-9">
												<div class="body">
													<h3 class="margin-clear"><a href="shop-product.html">Facilis eos nobis quas asperiores amet</a></h3>
													<p>
														<i class="fa fa-star text-default"></i>
														<i class="fa fa-star text-default"></i>
														<i class="fa fa-star text-default"></i>
														<i class="fa fa-star text-default"></i>
														<i class="fa fa-star"></i>
														<a href="#" class="btn-sm-link"><i class="icon-heart pr-5"></i>Add to Wishlist</a>
														<a href="#" class="btn-sm-link"><i class="icon-link pr-5"></i>View Details</a>
													</p>
													<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quas inventore modi.</p>
													<div class="elements-list clearfix">
														<span class="price"><del>$199.00</del> $150.00</span>
														<a href="#" class="pull-right btn btn-sm btn-default-transparent btn-animated">Add To Cart<i class="fa fa-shopping-cart"></i></a>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="listing-item mb-20">
										<div class="row grid-space-0">
											<div class="col-sm-6 col-md-4 col-lg-3">
												<div class="overlay-container">
													<img src="images/product-2.jpg" alt="">
													<a class="overlay-link" href="shop-product.html"><i class="fa fa-plus"></i></a>
													<span class="badge">20% OFF</span>
												</div>
											</div>
											<div class="col-sm-6 col-md-8 col-lg-9">
												<div class="body">
													<h3 class="margin-clear"><a href="shop-product.html">Lorem ipsum dolor sit amet</a></h3>
													<p>
														<i class="fa fa-star text-default"></i>
														<i class="fa fa-star text-default"></i>
														<i class="fa fa-star text-default"></i>
														<i class="fa fa-star text-default"></i>
														<i class="fa fa-star text-default"></i>
														<a href="#" class="btn-sm-link"><i class="icon-heart pr-5"></i>Add to Wishlist</a>
														<a href="#" class="btn-sm-link"><i class="icon-link pr-5"></i>View Details</a>
													</p>
													<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quas inventore modi.</p>
													<div class="elements-list clearfix">
														<span class="price"><del>$199.00</del> $160.00</span>
														<a href="#" class="pull-right btn btn-sm btn-default-transparent btn-animated">Add To Cart<i class="fa fa-shopping-cart"></i></a>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="listing-item mb-20">
										<div class="row grid-space-0">
											<div class="col-sm-6 col-md-4 col-lg-3">
												<div class="overlay-container">
													<img src="images/product-1.jpg" alt="">
													<a class="overlay-link" href="shop-product.html"><i class="fa fa-plus"></i></a>
													<span class="badge">30% OFF</span>
												</div>
											</div>
											<div class="col-sm-6 col-md-8 col-lg-9">
												<div class="body">
													<h3 class="margin-clear"><a href="shop-product.html">Consectetur adipisicing elit</a></h3>
													<p>
														<i class="fa fa-star text-default"></i>
														<i class="fa fa-star text-default"></i>
														<i class="fa fa-star text-default"></i>
														<i class="fa fa-star text-default"></i>
														<i class="fa fa-star"></i>
														<a href="#" class="btn-sm-link"><i class="icon-heart pr-5"></i>Add to Wishlist</a>
														<a href="#" class="btn-sm-link"><i class="icon-link pr-5"></i>View Details</a>
													</p>
													<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quas inventore modi.</p>
													<div class="elements-list clearfix">
														<span class="price"><del>$199.00</del> $150.00</span>
														<a href="#" class="pull-right btn btn-sm btn-default-transparent btn-animated">Add To Cart<i class="fa fa-shopping-cart"></i></a>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="listing-item mb-20">
										<div class="row grid-space-0">
											<div class="col-sm-6 col-md-4 col-lg-3">
												<div class="overlay-container">
													<img src="images/product-6.jpg" alt="">
													<a class="overlay-link" href="shop-product.html"><i class="fa fa-plus"></i></a>
													<span class="badge">35% OFF</span>
												</div>
											</div>
											<div class="col-sm-6 col-md-8 col-lg-9">
												<div class="body">
													<h3 class="margin-clear"><a href="shop-product.html">Perferendis necessitatibus dolorum delectus</a></h3>
													<p>
														<i class="fa fa-star text-default"></i>
														<i class="fa fa-star text-default"></i>
														<i class="fa fa-star text-default"></i>
														<i class="fa fa-star text-default"></i>
														<i class="fa fa-star text-default"></i>
														<a href="#" class="btn-sm-link"><i class="icon-heart pr-5"></i>Add to Wishlist</a>
														<a href="#" class="btn-sm-link"><i class="icon-link pr-5"></i>View Details</a>
													</p>
													<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quas inventore modi.</p>
													<div class="elements-list clearfix">
														<span class="price"><del>$50.00</del> $32.50</span>
														<a href="#" class="pull-right btn btn-sm btn-default-transparent btn-animated">Add To Cart<i class="fa fa-shopping-cart"></i></a>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="listing-item mb-20">
										<div class="row grid-space-0">
											<div class="col-sm-6 col-md-4 col-lg-3">
												<div class="overlay-container">
													<img src="images/product-8.jpg" alt="">
													<a class="overlay-link" href="shop-product.html"><i class="fa fa-plus"></i></a>
													<span class="badge">10% OFF</span>
												</div>
											</div>
											<div class="col-sm-6 col-md-8 col-lg-9">
												<div class="body">
													<h3 class="margin-clear"><a href="shop-product.html">Est ipsa quod sit dolorum alias suscipit</a></h3>
													<p>
														<i class="fa fa-star text-default"></i>
														<i class="fa fa-star text-default"></i>
														<i class="fa fa-star text-default"></i>
														<i class="fa fa-star"></i>
														<i class="fa fa-star"></i>
														<a href="#" class="btn-sm-link"><i class="icon-heart pr-5"></i>Add to Wishlist</a>
														<a href="#" class="btn-sm-link"><i class="icon-link pr-5"></i>View Details</a>
													</p>
													<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quas inventore modi.</p>
													<div class="elements-list clearfix">
														<span class="price"><del>$199.00</del> $180.00</span>
														<a href="#" class="pull-right btn btn-sm btn-default-transparent btn-animated">Add To Cart<i class="fa fa-shopping-cart"></i></a>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="listing-item mb-20">
										<div class="row grid-space-0">
											<div class="col-sm-6 col-md-4 col-lg-3">
												<div class="overlay-container">
													<img src="images/product-7.jpg" alt="">
													<a class="overlay-link" href="shop-product.html"><i class="fa fa-plus"></i></a>
													<span class="badge">30% OFF</span>
												</div>
											</div>
											<div class="col-sm-6 col-md-8 col-lg-9">
												<div class="body">
													<h3 class="margin-clear"><a href="shop-product.html">Fugiat nemo enim officiis repellendus</a></h3>
													<p>
														<i class="fa fa-star text-default"></i>
														<i class="fa fa-star text-default"></i>
														<i class="fa fa-star text-default"></i>
														<i class="fa fa-star text-default"></i>
														<i class="fa fa-star"></i>
														<a href="#" class="btn-sm-link"><i class="icon-heart pr-5"></i>Add to Wishlist</a>
														<a href="#" class="btn-sm-link"><i class="icon-link pr-5"></i>View Details</a>
													</p>
													<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quas inventore modi.</p>
													<div class="elements-list clearfix">
														<span class="price"><del>$300.00</del> $210.00</span>
														<a href="#" class="pull-right btn btn-sm btn-default-transparent btn-animated">Add To Cart<i class="fa fa-shopping-cart"></i></a>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="listing-item mb-20">
										<div class="row grid-space-0">
											<div class="col-sm-6 col-md-4 col-lg-3">
												<div class="overlay-container">
													<img src="images/product-5.jpg" alt="">
													<a class="overlay-link" href="shop-product.html"><i class="fa fa-plus"></i></a>
													<span class="badge">20% OFF</span>
												</div>
											</div>
											<div class="col-sm-6 col-md-8 col-lg-9">
												<div class="body">
													<h3 class="margin-clear"><a href="shop-product.html">Corporis, rem assumenda officia</a></h3>
													<p>
														<i class="fa fa-star text-default"></i>
														<i class="fa fa-star text-default"></i>
														<i class="fa fa-star text-default"></i>
														<i class="fa fa-star"></i>
														<i class="fa fa-star"></i>
														<a href="#" class="btn-sm-link"><i class="icon-heart pr-5"></i>Add to Wishlist</a>
														<a href="#" class="btn-sm-link"><i class="icon-link pr-5"></i>View Details</a>
													</p>
													<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quas inventore modi.</p>
													<div class="elements-list clearfix">
														<span class="price"><del>$99.00</del> $80.00</span>
														<a href="#" class="pull-right btn btn-sm btn-default-transparent btn-animated">Add To Cart<i class="fa fa-shopping-cart"></i></a>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<!-- pills end -->
							<!-- pagination start -->
							<nav class="text-center">
								<ul class="pagination">
									<li><a href="#" aria-label="Previous"><i class="fa fa-angle-left"></i></a></li>
									<li class="active"><a href="#">1</a></li>
									<li><a href="#">2</a></li>
									<li><a href="#">3</a></li>
									<li><a href="#">4</a></li>
									<li><a href="#">5</a></li>
									<li><a href="#" aria-label="Next"><i class="fa fa-angle-right"></i></a></li>
								</ul>
							</nav>
							<!-- pagination end -->
						</div>
						<!-- main end -->
						<!-- sidebar start -->
						<!-- ================ -->
						<aside class="col-md-3 col-md-offset-1">
							<div class="sidebar">
								<div class="block clearfix">
									<h3 class="title">Best Sellers</h3>
									<div class="separator-2"></div>
									<div id="carousel-sidebar" class="carousel slide" data-ride="carousel">
										<!-- Indicators -->
										<ol class="carousel-indicators top">
											<li data-target="#carousel-sidebar" data-slide-to="0" class="active"></li>
											<li data-target="#carousel-sidebar" data-slide-to="1"></li>
											<li data-target="#carousel-sidebar" data-slide-to="2"></li>
										</ol>
										<!-- Wrapper for slides -->
										<div class="carousel-inner" role="listbox">
											<div class="item active">
												<div class="listing-item">
													<div class="overlay-container">
														<img src="images/product-1.jpg" alt="product 1">
														<span class="badge">30% OFF</span>
														<a href="shop-product.html" class="overlay-link"><i class="fa fa-link"></i></a>
													</div>
													<h3><a href="shop-product.html">Suscipit consequatur velit</a></h3>
													<span class="price"><del>$199.00</del> $150.00</span>
												</div>
											</div>
											<div class="item">
												<div class="listing-item">
													<div class="overlay-container">
														<img src="images/product-2.jpg" alt="product 2">
														<span class="badge">20% OFF</span>
														<a href="shop-product.html" class="overlay-link"><i class="fa fa-link"></i></a>
													</div>
													<h3><a href="shop-product.html">Quas inventore modi lorem</a></h3>
													<span class="price"><del>$199.00</del> $150.00</span>
												</div>
											</div>
											<div class="item">
												<div class="listing-item">
													<div class="overlay-container">
														<img src="images/product-3.jpg" alt="product 3">
														<span class="badge">40% OFF</span>
														<a href="shop-product.html" class="overlay-link"><i class="fa fa-link"></i></a>
													</div>
													<h3><a href="shop-product.html">Consectetur adipisicing elit</a></h3>
													<span class="price"><del>$199.00</del> $150.00</span>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="block clearfix">
									<h3 class="title">Top Rated</h3>
									<div class="separator-2"></div>
									<div class="media margin-clear">
										<div class="media-left">
											<div class="overlay-container">
												<img class="media-object" src="images/product-5.jpg" alt="blog-thumb">
												<a href="shop-product.html" class="overlay-link small"><i class="fa fa-link"></i></a>
											</div>
										</div>
										<div class="media-body">
											<h6 class="media-heading"><a href="shop-product.html">Lorem ipsum dolor sit amet</a></h6>
											<p class="margin-clear">
												<i class="fa fa-star text-default"></i>
												<i class="fa fa-star text-default"></i>
												<i class="fa fa-star text-default"></i>
												<i class="fa fa-star text-default"></i>
												<i class="fa fa-star text-default"></i>
											</p>
											<p class="price">$99.00</p>
										</div>
										<hr>
									</div>
									<div class="media margin-clear">
										<div class="media-left">
											<div class="overlay-container">
												<img class="media-object" src="images/product-6.jpg" alt="blog-thumb">
												<a href="shop-product.html" class="overlay-link small"><i class="fa fa-link"></i></a>
											</div>
										</div>
										<div class="media-body">
											<h6 class="media-heading"><a href="shop-product.html">Eum repudiandae ipsam</a></h6>
											<p class="margin-clear">
												<i class="fa fa-star text-default"></i>
												<i class="fa fa-star text-default"></i>
												<i class="fa fa-star text-default"></i>
												<i class="fa fa-star text-default"></i>
												<i class="fa fa-star"></i>
											</p>
											<p class="price">$299.00</p>
										</div>
										<hr>
									</div>
									<div class="media margin-clear">
										<div class="media-left">
											<div class="overlay-container">
												<img class="media-object" src="images/product-7.jpg" alt="blog-thumb">
												<a href="shop-product.html" class="overlay-link small"><i class="fa fa-link"></i></a>
											</div>
										</div>
										<div class="media-body">
											<h6 class="media-heading"><a href="shop-product.html">Quia aperiam velit fuga</a></h6>
											<p class="margin-clear">
												<i class="fa fa-star text-default"></i>
												<i class="fa fa-star text-default"></i>
												<i class="fa fa-star text-default"></i>
												<i class="fa fa-star text-default"></i>
												<i class="fa fa-star"></i>
											</p>
											<p class="price">$9.99</p>
										</div>
										<hr>
									</div>
									<div class="media margin-clear">
										<div class="media-left">
											<div class="overlay-container">
												<img class="media-object" src="images/product-8.jpg" alt="blog-thumb">
												<a href="shop-product.html" class="overlay-link small"><i class="fa fa-link"></i></a>
											</div>
										</div>
										<div class="media-body">
											<h6 class="media-heading"><a href="shop-product.html">Fugit non natus officiis</a></h6>
											<p class="margin-clear">
												<i class="fa fa-star text-default"></i>
												<i class="fa fa-star text-default"></i>
												<i class="fa fa-star text-default"></i>
												<i class="fa fa-star"></i>
												<i class="fa fa-star"></i>
											</p>
											<p class="price">$399.00</p>
										</div>
									</div>
								</div>
								<div class="block clearfix">
									<h3 class="title">Categories</h3>
									<div class="separator-2"></div>
									<nav>
										<ul class="nav nav-pills nav-stacked list-style-icons">
											<li><a href="#"><i class="fa fa-caret-right pr-10"></i>Diamonds</a></li>
											<li><a href="#"><i class="fa fa-caret-right pr-10"></i>Clothes</a></li>
											<li><a href="#"><i class="fa fa-caret-right pr-10"></i>Shoes</a></li>
											<li><a href="#"><i class="fa fa-caret-right pr-10"></i>Watches</a></li>
											<li><a href="#"><i class="fa fa-caret-right pr-10"></i>Accessories</a></li>
											<li><a href="#"><i class="fa fa-caret-right pr-10"></i>Hats</a></li>
											<li><a href="#"><i class="fa fa-caret-right pr-10"></i>Perfumes</a></li>
										</ul>
									</nav>
								</div>
								<div class="block clearfix">
									<h3 class="title">End of Season Sales</h3>
									<div class="separator-2"></div>
									<p class="margin-clear">Debitis eaque officia illo impedit ipsa earum <a href="#">cupiditate repellendus</a> corrupti nisi nemo, perspiciatis optio harum, hic laudantium nulla maiores rem sit magni neque nihil sequi temporibus. Laboriosam ipsum reiciendis iste, nobis obcaecati, a autem voluptatum odio? Recusandae officiis dicta quod qui eligendi.</p>
								</div>
								<div class="block clearfix">
									<form role="search">
										<div class="form-group has-feedback">
											<input type="text" class="form-control" placeholder="Search">
											<i class="fa fa-search form-control-feedback"></i>
										</div>
									</form>
								</div>	
							</div>
						</aside>
						<!-- sidebar end -->
					</div>
				</div>
			</section>
			<!-- section end -->

			<!-- section start -->
			<!-- ================ -->
			<section class="section dark-translucent-bg background-img-2" style="background-position: 50% 52%;">
				<div class="container">
					<div class="row">
						<div class="col-md-12">
							<div class="call-to-action text-center">
								<div class="row">
									<div class="col-sm-8">
										<h1 class="title">Don't Miss Out Our Offers</h1>
										<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Rem quasi explicabo consequatur consectetur, a atque voluptate officiis eligendi nostrum.</p>
									</div>
									<div class="col-sm-4">
										<br>
										<p><a href="#" class="btn btn-lg btn-default btn-animated">Learn More<i class="fa fa-arrow-right pl-20"></i></a></p>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</section>
			<!-- section end -->

			<!-- section start -->
			<!-- ================ -->
			<section class="pv-30 clearfix">
				<div class="container">
					<div class="row">
						<div class="col-md-4">
							<div class="block clearfix">
								<h3 class="title">Top Rated</h3>
								<div class="separator-2"></div>
								<div class="media margin-clear">
									<div class="media-left">
										<div class="overlay-container">
											<img class="media-object" src="images/product-1.jpg" alt="blog-thumb">
											<a href="shop-product.html" class="overlay-link small"><i class="fa fa-link"></i></a>
										</div>
									</div>
									<div class="media-body">
										<h6 class="media-heading"><a href="shop-product.html">Lorem ipsum dolor sit amet</a></h6>
										<p class="margin-clear">
											<i class="fa fa-star text-default"></i>
											<i class="fa fa-star text-default"></i>
											<i class="fa fa-star text-default"></i>
											<i class="fa fa-star text-default"></i>
											<i class="fa fa-star text-default"></i>
										</p>
										<p class="price">$99.00</p>
									</div>
									<hr>
								</div>
								<div class="media margin-clear">
									<div class="media-left">
										<div class="overlay-container">
											<img class="media-object" src="images/product-2.jpg" alt="blog-thumb">
											<a href="shop-product.html" class="overlay-link small"><i class="fa fa-link"></i></a>
										</div>
									</div>
									<div class="media-body">
										<h6 class="media-heading"><a href="shop-product.html">Eum repudiandae ipsam</a></h6>
										<p class="margin-clear">
											<i class="fa fa-star text-default"></i>
											<i class="fa fa-star text-default"></i>
											<i class="fa fa-star text-default"></i>
											<i class="fa fa-star text-default"></i>
											<i class="fa fa-star"></i>
										</p>
										<p class="price">$299.00</p>
									</div>
									<hr>
								</div>
								<div class="media margin-clear">
									<div class="media-left">
										<div class="overlay-container">
											<img class="media-object" src="images/product-3.jpg" alt="blog-thumb">
											<a href="shop-product.html" class="overlay-link small"><i class="fa fa-link"></i></a>
										</div>
									</div>
									<div class="media-body">
										<h6 class="media-heading"><a href="shop-product.html">Quia aperiam velit fuga</a></h6>
										<p class="margin-clear">
											<i class="fa fa-star text-default"></i>
											<i class="fa fa-star text-default"></i>
											<i class="fa fa-star text-default"></i>
											<i class="fa fa-star text-default"></i>
											<i class="fa fa-star"></i>
										</p>
										<p class="price">$9.99</p>
									</div>
									<hr>
								</div>
								<div class="media margin-clear">
									<div class="media-left">
										<div class="overlay-container">
											<img class="media-object" src="images/product-4.jpg" alt="blog-thumb">
											<a href="shop-product.html" class="overlay-link small"><i class="fa fa-link"></i></a>
										</div>
									</div>
									<div class="media-body">
										<h6 class="media-heading"><a href="shop-product.html">Fugit non natus officiis</a></h6>
										<p class="margin-clear">
											<i class="fa fa-star text-default"></i>
											<i class="fa fa-star text-default"></i>
											<i class="fa fa-star text-default"></i>
											<i class="fa fa-star"></i>
											<i class="fa fa-star"></i>
										</p>
										<p class="price">$399.00</p>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-4">
							<div class="block clearfix">
								<h3 class="title">Related</h3>
								<div class="separator-2"></div>
								<div class="media margin-clear">
									<div class="media-left">
										<div class="overlay-container">
											<img class="media-object" src="images/product-5.jpg" alt="blog-thumb">
											<a href="shop-product.html" class="overlay-link small"><i class="fa fa-link"></i></a>
										</div>
									</div>
									<div class="media-body">
										<h6 class="media-heading"><a href="shop-product.html">Lorem ipsum dolor sit amet</a></h6>
										<p class="margin-clear">
											<i class="fa fa-star text-default"></i>
											<i class="fa fa-star text-default"></i>
											<i class="fa fa-star text-default"></i>
											<i class="fa fa-star text-default"></i>
											<i class="fa fa-star text-default"></i>
										</p>
										<p class="price">$99.00</p>
									</div>
									<hr>
								</div>
								<div class="media margin-clear">
									<div class="media-left">
										<div class="overlay-container">
											<img class="media-object" src="images/product-6.jpg" alt="blog-thumb">
											<a href="shop-product.html" class="overlay-link small"><i class="fa fa-link"></i></a>
										</div>
									</div>
									<div class="media-body">
										<h6 class="media-heading"><a href="shop-product.html">Eum repudiandae ipsam</a></h6>
										<p class="margin-clear">
											<i class="fa fa-star text-default"></i>
											<i class="fa fa-star text-default"></i>
											<i class="fa fa-star text-default"></i>
											<i class="fa fa-star text-default"></i>
											<i class="fa fa-star"></i>
										</p>
										<p class="price">$299.00</p>
									</div>
									<hr>
								</div>
								<div class="media margin-clear">
									<div class="media-left">
										<div class="overlay-container">
											<img class="media-object" src="images/product-7.jpg" alt="blog-thumb">
											<a href="shop-product.html" class="overlay-link small"><i class="fa fa-link"></i></a>
										</div>
									</div>
									<div class="media-body">
										<h6 class="media-heading"><a href="shop-product.html">Quia aperiam velit fuga</a></h6>
										<p class="margin-clear">
											<i class="fa fa-star text-default"></i>
											<i class="fa fa-star text-default"></i>
											<i class="fa fa-star text-default"></i>
											<i class="fa fa-star text-default"></i>
											<i class="fa fa-star"></i>
										</p>
										<p class="price">$9.99</p>
									</div>
									<hr>
								</div>
								<div class="media margin-clear">
									<div class="media-left">
										<div class="overlay-container">
											<img class="media-object" src="images/product-8.jpg" alt="blog-thumb">
											<a href="shop-product.html" class="overlay-link small"><i class="fa fa-link"></i></a>
										</div>
									</div>
									<div class="media-body">
										<h6 class="media-heading"><a href="shop-product.html">Fugit non natus officiis</a></h6>
										<p class="margin-clear">
											<i class="fa fa-star text-default"></i>
											<i class="fa fa-star text-default"></i>
											<i class="fa fa-star text-default"></i>
											<i class="fa fa-star"></i>
											<i class="fa fa-star"></i>
										</p>
										<p class="price">$399.00</p>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-4">
							<div class="block clearfix">
								<h3 class="title">Best Sellers</h3>
								<div class="separator-2"></div>
								<div class="media margin-clear">
									<div class="media-left">
										<div class="overlay-container">
											<img class="media-object" src="images/product-3.jpg" alt="blog-thumb">
											<a href="shop-product.html" class="overlay-link small"><i class="fa fa-link"></i></a>
										</div>
									</div>
									<div class="media-body">
										<h6 class="media-heading"><a href="shop-product.html">Lorem ipsum dolor sit amet</a></h6>
										<p class="margin-clear">
											<i class="fa fa-star text-default"></i>
											<i class="fa fa-star text-default"></i>
											<i class="fa fa-star text-default"></i>
											<i class="fa fa-star text-default"></i>
											<i class="fa fa-star text-default"></i>
										</p>
										<p class="price">$99.00</p>
									</div>
									<hr>
								</div>
								<div class="media margin-clear">
									<div class="media-left">
										<div class="overlay-container">
											<img class="media-object" src="images/product-5.jpg" alt="blog-thumb">
											<a href="shop-product.html" class="overlay-link small"><i class="fa fa-link"></i></a>
										</div>
									</div>
									<div class="media-body">
										<h6 class="media-heading"><a href="shop-product.html">Eum repudiandae ipsam</a></h6>
										<p class="margin-clear">
											<i class="fa fa-star text-default"></i>
											<i class="fa fa-star text-default"></i>
											<i class="fa fa-star text-default"></i>
											<i class="fa fa-star text-default"></i>
											<i class="fa fa-star"></i>
										</p>
										<p class="price">$299.00</p>
									</div>
									<hr>
								</div>
								<div class="media margin-clear">
									<div class="media-left">
										<div class="overlay-container">
											<img class="media-object" src="images/product-7.jpg" alt="blog-thumb">
											<a href="shop-product.html" class="overlay-link small"><i class="fa fa-link"></i></a>
										</div>
									</div>
									<div class="media-body">
										<h6 class="media-heading"><a href="shop-product.html">Quia aperiam velit fuga</a></h6>
										<p class="margin-clear">
											<i class="fa fa-star text-default"></i>
											<i class="fa fa-star text-default"></i>
											<i class="fa fa-star text-default"></i>
											<i class="fa fa-star text-default"></i>
											<i class="fa fa-star"></i>
										</p>
										<p class="price">$9.99</p>
									</div>
									<hr>
								</div>
								<div class="media margin-clear">
									<div class="media-left">
										<div class="overlay-container">
											<img class="media-object" src="images/product-1.jpg" alt="blog-thumb">
											<a href="shop-product.html" class="overlay-link small"><i class="fa fa-link"></i></a>
										</div>
									</div>
									<div class="media-body">
										<h6 class="media-heading"><a href="shop-product.html">Fugit non natus officiis</a></h6>
										<p class="margin-clear">
											<i class="fa fa-star text-default"></i>
											<i class="fa fa-star text-default"></i>
											<i class="fa fa-star text-default"></i>
											<i class="fa fa-star"></i>
											<i class="fa fa-star"></i>
										</p>
										<p class="price">$399.00</p>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<h3 class="logo-font">Brands</h3>
							<div class="separator-2"></div>
							<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. At distinctio quia, et natus nulla cumque consequuntur, <br> sed, quam aliquam excepturi ea necessitatibus facilis, vero illum dignissimos eligendi quasi consectetur possimus.</p>
							<div class="clients-container">
								<div class="clients">
									<div class="client-image object-non-visible" data-animation-effect="fadeIn" data-effect-delay="100">
										<a href="#"><img src="images/client-1.png" alt=""></a>
									</div>
									<div class="client-image object-non-visible" data-animation-effect="fadeIn" data-effect-delay="200">
										<a href="#"><img src="images/client-2.png" alt=""></a>
									</div>
									<div class="client-image object-non-visible" data-animation-effect="fadeIn" data-effect-delay="300">
										<a href="#"><img src="images/client-3.png" alt=""></a>
									</div>
									<div class="client-image object-non-visible" data-animation-effect="fadeIn" data-effect-delay="400">
										<a href="#"><img src="images/client-4.png" alt=""></a>
									</div>
									<div class="client-image object-non-visible" data-animation-effect="fadeIn" data-effect-delay="500">
										<a href="#"><img src="images/client-5.png" alt=""></a>
									</div>
									<div class="client-image object-non-visible" data-animation-effect="fadeIn" data-effect-delay="600">
										<a href="#"><img src="images/client-6.png" alt=""></a>
									</div>
									<div class="client-image object-non-visible" data-animation-effect="fadeIn" data-effect-delay="700">
										<a href="#"><img src="images/client-7.png" alt=""></a>
									</div>
									<div class="client-image object-non-visible" data-animation-effect="fadeIn" data-effect-delay="800">
										<a href="#"><img src="images/client-8.png" alt=""></a>
									</div>
									<div class="client-image object-non-visible" data-animation-effect="fadeIn" data-effect-delay="100">
										<a href="#"><img src="images/client-1.png" alt=""></a>
									</div>
									<div class="client-image object-non-visible" data-animation-effect="fadeIn" data-effect-delay="200">
										<a href="#"><img src="images/client-2.png" alt=""></a>
									</div>
									<div class="client-image object-non-visible" data-animation-effect="fadeIn" data-effect-delay="300">
										<a href="#"><img src="images/client-3.png" alt=""></a>
									</div>
									<div class="client-image object-non-visible" data-animation-effect="fadeIn" data-effect-delay="400">
										<a href="#"><img src="images/client-4.png" alt=""></a>
									</div>
									<div class="client-image object-non-visible" data-animation-effect="fadeIn" data-effect-delay="500">
										<a href="#"><img src="images/client-5.png" alt=""></a>
									</div>
									<div class="client-image object-non-visible" data-animation-effect="fadeIn" data-effect-delay="600">
										<a href="#"><img src="images/client-6.png" alt=""></a>
									</div>
									<div class="client-image object-non-visible" data-animation-effect="fadeIn" data-effect-delay="700">
										<a href="#"><img src="images/client-7.png" alt=""></a>
									</div>
									<div class="client-image object-non-visible" data-animation-effect="fadeIn" data-effect-delay="800">
										<a href="#"><img src="images/client-8.png" alt=""></a>
									</div>
									<div class="client-image object-non-visible" data-animation-effect="fadeIn" data-effect-delay="100">
										<a href="#"><img src="images/client-1.png" alt=""></a>
									</div>
									<div class="client-image object-non-visible" data-animation-effect="fadeIn" data-effect-delay="200">
										<a href="#"><img src="images/client-2.png" alt=""></a>
									</div>
									<div class="client-image object-non-visible" data-animation-effect="fadeIn" data-effect-delay="300">
										<a href="#"><img src="images/client-3.png" alt=""></a>
									</div>
									<div class="client-image object-non-visible" data-animation-effect="fadeIn" data-effect-delay="400">
										<a href="#"><img src="images/client-4.png" alt=""></a>
									</div>
									<div class="client-image object-non-visible" data-animation-effect="fadeIn" data-effect-delay="500">
										<a href="#"><img src="images/client-5.png" alt=""></a>
									</div>
									<div class="client-image object-non-visible" data-animation-effect="fadeIn" data-effect-delay="600">
										<a href="#"><img src="images/client-6.png" alt=""></a>
									</div>
									<div class="client-image object-non-visible" data-animation-effect="fadeIn" data-effect-delay="700">
										<a href="#"><img src="images/client-7.png" alt=""></a>
									</div>
									<div class="client-image object-non-visible" data-animation-effect="fadeIn" data-effect-delay="800">
										<a href="#"><img src="images/client-8.png" alt=""></a>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</section>
			<!-- section end -->

			<!-- section start -->
			<!-- ================ -->
			<section class="section dark-translucent-bg pv-40" style="background-image:url('images/shop-banner.jpg');background-position: 50% 32%;">
				<div class="container">
					<div class="row grid-space-10">
						<div class="col-md-3 col-sm-6">
							<div class="pv-30 ph-20 feature-box text-center object-non-visible" data-animation-effect="fadeInDownSmall" data-effect-delay="100">
								<span class="icon default-bg"><i class="fa fa-diamond"></i></span>
								<h3>Premium &amp; Guaranteed Quality</h3>
								<div class="separator clearfix"></div>
								<p>Voluptatem ad provident non repudiandae beatae cupiditate.</p>
								<a href="page-services.html" class="link-dark">Read More<i class="pl-5 fa fa-angle-double-right"></i></a>
							</div>
						</div>
						<div class="col-md-3 col-sm-6">
							<div class="pv-30 ph-20 feature-box text-center object-non-visible" data-animation-effect="fadeInDownSmall" data-effect-delay="150">
								<span class="icon default-bg"><i class="icon-lock"></i></span>
								<h3>Secure &amp; Safe Payment</h3>
								<div class="separator clearfix"></div>
								<p>Iure sequi unde hic. Sapiente quaerat sequi inventore.</p>
								<a href="page-services.html" class="link-dark">Read More<i class="pl-5 fa fa-angle-double-right"></i></a>
							</div>
						</div>
						<div class="clearfix visible-sm"></div>
						<div class="col-md-3 col-sm-6">
							<div class="pv-30 ph-20 feature-box text-center object-non-visible" data-animation-effect="fadeInDownSmall" data-effect-delay="200">
								<span class="icon default-bg"><i class="icon-globe"></i></span>
								<h3 class="pl-10 pr-10">Free &amp; Fast Shipping</h3>
								<div class="separator clearfix"></div>
								<p>Inventore dolores aut laboriosam cum consequuntur.</p>
								<a href="page-services.html" class="link-dark">Read More<i class="pl-5 fa fa-angle-double-right"></i></a>
							</div>
						</div>
						<div class="col-md-3 col-sm-6">
							<div class="pv-30 ph-20 feature-box text-center object-non-visible" data-animation-effect="fadeInDownSmall" data-effect-delay="250">
								<span class="icon default-bg"><i class="icon-thumbs-up"></i></span>
								<h3>24/7 Customer Support</h3>
								<div class="separator clearfix"></div>
								<p>Inventore dolores aut laboriosam cum consequuntur.</p>
								<a href="page-services.html" class="link-dark">Read More<i class="pl-5 fa fa-angle-double-right"></i></a>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="call-to-action text-center">
								<div class="row">
									<div class="col-md-8 col-md-offset-2">
										<h2 class="title"><strong>Subscribe</strong> To Our Newsletter</h2>
										<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Repellendus error pariatur deserunt laudantium nam, mollitia quas nihil inventore, quibusdam?</p>
										<div class="separator"></div>
										<form class="form-inline margin-clear">
											<div class="form-group has-feedback">
												<label class="sr-only" for="subscribe3">Email address</label>
												<input type="email" class="form-control form-control-lg" id="subscribe3" placeholder="Enter email" name="subscribe3" required="">
												<i class="fa fa-envelope form-control-feedback"></i>
											</div>
											<button type="submit" class="btn btn-lg btn-gray-transparent btn-animated margin-clear">Submit <i class="fa fa-send"></i></button>
										</form>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</section>
			<!-- section end -->
			
			<!-- footer top start -->
			<!-- ================ -->
			<div class="dark-bg   footer-top animated-text">
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
			<!-- footer top end -->
			
			<!-- footer start (Add "dark" class to #footer in order to enable dark footer) -->
			<!-- ================ -->
			<footer id="footer" class="clearfix dark">

				<!-- .footer start -->
				<!-- ================ -->
				<div class="footer">
					<div class="container">
						<div class="footer-inner">
							<div class="row">
								<div class="col-md-3">
									<div class="footer-content">
										<div class="logo-footer"><img id="logo-footer" src="images/logo_light_blue.png" alt=""></div>
										<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Necessitatibus illo vel dolorum soluta consectetur doloribus sit. Delectus non tenetur odit dicta vitae debitis suscipit doloribus. Ipsa, aut voluptas quaerat.</p>
										<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Maiores similique voluptatum, culpa ad iure sed.</p>
										<div class="icons-block mt-10 mb-10">
											<i class="fa fa-cc-paypal"></i>
											<i class="fa fa-cc-amex"></i>
											<i class="fa fa-cc-discover"></i>
											<i class="fa fa-cc-mastercard"></i>
											<i class="fa fa-cc-visa"></i>
											<i class="fa fa-cc-stripe"></i>
										</div>
									</div>
								</div>
								<div class="col-md-3">
									<div class="footer-content">
										<h2 class="title">My Account</h2>
										<div class="separator-2"></div>
										<nav class="mb-20">
											<ul class="nav nav-pills nav-stacked list-style-icons">
												<li><a href="components-social-icons.html"><i class="icon-tools"></i> Settings</a></li>
												<li><a href="components-buttons.html"><i class="icon-search"></i> My Orders</a></li>
												<li><a href="components-buttons.html"><i class="icon-basket-1"></i> Cart</a></li>
												<li><a href="components-forms.html"><i class="icon-heart"></i> Wish List</a></li>
												<li><a href="components-tabs-and-pills.html"><i class="icon-chat"></i> Notifications</a></li>
												<li><a target="_blank" href="http://htmlcoder.me/support"><i class="icon-thumbs-up"></i> Support</a></li>
												<li><a href="#"><i class="icon-lock"></i> Privacy</a></li>
											</ul>
										</nav>
									</div>
								</div>
								<div class="col-md-3">
									<div class="footer-content">
										<h2 class="title">Latest Products</h2>
										<div class="separator-2"></div>
										<div class="row grid-space-10">
											<div class="col-xs-6">
												<div class="overlay-container mb-10">
													<img src="images/product-1.jpg" alt="">
													<a href="portfolio-item.html" class="overlay-link small">
														<i class="fa fa-link"></i>
													</a>
												</div>
											</div>
											<div class="col-xs-6">
												<div class="overlay-container mb-10">
													<img src="images/product-2.jpg" alt="">
													<a href="portfolio-item.html" class="overlay-link small">
														<i class="fa fa-link"></i>
													</a>
												</div>
											</div>
											<div class="col-xs-6">
												<div class="overlay-container mb-10">
													<img src="images/product-3.jpg" alt="">
													<a href="portfolio-item.html" class="overlay-link small">
														<i class="fa fa-link"></i>
													</a>
												</div>
											</div>
											<div class="col-xs-6">
												<div class="overlay-container mb-10">
													<img src="images/product-4.jpg" alt="">
													<a href="portfolio-item.html" class="overlay-link small">
														<i class="fa fa-link"></i>
													</a>
												</div>
											</div>
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
		<script type="text/javascript" src="plugins/jquery.min.js"></script>
		<script type="text/javascript" src="bootstrap/js/bootstrap.min.js"></script>

		<!-- Modernizr javascript -->
		<script type="text/javascript" src="plugins/modernizr.js"></script>

		<!-- jQuery Revolution Slider  -->
		<script type="text/javascript" src="plugins/rs-plugin/js/jquery.themepunch.tools.min.js"></script>
		<script type="text/javascript" src="plugins/rs-plugin/js/jquery.themepunch.revolution.min.js"></script>

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
		<script src="js/jspdf.js"></script>
		<script>
		<?php
			if(isset($_POST['new_transition'])){
				 $mysqli = new mysqli("localhost", "root", "Zhan2003", "cap");

  //Output any connection error
  if ($mysqli->connect_error) {
    die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
  }
  $email=$_SESSION['Email'];
  $sql="select Order_Num from Reservation where User_Email='$email' ORDER BY Order_Date DESC limit 1;";
  $results = $mysqli->query($sql);
  $row=$results->fetch_array();
  if(isset($row)){
    $image_data = $row['Order_Num'];
  }else{
    echo 'failed';
  }
	
	
	$b64image = base64_encode(file_get_contents("https://api.qrserver.com/v1/create-qr-code/?data=http://zcilok.cloudapp.net/ci/index.php/test/index/".$image_data."&format=jpeg"));
			?>
				
				var content_div = $("<div id='content'> <table id='table_info'><tr><td>NAME:</td><td>Qintai Liu</td></tr><tr><td>GAME:</td><td>MIZZOU VS EASTERN MICHGAN</td></tr><tr><td>DATE:</td><td>09/10/2015 4:00PM</td></tr><tr><td>LOCATION:</td><td>Parking Spot : PK_2</td></tr><tr><td>PRICE:</td><td>$10</td></tr></table></div>");
				       $(document).ready(function(){ 
            var specialElementHandlers = {
                '#editor': function (element,renderer) {
                    return true;
                }
            };
        
                var doc = new jsPDF('p', 'pt', 'a4');
				
				doc.setFont("helvetica");
				  doc.setFontType("bold");
				  doc.text(40, 50, 'This is helvetica bold.');
	  
                doc.fromHTML(content_div.html(), 40, 60, {
                    'width': 50,
					'elementHandlers': specialElementHandlers
                });
				
				doc.setFont("helvetica");
				  doc.setFontType("bold");
				  doc.text(40, 270, 'This is helvetica bold.');
				  
				var show="data:image/jpeg;base64,<?=$b64image?>";
				doc.addImage(show, 'JPEG', 40, 300, 0, 0);
				
				
				
				 var temp = doc.output('datauristring');
				 
				 var pdf=temp.replace('data:application/pdf;base64,','');
				 
				 //pdf=pdf.replace('+','%20');
				  //console.log(pdf);
				  
				

				
				 var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (xhttp.readyState == 4 && xhttp.status == 200) {
      console.log(xhttp.responseText);
    }
  };
  xhttp.open("POST", "http://zcilok.cloudapp.net/ci/index.php/pdfprocess/createPdf", true);
  xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhttp.send("pdf="+pdf+"&email=<?=$email?>");
console.log("<?=$email?>  <?=$image_data?>");
			


        
        
		
		
		
		});
	  
	
	  
	
		<?php
			}
		?>
		</script>
	</body>
</html>
