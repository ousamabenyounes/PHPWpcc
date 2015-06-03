<?php

$rootDir = (isset($rootDir) ? $rootDir : '');

require($rootDir . 'class/Autoloader.php');
$autoload = new \Wpcc\Autoload($rootDir);
$autoload->register();

