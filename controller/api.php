<?php
include "../Model/Init.php";
include "../Model/Scan.php";
$scanClass = new Scan();
$action = $_GET['action'];

switch ($action){
    case 'scan':
        $files = $scanClass->scanDirectory($_POST['dir']);
        echo json_encode($files);
        break;
    case 'checkFile':
        $files = $_POST['files'];
        $data = [];
        foreach ($files as $file){
            // check image if exists on directories
            $fileName = $scanClass->cleanString($file['basename']);
            $fileExtension = $file['extension'];
            $fileDirectories = [];
            $fileOrientation = explode('_', $fileName)[0];
            if($fileOrientation == 'H' || $fileOrientation == 'V'){
                for($i = 0; $i < count(DIRECTORIES[$fileOrientation]); $i++){
                    $dirFiles = $scanClass->scanDirectory($scanClass->cleanString(DIRECTORIES[$fileOrientation][$i]['directory']));
                    $isExists = false;
                    foreach ($dirFiles as $dirFile){
                        if($fileName == $dirFile['basename']){
                            $isExists = true;
                        }
                    }

                    if($isExists == false){
                        $fileDirectories[] = DIRECTORIES[$fileOrientation][$i];
                    }
                }

                // delete if already moved to all directories
//            if(count($fileDirectories) == 0){
//                $dir = ROOT.'/Upload';
//                unlink($dir.'/'.$file['basename']);
//            }
                if(count($fileDirectories) > 0){
                    $data[] = [
                        'file' => $file['basename'],
                        'fileDirectory' => 'Upload/'.$file['basename'],
                        'ext' => $fileExtension,
                        'directories' => $fileDirectories
                    ];
                }
            }



        }

        echo json_encode($data);
        break;
    case 'move':
        $files = $_POST['data'];
        foreach ($files as $file){
            $source =  ROOT.'/Upload/'.$file['file'];
            $destinations = ROOT.'/'.$file['directory'].'/'.$scanClass->cleanString($file['file']);
            if(!copy($source, $destinations)){
                echo json_encode(false);
                exit;
            };
        }

        echo json_encode(true);

        break;

    case 'delete':
        $file = $_POST['data'];
        $dir = ROOT.'/Upload';
        unlink($dir.'/'.$file['file']);

        echo json_encode(true);

        break;
}