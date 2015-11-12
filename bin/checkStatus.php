<?php

namespace Phpwpcc;

require('localVars.php');
require($rootDir . 'config/wpcc_services.php');
require($rootDir . 'config/wpcc_groupurl.php');
require($rootDir . 'vendor/autoload.php');

try {
     $tests = new Tests('', $groupUrl, $servicesConfig);
     $mailContent = $tests->getReporting($rootDir);
     if (0 !== $tests->getNbTestsFailed() )
     {
        Mail::sendMail($mailContent, $rootDir);
     }

} catch (\Exception $e) {
    die ('ERROR: ' . $e->getMessage());
}
