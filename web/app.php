<?php

use Symfony\Component\ClassLoader\ApcClassLoader;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Debug\Debug;

umask(0000);

$env = (isset($_SERVER['HTTP_HOST']) && '.dev' === substr($_SERVER['HTTP_HOST'], -4)) ? 'dev' : 'prod';
$useCache = !('dev' === $env && isset($_GET['disableCache']) && !!($_GET['disableCache']));
$loader = require_once __DIR__.'/../app/' . ($useCache ? 'bootstrap.php.cache' : 'autoload.php');

if ('dev' === $env) {
    Debug::enable();
} elseif ('prod' === $env) {
    $apcLoader = new ApcClassLoader('sf2', $loader);
    $loader->unregister();
    $apcLoader->register(true);
}

require_once __DIR__ . '/../app/AppKernel.php';
$kernel = new AppKernel($env, 'dev' === $env);

if ('dev' !== $env) {
    $kernel->loadClassCache();
}

if ('prod' === $env) {
    require_once __DIR__.'/../app/AppCache.php';
    $kernel = new AppCache($kernel);
    Request::enableHttpMethodParameterOverride();
}

$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
