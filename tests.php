<?

namespace Wpcc;

require('class/Autoloader.php');
require('config/wpcc_services.php');
require('config/wpcc_groupurl.php');
require('config/wpcc_config.php');


try {
    $action = Utils::getVar('action');
    $wpccTests = new Tests(
        $phpwpcc_config["projectName"],
        $groupUrl,
        $servicesConfig
    );
    $wpccTests->printIndex();
} catch (Exception $e) {
    die ('ERROR: ' . $e->getMessage());
}

?>