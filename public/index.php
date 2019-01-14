<?php

namespace App;

require __DIR__ . '/../vendor/autoload.php';

use App\Application;
use function App\Renderer\render;
use function App\response;

$opt = array(
    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
);

$pdo = new \PDO('sqlite:/var/tmp/db.sqlite', null, null, $opt);
$repository = new ArticleRepository($pdo);

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


$app->get('/articles', function () use ($repository) {
    $articles = $repository->all();
    return response(render('articles/index', ['articles' => $articles]));
});

$app->get('/articles/new', function ($meta, $params, $attributes) {
    return response(render('articles/new', ['errors' => []]));
});

$app->delete('/articles/:id', function ($meta, $params, $attributes) use ($repository) {
    $repository->delete($attributes['id']);
    return response()->redirect('/articles');
});

$app->post('/articles', function ($meta, $params, $attributes) use ($repository) {
    $article = $params['article'];
    $errors = [];

    if (!$article['title']) {
        $errors['title'] = "Title can't be blank";
    }

    if (empty($errors)) {
        $repository->insert($article);
        return response()->redirect('/articles');
    } else {
        return response(render('articles/new', ['article' => $article, 'errors' => $errors]))
            ->withStatus(422);
    }
});


//Cookies
$goods = ['sweets', 'bread', 'water', 'meat', 'cheese'];

$app->get('/products', function ($meta, $params, $attributes, $cookies) use ($goods) {
    return response(render('/products/index', ['goods' => $goods]));
});

$app->get('/cart', function ($meta, $params, $attributes, $cookies) use ($goods) {
    $cart = array_key_exists('cart', $cookies) ? $cookies['cart'] : [];
    return response(render('/products/cart', ['goods' => json_decode($cart, true)]));
});

$app->post('/cart', function ($meta, $params, $attributes, $cookies) use ($goods) {
    $cart = array_key_exists('cart', $cookies) ? json_decode($cookies['cart'], true) : [];
    $good = $params['good'];
    if (array_key_exists($good, $cart)) {
        $cart[$good]++;
    } else {
        $cart[$good] = 1;
    }
    return response()->redirect('/cart')->withCookie('cart', json_encode($cart));
});

$app->delete('/cart', function ($meta, $params, $attributes, $cookies) use ($goods) {
    $cart = array_key_exists('cart', $cookies) ? json_decode($cookies['cart'], true) : [];
    $good = $params['good'];
    unset($cart[$good]);
    return response()->redirect('/cart')->withCookie('cart', json_encode($cart));
});

//Session
$app->get('/user', function ($meta, $params, $attributes, $cookies, $session) {
    $session->start();
    $nickname = $session->get('nickname');
    return response(render('users/index', ['nickname' => $nickname]));
});

$app->get('/session/new', function ($meta, $params, $attributes, $cookies, $session) {
    return response(render('users/new'));
});

$app->post('/session', function ($meta, $params, $attributes, $cookies, $session) {
    $session->start();
    $session->set('nickname', $params['nickname']);
    return response()->redirect('/user');
});

$app->delete('/session', function ($meta, $params, $attribute, $cookies, $session) {
    $session->start();
    $session->destroy();
    return response()->redirect('/user');
});

//$app->post('/', function () {
//    return response(render('index'));
//});

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
