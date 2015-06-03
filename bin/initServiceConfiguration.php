<?php
namespace Wpcc;

require('localVars.php');
require($rootDir . 'config/wpcc_services.php');
require($rootDir . 'config/wpcc_config.php');
require($rootDir . 'config/wpcc_groupurl.php');
require($rootDir . 'autoload.php');


try {
    $serviceInit = new ServiceInit();
    $serviceInit->serviceInitBegin($rootDir);
    
    $serviceInitConfig = array();
    $serviceInitConfig[ServiceInit::PROJECTNAME] = $phpwpcc_config['projectName'];
    $serviceInitConfig[ServiceInit::SERVICECONFIGURATION] = $servicesConfig;
    $serviceInit->generateAllUrlConfig($serviceInitConfig, $groupUrl);
    
    $wpccTests = new Tests (
    	       	     	   $phpwpcc_config["projectName"],
			   $groupUrl,
			   $servicesConfig,
			   $rootDir
    );
    $wpccTests->regenerateTests(Config::getVarFromConfig('projectName', $rootDir));
    
} catch (Exception $e) {
    die ('ERROR: ' . $e->getMessage());
}

