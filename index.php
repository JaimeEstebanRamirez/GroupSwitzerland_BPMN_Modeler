<?php
//start session
session_start();

//config file
include_once 'config.php';

$userLoggedIn = 0;

//get session data
$sessData = !empty($_SESSION['sessData'])?$_SESSION['sessData']:'';

//check whether user ID is available in cookie
if(!empty($_COOKIE['rememberUserId'])){
	$_SESSION['sessData']['userLoggedIn'] = TRUE;
	$_SESSION['sessData']['userId'] = $_COOKIE['rememberUserId'];
}

//validate login
require_once 'validate_login.php';

//get status message from session
if(!empty($sessData['status']['msg'])){
    $statusMsg = $sessData['status']['msg'];
    $statusMsgType = $sessData['status']['type'];
    unset($_SESSION['sessData']['status']);
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>BPMN Modeler Management System | <?php echo SITE_NAME; ?></title>
	<meta name="description" content="" />
	<meta name="keywords" content="" />
	<link rel="stylesheet" href="//fonts.googleapis.com/css?family=Roboto:400,100,300,500,700,900" 	type="text/css" media="all">
	<link href="<?php echo BST_URL; ?>css/bootstrap.min.css" rel="stylesheet" type="text/css" media="all" />
	<link href="<?php echo CSS_URL; ?>style.css" rel="stylesheet" type="text/css" media="all" />
	<script src="<?php echo JS_URL; ?>jquery.min.js"></script>
	<script>
	$(document).ready(function(){
		$( ".menu-icon" ).on('click', function() {
			$( "ul.nav1" ).slideToggle( 300 );
		});
	});
	</script>
</head>
<body>
<!-- Navigation -->
<?php require_once 'elements/nav_menu.php'; ?> 

<header class="bg-primary text-white">
	<div class="container text-center">
		<h1>USER ACCOUNT <?php echo ($userLoggedIn == 0)?'LOGIN':''; ?></h1>
	</div>
</header>
<section id="about">
	<div class="container">
		<div class="row">
			<div class="col-lg-12 mx-auto">
				<div class="user-box">
					<?php if($userLoggedIn == 1){ ?>
					<div class="menu">
						<span class="menu-icon"><a href="javascript:void(0);"><i></i><i></i><i></i></a></span>	
						<ul class="nav1">
							<li><a href="<?php echo BASE_URL; ?>profile.php">Profile</a></li>
							<li><a href="<?php echo BASE_URL; ?>settings.php">Settings</a></li>
						</ul>
					</div>
					<div class="regisFrm">
						<div class="content-wrap">
							<div class="left">
								<img src="<?php echo $userPicture; ?>" alt="<?php echo $userName; ?>" class="img-responsive">
							</div>
							<div class="right">
								<p><b>Name: </b><?php echo $userName; ?></p>
								<p><b>Email: </b><?php echo $userData['email']; ?></p>
								<p><b>Username: </b><?php echo $userData['username']; ?></p>
								<p><b>Date of Birth: </b><?php echo $userData['dob']; ?></p>
								<p><b>Affiliation: </b><?php echo $userData['affiliation']; ?></p>
							</div>
						</div>
					</div>
					<?php }else{ ?>
					<h2>Login to Your Account</h2>
					<?php echo !empty($statusMsg)?'<p class="statusMsg '.$statusMsgType.'">'.$statusMsg.'</p>':''; ?>
					<div class="regisFrm">
						<form action="<?php echo BASE_URL; ?>userAccount.php" method="post">
							<input type="text" name="username" placeholder="Username" required="">
							<input type="password" name="password" placeholder="Password" required="">
							<div class="cw_remember">
								<div class="cw_remember_left">
								<div class="check">
									<label class="checkbox"><input type="checkbox" name="rememberMe" value="1"><i> </i>remember me</label>
								</div>
								</div>
								<div class="cw_remember_right">
									<a href="<?php echo BASE_URL; ?>forgotPassword.php">Forgot Password?</a>
								</div>
								<div class="clear"> </div>
							</div>
							<div class="send-button">
								<input type="submit" name="loginSubmit" value="LOGIN">
							</div>
						</form>
						<p class="mrt-10">Don't have an account? <a href="<?php echo BASE_URL; ?>registration.php">Sign Up</a></p>
					</div>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
</section>

<!-- Footer -->
<?php require_once 'elements/footer.php'; ?> 

</body>
</html>