<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;

class RoutingTest extends TestCase
{
    private $client;

    public function setUp()
    {
        $this->client = new \GuzzleHttp\Client(['base_uri' => 'http://localhost:8080/']);
    }

    public function testGetMethodRoute()
    {
        $response = $this->client->request('GET', 'home');

        $this->assertContains('Home page', (string) $response->getBody());
    }

    public function testPostMethodRoute()
    {
        $response = $this->client->request('POST', 'home');

        $this->assertContains('Post Home page', (string) $response->getBody());
    }

    public function tearDown() {
        $this->http = null;
    }
}