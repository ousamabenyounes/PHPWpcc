<?php
    header('Content-Type: application/json');
    require('localVars.php');
    require($root_dir . 'class/Autoloader.php');
    require($root_dir . 'config/wpcc_config.php');
    try {
        echo \Wpcc\Tests::launchPortailTest($phpwpcc_config['projectName'], $root_dir);
    } catch (\Exception $e) {
        echo json_encode(array('success' => false, 'content' => $e->getMessage()));
    }
