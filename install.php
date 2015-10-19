<?php

namespace Phpwpcc;

require('config/wpcc_services.php');
require('config/wpcc_groupurl.php');
require('config/wpcc_config.php');
require('vendor/autoload.php');
    
try {
    $config = new Config($servicesConfig);
    $action = Utils::getVar('action');
    $wpccTests = new Tests (
                    $phpwpcc_config["projectName"],
                    $groupUrl,
                    $servicesConfig
         );

    if (0 !== sizeof($_POST))
    {
        $nextStep = Utils::getVar('nextStep', Utils::POST);
        // ************ Service Configuration Form ************ //
        if ('configureServicesForm' === $nextStep
            && 0 !== sizeof($servicesConfig)) {
            $oldProjectName = $phpwpcc_config['projectName'];
            $config->configureProjectGenerate();
            require('config/wpcc_config.php');
	    $newProjectName = Config::getVarFromConfig('projectName');
	    $wpccTests->regenerateTests($newProjectName, $oldProjectName);
        }
    }
    $config->configureProjectForm($phpwpcc_config);

} catch (\Exception $e) {
    die ('ERROR: ' . $e->getMessage());
}

