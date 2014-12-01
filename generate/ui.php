<?php
// include and register Twig auto-loader
include '../vendor/autoload.php';

class UI {

 protected $_webParsingConfig;
 protected $_twig;
 protected $_nbFileConfig;

 public function __construct($webParsingConfig, $nbFileConfig = array()) {
       Twig_Autoloader::register();
       $loader = new Twig_Loader_Filesystem('templates');
       $this->_twig = new Twig_Environment($loader, array('debug' => true));
       $this->_twig->addExtension(new Twig_Extension_Debug());
       $this->_webParsingConfig = $webParsingConfig;
       $this->_nbFileConfig = $nbFileConfig;
 }

 public function generateConfig() {
  try {
      foreach($this->_webParsingConfig as $service)

      $template = $this->_twig->loadTemplate('php/phpwpcc_config.php.tpl');
      $wpcc_config = $template->render(array(
      	   'post' =>  $this->_webParsingConfig,
      ));

      $dateString = date("YmdHis");
      file_put_contents("wpcc_config.php", $wpcc_config);
      file_put_contents("config_sav/wpcc_config_" . $dateString . ".php", $wpcc_config);

      $output = file_get_contents("http://ftven.jecodedoncjeteste.fr/wpccGenerate.php");

      $template = $this->_twig->loadTemplate('phpwpcc_generate.tpl');
      echo $template->render(array(
           'generate_message' =>  $output,
     ));

   } catch (Exception $e) {
     die ('ERROR: ' . $e->getMessage());
   }

 }


}

?>