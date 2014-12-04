<?
require('localVars.php');
require $root_dir . "vendor/autoload.php";
require($root_dir . 'lib/wpccUi.php');
require($root_dir . 'config/wpcc_config.php');

if (0 === sizeof($_POST) && 0 === sizeof($webParsingConfig)) {
   $ui = new wpccUi();
   $ui->createForm();
}

if (0 === sizeof($_POST) && 0 !== sizeof($webParsingConfig)) {
   $ui = new wpccUi($webParsingConfig);
   $ui->updateForm();
}


if (0 !== sizeof($_POST) 
   && 'create' === $_POST['formType']) {
   $ui = new wpccUi($_POST["service"], $_POST["nbConfig"]);
   $ui->createFormStep2();
} 

/*if (0 !== sizeof($_POST)
   && 'generate' === $_POST['formType']) {
   $web = new UI($_POST);
   $web->generateConfig();
//   $outPut = $web->generatePhpunitTest();
}*/


?>