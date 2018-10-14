<?php

require_once 'vendor/autoload.php';

use App\Application;
use function App\Renderer\render;

$app = new Application;

$app->get('/home', function () {
    return render('home', ['title' => 'Home page']);
});

$app->post('/', function () {
    return render('index');
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
