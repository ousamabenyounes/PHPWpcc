<?php

 class generateCheckFilesOnMySite {

 protected $_checkClassName = 'Ftven';
 protected $_outputDirectory = 'demo';

 protected $_globalUrls =  array(
                      'http://www.epitech.fr',
		      'http://www.supinfo.fr'		      
 ); // Missing pluzzVad
		
 protected $_webParsingConfig = array();
 protected $_phpOutput = '';
 protected $_files = array();
 protected $_urlsToCheck = array();
 protected $_serviceVersions = array();
 protected $_serviceName = '';

 public function __construct() {
 	$this->_webParsingConfig = array(
             'Jquery' => array(
                      'acceptedConfig' => array(
                              '1.0' => array(
			      		 'http://ajax.googleapis.com/ajax/libs/jquery/1.8/jquery.min.js'
                              ),
			      'supinfoJs' => array(
					 '/js/jquery-1.7.2.min.js'
			      )
                      ),
                      'urls' => $this->_globalUrls,
             ),	    

       );
  }


  // Initialise futur test class file content  
  public function generateMainClassBegin() {
  	 $this->_phpOutput = '<?php
require("' . $this->_checkClassName . 'Check.php");
class ' . $this->_checkClassName . 'Check' . $this->_serviceName  . 'Test extends ' . $this->_checkClassName . 'Check {

';

  }


  public function generateMainClassEnd() {
  	 $this->_phpOutput .= '
}
?>
';
  }


  public function addCheckMethod() {
 	$this->_phpOutput .=  '
   // Check if the given webSiteContent is compatible with allowed ' . $this->_serviceName . ' configuration
   // @params String $html Given html content
   // @return Boolean 
   public function ' . UCFirst($this->_checkClassName) . 'Check' . $this->_serviceName . '($html) {
';   
   foreach ($this->_files as $versionKey => $files) {
   $version = $this->_serviceVersions[$versionKey];   
        $this->_phpOutput .= '     // Check ' . $version . ' configuration
     if (';
        foreach ($files as $i => $file) {
	    $this->_phpOutput .= 'FALSE !== strpos($html, "' . $file. '" )';
	if (isset($files[$i + 1])) {
	   $this->_phpOutput .= ' && ';
	} else {
	  $this->_phpOutput .= ') {
          return true;
     }
';
	}

         }
   }
   $this->_phpOutput .= '     return false;
     }
';

  }





  public function getDomainWithoutExtention($webSiteUrl) {
      $domainName = str_replace('http://', '', $webSiteUrl);
      $domainName = str_replace('www', '', $domainName);
      $domainName = str_replace('.', '', $domainName);
      return $domainName;
  }

  public function getAsserts($urlsToCheck, $version) {
      $asserts = '';
      foreach ($urlsToCheck as $i => $url) {
         $asserts .= '         $this->assertContains($this->_var'  . UCFirst($this->_serviceName) . UCFirst($version) . $i  . ', $html);
';
     }
     return ($asserts);	 
  }

  public function addPhpUnitTests($acceptedConfig) {   

   foreach ($this->_urlsToCheck as $webSiteUrl) 
   {
       $domainName = $this->getDomainWithoutExtention($webSiteUrl);
       $this->_phpOutput .= '
  public function testIf' . UCFirst($domainName) . 'Contains' . UCFirst($this->_serviceName) .'() {
        $domainName = "' . UCFirst($domainName)  . '";
        $html = $this->getHtmlContent("' . $webSiteUrl  . '");
        $this->assertTrue($this->' . UCFirst($this->_checkClassName) . 'Check' . $this->_serviceName . '($html), "testIf' . UCFirst($domainName) . 'Contains' . UCFirst($this->_serviceName).'KO");
    }
';        
     }
  }
  

  // Generate all test class files 
  public function generateAllFiles() {
  	 foreach ($this->_webParsingConfig as $this->_serviceName => $serviceConfig) {
	 	 $this->generateMainClassBegin();
		 $this->getConfig($serviceConfig);                
		 $this->addCheckMethod();
		 $this->addPhpUnitTests($serviceConfig['acceptedConfig']);
		 $this->generateMainClassEnd();	 		 
		 $this->generateTestFile($this->_checkClassName . 'Check' . $this->_serviceName . 'Test.php');
	 }	
	 $this->generateMainTestFile();
  }

  public function generateTestFile($fileName) {
  	 file_put_contents($fileName, $this->_phpOutput);
  }

  public function generateMainTestFile() { 

  $sep = "'/'";
  $this->_phpOutput = '<?php
use Guzzle\Tests\GuzzleTestCase,
    Guzzle\Plugin\Mock\MockPlugin,
    Guzzle\Http\Message\Response,
    Guzzle\Http\Client as HttpClient,
    Guzzle\Service\Client as ServiceClient,
    Guzzle\Http\EntityBody;

class ' . $this->_checkClassName . 'Check extends GuzzleTestCase
{
    protected $_client;

    protected static $_cache;

    protected function getHtmlContent($url) {

    if (!isset(self::$_cache[$url])) {
           $client = new HttpClient($url);
           $request = $client->get(' . $sep . ');
           $response = $request->send();
	      self::$_cache[$url] = $response->getBody(true);
	   }  
        return (self::$_cache[$url]);
    }
}
';
    $this->generateTestFile($this->_checkClassName . 'Check.php');

  }


  public function getConfig($serviceConfig) {
  	 $this->_serviceVersions = array_keys($serviceConfig['acceptedConfig']);
         $this->_files = array_values($serviceConfig['acceptedConfig']);
	 $this->_urlsToCheck = array_values($serviceConfig['urls']);	 
  }
}



 $checkFilesOnMySite = new generateCheckFilesOnMySite();
 $checkFilesOnMySite->generateAllFiles();

?>