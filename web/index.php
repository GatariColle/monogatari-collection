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

$app->run();
