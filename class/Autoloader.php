<?php

namespace Wpcc;

class Autoload {

    private $rootDir;

    public function __construct($rootDir = '')
    {
	$this->rootDir = $rootDir;
    }

    public function autoload($className) {
        $filename = $this->rootDir . 'class/' . $className . '.php';
        $filename = str_replace(__NAMESPACE__. '\\', '', $filename);	
        if (is_readable($filename)) {
            require $filename;
        }
    }


    public function register() {
        require_once($this->rootDir . "vendor/autoload.php");
	spl_autoload_register(function($className) {
	    $this->autoload($className);
    	});
    }

}
