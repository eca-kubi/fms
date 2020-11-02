<?php
// Autoload Core Libraries
spl_autoload_register(static function ($class_name) {
    $dirs = array(
        __DIR__ . "\models",
        __DIR__ . "\libraries",
        __DIR__ . "\classes"
    );
    foreach ($dirs as $dir) {
        $path = "$dir\\$class_name.php";
        if (!file_exists($path)) {
            continue;
        }
        require_once $path;
    }
});

require_once dirname(__DIR__) . "/vendor/autoload.php";

require_once __DIR__ . '/config/host.php';
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/helpers/url_helper.php';
require_once __DIR__ . '/helpers/session_helper.php';
require_once __DIR__ . '/helpers/misc_helper.php';
require_once __DIR__ . '/helpers/validation_helper.php';
