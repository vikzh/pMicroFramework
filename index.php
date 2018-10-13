<?php

require_once 'vendor/autoload.php';

use App\Application;
use function App\Renderer\render;

$app = new Application;

$app->get('/home', function (){
    return render('home', ['title' => 'Home page']);
});

$app->post('/', function (){
    return render('index');
});

$app->run();
