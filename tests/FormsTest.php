<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;

class FormsTest extends TestCase
{
    private $http;

    public function setUp()
    {
        $this->http = new \GuzzleHttp\Client(['base_uri' => 'http://localhost:8080/']);
    }

    public function testArticleCreated()
    {
        $response = $this->http->request('POST', 'articles', [
            'form_params' => ['article' => ['title' => 'test']]]);

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function tearDown()
    {
        $this->http = null;
    }
}