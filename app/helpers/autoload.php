<?php
spl_autoload_register(static function ($class_name) {
    $dirs = array(
        '../models',
        '../libraries/',
        '../classes/'
    );
    foreach ($dirs as $dir) {
        if (!file_exists('../app/' . $dir . $class_name . '.php')) {
            continue;
        }
        require_once("../app/$dir" . "$class_name.php");
    }
});
require_once '../../vendor/autoload.php';
