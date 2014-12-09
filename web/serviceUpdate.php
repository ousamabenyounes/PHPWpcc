<?
require('localVars.php');
require $root_dir . "vendor/autoload.php";
require($root_dir . 'config/wpcc_config.php');
require($root_dir . 'config/wpcc_services.php');
require($root_dir . 'config/wpcc_groupurl.php');
require($root_dir . 'lib/wpccService.php');
require($root_dir . 'lib/wpccFile.php');

try {
    $service = new wpccService($root_dir);
    if (0 === sizeof($_POST))
    {
        // ************ Service Configuration Form ************ //
        if (0 !== sizeof($servicesConfig)) {

            $service = new wpccService($root_dir, $servicesConfig);
            $service->updateService($groupUrl);
        }

    } else {
        die;
        $nextStep = $_POST['nextStep'];

        // ************ Service Configuration Form ************ //
        if ('configureServicesForm' === $nextStep
            && 0 === sizeof($servicesConfig)) {
            $service->configureProjectGenerate();
            $service->configureServicesForm();
        }

        // ************ Service Configuration Form ************ //
        if ('configureServicesForm' === $nextStep
            && 0 !== sizeof($servicesConfig)) {
            $service = new wpccService($root_dir, $servicesConfig);
            $service->updateForm($groupUrl);
        }

        // ************ Service Configuration Form ************ //
        if ('configureServicesFormStep2' === $nextStep
            && 0 === sizeof($servicesConfig)) {
            $service = new wpccService($root_dir, $_POST["service"], $_POST["nbConfig"]);
            $service->configureServicesFormStep2();
        }

        // ************ Service Configuration Generation s************ //
        if ('configureServicesFormGenerate' === $nextStep) {
            $service->configureServicesGenerate($groupUrl);

        }
    }


} catch (Exception $e) {
    die ('ERROR: ' . $e->getMessage());
}

?>
