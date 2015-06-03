<?
namespace Wpcc;

require('config/wpcc_services.php');
require('config/wpcc_groupurl.php');
require('config/wpcc_config.php');
require('autoload.php');

try {
    $groupUrlObj = new GroupUrl($groupUrl);
    if (0 === sizeof($_POST))
    {
        if (0 !== sizeof($groupUrl)) {
            $groupUrlObj = new GroupUrl($groupUrl);
            $groupUrlObj->updateGroupUrlForm();
        }

    } else {
        $nextStep = $_POST['nextStep'];
        if ('updateGroupUrl' === $nextStep) {

            $groupUrlObj->updategroupUrl($_POST);
            require($rootDir . 'config/wpcc_groupurl.php'); // Refresh configuration before generating tests
            $groupUrlObj = new GroupUrl($groupUrl);
            $groupUrlObj->updategroupUrlForm();
	    $wpccTests = new Tests (
					$phpwpcc_config["projectName"],
					$groupUrl,
					$servicesConfig
            );
	    $wpccTests->regenerateTests(Config::getVarFromConfig('projectName'));
        }


    }


} catch (Exception $e) {
    die ('ERROR: ' . $e->getMessage());
}

?>
