<?php
require "../../vendor/autoload.php";

class T {

 protected $_webParsingConfig;
 protected $_twig;
 protected $_nbFileConfig;

 public static $templateDir = '../../views';
 public static $projectName = 'phpwpcc';

 public function __construct($webParsingConfig = array(), $nbFileConfig = array()) {
       Twig_Autoloader::register();
       $loader = new Twig_Loader_Filesystem(self::$templateDir);
       $this->_twig = new Twig_Environment($loader, array('debug' => true));
       $this->_twig->addExtension(new Twig_Extension_Debug());

       $this->_webParsingConfig = $webParsingConfig;
       $this->_nbFileConfig = $nbFileConfig;

       /*
       $this->_twig->addExtension(new Twig_Extensions_Extension_I18n());
       // Set language to French
       putenv('LC_ALL=fr_FR');
       setlocale(LC_ALL, 'fr_FR');

       // Specify the location of the translation tables
       bindtextdomain(self::$projectName, self::$templateDir . '/lang');
       bind_textdomain_codeset(self::$projectName, 'UTF-8');

       // Choose domain
       textdomain(self::$projectName);
*/


	$this->_twig->addExtension(new Twig_Extensions_Extension_I18n());

       // Set language to French
       putenv('LC_ALL=fr_FR');
       setlocale(LC_ALL, 'fr_FR');

       // Specify the location of the translation tables
       bindtextdomain('myAppPhp', '/var/www/prestashoptest.fr/subdomains/cloud.prestashoptest.fr/web/lib/includes/locale');
       bind_textdomain_codeset('myAppPhp', 'UTF-8');

       // Choose domain
       textdomain('myAppPhp');
       echo gettext("Hello World");
       exit();

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

$t = new T();
$t->step1form();

?>