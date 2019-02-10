<?php
//start session
session_start();

//config file
include_once 'config.php';

$userLoggedIn = 0;

//validate login
require_once 'validate_login.php';

// Get user data
$fileData = array();
$fileID = 0;
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
    
    $fileID = $fileData['id'];
    $filePath = !empty($fileData['name'])?'uploads/files/'.$sessUserId.'/'.$fileData['name']:'assets/diagrams/diagram-starter.bpmn';
}else{
  header("Location: ".BASE_URL);
}

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>BPMN Modeler View | <?php echo SITE_NAME; ?></title>
	<meta name="description" content="" />
	<meta name="keywords" content="" />
	<link rel="stylesheet" href="//fonts.googleapis.com/css?family=Roboto:400,100,300,500,700,900" 	type="text/css" media="all">
	<link href="<?php echo BST_URL; ?>css/bootstrap.min.css" rel="stylesheet" type="text/css" media="all" />
	
    <!-- required modeler styles -->
    <link rel="stylesheet" href="https://unpkg.com/bpmn-js@3.0.4/dist/assets/diagram-js.css">
    <link rel="stylesheet" href="https://unpkg.com/bpmn-js@3.0.4/dist/assets/bpmn-font/css/bpmn.css">

    <!-- modeler distro -->
    <script src="https://unpkg.com/bpmn-js@3.1.0/dist/bpmn-viewer.development.js"></script>

    <!-- needed for this example only -->
    <!--<script src="https://unpkg.com/jquery@3.3.1/dist/jquery.js"></script>-->
	<script src="<?php echo JS_URL; ?>jquery.min.js"></script>
	
	<link href="<?php echo CSS_URL; ?>style.css" rel="stylesheet" type="text/css" media="all" />
	<link href="<?php echo CSS_URL; ?>modeler.css" rel="stylesheet" type="text/css" media="all" />
	
</head>
<body>
<!-- Navigation -->
<?php require_once 'elements/nav_menu.php'; ?> 
<section id="about" class="modeler-sec">
	<div class="container">
		<div class="canvas">
      <div id="js-canvas"></div>
    </div>

	</div>
</section>

<!-- Footer -->
<?php require_once 'elements/footer.php'; ?>


<script>
var diagramUrl = '<?php echo BASE_URL.$filePath; ?>';

var viewer = new BpmnJS({
  container: $('#js-canvas'),
  height: 600
});

function log(str) {
  var console = $('#js-console');
  console.val(console.val() + str + '\n');
}

function openFromUrl(url) {

  log('attempting to open <' + url + '>');

  $.ajax(url, { dataType : 'text' }).done(function(xml) {

    viewer.importXML(xml, function(err) {

      if (err) {
        log('error: ' + err.message);
        console.error(err);
      } else {
        viewer.get('canvas').zoom('fit-viewport');
        log('success');
      }
    });
  });
}

///// auto open ?url=diagram-url ///////////////////////

(function() {
    openFromUrl(diagramUrl);
})();
</script>
</body>
</html>