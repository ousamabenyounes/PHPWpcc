<?php
require('localVars.php');
require($root_dir . 'vendor/autoload.php');
require($root_dir . 'lib/wpccFile.php');
require($root_dir . 'lib/wpccConfig.php');
require($root_dir . 'lib/wpccConfigLog.php');

class GroupUrl {

 protected $_twig;
 protected $_rootDir;


    /**
     * @param string $root_dir
     */
    public function __construct($root_dir) {
       $this->_rootDir = $root_dir;
       Twig_Autoloader::register();
       $loader = new Twig_Loader_Filesystem($this->_rootDir . 'views');
       $this->_twig = new Twig_Environment($loader, array('debug' => true));
       $this->_twig->addExtension(new Twig_Extension_Debug());
 }


 public function generateUrlConfig() {
  try {
      $json_url = "http://api.lereferentiel.francetv.fr/sites/";
      $json = file_get_contents($json_url);
      $sites = json_decode($json, TRUE);      
      $groupUrl = array();
      $tokens = array("videos", "emissions", "programme-tv");
      foreach ($sites as $site) {
      	  if ('En ligne' === $site['etat'] && "" !== trim($site["portail"])
              && !strstr($site['url'], 'programmes.france')
              && !strstr($site['url'], '-preprod')) {
              $site['url'] = trim(rtrim($site['url'], "/"));
             $pages = array($site['url']);
             foreach ($tokens as $token) {
                 $pages[] = trim($site["url"]) . '/' .$token;
             }
             $groupUrl[$site["portail"]][] = array($site["url"] => $pages);
          }
       }



      $template = $this->_twig->loadTemplate('php/phpwpcc_groupurl.php.tpl');
      $groupUrlContent = $template->render(array(
         'groupUrl' =>  $groupUrl
      ));

    wpccConfig::save($this->_rootDir, $groupUrlContent, 'wpcc_groupurl');

   } catch (Exception $e) {
     die ('ERROR: ' . $e->getMessage());
   }
  }
 

}

$groupurl = new GroupUrl($root_dir);
$groupurl->generateUrlConfig();

?>