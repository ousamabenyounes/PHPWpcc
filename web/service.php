<?
require('localVars.php');
require $root_dir . "vendor/autoload.php";
require($root_dir . 'config/wpcc_config.php');
require($root_dir . 'config/wpcc_services.php');
require($root_dir . 'config/wpcc_groupurl.php');
require($root_dir . 'lib/wpccService.php');
require($root_dir . 'lib/wpccFile.php');

try {
    $wpccService = new wpccService($root_dir);

    if (0 !== sizeof($_POST))
    {
        var_dump($phpwpcc_config["projectName"]);
        var_dump($_POST);
        $wpccService->attachUrlWithServicesGenerate($phpwpcc_config["projectName"], $groupUrl, $servicesConfig, $_POST);

    } else {
        $service = strip_tags($_GET["p"]);
        if ('' !== trim($service))
        {
            $wpccService->attachUrlWithServices($groupUrl, $service, $servicesConfig);
        }
    }

} catch (Exception $e) {
    die ('ERROR: ' . $e->getMessage());
}

?>
