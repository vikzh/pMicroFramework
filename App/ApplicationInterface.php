<?php

namespace App;

interface ApplicationInterface
{
    public function get($url, $func);
    public function post($url, $func);
    public function run();
}
