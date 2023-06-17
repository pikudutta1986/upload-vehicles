<?php 
include("./config/index.php");
$database = new Database();
$db = $database->getConnection();


function getListings($db) {
    $listingQuery = 'SELECT *
            FROM carlisting_listings AS l
            LEFT JOIN carlisting_plugin_gallery AS g
            ON l.id = g.foreign_id
            WHERE l.owner_id = "'. $_GET['dealer_id'] .'"';
    $listingStmt = $db->query($listingQuery);
    $listingRows = $listingStmt->fetchAll(PDO::FETCH_ASSOC);
    // return $listingRows;
    echo '<pre>';
    print_r($listingRows);
}

$cars = getListings($db);

function getDealerInfo($db) {
    $dealerQuery = 'SELECT * FROM carlisting_users WHERE id="' . $_GET['dealer_id'] . '"';
    $dealerStmt = $db->query($dealerQuery);
    $dealerRows = $dealerStmt->fetch(PDO::FETCH_ASSOC);
    return $dealerRows;
}

$dealersArray = getDealerInfo($db);
$title = $dealersArray['name'] . "'s";

// function getListingsById($db) {
//     $listingQuery = 'SELECT * FROM carlisting_listings WHERE owner_id="' . $_GET['dealer_id'] . '"';
//     $listingStmt = $db->query($listingQuery);
//     $listingRows = $listingStmt->fetchAll(PDO::FETCH_ASSOC); 
//     return $listingRows;
// }

// function getImages($db) {
//     $listingsArray = getListingsById($db);
//     $galleryRows = array();

//     foreach($listingsArray as $item) {
//         $imageQuery = 'SELECT * FROM carlisting_plugin_gallery WHERE foreign_id="' . $item['id'] . '"';
//         $imageStmt = $db->query($imageQuery);
//         $row = $imageStmt->fetch(PDO::FETCH_ASSOC);
//         $galleryRows[] = $row;
//     }

//     return $galleryRows;
// }

// $gallery = getImages($db);

// function getcarMake($db) {
//     $makeQuery = 'SELECT * FROM carlisting_listings WHERE id="' . $item['foreign_id'] . '"';
//         $stmt = $db->query($query);
//         $row = $stmt->fetch(PDO::FETCH_ASSOC);
// }

// function getListingsInfo($db) {
//     $gallery = getImages($db);
//     $listingInfos = array();

//     foreach($gallery as $item) {
//         $query = 'SELECT * FROM carlisting_listings WHERE id="' . $item['foreign_id'] . '"';
//         $stmt = $db->query($query);
//         $row = $stmt->fetch(PDO::FETCH_ASSOC);
//         $listingInfos[] = $row;
//     }

//     return $listingInfos;
    
// }

// $listingInfoArray = getListingsInfo($db);

// echo '<pre>';
// print_r($listingInfoArray);
// die();


?>


<!DOCTYPE HTML>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width,initial-scale=1">
<meta name="keywords" content="">
<meta name="description" content="">
<title> <?php echo $title; ?> Website </title>
<!--Bootstrap -->
<link rel="stylesheet" href="<?php echo $SITE_URL; ?>inventory/assets/css/bootstrap.min.css" type="text/css">
<link rel="stylesheet" href="<?php echo $SITE_URL; ?>inventory/assets/css/bootstrap-datepicker.css" type="text/css">
<link rel="stylesheet" href="<?php echo $SITE_URL; ?>inventory/assets/css/bootstrap-timepicker.min.css" type="text/css">
<!--Custome Style -->
<link rel="stylesheet" href="<?php echo $SITE_URL; ?>inventory/assets/css/style.css" type="text/css">
<link rel="stylesheet" href="<?php echo $SITE_URL; ?>inventory/assets/css/custom.css" type="text/css">
<!--OWL Carousel slider-->
<link rel="stylesheet" href="<?php echo $SITE_URL; ?>inventory/assets/css/owl.carousel.css" type="text/css">
<link rel="stylesheet" href="<?php echo $SITE_URL; ?>inventory/assets/css/owl.transitions.css" type="text/css">
<!--slick-slider -->
<link href="<?php echo $SITE_URL; ?>inventory/assets/css/slick.css" rel="stylesheet">
<!--bootstrap-slider -->
<link href="<?php echo $SITE_URL; ?>inventory/assets/css/bootstrap-slider.min.css" rel="stylesheet">
<!--FontAwesome Font Style -->
<!-- <link href="<?php echo $SITE_URL; ?>inventory/assets/css/font-awesome.min.css" rel="stylesheet"> -->
<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/> -->

