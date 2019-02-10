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
	<title>Reset Password | <?php echo SITE_NAME; ?></title>
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
		<h1>RESET ACCOUNT PASSWORD</h1>
	</div>
</header>
<section id="about">
	<div class="container">
		<div class="row">
			<div class="col-lg-12 mx-auto">
				<div class="user-box">
					<div class="regisFrm">
						<?php echo !empty($statusMsg)?'<p class="statusMsg '.$statusMsgType.'">'.$statusMsg.'</p>':''; ?>
						<form action="<?php echo BASE_URL; ?>userAccount.php" method="post">
							<input type="password" name="password" placeholder="Password" required=" ">
							<input type="password" name="confirm_password" placeholder="Confirm password" required=" ">
							<div class="send-button">
								<input type="hidden" name="fp_code" value="<?php echo $_REQUEST['fp_code']; ?>"/>
								<input type="submit" name="resetSubmit" value="Update Pasword">
							</div>
						</form>
						<p class="mrt-10">Don't want to reset? <a href="<?php echo BASE_URL; ?>">Sign In</a></p>
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