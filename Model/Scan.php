<?php

/**
 * Created by PhpStorm.
 * User: Grabe Grabe
 * Date: 1/2/2019
 * Time: 6:14 AM
 */
class Scan
{
    public function scanDirectory($folder){
        $dir = ROOT.'/'.$folder;
        $filesArray = array_values(array_diff(scandir($dir), array('.', '..')));

        $data = [];
        // check file extention
        for($i = 0; $i < count($filesArray); $i++){
            $file = pathinfo($filesArray[$i]);
            if(in_array($file['extension'], ALLOWED_FILE_EXTENSION)){
                $data[] = $file;
            }
        }



        return $data;
    }


    public function cleanString($string) {
        $string = str_replace(' ', '_', $string); // Replaces all spaces with hyphens.
        return preg_replace('/[^A-Za-z0-9\-.]/', '_', $string); // Removes special chars.
    }

}