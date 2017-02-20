<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use PHPoole\PHPoole;

//Request::setTrustedProxies(array('127.0.0.1'));

$app->get('/', function () use ($app) {
    return $app['twig']->render('index.html.twig', array());
})
->bind('homepage')
;

$app->get('/build/{gistId}', function ($gistId) use ($app) {
    
    $options  = array('http' => array('user_agent'=> $_SERVER['HTTP_USER_AGENT']));
    $context  = stream_context_create($options);
    $url = 'https://api.github.com/gists/'.$gistId;
    $json = file_get_contents($url, false, $context);
    $gist = json_decode($json);
    $contentUrl = $gist->{'files'}->{'index.md'}->{'raw_url'};
    $content = file_get_contents($contentUrl, false, $context);

    $dir = __DIR__.'/../web';
    if (!is_dir($dir.'/'.$gistId)) {
        mkdir($dir.'/'.$gistId, 0700);
    }
    file_put_contents($dir.'/'.$gistId.'/index.md', $content);

    PHPoole::create(
        [
            'site' => [
                'title'       => "gist phpoole.org",
                'description' => '',
                'baseurl'     => 'http://localhost:8888/'.$gistId.'/',
            ],
            'content' => [
                'dir' => $gistId
            ],
            'output'  => [
                'dir' => $gistId
            ],
            'layouts' => [
                'dir' => 'layouts'
            ],
            'static' => [
                'dir' => 'static'
            ]
        ]
    )
    ->setSourceDir($dir)
    ->setDestinationDir($dir)
    ->build();

    return $app['twig']->render('build.html.twig', array('content' => $content));
});

$app->error(function (\Exception $e, Request $request, $code) use ($app) {
    if ($app['debug']) {
        return;
    }

    // 404.html, or 40x.html, or 4xx.html, or error.html
    $templates = array(
        'errors/'.$code.'.html.twig',
        'errors/'.substr($code, 0, 2).'x.html.twig',
        'errors/'.substr($code, 0, 1).'xx.html.twig',
        'errors/default.html.twig',
    );

    return new Response($app['twig']->resolveTemplate($templates)->render(array('code' => $code)), $code);
});
