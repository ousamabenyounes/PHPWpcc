<?php
require('localVars.php');
require($root_dir . 'vendor/autoload.php');
require($root_dir . 'config/wpcc_groupurl.php');
require($root_dir . 'lib/wpccCache.php');

try {
    if (isset($argc[1]) &&
        in_array(
            $argc[1],
            array('all', 'screenshot', 'content')
        )
    ) {

    } else {
        $type = 'content';
    }
    $wpccCache = new wpccCache($root_dir);
    $wpccCache->generateCache($groupUrl, $type);
} catch (Exception $e) {
    die ('ERROR: ' . $e->getMessage());
}

?>