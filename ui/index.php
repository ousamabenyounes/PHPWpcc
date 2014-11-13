<?
require("wpcc_config.php");
require("ui.php");


if (0 === sizeof($_POST) && 0 === sizeof($webParsingConfig)) {
   $ui = new UI();
   $ui->createForm();
}

if (0 === sizeof($_POST) && 0 !== sizeof($webParsingConfig)) {
   $ui = new UI($webParsingConfig);
   $ui->updateForm();
}


if (0 !== sizeof($_POST) 
   && 'create' === $_POST['formType']) {
   $ui = new UI($_POST["service"], $_POST["nbConfig"]);
   $ui->createFormStep2();
} 

if (0 !== sizeof($_POST)
   && 'generate' === $_POST['formType']) {
   $ui = new UI($_POST);
   $ui->generateConfig();
//   $outPut = $ui->generatePhpunitTest();   
}


?>