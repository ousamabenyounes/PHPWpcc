<?
namespace Wpcc;

require('class/Autoloader.php');
require('config/wpcc_services.php');
require('config/wpcc_groupurl.php');
require('config/wpcc_config.php');


try {
    $config = new Config($servicesConfig);
    $action = Utils::getVar('action');
    $wpccTests = new Tests (
                    $phpwpcc_config["projectName"],
                    $groupUrl,
                    $servicesConfig
         );

    if ('generateTests' === $action) {
	 $wpccTests->regenerateTests(Config::getVarFromConfig('projectName'));
    }
    if (0 !== sizeof($_POST))
    {
        $nextStep = Utils::getVar('nextStep', Utils::POST);
        // ************ Service Configuration Form ************ //
        if ('configureServicesForm' === $nextStep
            && 0 !== sizeof($servicesConfig)) {
            $oldProjectName = $phpwpcc_config['projectName'];
            $config->configureProjectGenerate();
            require('config/wpcc_config.php');
	    $wpccTests->regenerateTests(Config::getVarFromConfig('projectName'));
        }
    }
    $config->configureProjectForm($phpwpcc_config);

} catch (Exception $e) {
    die ('ERROR: ' . $e->getMessage());
}

?>
