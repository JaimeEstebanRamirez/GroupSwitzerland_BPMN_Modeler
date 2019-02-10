<?php 
//get session data
$sessData = !empty($_SESSION['sessData'])?$_SESSION['sessData']:'';

//redirect to homepage if user not logged in
if(!empty($sessData['userLoggedIn']) && !empty($sessData['userID'])){
	$userLoggedIn = 1;
	$sessUserId = $sessData['userID'];
	
	include_once 'User.class.php';
	$user = new User();
	$conditions['where'] = array(
		'id' => $sessData['userID'],
	);
	$conditions['return_type'] = 'single';
	$userData = $user->getRows($conditions);
    
	$loggedInUserID = $userData['id'];
    $userPicture = !empty($userData['picture'])?UPLOAD_URL.'profile_picture/'.$userData['picture']:IMG_URL.'no-profile-pic.png';
    $userName = $userData['first_name'].' '.$userData['last_name'];
}else{
	if(basename($_SERVER['PHP_SELF']) != 'index.php'){
		header("Location: ".BASE_URL);
	}
}