<?
require('localVars.php');
require $root_dir . "vendor/autoload.php";
require($root_dir . 'config/wpcc_config.php');
require($root_dir . 'config/wpcc_services.php');
require($root_dir . 'config/wpcc_groupurl.php');
require($root_dir . 'lib/wpccService.php');
require($root_dir . 'lib/wpccFile.php');
require($root_dir . 'lib/wpccTwig.php');

try {
    $wpccService = new wpccService($root_dir);

    if (0 !== sizeof($_POST))
    {
        $choosenUrlArray = array_map('trim', explode(',', $_POST['choosenUrl']));

        $wpccService->attachUrlWithServicesGenerate($phpwpcc_config["projectName"],
             $servicesConfig, $choosenUrlArray, $_POST['service']);
        require($root_dir . 'config/wpcc_groupurl.php');


    }
    if (isset($_POST['service']))
    {
        $service = $_POST["service"];
    } else {
        $service = strip_tags($_GET["p"]);
    }
    if ('' !== trim($service))
    {
        $wpccService->attachUrlWithServices($groupUrl, $service, $servicesConfig);
    }


} catch (Exception $e) {
    die ('ERROR: ' . $e->getMessage());
}

?>
