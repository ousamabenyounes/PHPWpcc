<?php

namespace Phpwpcc;

require('config/wpcc_services.php');
require('config/wpcc_groupurl.php');
require('config/wpcc_config.php');
require('vendor/autoload.php');

try {
    $action = Utils::getVar('action');
    $wpccTests = new Tests(
        $phpwpcc_config["projectName"],
        $groupUrl,
        $servicesConfig
    );
    $wpccTests->printIndex();
} catch (\Exception $e) {
  $error = new Error($e);
  $error->sendRedirection();
}
