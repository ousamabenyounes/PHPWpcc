<?
require('localVars.php');
require $root_dir . "vendor/autoload.php";
require($root_dir . 'lib/wpccUi.php');

try {

    if (0 !== sizeof($_POST))
    {
        $nextStep = $_POST['nextStep'];
        //  Service Configuration Form 1/2
        if ('configureServicesForm' === $nextStep
                && 0 === sizeof($webParsingConfig)) {
            $ui = new wpccUi();
            $ui->configureProjectGenerate($_POST);
            $ui->configureServicesForm();
        }
        //  Service Configuration Form 2/2
        if ('configureServicesFormStep2' === $nextStep
            && 0 === sizeof($webParsingConfig)) {
            $ui = new wpccUi($_POST["service"], $_POST["nbConfig"]);
            $ui->configureServicesFormStep2();
        }
        if ('configureServicesFormGenerate' === $nextStep) {

        }
    } else {
        // Main configuration Form - First Step
        $ui = new wpccUi();
        $ui->configureProjectForm();
    }

} catch (Exception $e) {
    die ('ERROR: ' . $e->getMessage());
}

?>
