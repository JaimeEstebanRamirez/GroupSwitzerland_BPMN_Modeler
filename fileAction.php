<?php
// config file
include_once 'config.php';

//start session
session_start();

//load and initialize user class
include_once 'File.class.php';
$file = new File();

$allowEdit = 0;
$redirectURL = 'fileManager.php';
if(isset($_POST['fileSubmit']) && !empty($_SESSION['sessData']['userID'])){
	$sessData = $_SESSION['sessData'];
	$sessUserId = $sessData['userID'];
	
	// Get submitted data
	$id     = $_POST['id'];
	
	if(!empty($id)){
		$prevCon = array(
			'where' => array('id'=>$id),
			'return_type' => 'single'
		);
		$preFileData = $file->getRows($prevCon);
		if(!empty($preFileData) && $preFileData['user_id'] == $sessUserId){
			$allowEdit = 1;
		}
	}
	
	// Submitted user data
	$fileData = array();
	
	// Store submitted data into session
	$sessData['postData'] = $fileData;
	$sessData['postData']['id'] = $id;
	
	// ID query string
	$idStr = !empty($id)?'?id='.$id:'';
	
	// If the data is not empty
    if($_FILES['file']['name']!=""){
		
		//file upload
		$fileErr = 1;
		if(isset($_FILES['file']['name']) && $_FILES['file']['name']!=""){
			$targetDir = 'uploads/files/'.$sessUserId.'/';
			$fileName = basename($_FILES["file"]["name"]);
			
			if(!is_dir($targetDir)){
				$oldmask = umask(0);
				mkdir($targetDir, 0777, true);
				umask($oldmask);      
			}

			$fileName = file_exists($targetDir.$fileName)?time().'_'.$fileName:$fileName;
			$targetFilePath = $targetDir. $fileName;
			$fileType = pathinfo($targetFilePath,PATHINFO_EXTENSION);
			$fileType = strtolower($fileType);
			$allowTypes = array('bpmn');
			if(in_array($fileType, $allowTypes)){
				if(move_uploaded_file($_FILES["file"]["tmp_name"], $targetFilePath)){
					$fileData['name'] = $fileName;
					$fileErr = 0;
					//delete previous file
					if($allowEdit == 1 && !empty($preFileData['name'])){
						@unlink($targetDir.$preFileData['name']);
					}
				}
			}else{
				$sessData['status']['type'] = 'error';
				$sessData['status']['msg'] = 'Please select only BPMN file.';
				// Set redirect url
                $redirectURL = 'fileAddEdit.php'.$idStr;
			}
		}
		
		if($fileErr == 0){
			if($allowEdit == 1){
				// Update data
                $condition = array('id' => $id);
                $update = $file->update($fileData, $condition);
                
                if($update){
                    $sessData['postData'] = '';
                    $sessData['status']['type'] = 'success';
                    $sessData['status']['msg']  = 'File has been updated successfully.';
                }else{
                    $sessData['status']['type'] = 'error';
                    $sessData['status']['msg']  = 'Some problem occurred, please try again.';
                    
                    // Set redirect url
                    $redirectURL = 'fileAddEdit.php'.$idStr;
                }
            }else{
                // Insert data
				$fileData['user_id'] = $sessUserId;
                $insert = $file->insert($fileData);
                
                if($insert){
                    $sessData['postData'] = '';
                    $sessData['status']['type'] = 'success';
                    $sessData['status']['msg']  = 'File has been added successfully.';
                }else{

                    $sessData['status']['type'] = 'error';
                    $sessData['status']['msg']  = 'Some problem occurred, please try again.';
                    
                    // Set redirect url
                    $redirectURL = 'fileAddEdit.php';
                }
			}
		}
    }else{
        $sessData['status']['type'] = 'error';
        $sessData['status']['msg'] = 'All fields are mandatory, please fill all the fields.';
        
        // Set redirect url
        $redirectURL = 'fileAddEdit.php'.$idStr;
    }
	
	// Store status into the session
    $_SESSION['sessData'] = $sessData;
}elseif(($_REQUEST['action_type'] == 'delete') && !empty($_GET['id']) && !empty($_SESSION['sessData']['userID'])){
	$sessData = $_SESSION['sessData'];
	$sessUserId = $sessData['userID'];
	if(!empty($_GET['id'])){
		$prevCon = array(
			'where' => array('id'=>$_GET['id']),
			'return_type' => 'single'
		);
		$preFileData = $file->getRows($prevCon);
		if(!empty($preFileData) && $preFileData['user_id'] == $sessUserId){
			// Delete data
			$condition = array('id' => $_GET['id']);
			$delete = $file->delete($condition);
			if($delete){
				//delete previous file
				$targetDir = 'uploads/files/'.$sessUserId.'/';
				if(!empty($preFileData['name'])){
					@unlink($targetDir.$preFileData['name']);
				}
				
				$sessData['status']['type'] = 'success';
				$sessData['status']['msg'] = 'File has been deleted successfully.';
			}else{
				$sessData['status']['type'] = 'error';
				$sessData['status']['msg'] = 'Some problem occurred, please try again.';
			}
			
			// Store status into the session
			$_SESSION['sessData'] = $sessData;
		}
	}
}

// Redirect the user
header("Location: ".BASE_URL.$redirectURL);
exit();
