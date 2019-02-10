<?php
//start session
session_start();

//config file
include_once 'config.php';

$userLoggedIn = 0;

//get session data
$sessData = !empty($_SESSION['sessData'])?$_SESSION['sessData']:'';

//redirect to homepage if user logged in
if(!empty($sessData['userLoggedIn']) && $sessData['userLoggedIn'] == true){
	header("Location: index.php");
}
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
	<title>Forgot Password | <?php echo SITE_NAME; ?></title>
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
		<h1>RECOVER ACCOUNT PASSWORD</h1>
	</div>
</header>
<section id="about">
	<div class="container">
		<div class="row">
			<div class="col-lg-12 mx-auto">
				<div class="user-box">
					<div class="regisFrm">
						<h5>Enter the email address you used to sign up and we'll send you a link to reset your password.</h5>
						<?php if(isset($_GET['frmDis']) && $_GET['frmDis'] == 0){ ?>
						<?php echo !empty($statusMsg)?'<p class="statusMsg '.$statusMsgType.'">'.$statusMsg.'</p>':''; ?>
						<h5>Didn’t receive the email? <a href="forgotPassword.php">Request reset link</a></h5>
						<?php }else{ ?>
						<?php echo !empty($statusMsg)?'<p class="statusMsg '.$statusMsgType.'">'.$statusMsg.'</p>':''; ?>
						<form action="<?php echo BASE_URL; ?>userAccount.php" method="post">
							<input type="email" name="email" placeholder="Email" required="">
							<div class="send-button">
								<input type="submit" name="forgotSubmit" value="Continue">
							</div>
						</form>
						<p class="mrt-10">Don't want to reset? <a href="<?php echo BASE_URL; ?>">Sign In</a></p>
						<?php } ?>
					</div>
				</div>
			</div>
			</div>
		</div>
	</div>
</section>

<!-- Footer -->
<?php require_once 'elements/footer.php'; ?> 

</body>
</html>