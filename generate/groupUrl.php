<?php
// include and register Twig auto-loader
require '../vendor/autoload.php';
require('config/wpcc_groupurl.php');

class GroupUrl {

 protected $_twig;

 public function __construct() {
       Twig_Autoloader::register();
       $loader = new Twig_Loader_Filesystem('templates');
       $this->_twig = new Twig_Environment($loader, array('debug' => true));
       $this->_twig->addExtension(new Twig_Extension_Debug());
 }


 public function generateUrlConfig() {
  try {
      $json_url = "http://api.lereferentiel.francetv.fr/sites/";
      $json = file_get_contents($json_url);
      $sites = json_decode($json, TRUE);      
      $groupUrl = array();
      foreach ($sites as $site) {
      	  if ("En ligne" === $site["etat"] && "" !== trim($site["portail"])) {
             $groupUrl[$site["portail"]][] = $site["url"];
          }
       }

      $template = $this->_twig->loadTemplate('php/phpwpcc_groupurl.php.tpl');
      $groupUrlContent = $template->render(array(
         'groupUrl' =>  $groupUrl
      ));
      file_put_contents("config/wpcc_groupurl.php", $groupUrlContent);

   } catch (Exception $e) {
     die ('ERROR: ' . $e->getMessage());
   }
  }
 

}


$groupurl = new GroupUrl();
$groupurl->generateUrlConfig();

?>