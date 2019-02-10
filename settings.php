<?php
//start session
session_start();

//config file
include_once 'config.php';

$userLoggedIn = 0;

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
	<title>Password Update | <?php echo SITE_NAME; ?></title>
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
		<h1>UPDATE PASSWORD</h1>
	</div>
</header>
<section id="about">
	<div class="container">
		<div class="row">
			<div class="col-lg-12 mx-auto">
				<div class="user-box">
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
								<?php echo !empty($statusMsg)?'<p class="statusMsg '.$statusMsgType.'">'.$statusMsg.'</p>':''; ?>
								<form action="<?php echo BASE_URL; ?>userAccount.php" method="post">
								<input type="password" name="old_password" placeholder="Current password" value="" required=" ">
								<input type="password" name="password" placeholder="New password" value="" required=" ">
								<input type="password" name="confirm_password" placeholder="Confirm new password" value="" required=" ">
								<div class="send-button">
									<input type="submit" name="updatePassword" value="Update">
								</div>
								</form>
							</div>
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