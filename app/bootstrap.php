<?php
// Autoload Core Libraries

spl_autoload_register(static function($class_name){
    $dirs = array(
            'models/',
            'libraries/',
            'classes/'
        );
    foreach( $dirs as $dir ) {
        if (!file_exists('../app/'. $dir.$class_name.'.php')) {
            continue;
        }
        require_once('../app/'.$dir.$class_name.'.php');
    }
});
require_once '../vendor/autoload.php';
require_once '../app/config/host.php';
require_once '../app/config/config.php';
require_once 'helpers/url_helper.php';
require_once 'helpers/session_helper.php';
require_once 'helpers/misc_helper.php';
require_once 'helpers/validation_helper.php';
require_once 'helpers/init.php';
