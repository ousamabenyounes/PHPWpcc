<?
namespace Wpcc;

require('localVars.php');
require($root_dir . 'class/Autoloader.php');
require($root_dir . 'config/wpcc_services.php');
require($root_dir . 'config/wpcc_groupurl.php');

try {
     $tests = new Tests('', $groupUrl, $servicesConfig);
     $mailContent = $tests->getReporting($root_dir);
     if (0 !== $tests->getNbTestsFailed() )
     {
        Mail::sendMail($mailContent, $root_dir);
     } 

} catch (Exception $e) {
    die ('ERROR: ' . $e->getMessage());
}
