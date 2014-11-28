<?php
require dirname(__DIR__) . '/../vendor/autoload.php';
require dirname(__DIR__) . '/config/wpcc_groupurl.php';

use Guzzle\Tests\GuzzleTestCase,
    Guzzle\Plugin\Mock\MockPlugin,
    Guzzle\Http\Message\Response,
    Guzzle\Http\Client as HttpClient,
    Guzzle\Service\Client as ServiceClient,
    Guzzle\Http\EntityBody;
use Guzzle\Http\Message\Request;


class urlsCache {
      
      private $_siteContent;
      

      public function __construct() {      	     
      }


      public function parseSite($site) {
      	   $client = new HttpClient($site);
           $request = $client->get('/');
           $response = $request->send();
	   var_dump($response);
	   die();
      }

      

}



	   //parsing begins here:
	   $doc = new DOMDocument();
	   @$doc->loadHTML($response);
	   $metas = $doc->getElementsByTagName('meta');
	   for ($i = 0; $i < $metas->length; $i++)
	   {
		$meta = $metas->item($i);
    		if($meta->getAttribute('name') == 'archimade_idsite')
		   $idSite = $meta->getAttribute('content');    
		if($meta->getAttribute('name') == 'archimade_token')
                   $token = $meta->getAttribute('content');
           }
  var_dump($token);
  var_dump($idSite);
?>