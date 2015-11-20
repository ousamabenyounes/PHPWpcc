<?php

namespace Phpwpcc;

require('config/wpcc_services.php');
require('config/wpcc_groupurl.php');
require('config/wpcc_config.php');
require('vendor/autoload.php');
    

$error = new Error($serviceConfig);
$error->printIndex();

