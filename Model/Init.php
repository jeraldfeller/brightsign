<?php
DEFINE('ROOT', dirname(dirname(__FILE__)));
DEFINE('ALLOWED_FILE_EXTENSION', array('jpg', 'jpeg', 'png', 'mp4'));
DEFINE('DIRECTORIES',
    array(
        'H' => array(
            array('name' => 'Device Channel H', 'directory' => 'Device_H'),
            array('name' => 'Closing Station H', 'directory' => 'Closing_Station_H'),
            array('name' => 'General Horizontal I', 'directory' => 'Gen_H_One'),
            array('name' => 'General Horizontal II', 'directory' => 'Gen_H_Two'),
            array('name' => 'Prepaid', 'directory' => 'Prepaid'),
            array('name' => 'Motorola', 'directory' => 'Sponsored'),
        ),
        'V' => array(
            array('name' => 'Device Channel V', 'directory' => 'Device_V'),
            array('name' => 'Closing Station V', 'directory' => 'Closing_Station_V'),
            array('name' => 'General Vertical  I', 'directory' => 'Gen_V_One'),
            array('name' => 'General Vertical  II', 'directory' => 'Gen_V_Two'),
            array('name' => 'Mall Out-Facing', 'directory' => 'Mall_Out_V')
        )
    )
);