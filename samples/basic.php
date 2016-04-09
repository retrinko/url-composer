<?php

require_once __DIR__.'/../vendor/autoload.php';

use UrlComposer\UrlComposer;

try
{
    $urlComposer = new UrlComposer('http://my-url.com');
    $urlComposer->addToUrl('blog')->addToQuery('id', 25);
    $url = $urlComposer->compose();
    printf('URL: %s' . PHP_EOL, $url);
}
catch (\Exception $e)
{
    printf('Exception!: %s' . PHP_EOL, $e->getMessage());
}


