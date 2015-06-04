<?php
namespace Wpcc;

require('localVars.php');
require($rootDir . 'config/wpcc_groupurl.php');
require($rootDir . 'config/wpcc_config.php');
require($rootDir . 'autoload.php');

try {
    if (isset($argv[1]) &&
            in_array(
                $argv[1],
                array('all', 'screenshot', 'content', 'screenshotRefresh')
            )
        )
    {
        $type = $argv[1];

    } else {
        $type = 'content';
    }
    $wpccCache = new Cache($rootDir);
    $wpccCache->generateCache($groupUrl, $type);
} catch (\Exception $e) {
    die ('ERROR: ' . $e->getMessage());
}
