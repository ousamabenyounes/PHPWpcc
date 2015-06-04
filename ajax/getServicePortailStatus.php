<?php
    header('Content-Type: application/json');
    require('localVars.php');
    require($root_dir . 'class/Autoloader.php');
    echo \Wpcc\Tests::getServicePortailStatus($root_dir);
