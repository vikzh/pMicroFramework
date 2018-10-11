<?php

require_once 'vendor/autoload.php';

use App\Application;

$app = new Application;

$app->get('/home', function (){
    return 'Home page';
});

$app->post('/', function (){
    return 'Main page';
});

$app->run();