<!-- Custom Colors -->
<link rel="stylesheet" href="<?php echo $SITE_URL; ?>inventory/assets/colors/red.css">
        
<!-- Fav and touch icons -->
<link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?php echo $SITE_URL; ?>inventory/assets/images/favicon-icon/apple-touch-icon-144-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php echo $SITE_URL; ?>inventory/assets/images/favicon-icon/apple-touch-icon-114-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo $SITE_URL; ?>inventory/assets/images/favicon-icon/apple-touch-icon-72-precomposed.png">
<link rel="apple-touch-icon-precomposed" href="<?php echo $SITE_URL; ?>inventory/assets/images/favicon-icon/apple-touch-icon-57-precomposed.png">
<link rel="shortcut icon" href="<?php echo $SITE_URL; ?>inventory/assets/images/favicon-icon/favicon.png"> 
</head>
<body>

<!--Header-->
<header>  <div class="default-header">
    <div class="container">
        <h1>
          <div style="float: left;"><a href="https://www.adealerwebsite.com/"><?php echo $title; ?> Website</a></div>
        </h1>
        <h3>
          <div style="float: right;"><?php echo $dealersArray['contact_phone']; ?></div>
        </h3>
        <div style="clear: both;"></div>
		    <div class="social-follow">
              <ul>
                <li><a href=""><i class="fa fa-facebook-square" aria-hidden="true"></i></a></li>
                <li><a href=""><i class="fa fa-twitter-square" aria-hidden="true"></i></a></li>
                <li><a href=""><i class="fa fa-linkedin-square" aria-hidden="true"></i></a></li>
                <li><a href=""><i class="fa fa-google-plus-square" aria-hidden="true"></i></a></li>
                <li><a href=""><i class="fa fa-instagram" aria-hidden="true"></i></a></li>
              </ul>
            </div>
    </div>
  </div>
  
  <!-- Navigation -->
  <nav id="navigation_bar" class="navbar navbar-default">
    <div class="container">
      <div class="navbar-header">
        <button id="menu_slide" data-target="#navigation" aria-expanded="false" data-toggle="collapse" class="navbar-toggle collapsed" type="button"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
      </div>
      <div class="header_wrap">
        <div class="user_login">
          <ul> <li class="dropdown"> <a href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-user-circle" aria-hidden="true"></i> Dealer Admin <i class="fa fa-angle-down" aria-hidden="true"></i></a>
              <ul class="dropdown-menu"> <li><a href="https://www.dmotorworks.com/ia.ui/broker/main.page" target="_blank">Backend Portal</a></li>
              </ul>
            </li>
          </ul>
        </div>
        
<div class="header_search">
          <!-- old search -->
        </div>
      </div>
      <div class="collapse navbar-collapse" id="navigation">
	  <ul class="nav navbar-nav">
<li><a href="https://www.adealerwebsite.com/">Homepage</a></li>
<li><a href="https://www.adealerwebsite.com/about.php">About Us</a></li>
<li><a href="https://www.adealerwebsite.com/vehicles-list.php">Inventory List</a></li>
<li><a href="https://www.adealerwebsite.com/finance.php">Apply for Financing</a></li>
<li><a href="https://www.adealerwebsite.com/contact.php">Contact Dealership</a></li>
</ul>
</div>
    </div>
  </nav>
  <!-- Navigation end --> 
  
</header>
<!-- /Header --> 

<!--Page Header-->
<section class="page-header profile_page">
  <div class="container">
    <div class="page-header_wrap">
      <div class="page-heading">
        <h1>Inventory List</h1>
      </div>
      <ul class="coustom-breadcrumb">
        <li><a href="https://www.adealerwebsite.com/">Homepage</a></li>
        <li>All Vehicles List</li>
      </ul>
    </div>
  </div>
  <!-- Dark Overlay-->
  <div class="dark-overlay"></div>
</section>
<!-- /Page Header--> 

<!--Dealers-list-->

<section class="inner_pages">
  <div class="container">
    <div class="result-sorting-wrapper">
      <div class="sorting-count">
        <p>1 - 0 <span>of 0 Results for your search.</span></p>
      </div>
      <div class="result-sorting-by">
        <p>Sort by:</p>
        <form action="vehicles-list.php?" method="post">
          <div class="form-group select sorting-select">
            <select name="sort" class="form-control" onchange="this.form.submit()">
              <option value="highest" selected>Highest Price</option>
              <option value="lowest" >Lowest Price</option>
            </select>
          </div>
        </form>
      </div>
    </div>
    <div class="dealers_list_wrap">
        <?php foreach($cars as $car) { if($car) {?>
        <div class="row item-row">
            <div class="col-md-5">
                <img class="car-images" src="<?php echo $SITE_URL . $car['medium_path']; ?>" alt="car-images"/>
            </div>
            <div class="col-md-7">
                <h2></h2>
                <div class="row">
                    <div class="col-md-6"></div>
                    <div class="col-md-6"></div>
                </div>
                <div class="row">
                    <div class="col-md-6"></div>
                    <div class="col-md-6"></div>
                </div>
            </div>
        </div>
        <hr>
        <?php } } ?>
        <div class="pagination">
            <ul>
            </ul>
        </div>
    </div>
