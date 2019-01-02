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
            for($i = 0; $i < count(DIRECTORIES); $i++){
                $dirFiles = $scanClass->scanDirectory($scanClass->cleanString(DIRECTORIES[$i]));
                $isExists = false;
                foreach ($dirFiles as $dirFile){
                    if($fileName == $dirFile['basename']){
                        $isExists = true;
                    }
                }

                if($isExists == false){
                    $fileDirectories[] = DIRECTORIES[$i];
                }
            }

            // delete if already moved to all directories
            if(count($fileDirectories) == 0){
                $dir = ROOT.'/Upload';
                unlink($dir.'/'.$file['basename']);
            }
            if(count($fileDirectories) > 0){
                $data[] = [
                    'file' => $file['basename'],
                    'fileDirectory' => 'Upload/'.$file['basename'],
                    'ext' => $fileExtension,
                    'directories' => $fileDirectories
                ];
            }
        }

        echo json_encode($data);
        break;
    case 'move':
        $files = $_POST['data'];
        foreach ($files as $file){
            $source =  ROOT.'/Upload/'.$file['file'];
            $destinations = ROOT.'/'.$scanClass->cleanString($file['directory']).'/'.$scanClass->cleanString($file['file']);
            if(!copy($source, $destinations)){
                echo json_encode(false);
                break;
            };
        }

        echo json_encode(true);

        break;
}