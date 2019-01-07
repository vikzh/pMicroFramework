<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;

class TemplateTest extends TestCase
{
    private $client;

    public function setUp()
    {
        $this->client = new \GuzzleHttp\Client(['base_uri' => 'http://localhost:8080/']);
    }

    public function testGetMethodRoute()
    {
        $response = $this->client->request('GET', 'home');

        $this->assertContains('<h1>Home page</h1>', (string) $response->getBody());
    }

    public function tearDown() {
        $this->http = null;
    }
}