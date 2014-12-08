<?php
 require("ui/wpcc_config.php");
l
 class WebPageContentCheckerGenerator {

 protected $_checkClassName = 'Demo';
 protected $_outputDirectory = 'tests/';

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

 public function __construct($webParsingConfig) {
 	$this->_webParsingConfig = $webParsingConfig;	
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
        $html = $this->getHtmlContent("' . $webSiteUrl  . '");
        $this->assertTrue($this->' . UCFirst($this->_checkClassName) . 'Check' . $this->_serviceName . '($html), "testIf' . UCFirst($domainName) . 'Contains' . UCFirst($this->_serviceName).'KO");
    }
';        
     }
  }
  

  // Generate all test class files 
  public function generateAllFiles() {
  	 echo '<div class="alert-box notice"><span>info: </span> - Generating all PHPunit test files </div>';
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
  	 echo '<div class="alert-box success"><span>success: </span> - Creating ' . $fileName . ' </div>';
  	 file_put_contents($this->_outputDirectory . $fileName, $this->_phpOutput);
	 
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


 $checkFilesOnMySite = new WebPageContentCheckerGenerator($webParsingConfig);
 $checkFilesOnMySite->generateAllFiles();

?>