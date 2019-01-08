<?php
include "../Model/Init.php";
include "../Model/Scan.php";
$scanClass = new Scan();
$action = $_GET['action'];

switch ($action) {
    case 'scan':
        $files = $scanClass->scanDirectory($_POST['dir']);
        echo json_encode($files);
        break;
    case 'checkFileSingle':
        $data = [];
        if(isset($_POST['files'])){
            $files = $_POST['files'];
            $dir = $_POST['dir'];
            foreach ($files as $file) {
                // check image if exists on directories
                $fileName = $scanClass->cleanString($file['basename']);
                $fileExtension = $file['extension'];
                $fileDirectories = [];
                $dirFiles = $scanClass->scanDirectory($scanClass->cleanString($dir));
                $fileOrientation = explode('_', $fileName)[0];
                $data[] = [
                    'orientation' => $fileOrientation,
                    'file' => $file['basename'],
                    'fileDirectory' => $dir.'/'.$file['basename'],
                    'ext' => $fileExtension,
                    'directory' => $dir
                ];
            }
        }
        echo json_encode($data);
        break;
    case 'checkFile':
        $files = $_POST['files'];
        $data = [];
        foreach ($files as $file) {
            // check image if exists on directories
            $fileName = $scanClass->cleanString($file['basename']);
            $fileExtension = $file['extension'];
            $fileDirectories = [];
            $fileOrientation = explode('_', $fileName)[0];
            if ($fileOrientation == 'H' || $fileOrientation == 'V') {
                for ($i = 0; $i < count(DIRECTORIES[$fileOrientation]); $i++) {
                    $dirFiles = $scanClass->scanDirectory($scanClass->cleanString(DIRECTORIES[$fileOrientation][$i]['directory']));
                    $isExists = false;
                    foreach ($dirFiles as $dirFile) {
                        if ($fileName == $dirFile['basename']) {
                            $isExists = true;
                        }
                    }

                    if ($isExists == false) {
                        $fileDirectories[] = DIRECTORIES[$fileOrientation][$i];
                    }
                }

                // delete if already moved to all directories
//            if(count($fileDirectories) == 0){
//                $dir = ROOT.'/Upload';
//                unlink($dir.'/'.$file['basename']);
//            }
                if (count($fileDirectories) > 0) {
                    $data[] = [
                        'orientation' => $fileOrientation,
                        'file' => $file['basename'],
                        'fileDirectory' => 'Upload/' . $file['basename'],
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
        $action = $_POST['action'];
        foreach ($files as $file) {
            if($action == 0){
                $source = ROOT . '/Upload/' . $file['file'];
                $destinations = ROOT . '/' . $file['directory'] . '/' . $scanClass->cleanString($file['file']);
            }else{
                // its a move back to UPLOAD, delete after
                $source = ROOT . '/' . $file['directory'] . '/' . $scanClass->cleanString($file['file']);
                $destinations = ROOT . '/Upload/' . $file['file'];
            }

            if (!copy($source, $destinations)) {
                echo json_encode(false);
                exit;
            }else{
                if($action == 1){
                    unlink($source);
                }
            }
        }

        echo json_encode(true);

        break;

    case 'delete':
        $file = $_POST['data'];
        $dir = (isset($_POST['data']['dir']) ? $_POST['data']['dir'] : 'Upload');
        $dir = ROOT . '/'.$dir;
        unlink($dir . '/' . $file['file']);

        echo json_encode(true);

        break;
}