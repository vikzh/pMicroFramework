<?php

namespace App;

require __DIR__ . '/../vendor/autoload.php';

use App\Application;
use function App\Renderer\render;
use function App\response;

$app = new Application();

//HTTP Method Get handler
$app->get('/home', function () {
    return response(render('home', ['title' => 'Home page']));
});

//HTTP Method Post handler
$app->post('/home', function () {
    return response(render('home', ['title' => 'Post Home page']));
});

//Dynamic Route
$app->get('/articles/:id', function ($params, $data, $arguments) {
    return response($arguments);
});

$app->post('/', function () {
    return response(render('index'));
});

$data = [
    []
];

$app->get('/dataSort', function ($params) use ($data) {
    if (array_key_exists('sort', $params)) {
        list($key, $order) = explode(' ', $params['sort']);

        usort($data, function ($prev, $next) use ($key, $order) {
            $prevValue = $prev[$key];
            $nextValue = $next[$key];

            if ($prevValue == $nextValue) {
                return 0;
            }

            if ($order == 'desc') {
                return $prevValue < $nextValue ? 1 : -1;
            } else if ($order == 'asc') {
                return $prevValue > $nextValue ? 1 : -1;
            }
        });
    }
    return json_encode($data);
});

$app->run();
