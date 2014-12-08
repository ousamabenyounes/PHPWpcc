<?php
require('localVars.php');
require($root_dir . 'vendor/autoload.php');
require($root_dir . 'config/wpcc_groupurl.php');
require($root_dir . 'lib/wpccCache.php');

try {
    if (isset($argv[1]) &&
        in_array(
            $argv[1],
            array('all', 'screenshot', 'content')
        )
    )
    {
        $type = $argv[1];

    } else {
        $type = 'content';
    }
    $wpccCache = new wpccCache($root_dir);
    $wpccCache->generateCache($groupUrl, $type);
} catch (Exception $e) {
    die ('ERROR: ' . $e->getMessage());
}

?>