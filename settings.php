<?php
// Settings File
$settings = array();

// Path settings
$settings['path'] = dirname(__FILE__);
$settings['media_path'] = $settings['path'] . '/media/';
$settings['local_settings'] = $settings['path']. "/local_settings.php";


// Settings Database
$settings['db_diver'] = 'mysql';
$settings['hostname'] = 'localhost';
$settings['database'] = 'chimpanze';
$settings['username'] = 'root';
$settings['password'] = '';

// Include local_settings
if(file_exists($settings['local_settings'])){
    require_once($settings['local_settings']);
}
