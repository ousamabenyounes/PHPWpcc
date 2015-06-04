<?php

namespace Wpcc;

use Guzzle\Http\Message\Response,
    Guzzle\Http\Client as HttpClient;
    
use Guzzle\Http\Message\Request;

class wRequest
{
    /**
     * This function send an Http Request on the given url and return the web page content
     *
     * @param $url
     * @return Response
     */
    public static function sendRequest($url)
    {
        $client = new HttpClient($url);
        $request = $client->get($url);
        $response = $request->send();

        return $response->getBody(true);
    }
}