</section>
<!--/Dealers-list--> 


<!--Footer -->
<footer>
  <div class="footer-top">
    <div class="container">
      <div class="row">
        <div class="col-md-3 col-sm-6">
          <h6>Inventory</h6>
          <ul>
            <li><a href="https://www.adealerwebsite.com/#">Under $5,000</a></li>
            <li><a href="https://www.adealerwebsite.com/#">Under $10,000</a></li>
            <li><a href="https://www.adealerwebsite.com/#">Under $15,000</a></li>
            <li><a href="https://www.adealerwebsite.com/#">Under $20,000</a></li>
          </ul>
        </div>
        <div class="col-md-3 col-sm-6">
          <h6>Dealership</h6> <ul>
            <li><a href="https://www.adealerwebsite.com/about.php">About</a></li>    <li><a href="https://www.adealerwebsite.com/vehicles-list.php">Inventory</a></li>
            <li><a href="https://www.adealerwebsite.com/finance.php">Financing</a></li>
            <li><a href="https://www.adealerwebsite.com/contact.php">Contact</a></li>
          </ul>
        </div>
        <div class="col-md-3 col-sm-6"> <h6>Legal</h6>
          <ul>  
<li><a href="https://www.adealerwebsite.com/disclaimer.php">Disclaimer</a></li>
<li><a href="https://www.adealerwebsite.com/privacy.php">Privacy Policy</a></li>
<li><a href="https://www.safercar.gov/">SaferCar.gov</a></li> 
<li><a href="https://www.adealerwebsite.com/terms.php">Terms of Service</a></li>
</ul>
        </div>
        <div class="col-md-3 col-sm-6">
          <h6>We'll Text You </h6>
          <div class="newsletter-form">
            <form action="#">
              <div class="form-group">
                <input type="phone" class="form-control newsletter-input" required placeholder="Enter Mobile Number" />
              </div>
              <button type="submit" class="btn btn-block">Quick Connect <span class="angle_arrow"><i class="fa fa-angle-right" aria-hidden="true"></i></span></button>
            </form>
            <p class="subscribed-text">*Just enter your mobile phone number and we will send you a quick text right away.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="footer-bottom">
    <div class="container">
      <div class="row">
        <div class="col-md-6 col-md-push-6 text-right">
          <div class="footer_widget">  <p>Dealer Site by <a href="https://www.autobuyersmarket.com/" target="_blank">Auto Buyers Market</a></p>
           
          </div>
          <div class="footer_widget">
            <p>Connect with Us:</p>
            <ul>
              <li><a href="#"><i class="fa fa-facebook-square" aria-hidden="true"></i></a></li>
              <li><a href="#"><i class="fa fa-twitter-square" aria-hidden="true"></i></a></li>
              <li><a href="#"><i class="fa fa-linkedin-square" aria-hidden="true"></i></a></li>
              <li><a href="#"><i class="fa fa-google-plus-square" aria-hidden="true"></i></a></li>
              <li><a href="#"><i class="fa fa-instagram" aria-hidden="true"></i></a></li>
            </ul>
          </div>
        </div>
        <div class="col-md-6 col-md-pull-6">
          <p class="copy-right">Copyright &copy; 2018 by <a href="https://www.adealerwebsite.com/">A Dealer Website</a> </p>
        </div>
      </div>
    </div>
  </div>
</footer>
<!-- /Footer--> 

<!--Back to top-->
<div id="back-top" class="back-top"> <a href="#top"><i class="fa fa-angle-up" aria-hidden="true"></i> </a> </div>
<!--/Back to top--> 

