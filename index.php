<?php
session_start();
include "dbconn.php";
?>

<!DOCTYPE html>
<html lang="en"><!-- Basic -->
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">   
   
    <!-- Mobile Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
 
     <!-- Site Metas -->
    <title>FlowerCaféXpress</title>  
    <meta name="keywords" content="">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Site Icons -->
    <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">
    <link rel="apple-touch-icon" href="images/apple-touch-icon.png">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css">    
	<!-- Site CSS -->
    <link rel="stylesheet" href="css/style.css">    
    <!-- Responsive CSS -->
    <link rel="stylesheet" href="css/responsive.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/custom.css">

    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>
    <?php $base_path = ''; include 'includes/header.php'; ?>
	
	<!-- Start slides -->
	<div id="slides" class="cover-slides">
		<ul class="slides-container">
			<li class="text-center">
				<img src="images/banner.jpg" alt="">
				<div class="container">
					<div class="row">
						<div class="col-md-12">
							<h1 class="m-b-20"><strong>Welcome To <br> FlowerCaféXpress</strong></h1>
						</div>
					</div>
				</div>
			</li>
			<li class="text-center">
				<img src="images/depan pintu.jpg" alt="">
				<div class="container">
					<div class="row">
						<div class="col-md-12">
							<h1 class="m-b-20"><strong>Welcome To <br> FlowerCaféXpress</strong></h1>
						</div>
					</div>
				</div>
			</li>
			<li class="text-center">
				<img src="images/signboard.jpg" alt="">
				<div class="container">
					<div class="row">
						<div class="col-md-12">
							<h1 class="m-b-20"><strong>Welcome To <br> FlowerCaféXpress</strong></h1>
						</div>
					</div>
				</div>
			</li>
		</ul>
		<div class="slides-navigation">
			<a href="#" class="next"><i class="fa fa-angle-right" aria-hidden="true"></i></a>
			<a href="#" class="prev"><i class="fa fa-angle-left" aria-hidden="true"></i></a>
		</div>
	</div>
	<!-- End slides -->
	
	<!-- Start About -->
	<div class="about-section-box">
		<div class="container">
			<div class="row">
				<div class="col-lg-6 col-md-6 col-sm-12">
					<img src="images/gambar bunga kafe.jpg" alt="" class="img-fluid">
				</div>
				<div class="col-lg-6 col-md-6 col-sm-12 text-center">
					<div class="inner-column">
						<h1>Welcome To <span>FlowerCaféXpress</span></h1>
						<h4>Little Introduction</h4>
						<p>FlowerCaféXpress is a smart and economical food  system designed to connect customers 
							with the university's cafe so that the customer can see the menu and place an order. 
							The system supports three main user roles; customers, cafe owner, 
							cafe workers with tailor-made features for managing orders, marking order status, and monitoring operations. 
							Innovatively engineered to simulate an actual food ordering system, 
							FlowerCaféXpress focuses on intuitive user interfaces and accurate ordering with the assurance 
							that the backend would seamlessly process many orders simultaneously. </p>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- End About -->
	
	<!-- Start QT -->
	<div class="qt-box qt-background">
		<div class="container">
			<div class="row">
				<div class="col-md-8 ml-auto mr-auto text-left">
					<p class="lead ">
						" If you're not the one cooking, stay out of the way and compliment the chef. "
					</p>
					<span class="lead">-Cik Lan</span>
				</div>
			</div>
		</div>
	</div>
	<!-- End QT -->
	
	<!-- Start Customer Reviews -->
	<div class="customer-reviews-box">
		<div class="container">
			<div class="row">
				<div class="col-lg-12">
					<div class="heading-title text-center">
						<h2>Customer Reviews</h2>
						<p>Listen to what our regular customers has to say!</p>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-8 mr-auto ml-auto text-center">
					<div id="reviews" class="carousel slide" data-ride="carousel">
						<div class="carousel-inner mt-4">
							<div class="carousel-item text-center active">
								<div class="img-box p-1 border rounded-circle m-auto">
									<img class="d-block w-100 rounded-circle" src="images/kir.png" alt="">
								</div>
								<h5 class="mt-4 mb-0"><strong class="text-warning text-uppercase">Shakir</strong></h5>
								<h6 class="text-dark m-0">CS Student</h6>
								<p class="m-0 pt-3">They have incredibly tasty food here, with so many dishes to choose from!</p>
							</div>
							<div class="carousel-item text-center">
								<div class="img-box p-1 border rounded-circle m-auto">
									<img class="d-block w-100 rounded-circle" src="images/aydin.png" alt="">
								</div>
								<h5 class="mt-4 mb-0"><strong class="text-warning text-uppercase">Aydin</strong></h5>
								<h6 class="text-dark m-0">CS Student</h6>
								<p class="m-0 pt-3">The price of all the dishes here are generously cheap without sacrificing quality.</p>
							</div>
							<div class="carousel-item text-center">
								<div class="img-box p-1 border rounded-circle m-auto">
									<img class="d-block w-100 rounded-circle" src="images/syaheed.jpg" alt="">
								</div>
								<h5 class="mt-4 mb-0"><strong class="text-warning text-uppercase">Syaheed</strong></h5>
								<h6 class="text-dark m-0">CS Student</h6>
								<p class="m-0 pt-3">They have the best buttermilk chicken here!!</p>
							</div>
						</div>
						<a class="carousel-control-prev" href="#reviews" role="button" data-slide="prev">
							<i class="fa fa-angle-left" aria-hidden="true"></i>
							<span class="sr-only">Previous</span>
						</a>
						<a class="carousel-control-next" href="#reviews" role="button" data-slide="next">
							<i class="fa fa-angle-right" aria-hidden="true"></i>
							<span class="sr-only">Next</span>
						</a>
                    </div>
				</div>
			</div>
		</div>
	</div>
	<!-- End Customer Reviews -->
	
	<?php include 'includes/contact_info.php'; ?>
	<?php include 'includes/footer.php'; ?>
	
	<a href="#" id="back-to-top" title="Back to top" style="display: none;">&uarr;</a>

	<?php include 'includes/scripts.php'; ?>
	<?php include 'includes/modals.php'; ?>
</body>
</html>