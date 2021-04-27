<?php

require('../vendor/autoload.php');

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

$app->get('/', function() use($app) {
  $app['monolog']->addDebug('logging output.');
  return $app['templating']->render(__DIR__.'/views/index.php');
//   return $app['twig']->render('index.twig');

});

$app->get('/read/{title_id}', function($title_id) use ($app) {
    $app['monolog']->addDebug('logging output.');
    return $app['templating']->render(__DIR__.'/views/title.php',
        array('title_id' => $title_id));
});

// TODO: implement route 'title_id/chapter_id'
$app->get('/read/{title_id}/{chapter_id}', function($title_id, $chapter_id) use ($app) {
    $app['monolog']->addDebug('logging output.');
    return $app['templating']->render(__DIR__.'/views/chapter.php',
        array('title_id' => $title_id, 'chapter_id' => $chapter_id ));
});

$app->run();
