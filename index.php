<?php
function server($url)
{
    if (preg_match('/^\/home\/?$/i', $url)) {
        return "<h1>Home page</h1>";
    }
}

echo server($_SERVER['REQUEST_URI']);