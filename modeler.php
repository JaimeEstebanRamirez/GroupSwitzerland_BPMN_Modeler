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
}

$filePath = !empty($fileData['name'])?'uploads/files/'.$sessUserId.'/'.$fileData['name']:'assets/diagrams/diagram.bpmn';

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>BPMN Modeler | <?php echo SITE_NAME; ?></title>
	<meta name="description" content="" />
	<meta name="keywords" content="" />
	<link rel="stylesheet" href="//fonts.googleapis.com/css?family=Roboto:400,100,300,500,700,900" 	type="text/css" media="all">
	<link href="<?php echo BST_URL; ?>css/bootstrap.min.css" rel="stylesheet" type="text/css" media="all" />
	
	<!-- required modeler styles -->
    <link rel="stylesheet" href="https://unpkg.com/bpmn-js@3.0.4/dist/assets/diagram-js.css">
    <link rel="stylesheet" href="https://unpkg.com/bpmn-js@3.0.4/dist/assets/bpmn-font/css/bpmn.css">

    <!-- modeler distro -->
    <script src="https://unpkg.com/bpmn-js@3.0.4/dist/bpmn-modeler.development.js"></script>

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
		<div id="canvas"></div>

		<button id="save-button">Save BPMN Diagram</button>
	</div>
</section>

<!-- Footer -->
<?php require_once 'elements/footer.php'; ?>


<!-- Modal -->
<div class="modal fade" id="fileInfoModal" tabindex="-1" role="dialog" aria-labelledby="fileModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="fileModalLabel">BPMN Diagram Info</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label>File Name</label>
          <input type="text" class="form-control" id="fileName" placeholder="Without extension" value="<?php echo !empty($fileData['name'])?str_replace('.bpmn', '', $fileData['name']).'.bpmn':''; ?>" >
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="modalSaveFile">Save File</button>
      </div>
    </div>
  </div>
</div>


<script src="<?php echo BST_URL; ?>js/bootstrap.min.js"></script>
<script> 
var diagramUrl = '<?php echo $filePath; ?>';
// modeler instance
var bpmnModeler = new BpmnJS({
  container: '#canvas',
  keyboard: {
	bindTo: window
  }
});

/**
 * Save diagram contents and print them to the console.
 */
function exportDiagram() {
  bpmnModeler.saveXML({ format: true }, function(err, xml) {
	if (err) {
    alert('Could not save BPMN 2.0 diagram.');
	  return console.error('could not save BPMN 2.0 diagram', err);
	}
	//alert('Diagram exported. Check the developer tools!');
	sendDiagram(xml);
	//console.log('DIAGRAM', xml);
  });
}
/**
 * Open diagram in our modeler instance.
 *
 * @param {String} bpmnXML diagram to display
 */
function openDiagram(bpmnXML) {
  // import diagram
  bpmnModeler.importXML(bpmnXML, function(err) {
	if (err) {
	  return console.error('could not import BPMN 2.0 diagram', err);
	}
	// access modeler components
	var canvas = bpmnModeler.get('canvas');
	var overlays = bpmnModeler.get('overlays');
	// zoom to fit full viewport
	canvas.zoom('fit-viewport');
	// attach an overlay to a node
	overlays.add('SCAN_OK', 'note', {
	  position: {
		bottom: 0,
		right: 0
	  },
	  html: '<div class="diagram-note">Mixed up the labels?</div>'
	});
	// add marker
	canvas.addMarker('SCAN_OK', 'needs-discussion');
  });
}
// load external diagram file via AJAX and open it
$.get(diagramUrl, openDiagram, 'text');
// wire save button
//$('#save-button').click(exportDiagram);
$('#save-button').click(openFileModal);

function sendDiagram(xml){
  var fileID = <?php echo $fileID; ?>;
  var fileName = $('#fileName').val();
  $.ajax({
	type: "POST",
	url: 'save_diagram_ajax.php',
	data: '{"userID":<?php echo $sessUserId; ?>,"fileID":'+fileID+',"fileName":"'+fileName+'","xml": ' + JSON.stringify(xml) + '}',
	contentType: "application/json; charset=utf-8",
	dataType: "json",
	success: function (response) {
    var resp = response;
    console.log(resp);
    if(resp.status == 'ok'){
      alert('The file has been saved successfully.');
      $('#fileInfoModal').modal('hide');
      //if(fileID == 0){
        window.location.href = '<?php echo BASE_URL.'fileManager.php'; ?>';
      //} 
    }else if(resp.status == 'fn_err'){
      alert('Given file name already exists, please enter a different name.');
    }else if(resp.status == 'f_err'){
      alert('Please enter the file name.');
    }else{
      alert('Some problem occurred, please try again.');
      $('#fileInfoModal').modal('hide'); 
    }
	  
	},
	failure: function (response) {
	  //alert(response.d);
    alert('Some problem occurred, please try again.');
    $('#fileInfoModal').modal('hide');
	},
	error: function (response) {
	  //alert(response.d);
    alert('Some problem occurred, please try again.');
    $('#fileInfoModal').modal('hide');
	}
  });
}


function openFileModal(){
  $('#fileInfoModal').modal('show'); 
}

$('#modalSaveFile').click(exportDiagram);
</script>
</body>
</html>