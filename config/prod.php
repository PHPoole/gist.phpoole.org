<?php

// configure your app for the production environment

$app['twig.path'] = array(__DIR__.'/../templates');
$app['twig.options'] = array('cache' => __DIR__.'/../var/cache/twig');
$app['url'] = 'https://cryptic-hamlet-26917.herokuapp.com/';
$app['gist.default'] = '';
