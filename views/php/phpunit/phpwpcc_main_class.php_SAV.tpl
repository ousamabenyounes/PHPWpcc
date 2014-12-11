<?php
require('../lib/wpccFile.php');
require('../lib/wpccUtils.php');

use Guzzle\Tests\GuzzleTestCase,
    Guzzle\Plugin\Mock\MockPlugin,
    Guzzle\Http\Message\Response,
    Guzzle\Http\Client as HttpClient,
    Guzzle\Service\Client as ServiceClient,
    Guzzle\Http\EntityBody;

class {{ projectName }}Check extends GuzzleTestCase
{
    protected $_client;

    protected function getHtmlContent($url) {

        $cleanUrl = wpccUtils::urlToString($url);
        $fileName = '../cache/current/content/' . $cleanUrl . '.php';
        $response = wpccFile::getContentFromFile($fileName, false);
        if (false !== $response) {
            self::$_cache[$url] = $response
            return $response;
        }

        if (!isset(self::$_cache[$url])) {
           $client = new HttpClient($url);
           $request = $client->get('/');
           $response = $request->send();
           self::$_cache[$url] = $response->getBody(true);
        }
        return (self::$_cache[$url]);
    }
}
