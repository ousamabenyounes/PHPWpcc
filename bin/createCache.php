<?php
namespace Wpcc;

require('localVars.php');
require($root_dir . 'class/Autoloader.php');
require($root_dir . 'config/wpcc_groupurl.php');
require($root_dir . 'config/wpcc_config.php');


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
    $wpccCache = new Cache($root_dir);
    $wpccCache->generateCache($groupUrl, $type);
} catch (Exception $e) {
    die ('ERROR: ' . $e->getMessage());
}

?>