<?
require('localVars.php');
require $root_dir . "vendor/autoload.php";
require($root_dir . 'lib/wpccUi.php');

if (0 !== sizeof($_POST))
{
	var_dump($_POST);
	die;
}


   $ui = new wpccUi();
   $ui->step1Form();



?>
