<?php
function get_connection($settings){
    $db_string = $settings['db_diver'] . ':host=' . $settings['hostname'] . ';dbname=' . $settings['database'];
    $db = new PDO($db_string, $settings['username'], $settings['password']);
    return $db;
}
