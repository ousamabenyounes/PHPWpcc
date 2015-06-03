<?
namespace Wpcc;

require('config/wpcc_services.php');
require('config/wpcc_groupurl.php');
require('config/wpcc_config.php');
require('autoload.php');

try {
    $wpccService = new Service();

    if (0 !== sizeof($_POST))
    {
        $choosenUrlArray = array_map('trim', explode(',', $_POST['choosenUrl']));
        $wpccService->attachUrlWithServicesGenerate($choosenUrlArray, $_POST['service'], $groupUrl);
        require('config/wpcc_groupurl.php'); // Refresh configuration before generating tests
	$wpccTests = new Tests (
         	     $phpwpcc_config["projectName"],
		     $groupUrl,
		     $servicesConfig
        );	
	$wpccTests->regenerateTests(Config::getVarFromConfig('projectName'));
    }
    if (isset($_POST['service']))
    {
        $service = $_POST["service"];
    } else {
        $service = Utils::getVar("p");
    }
    if ('' !== trim($service))
    {
        $wpccService->attachUrlWithServices($groupUrl, $service, $servicesConfig);
    }

} catch (Exception $e) {
    die ('ERROR: ' . $e->getMessage());
}

