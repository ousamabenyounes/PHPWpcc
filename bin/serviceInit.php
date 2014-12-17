<?
require('localVars.php');
require $root_dir . "vendor/autoload.php";
require($root_dir . 'config/wpcc_config.php');
require($root_dir . 'config/wpcc_services.php');
require($root_dir . 'lib/wpccTests.php');
require($root_dir . 'lib/wpccFile.php');

try {
    $wpccTests = new wpccTests($root_dir);
    $wpccTests->generateServicesLib($phpwpcc_config["projectName"], $servicesConfig);

    

} catch (Exception $e) {
    die ('ERROR: ' . $e->getMessage());
}

?>
