<?php
require('localVars.php');
require($root_dir . 'vendor/autoload.php');
require($root_dir . 'config/wpcc_groupurl.php');
require($root_dir . 'lib/wpccCache.php');

try {
    $wpccCache = new wpccCache();
    $wpccCache->generateCache($groupUrl, 'all');
} catch (Exception $e) {
    die ('ERROR: ' . $e->getMessage());
}

?>