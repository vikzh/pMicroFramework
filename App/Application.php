<?php

namespace App;

class Application implements ApplicationInterface
{
    private $handlers = [];

    public function get($route, $handler)
    {
        $this->append('GET', $route, $handler);
    }

    public function post($route, $handler)
    {
        $this->append('POST', $route, $handler);
    }

    private function append($method, $route, $handler)
    {
        $updatedRoute = $route;
        if (preg_match_all('/:([^\/]+)/', $route, $matches)) {
            $updatedRoute = array_reduce($matches[1], function ($acc, $value) {
                $group = "(?P<$value>[\w-]+)";
                return str_replace(":{$value}", $group, $acc);
            }, $route);
        }
        $this->handlers[] = [$updatedRoute, $method, $handler];
    }

    public function run()
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $method = $_SERVER['REQUEST_METHOD'];
        foreach ($this->handlers as $item) {
            list($route, $handlerMethod, $handler) = $item;
            $preparedRoute = str_replace('/', '\/', $route);
            $matches = [];
            if ($method == $handlerMethod && preg_match("/^$preparedRoute$/i", $uri, $matches)) {
                $arguments = array_filter($matches, function ($key) {
                    return !is_numeric($key);
                }, ARRAY_FILTER_USE_KEY);

                $meta = [
                    'method' => $method,
                    'uri' => $uri,
                    'headers' => getallheaders()
                ];

                $response = $handler($meta, array_merge($_GET, $_POST), $arguments);
                http_response_code($response->getStatusCode());
                foreach ($response->getHeaderLines() as $header) {
                    header($header);
                }
                echo $response->getBody();
                return;
            }
        }
    }
}