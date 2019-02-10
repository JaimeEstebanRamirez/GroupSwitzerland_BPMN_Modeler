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
	<title>Profile Update | <?php echo SITE_NAME; ?></title>
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
		<h1>UPDATE PROFILE INFORMATION</h1>
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
								<form action="<?php echo BASE_URL; ?>userAccount.php" method="post" enctype="multipart/form-data">
								<input type="file" name="picture" placeholder="Choose Image File" value="">
								<input type="text" name="first_name" placeholder="Name" value="<?php echo !empty($userData['first_name'])?$userData['first_name']:''; ?>" required=" ">
								<input type="text" name="last_name" placeholder="Surname" value="<?php echo !empty($userData['last_name'])?$userData['last_name']:''; ?>" required=" ">
								<input type="email" name="email" placeholder="Email" value="<?php echo !empty($userData['email'])?$userData['email']:''; ?>" required=" ">
								<input type="text" name="username" placeholder="Username" value="<?php echo !empty($userData['username'])?$userData['username']:''; ?>" required>
                                
                                <?php
                                $dob_date = '';
                                $dob = !empty($userData['dob'])?$userData['dob']:'';
                                $dobArr = explode('-', $dob);
                                if(!empty($dobArr)){
                                    $dob_date = $dobArr[2];
                                    $dob_month = $dobArr[1];
                                    $dob_year = $dobArr[0];
                                    $dob_date = $dob_date.'/'.$dob_month.'/'.$dob_year;
                                }
                                ?>
                                
								<input type="text" name="dob" placeholder="Date of Birth (e.g. 19/09/1994)" value="<?php echo $dob_date; ?>">
								<input type="text" name="affiliation" placeholder="Affiliation" value="<?php echo !empty($userData['affiliation'])?$userData['affiliation']:''; ?>">
								<div class="send-button">
									<input type="submit" name="updateProfile" value="Update">
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