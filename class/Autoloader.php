<?php

namespace Wpcc;

use Symfony\Component\HttpFoundation\Request;

class Autoload {

    static function autoload($className) {
        global $root_dir;

        $filename = $root_dir . 'class/' . $className . '.php';
        $filename = str_replace(__NAMESPACE__. '\\', '', $filename);	
        if (is_readable($filename)) {
            require $filename;
        }
    }

    static function register($rootDir = '') {
        require_once($rootDir . "vendor/autoload.php");
        spl_autoload_register(array(__CLASS__, 'autoload'));
    }

}
if (!isset($root_dir)) {
    $root_dir = '';
}

Autoload::register($root_dir);

