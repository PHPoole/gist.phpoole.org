<?php
date_default_timezone_set('America/New_York');

use Silex\Application;
use Silex\Provider\AssetServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;
use Silex\Provider\HttpFragmentServiceProvider;
use Silex\Provider\FormServiceProvider;

$app = new Application();
$app->register(new ServiceControllerServiceProvider());
$app->register(new AssetServiceProvider());
$app->register(new TwigServiceProvider());
$app->register(new HttpFragmentServiceProvider());
$app->register(new FormServiceProvider());
$app->register(new Silex\Provider\TranslationServiceProvider(), array(
    'translator.domains' => array(),
));
$app['twig'] = $app->extend('twig', function ($twig, $app) {
    // add custom globals, filters, tags, ...

    return $twig;
});
$app['locale'] = 'fr_FR';

return $app;
