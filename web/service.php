<?
require('localVars.php');
require $root_dir . "vendor/autoload.php";
require($root_dir . 'lib/wpccUi.php');
require($root_dir . 'lib/wpccFile.php');
require($root_dir . 'config/wpcc_services.php');
require($root_dir . 'config/wpcc_groupurl.php');

try {
    $ui = new wpccUi($root_dir);


    $ui->attachUrlWithServices($groupUrl);

    die;
    if (0 === sizeof($_POST))
    {
        // ************ Main configuration Form *************** //
        $ui->configureProjectForm();
    } else {
        $nextStep = $_POST['nextStep'];

        // ************ Service Configuration Form ************ //
        if ('configureServicesForm' === $nextStep
                && 0 === sizeof($servicesConfig)) {
            $ui->configureProjectGenerate();
            $ui->configureServicesForm();
        }

        // ************ Service Configuration Form ************ //
        if ('configureServicesForm' === $nextStep
            && 0 !== sizeof($servicesConfig)) {
            $ui->attachUrlWithServices($groupUrl);
        }

        // ************ Service Configuration Form ************ //
        if ('configureServicesFormStep2' === $nextStep
            && 0 === sizeof($servicesConfig)) {
            $ui = new wpccUi($root_dir, $_POST["service"], $_POST["nbConfig"]);
            $ui->configureServicesFormStep2();
        }

        // ************ Service Configuration Generation s************ //
        if ('configureServicesFormGenerate' === $nextStep) {
            $ui->configureServicesGenerate($groupUrl);

        }
    }

} catch (Exception $e) {
    die ('ERROR: ' . $e->getMessage());
}

?>
