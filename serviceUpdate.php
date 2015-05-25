<?

namespace Wpcc;

require('class/Autoloader.php');
require('config/wpcc_services.php');
require('config/wpcc_groupurl.php');
require('config/wpcc_config.php');

try {
    $service = new Service();
    if (0 === sizeof($_POST))
    {
        if (0 !== sizeof($servicesConfig)) {
            $service = new Service($servicesConfig);
            $service->updateServiceForm($groupUrl);
        }

    } else {
        $nextStep = $_POST['nextStep'];

        if ('updateService' === $nextStep) {
            $service->updateService($_POST);
            require('config/wpcc_services.php'); // Refresh configuration before generating tests
            $wpccTests = new Tests (
                     $phpwpcc_config["projectName"],
                     $groupUrl,
                     $servicesConfig
            );
            $wpccTests->regenerateTests(Config::getVarFromConfig('projectName'));

	    $service = new Service($servicesConfig);
            $service->updateServiceForm($groupUrl);
	    
        }

/*        if ('configureServicesForm' === $nextStep
            && 0 === sizeof($servicesConfig)) {
            $service->configureProjectGenerate();
            $service->configureServicesForm();
        }

        if ('configureServicesForm' === $nextStep
            && 0 !== sizeof($servicesConfig)) {
            $service = new Service($servicesConfig);
            $service->updateForm($groupUrl);
        }

        if ('configureServicesFormStep2' === $nextStep
            && 0 === sizeof($servicesConfig)) {
            $service = new Service($_POST["service"], $_POST["nbConfig"]);
            $service->configureServicesFormStep2();
        }
*/

    }


} catch (Exception $e) {
    die ('ERROR: ' . $e->getMessage());
}

?>
