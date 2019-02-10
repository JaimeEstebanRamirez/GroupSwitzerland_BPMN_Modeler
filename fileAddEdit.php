<?php
//start session
session_start();

//config file
include_once 'config.php';

$userLoggedIn = 0;

//validate login
require_once 'validate_login.php';

$fileData = $postData = array();

// Get posted data from session
if(!empty($sessData['postData'])){
    $postData = $sessData['postData'];
    unset($_SESSION['sessData']['postData']);
}

// Get user data
if(!empty($_GET['id'])){
    require_once 'File.class.php';
	$file = new File();
    $conditions['where'] = array(
		'user_id' => $loggedInUserID,
        'id' => $_GET['id']
    );
    $conditions['return_type'] = 'single';
    $fileData = $file->getRows($conditions);
	
	if(empty($fileData)){
		header("Location: ".BASE_URL);
	}
}

// Pre-filled data
$fileData = !empty($fileData)?$fileData:$postData;

// Define action
$actionLabel = !empty($_GET['id'])?'Edit':'Add';


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
	<title>File Add/Edit | <?php echo SITE_NAME; ?></title>
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
		<h1><?php echo strtoupper($actionLabel); ?> BPMN FILE</h1>
	</div>
</header>
<section id="about">
	<div class="container">
		<!-- Display status message -->
		<?php if(!empty($statusMsg) && ($statusMsgType == 'success')){ ?>
		<div class="col-xs-12">
			<div class="alert alert-success"><?php echo $statusMsg; ?></div>
		</div>
		<?php }elseif(!empty($statusMsg) && ($statusMsgType == 'error')){ ?>
		<div class="col-xs-12">
			<div class="alert alert-danger"><?php echo $statusMsg; ?></div>
		</div>
		<?php } ?>
		
		<div class="row">
			<div class="col-md-6">
				<form action="<?php echo BASE_URL; ?>fileAction.php" method="post" enctype="multipart/form-data">
					<div class="form-group">
						<label>BPMN File</label>
						<p><?php echo !empty($fileData['name'])?$fileData['name']:''; ?></p>
						<input type="file" class="form-control" name="file" placeholder="Select file">
					</div>
					<input type="hidden" name="id" value="<?php echo !empty($fileData['id'])?$fileData['id']:''; ?>">
					<a href="<?php echo BASE_URL; ?>fileManager.php" class="btn btn-secondary">Back</a>
					<input type="submit" name="fileSubmit" class="btn btn-success" value="Upload">
				</form>
			</div>
		</div>
	</div>
</section>

<!-- Footer -->
<?php require_once 'elements/footer.php'; ?> 

</body>
</html>