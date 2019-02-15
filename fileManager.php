<?php
//start session
session_start();

//config file
include_once 'config.php';

$userLoggedIn = $loggedInUserID = 0;

//validate login
require_once 'validate_login.php';

// Load pagination class
require_once 'Pagination.class.php';

// Load and initialize database class
require_once 'File.class.php';
$file = new File();

// Page offset and limit
$offset = !empty($_GET['page'])?(($_GET['page']-1)*PER_PAGE_LIMIT):0;

// Get search keyword
$searchKeyword = !empty($_GET['searchKeyword'])?$_GET['searchKeyword']:'';
$searchStr = !empty($searchKeyword)?'?searchKeyword='.$searchKeyword:'';

// Search DB query
$searchArr = '';
if(!empty($searchKeyword)){
    $searchArr = array(
        'name' => $searchKeyword
    );
}

// Get count of the users
$con = array(
	'where' => array('user_id' => $loggedInUserID),
    'like' => $searchArr,
    'return_type' => 'count'
);
$rowCount = $file->getRows($con);

// Initialize pagination class
$pagConfig = array(
    'baseURL' => BASE_URL.'fileManager.php'.$searchStr,
    'totalRows' => $rowCount,
    'perPage' => PER_PAGE_LIMIT
);
$pagination = new Pagination($pagConfig);

// Get users from database
$con['return_type'] = 'all';
$con['start'] = $offset;
$con['limit'] = PER_PAGE_LIMIT;
$userFiles = $file->getRows($con);

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
	<title>BPMN Files Manager | <?php echo SITE_NAME; ?></title>
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
		<h1>MANAGE BPMN FILES</h1>
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
			<div class="col-md-12 search-panel">
				<!-- Search form -->
				<form>
					<div class="input-group mb-3">
						<input type="text" name="searchKeyword" class="form-control" placeholder="Search by Name..." value="<?php echo $searchKeyword; ?>">
						<div class="input-group-append">
						  <input type="submit" name="submitSearch" class="btn btn-outline-secondary" value="Search">
						  <a href="<?php echo BASE_URL.'fileManager.php'; ?>" class="btn btn-outline-secondary">Reset</a>
						</div>
					</div>
				</form>
				
				<!-- Add link -->
				<div class="float-right">
          <a href="<?php echo BASE_URL.'fileAddEdit.php'; ?>" class="btn btn-success"><i class="upf"></i> Upload File</a>
					<a href="<?php echo BASE_URL.'modeler.php'; ?>" class="btn btn-success"><i class="plus"></i> New File</a>
				</div>
			</div>
			
			<!-- Data list table --> 
			<table class="table table-striped table-bordered">
				<thead class="thead-dark">
					<tr>
						<th>File Name</th>
						<th>Created</th>
						<th>Modified</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php
					if(!empty($userFiles)){ foreach($userFiles as $row){
					?>
					<tr>
						<td><?php echo $row['name']; ?></td>
						<td><?php echo $row['created']; ?></td>
						<td><?php echo $row['modified']; ?></td>
						<td>
							<a href="<?php echo BASE_URL.'modelerView.php?id='.$row['id']; ?>" target="_blank" class="btn btn-primary">view</a>
							<a href="<?php echo BASE_URL.'modeler.php?id='.$row['id']; ?>" class="btn btn-warning">edit</a>
							<a href="<?php echo BASE_URL.'fileAction.php?action_type=delete&id='.$row['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure to delete?')">delete</a>
						</td>
					</tr>
					<?php } }else{ ?>
					<tr><td colspan="4">No file(s) found...</td></tr>
					<?php } ?>
				</tbody>
			</table>
			
			<!-- Display pagination links -->
			<div class="pagination pull-right">
				<?php echo $pagination->createLinks(); ?>
			</div>
		</div>
	</div>
</section>

<!-- Footer -->
<?php require_once 'elements/footer.php'; ?> 

</body>
</html>