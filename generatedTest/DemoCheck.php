<?php
use Guzzle\Tests\GuzzleTestCase,
    Guzzle\Plugin\Mock\MockPlugin,
    Guzzle\Http\Message\Response,
    Guzzle\Http\Client as HttpClient,
    Guzzle\Service\Client as ServiceClient,
    Guzzle\Http\EntityBody;

class DemoCheck extends GuzzleTestCase
{
    protected $_client;

    protected static $_cache;

    protected function getHtmlContent($url) {

    if (!isset(self::$_cache[$url])) {
           $client = new HttpClient($url);
           $request = $client->get('/');
           $response = $request->send();
           self::$_cache[$url] = $response->getBody(true);
        }
        return (self::$_cache[$url]);
    }
}
