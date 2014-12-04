<?php
require('wpcc.php');

class wpccUi extends wpcc
{

 protected $_webParsingConfig;
 protected $_twig;
 protected $_nbFileConfig;

 public static $projectName = 'phpwpcc';

 public function __construct($webParsingConfig = array(), $nbFileConfig = array()) {
       Twig_Autoloader::register();
       $loader = new Twig_Loader_Filesystem(self::$root_dir . 'templates');
       $this->_twig = new Twig_Environment($loader);

       $this->_webParsingConfig = $webParsingConfig;
       $this->_nbFileConfig = $nbFileConfig;
 }

 public function createForm() {
  try {

     $template = $this->_twig->loadTemplate('phpwpcc_create.tpl');
     echo $template->render(array());

   } catch (Exception $e) {
   die ('ERROR: ' . $e->getMessage());
  }
	
 }

 public function createFormStep2() {
  try {
      $template = $this->_twig->loadTemplate('phpwpcc_create_step2.tpl');
      echo $template->render(array(
      'services' =>  $this->_webParsingConfig,
      'nbFileConfig' => $this->_nbFileConfig
      ));

   } catch (Exception $e) {
   die ('ERROR: ' . $e->getMessage());
   }

 }

 public function updateForm() {
  try {
      $template = $this->_twig->loadTemplate('phpwpcc_update.tpl');
      echo $template->render(array(
      'services' =>  $this->_webParsingConfig,
      ));

   } catch (Exception $e) {
   die ('ERROR: ' . $e->getMessage());
   }

 }

 public function step1Form() {
  try {
     $template = $this->_twig->loadTemplate('form/phpwpcc_step1.tpl');
     echo $template->render(array());

   } catch (Exception $e) {
   die ('ERROR: ' . $e->getMessage());
  }

 }




}

?>