<?php

namespace App;

class Application implements ApplicationInterface
{
    private $handlers = [];

    public function get($url, $func)
    {
        $this->append('GET', $url, $func);
    }

    public function post($url, $func)
    {
        $this->append('POST', $url, $func);
    }

    public function run()
    {
        $uri = $_SERVER['REQUEST_URI'];
        $method = $_SERVER['REQUEST_METHOD'];

        foreach ($this->handlers as $handler) {
            [$httpMethod, $route, $handlerMethod] = $handler;

            $preparedRoute = preg_quote($route, '/');
            if ($method == $httpMethod && preg_match("/^$preparedRoute$/i", $uri)) {
                echo $handlerMethod();
                return;
            }
        }
    }

    private function append($method, $url, $func)
    {
        $this->handlers[] = [$method, $url, $func];
    }
}