<?
require('localVars.php');
require $root_dir . "vendor/autoload.php";
require($root_dir . 'lib/wpccUi.php');

try {

    if (0 !== sizeof($_POST))
    {

        $nextStep = $_POST['nextStep'];
        if ('configureServicesForm' === $nextStep
                && 0 === sizeof($webParsingConfig)) {
            $ui = new wpccUi();
            $ui->configureProjectGenerate($_POST);
            $ui->configureServicesForm();
        }
        if ('configureServicesFormStep2' === $nextStep
            && 0 === sizeof($webParsingConfig)) {
            $ui = new wpccUi($_POST["service"], $_POST["nbConfig"]);
            $ui->configureServicesFormStep2();
        }

    } else {
        $ui = new wpccUi();
        $ui->configureProjectForm();
    }

} catch (Exception $e) {
    die ('ERROR: ' . $e->getMessage());
}

?>
