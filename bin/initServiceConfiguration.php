<?php
namespace Wpcc;

require('localVars.php');
require($root_dir . 'class/Autoloader.php');
require($root_dir . 'config/wpcc_services.php');
require($root_dir . 'config/wpcc_config.php');
require($root_dir . 'config/wpcc_groupurl.php');

try {
    $serviceInit = new ServiceInit();
    $serviceInit->serviceInitBegin($root_dir);
    
    $serviceInitConfig = array();
    $serviceInitConfig[ServiceInit::PROJECTNAME] = $phpwpcc_config['projectName'];
    $serviceInitConfig[ServiceInit::SERVICECONFIGURATION] = $servicesConfig;
    $serviceInit->generateAllUrlConfig($serviceInitConfig, $groupUrl);
    
    $wpccTests = new Tests (
    	       	     	   $phpwpcc_config["projectName"],
			   $groupUrl,
			   $servicesConfig,
			   $root_dir
    );
    $wpccTests->regenerateTests(Config::getVarFromConfig('projectName', $root_dir));
    
} catch (Exception $e) {
    die ('ERROR: ' . $e->getMessage());
}