<!--Login-Form -->
<div class="modal fade" id="loginform">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h3 class="modal-title">Login</h3>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="login_wrap">
            <div class="col-md-6 col-sm-6">
              <form action="#" method="get">
                <div class="form-group">
                  <input type="text" class="form-control" placeholder="Username or Email address*">
                </div>
                <div class="form-group">
                  <input type="password" class="form-control" placeholder="Password*">
                </div>
                <div class="form-group checkbox">
                  <input type="checkbox" id="remember">
                  <label for="remember">Remember Me</label>
                </div>
                <div class="form-group">
                  <input type="submit" value="Login" class="btn btn-block">
                </div>
              </form>
            </div>
            <div class="col-md-6 col-sm-6">
              <h6 class="gray_text">Login the Quick Way</h6>
              <a href="#" class="btn btn-block facebook-btn"><i class="fa fa-facebook-square" aria-hidden="true"></i> Login with Facebook</a> <a href="#" class="btn btn-block twitter-btn"><i class="fa fa-twitter-square" aria-hidden="true"></i> Login with Twitter</a> <a href="#" class="btn btn-block googleplus-btn"><i class="fa fa-google-plus-square" aria-hidden="true"></i> Login with Google+</a> </div>
            <div class="mid_divider"></div>
          </div>
        </div>
      </div>
      <div class="modal-footer text-center">
        <p>Don't have an account? <a href="#signupform" data-toggle="modal" data-dismiss="modal">Signup Here</a></p>
        <p><a href="#forgotpassword" data-toggle="modal" data-dismiss="modal">Forgot Password ?</a></p>
      </div>
    </div>
  </div>
</div>
<!--/Login-Form --> 

<!--Register-Form -->
<div class="modal fade" id="signupform">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h3 class="modal-title">Sign Up</h3>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="signup_wrap">
            <div class="col-md-6 col-sm-6">
              <form action="#" method="get">
                <div class="form-group">
                  <input type="text" class="form-control" placeholder="Full Name">
                </div>
                <div class="form-group">
                  <input type="email" class="form-control" placeholder="Email Address">
                </div>
                <div class="form-group">
                  <input type="password" class="form-control" placeholder="Password">
                </div>
                <div class="form-group">
                  <input type="password" class="form-control" placeholder="Confirm Password">
                </div>
                <div class="form-group checkbox">
                  <input type="checkbox" id="terms_agree">
                  <label for="terms_agree">I Agree with <a href="#">Terms and Conditions</a></label>
                </div>
                <div class="form-group">
                  <input type="submit" value="Sign Up" class="btn btn-block">
                </div>
              </form>
            </div>
            <div class="col-md-6 col-sm-6">
              <h6 class="gray_text">Login the Quick Way</h6>
              <a href="#" class="btn btn-block facebook-btn"><i class="fa fa-facebook-square" aria-hidden="true"></i> Login with Facebook</a> <a href="#" class="btn btn-block twitter-btn"><i class="fa fa-twitter-square" aria-hidden="true"></i> Login with Twitter</a> <a href="#" class="btn btn-block googleplus-btn"><i class="fa fa-google-plus-square" aria-hidden="true"></i> Login with Google+</a> </div>
            <div class="mid_divider"></div>
          </div>
        </div>
      </div>
      <div class="modal-footer text-center">
        <p>Already got an account? <a href="#loginform" data-toggle="modal" data-dismiss="modal">Login Here</a></p>
      </div>
    </div>
  </div>
</div>
<!--/Register-Form --> 

<!--Forgot-password-Form -->
<div class="modal fade" id="forgotpassword">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">

        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h3 class="modal-title">Password Recovery</h3>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="forgotpassword_wrap">
            <div class="col-md-12">
              <form action="#" method="get">
                <div class="form-group">
                  <input type="email" class="form-control" placeholder="Your Email address*">
                </div>
                <div class="form-group">
                  <input type="submit" value="Reset My Password" class="btn btn-block">
                </div>
              </form>
              <div class="text-center">
                <p class="gray_text">For security reasons we don't store your password. Your password will be reset and a new one will be send.</p>
                <p><a href="#loginform" data-toggle="modal" data-dismiss="modal"><i class="fa fa-angle-double-left" aria-hidden="true"></i> Back to Login</a></p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!--/Forgot-password-Form --> 

<!-- Scripts --> 
<script src="<?php echo $SITE_URL; ?>inventory/assets/js/jquery.min.js"></script>
<script src="<?php echo $SITE_URL; ?>inventory/assets/js/bootstrap.min.js"></script> 
<script src="<?php echo $SITE_URL; ?>inventory/assets/js/interface.js"></script> 
<!--bootstrap-slider-JS--> 
<script src="<?php echo $SITE_URL; ?>inventory/assets/js/bootstrap-slider.min.js"></script> 
<!--Slider-JS--> 
<script src="<?php echo $SITE_URL; ?>inventory/assets/js/slick.min.js"></script> 
<script src="<?php echo $SITE_URL; ?>inventory/assets/js/owl.carousel.min.js"></script>

</body>
</html>