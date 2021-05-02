<?php

require('../vendor/autoload.php');
require dirname(__FILE__).'/../php/functions/functions.php';

use Symfony\Component\HttpFoundation\Request;

$app = new Silex\Application();
$app['debug'] = true;

// Register the monolog logging service
$app->register(new Silex\Provider\MonologServiceProvider(), array(
  'monolog.logfile' => 'php://stderr',
));

// Register view rendering
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/views',
));

// use php as rendering engine
use Symfony\Component\Templating\PhpEngine;
use Symfony\Component\Templating\TemplateNameParser;
use Symfony\Component\Templating\Loader\FilesystemLoader;
$app['templating'] = function() {

    $loader = new FilesystemLoader(array(
        __DIR__.'views/%name%'
    ));

    $templating = new PhpEngine(new TemplateNameParser(), $loader);
    return $templating;
};

// Our web handlers

$app->get('/', function() use ($app) {
    $popular = getPopularTitles();
    $recent = getRecentTitles();
    return $app['twig']->render('index.twig',
        array('popular' => $popular, 'recent' => $recent));
});

$app->get('/read/{title_id}', function($title_id) use ($app) {
    $titleInfo = gettitleinfo($title_id);
    if (empty($titleInfo)) {
        return $app['templating']->render(__DIR__ . '/views/error_page.twig',
            array('message' => "Страница не найдена"));
    }
    $chaptersList = getchapterlist($title_id);
    visitcounter($title_id);

    return $app['twig']->render('title.twig',
        array('titleInfo' => $titleInfo, 'chaptersList' => $chaptersList));
});


$app->get('/read/{title_id}/{chapter_id}', function($title_id, $chapter_id) use ($app) {
    $chapter = getchapter($title_id, $chapter_id);
    return $app['twig']->render('chapter.twig', array('chapter' => $chapter));
});

$app->get('/about', function() use ($app) {
    return $app['twig']->render('about.twig');
});

$app->get('/login', function() use ($app) {
    return $app['twig']->render('login.twig');
});

$app->get('/search', function () use ($app) {
    $popularTitles = getPopularTitles();
    return $app['twig']->render('search.twig', array('data' => $popularTitles));
});

$app->post('/search', function (Request $request) use ($app) {
    $query = $request->get('query') ?? '';
    $searchResult = search($query);
    return $app['twig']->render('search.twig', array('data' => $searchResult));
});

$app->error(function(\Exception $e, Request $request, $code) use ($app) {
    switch ($code) {
        case 404:
            $message = "Страница не найдена";
            break;
        default:
            $message = "Произошла какая-то ошибка";
    }
    return $app['twig']->render('error_page.twig',
        array('message' => $message));
});

$app->run();
