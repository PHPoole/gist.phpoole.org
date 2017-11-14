<?php
date_default_timezone_set('America/New_York');

use Silex\Application;
use Silex\Provider\AssetServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;
use Silex\Provider\HttpFragmentServiceProvider;
use Silex\Provider\FormServiceProvider;
use Silex\Provider\TranslationServiceProvider;
use Silex\Provider\MonologServiceProvider;

$app = new Application();
$app->register(new ServiceControllerServiceProvider());
$app->register(new AssetServiceProvider());
$app->register(new TwigServiceProvider());
$app->register(new HttpFragmentServiceProvider());
$app->register(new FormServiceProvider());
$app->register(new TranslationServiceProvider(), array(
    'translator.domains' => array(),
));
$app->register(new MonologServiceProvider(), array(
    'monolog.logfile' => 'php://stderr',
));

$app['twig'] = $app->extend('twig', function ($twig, $app) {
    // add custom globals, filters, tags, ...

    return $twig;
});
$app['locale'] = 'fr_FR';

return $app;
