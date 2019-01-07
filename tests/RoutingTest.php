<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;

class RoutingTest extends TestCase
{
    private $http;

    public function setUp()
    {
        $this->http = new \GuzzleHttp\Client(['base_uri' => 'http://localhost:8080']);
    }

    public function testGetMethodRoute()
    {
        $response = $this->http->request('GET', '/home');

        $this->assertEquals('home page', $response->getgetBody());
    }

    public function testPostMethodRoute()
    {
        $response = $this->http->request('POST', '/home');

        $this->assertEquals('post home page', $response->getBody());
    }

    public function tearDown() {
        $this->http = null;
    }
}