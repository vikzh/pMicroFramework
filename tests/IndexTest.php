<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;

class IndexTest extends TestCase
{
    private $http;

    public function setUp()
    {
        $this->http = new \GuzzleHttp\Client(['base_uri' => 'http://localhost:8080']);
    }

    public function testIndexPageLoadSuccessfully()
    {
        $response = $this->http->request('GET', '');

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function tearDown() {
        $this->http = null;
    }
}