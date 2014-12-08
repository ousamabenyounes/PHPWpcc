<?php
require('localVars.php');
require($root_dir . 'vendor/autoload.php');
require($root_dir . 'lib/wpccPngToJpg.php');

try {

    wpccPngToJpg::compress($root_dir . 'cache/20141204_00h/screenshot/', 'shop2toutcom');

} catch (Exception $e) {
    die ('ERROR: ' . $e->getMessage());
}

?>