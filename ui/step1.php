<?
require('lib/ui.php');

if (0 !== sizeof($_POST))
{
	var_dump($_POST);
	die;
}


   $ui = new UI();
   $ui->step1Form();



?>
