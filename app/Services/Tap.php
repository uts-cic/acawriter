<?php

/**
 * Created by IntelliJ IDEA.
 * User: Developer CIC: Radhika Mogarkar
 * Date: 2/11/17
 * Time: 11:40 AM
 */

namespace App\Services;

use GuzzleHttp\Client;

class Tap
{

    public $client;

    function __construct()
    {
        $this->client = new Client();
    }

    public function getRequest($url = NULL)
    {
        $uri = env('TAP_API', '') . "/" . $url;
        $request = $this->client->get($uri);
        $body = $request->getBody();
        $response = \GuzzleHttp\json_decode($body);
        return $response;
    }
}
