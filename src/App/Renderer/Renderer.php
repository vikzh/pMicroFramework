<?php

namespace App\Renderer;

use function App\Template\render as templateRender;

function render($filepath, $params = [])
{
    $parts = [getcwd(), 'resources', 'views', $filepath];
    $templatePath = implode($parts, DIRECTORY_SEPARATOR) . '.phtml';
    return templateRender($templatePath, $params);
}