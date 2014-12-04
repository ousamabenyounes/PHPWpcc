<?
require('localVars.php');
require $root_dir . "vendor/autoload.php";
require($root_dir . 'lib/wpccUi.php');

try {

    if (0 !== sizeof($_POST))
    {
        $ui = new wpccUi();
        $nextStep = $_POST['nextStep'];
        if ('configureServicesForm' === $nextStep
                && 0 === sizeof($webParsingConfig)) {
            $ui->configureProjectGenerate($_POST);
            $ui->configureServicesForm();
        }
    } else {
        $ui = new wpccUi();
        $ui->configureProjectForm();
    }

} catch (Exception $e) {
    die ('ERROR: ' . $e->getMessage());
}

?>
