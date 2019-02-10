<?php
//config file
include_once 'config.php';

$data = json_decode(file_get_contents('php://input'), true);

$status = 'err';
if(!empty($data['userID']) && !empty($data['xml'])){
    if(!empty($data['fileName'])){
        require_once 'File.class.php';
        $file = new File();
        
        $xmlData = $data['xml'];
        $userID = $data['userID'];
        $targetDir = 'uploads/files/'.$userID.'/';
        $fileName = str_replace('.bpmn', '', $data['fileName']).'.bpmn';
        $fileName = str_replace(' ', '-', $fileName);
        $fileID = $data['fileID'];
        
        if(!empty($fileID)){
            $conditions['where_not'] = array(
                'id' => $fileID
            );
            $conditions['where'] = array(
                'name' => $fileName
            );
            $conditions['return_type'] = 'count';
            $fileNameCount = $file->getRows($conditions);
        }else{
            $conditions['where'] = array(
                'name' => $fileName
            );
            $conditions['return_type'] = 'count';
            $fileNameCount = $file->getRows($conditions);
        }
        $conditions = array();
        if($fileNameCount > 0){
            $status = 'fn_err';
        }else{
            if(!empty($fileID)){
                $conditions['where'] = array(
                    'user_id' => $userID,
                    'id' => $fileID
                );
                $conditions['return_type'] = 'single';
                $fileDataPrev = $file->getRows($conditions);
                
                if(!empty($fileDataPrev)){
                    // create directory
                    if(!is_dir($targetDir)){
                        $oldmask = umask(0);
                        mkdir($targetDir, 0777, true);
                        umask($oldmask);      
                    }
                    
                    $fileName = file_exists($targetDir.$fileName) && ($fileName != $fileDataPrev['name'])?time().'_'.$fileName:$fileName;
                    $targetFilePath = $targetDir. $fileName;
                    
                    // create file
                    $myfile = fopen($targetFilePath, "w") or die("Unable to open file!");
                    fwrite($myfile, $xmlData);
                    fclose($myfile);
                    
                    // update file data
                    $fileData = array(
                        'name' => $fileName
                    );
                    $condition = array('id' => $fileID);
                    $update = $file->update($fileData, $condition);
                    if($update && !empty($fileDataPrev['name']) && file_exists($targetDir.$fileDataPrev['name']) && ($fileName != $fileDataPrev['name'])){
                        @unlink($targetDir.$fileDataPrev['name']);
                    }
                    $status = 'ok';
                }
            }else{
                // create directory
                if(!is_dir($targetDir)){
                    $oldmask = umask(0);
                    mkdir($targetDir, 0777, true);
                    umask($oldmask);      
                }
                
                $fileName = file_exists($targetDir.$fileName)?time().'_'.$fileName:$fileName;
                $targetFilePath = $targetDir. $fileName;
                
                // create file
                $myfile = fopen($targetFilePath, "w") or die("Unable to open file!");
                fwrite($myfile, $xmlData);
                fclose($myfile);
                
                // update file data
                $fileData = array(
                    'user_id' => $userID,
                    'name' => $fileName
                );
                $insert = $file->insert($fileData);
                $status = 'ok';
            }
        }
    }else{
        $status = 'f_err';
    }
}
$response = array('status'=>$status);
echo json_encode($response);
die();
?>