<?
require('localVars.php');
require $root_dir . "vendor/autoload.php";
require($root_dir . 'lib/wpccUi.php');
require($root_dir . 'lib/wpccFile.php');
require($root_dir . 'config/wpcc_services.php');
require($root_dir . 'config/wpcc_groupurl.php');

try {
    if (0 !== sizeof($_POST))
    {
        var_dump($_POST);
        die;
    }

    $service = $_GET["service"];

    $ui = new wpccUi($root_dir);
    $ui->attachUrlWithServices($groupUrl, $service);




} catch (Exception $e) {
    die ('ERROR: ' . $e->getMessage());
}

?>
